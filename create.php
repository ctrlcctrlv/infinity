<?php

include "inc/functions.php";
include "inc/lib/ayah/ayah.php";
include "inc/mod/auth.php";
checkBan('*');
$bannedWords = array('/^cake$/', '8ch', '/^cp$/', 'child', '/^inc$/', '/^static$/', '/^templates$/', '/^js$/', '/^stylesheets$/', '/^tools$/', '/^pedo$/');

$ayah = new AYAH();

if (!isset($_POST['uri'], $_POST['title'], $_POST['subtitle'], $_POST['username'], $_POST['password'])) {
$publisher_html = $ayah->getPublisherHTML();
$password = base64_encode(openssl_random_pseudo_bytes(9));

$body = <<<EOT
<form method="POST">
<table class="modlog" style="width:auto">
<tbody>
<tr><th>URI</th><td>/<input name="uri" type="text" size="5">/ <span class="unimportant">(must be all lowercase or numbers and < 10 chars)</td></tr>
<tr><th>Title</th><td><input name="title" type="text"> <span class="unimportant">(must be < 40 chars)</td></tr>
<tr><th>Subtitle</th><td><input name="subtitle" type="text"> <span class="unimportant">(must be < 200 chars)</td></tr>
<tr><th>Username</th><td><input name="username" type="text"> <span class="unimportant">(must contain only alphanumeric, periods and underscores)</span></td></tr>
<tr><th>Password</th><td><input name="password" type="text" value="{$password}" readonly> <span class="unimportant">(write this down)</span></td></tr>
<tr><th>Game</th><td>{$publisher_html}</td></tr>
</tbody>
</table>
<ul style="padding:0;text-align:center;list-style:none"><li><input type="submit" value="Create board"></li></ul>
</form>

EOT;

echo Element("page.html", array("config" => $config, "body" => $body, "title" => "Create your board", "subtitle" => "before someone else does"));
}

else {
$uri = $_POST['uri'];
$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$username = $_POST['username'];
$password = $_POST['password'];
$score = $ayah->scoreResult();

if (!preg_match('/^[a-z0-9]{1,10}$/', $uri))
	error('Invalid URI');
if (!(strlen($title) < 40))
	error('Invalid title');
if (!(strlen($subtitle) < 200))
	error('Invalid subtitle');
if (!preg_match('/^[a-zA-Z0-9._]{1,30}$/', $username))
	error('Invalid username');
if (!$score)
	error('You failed the game');
foreach (listBoards() as $i => $board) {
	if ($board['uri'] == $uri)
		error('Board already exists!');
}

foreach ($bannedWords as $i => $w) {
	if ($w[0] !== '/') {
		if (strpos($uri,$w) !== false)
			error("Cannot create board with banned word $w");
	} else {
		if (preg_match($w,$uri))
			error("Cannot create board matching banned pattern $w");
	}
}
$query = prepare('SELECT * FROM ``mods``');
$query->execute() or error(db_error($query));
$users = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $i => $user) {
	if ($user['username'] == $username)
		error('Username taken!');
}

$salt = generate_salt();
$password = hash('sha256', $salt . sha1($password));

$query = prepare('INSERT INTO ``mods`` VALUES (NULL, :username, :password, :salt, :type, :boards)');
$query->bindValue(':username', $username);
$query->bindValue(':password', $password);
$query->bindValue(':salt', $salt);
$query->bindValue(':type', 20);
$query->bindValue(':boards', $uri);
$query->execute() or error(db_error($query));
		
$query = prepare('INSERT INTO ``boards`` VALUES (:uri, :title, :subtitle)');
$query->bindValue(':uri', $_POST['uri']);
$query->bindValue(':title', $_POST['title']);
$query->bindValue(':subtitle', $_POST['subtitle']);
$query->execute() or error(db_error($query));

$query = Element('posts.sql', array('board' => $uri));
query($query) or error(db_error());

if (!openBoard($_POST['uri']))
	error(_("Couldn't open board after creation."));
if ($config['cache']['enabled'])
	cache::delete('all_boards');

// Build the board
buildIndex();

rebuildThemes('boards');

query("INSERT INTO ``board_create``(uri) VALUES('$uri')") or error(db_error());

_syslog(LOG_NOTICE, "New board: $uri");

$body = <<<EOT

<p>Your new board is created and is live at <a href="/{$uri}">/{$uri}/</a>.</p>

<p>Make sure you don't forget your password, <tt>{$_POST['password']}</tt>!</p>

<p>You can manage your site at <a href="http://8chan.co/mod.php?/">http://8chan.co/mod.php?/</a>.</p>

EOT;

echo Element("page.html", array("config" => $config, "body" => $body, "title" => "Success", "subtitle" => "This was a triumph"));
}
