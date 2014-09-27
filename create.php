<?php

include "inc/functions.php";
include "inc/lib/ayah/ayah.php";
include "inc/mod/auth.php";
$cbRecaptcha = false;
//don't load recaptcha LIB unless its enabled!
if ($config['cbRecaptcha']){
$cbRecaptcha = true;
include "inc/lib/recaptcha/recaptchalib.php";
}


checkBan('*');
$bannedWords = array('/^cake$/', '8ch', '/^cp$/', 'child', '/^inc$/', '/^static$/', '/^templates$/', '/^js$/', '/^stylesheets$/', '/^tools$/', '/^pedo$/');

$ayah = (($config['ayah_enabled']) ? new AYAH() : false);

if (!isset($_POST['uri'], $_POST['title'], $_POST['subtitle'], $_POST['username'], $_POST['password'])) {
if (!$ayah){
	$game_html =  '';
} else {
	$game_html = '<tr><th>'._('Game').'</th><td>' .  $ayah->getPublisherHTML() . '</td></tr>';
}

if (!$cbRecaptcha){
	$recapcha_html = '';
} else {
	$recapcha_html = '<tr><th>reCaptcha</th><td>' .  recaptcha_get_html($config['recaptcha_public']) . '</td></tr>';
}


$password = base64_encode(openssl_random_pseudo_bytes(9));

$body = Element("8chan/create.html", array("config" => $config, "password" => $password, "game_html" => $game_html, "recapcha_html" => $recapcha_html));

echo Element("page.html", array("config" => $config, "body" => $body, "title" => _("Create your board"), "subtitle" => _("before someone else does")));
}

else {
$uri = $_POST['uri'];
$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$username = $_POST['username'];
$password = $_POST['password'];

  $resp = ($cbRecaptcha) ? recaptcha_check_answer ($config['recaptcha_private'],
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]):false;

if ($resp != false){
$passedCaptcha = $resp->is_valid;
} else {
$passedCaptcha = true;
}

if (!$ayah){
$score = true;
} else {
$score = $ayah->scoreResult();
}
if (!$score)
	error(_('You failed the game'));
if (!$passedCaptcha)
	error(_('You failed to enter the reCaptcha correctly'));
if (!preg_match('/^[a-z0-9]{1,10}$/', $uri))
	error(_('Invalid URI'));
if (!(strlen($title) < 40))
	error(_('Invalid title'));
if (!(strlen($subtitle) < 200))
	error(_('Invalid subtitle'));
if (!preg_match('/^[a-zA-Z0-9._]{1,30}$/', $username))
	error(_('Invalid username'));

foreach (listBoards() as $i => $board) {
	if ($board['uri'] == $uri)
		error(_('Board already exists!'));
}

foreach ($bannedWords as $i => $w) {
	if ($w[0] !== '/') {
		if (strpos($uri,$w) !== false)
			error(_("Cannot create board with banned word $w"));
	} else {
		if (preg_match($w,$uri))
			error(_("Cannot create board matching banned pattern $w"));
	}
}
$query = prepare('SELECT ``username`` FROM ``mods`` WHERE ``username`` = :username');
$query->bindValue(':username', $username);
$query->execute() or error(db_error($query));
$users = $query->fetchAll(PDO::FETCH_ASSOC);

if (sizeof($users) > 0){
error(_('The username you\'ve tried to enter already exists!'));
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

$query = prepare("INSERT INTO ``board_create``(uri) VALUES(:uri)");
$query->bindValue(':uri', $uri);
$query->execute() or error(db_error());

_syslog(LOG_NOTICE, "New board: $uri");

$body = Element("8chan/create_success.html", array("config" => $config, "password" => $_POST['password'], "uri" => $uri));

echo Element("page.html", array("config" => $config, "body" => $body, "title" => _("Success"), "subtitle" => _("This was a triumph")));
}
?>
