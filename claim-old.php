<?php
/*
include "inc/functions.php";
include "inc/mod/auth.php";

function last_activity($board) {
	// last post
	$query = prepare(sprintf("SELECT MAX(time) AS time FROM posts_%s", $board));
	$query->execute();
	$row = $query->fetch();
	$ago = (new DateTime)->sub(new DateInterval('P1W'));
	$mod_ago = (new DateTime)->sub(new DateInterval('P2W'));

	$last_activity_date = new DateTime();
	$last_mod_date = new DateTime();	

	$last_activity_date->setTimestamp($row['time']);

	$query = query("SELECT id, username FROM mods WHERE boards = '$board'");
	$mods = $query->fetchAll();

	if ($mods) {
		$mod = $mods[0]['id'];
		$query = query("SELECT MAX(time) AS time FROM modlogs WHERE `mod` = $mod");
		$a = $query->fetchAll(PDO::FETCH_COLUMN);

		if ($a[0]) { 
			$last_mod_date->setTimestamp($a[0]);
			if (!$row['time'])
				$last_activity_date->setTimestamp($a[0]);
		} else {// no one ever logged in, try board creation time
			$query = query("SELECT UNIX_TIMESTAMP(time) AS time FROM board_create WHERE uri = '$board'");
			$crt = $query->fetchAll(PDO::FETCH_COLUMN);
			$last_activity_date->setTimestamp($crt[0]);
			$last_mod_date = false;
		}
	}
	
	if ($mods and ($last_activity_date < $ago or ($last_mod_date and $last_mod_date < $mod_ago))) {
		return array($last_activity_date, $last_mod_date, $mods);
	}
	else {
		return false;
	}
}

// Find out the last activity for our board
if (!isset($_GET['claim'])) {
$title = "Boards that need new owners";
$q = query("SELECT uri FROM boards");
$body = '<p>The following boards have been abandoned by their owners or have less than one post in a seventy-two hour period. Think you can do a better job? Claim one! Note: You can only reclaim one board per IP per 72 hours. Choose carefully!</p><table class="modlog" style="width:auto"><thead><tr><th>Board</th><th>Last activity</th><th>Last mod login</th><th>Reclaim</th></thead></tr><tbody>';
$boards = $q->fetchAll(PDO::FETCH_COLUMN);

$delete = array();
foreach($boards as $board) {
	$last_activity = last_activity($board);

	if ($last_activity) {
		list($last_activity_date, $last_mod_date, $mods) = $last_activity;

		$delete[] = array('board' => $board, 'last_activity' => $last_activity_date, 'last_mod' => $last_mod_date);
		$last_mod_f = $last_mod_date ? $last_mod_date->format('Y-m-d H:i:s') : '<em>never</em>';
		$body .= "<tr><td><a href='/{$board}/'>/{$board}/</a></td><td>{$last_activity_date->format('Y-m-d H:i:s')}</td><td>{$last_mod_f}</td><td><form style='margin-bottom:0!important'><input type='hidden' name='claim' value='{$board}'><input type='submit' value='Claim!'></form></td></tr>";
		
	}
}
$body .= "</table>";
}
else {
$query = prepare('SELECT `last` FROM ``claim`` WHERE `ip` = :ip');
$query->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
$query->execute();

$r_last = $query->fetch(PDO::FETCH_ASSOC);
$last = new DateTime($r_last['last'], new DateTimeZone('UTC'));
$ago = (new DateTime('',new DateTimeZone('UTC')))->sub(new DateInterval('P3D'));
if ($last > $ago and $r_last) {
error('You already claimed a board today');
}

openBoard($_GET['claim']);

$title = "Claiming board /{$board['uri']}/";
$last_activity = last_activity($board['uri']);
if ($last_activity) {
list($last_activity_date, $last_mod_date, $mods) = $last_activity;

$query = prepare('INSERT INTO ``claim``(`ip`, `last`) VALUES(:ip, NOW()) ON DUPLICATE KEY UPDATE `last`=NOW()');
$query->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
$query->execute();

$password = base64_encode(openssl_random_pseudo_bytes(9));
$salt = generate_salt();
$hashed = hash('sha256', $salt . sha1($password));

$query = prepare('UPDATE ``mods`` SET `password` = :hashed, `salt` = :salt WHERE BINARY username = :mod');
$query->bindValue(':hashed', $hashed);
$query->bindValue(':salt', $salt);
$query->bindValue(':mod', $mods[0]['username']);
$query->execute();

query(sprintf("UPDATE posts_%s SET ip = '127.0.0.1'", $board['uri']));
$query = prepare("DELETE FROM bans WHERE board = :board");
$query->bindValue(":board", $board['uri']);
$query->execute();
_syslog(LOG_NOTICE, "Board claimed: {$board['uri']}");

$body = "<p>Please read the following instructions carefully:</p>

<p>The username of the admin of this board is <strong>{$mods[0]['username']}</strong>. Please write this down, you will need it to log in. It cannot be changed.</p>
<p>A new password has been generated for this board. It is <tt>{$password}</tt> . Please write this down, you will need it to log in. <em>If you forget your password, it cannot be changed. You must wait to reclaim the board again! Do not lose it.</em></p>
<p>The URL you use to manage your board is <a href='/mod.php'>https://8ch.net/mod.php</a>. Log in using the details above. Note: The board can still be claimed by another user until you log in for the first time or if it still meets the inactivity criteria, so post something!</p>
<p>

";
} else {
error('Board active or does not exist, cannot be reclaimed.');
}
}

$config['default_stylesheet'] = array('Yotsuba B', $config['stylesheets']['Yotsuba B']);
echo Element("page.html", array("config" => $config, "body" => $body, "title" => $title));
*/
