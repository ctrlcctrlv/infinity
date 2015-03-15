<?php
include "inc/functions.php";

if (!(php_sapi_name() == "cli")) {
	die('nope');
}
$protected = array('burgers', 'cow', 'wilno', 'cute', 'yoga');
$q = query("SELECT uri FROM boards");
$boards = $q->fetchAll(PDO::FETCH_COLUMN);
$now = new DateTime();
$ago = (new DateTime)->sub(new DateInterval('P14D'));
$mod_ago = (new DateTime)->sub(new DateInterval('P7D'));

// Find out the last activity for our board
$delete = array();
foreach($boards as $board) {
	if (in_array($board, $protected)) {
		continue;
	}

	// last post
	$query = prepare(sprintf("SELECT MAX(time) AS time FROM posts_%s", $board));
	$query->execute();
	$row = $query->fetch();

	//count posts
	$query = prepare(sprintf("SELECT COUNT(id) AS count FROM posts_%s", $board));
	$query->execute();
	$count = $query->fetch();

	$last_activity_date = new DateTime();
	$last_mod_date = new DateTime();	

	$last_activity_date->setTimestamp($row['time']);

	$query = query("SELECT id, username FROM mods WHERE boards = '$board'");
	$mods = $query->fetchAll();

	if ($mods) {
		$mod = $mods[0]['id'];
		$query = query("SELECT MAX(time) AS time FROM modlogs WHERE `mod` = BINARY $mod");
		$a = $query->fetchAll(PDO::FETCH_COLUMN);

		if ($a[0]) { 
			$last_mod_date->setTimestamp($a[0]);
			if (!$row['time'])
				$last_activity_date->setTimestamp($a[0]);
		} else {// no one ever logged in, try board creation time
			$query = query("SELECT UNIX_TIMESTAMP(time) AS time FROM board_create WHERE uri = '$board'");
			$crt = $query->fetchAll(PDO::FETCH_COLUMN);
			if (!empty($crt)) $last_activity_date->setTimestamp($crt[0]);
			$last_mod_date = false;
		}
	}

	if (($last_activity_date < $ago or ($last_mod_date and $last_mod_date < $mod_ago)) and (int)$count['count'] < 5 and isset($mods[0]['id'])) {
	#if (($last_activity_date < $ago or ($last_mod_date and $last_mod_date < $mod_ago)) and isset($mods[0]['id'])) {
		echo $board, ' ', $last_activity_date->format('Y-m-d H:i:s'), ' ', ($last_mod_date ? $last_mod_date->format('Y-m-d H:i:s') : 'false'), ' ', $count['count'], ' ', $mod, "\r\n";
		$delete[] = array('board' => $board, 'last_activity' => $last_activity_date, 'last_mod' => $last_mod_date, 'mod' => isset($mods[0]['username']) ? $mods[0]['username'] : false, 'count' => $count['count']);
	}
}
if ($argc > 1) {
$f = fopen('rip.txt', 'a');
fwrite($f, "--\r\n".date('c')."\r\n");
foreach($delete as $i => $d){
	$s = "RIP /".$d['board']."/, created by ".($d['mod']?$d['mod']:'?')." and last active on ".$d['last_activity']->format('Y-m-d H:i:s.').($d['last_mod'] ? ' Mod last active on ' . $d['last_mod']->format('Y-m-d H:i:s.') : ' Mod never active.') . " Number of posts: {$d['count']}." . "\r\n";
	echo $s;
	fwrite($f, $s);

	if (!openBoard($d['board'])) continue;;

	$query = prepare('DELETE FROM ``boards`` WHERE `uri` = :uri');
	$query->bindValue(':uri', $board['uri']);
	$query->execute() or error(db_error($query));
	
	if ($config['cache']['enabled']) {
		cache::delete('board_' . $board['uri']);
		cache::delete('all_boards');
	}
	
	// Delete posting table
	$query = query(sprintf('DROP TABLE IF EXISTS ``posts_%s``', $board['uri'])) or error(db_error());
	
	// Clear reports
	$query = prepare('DELETE FROM ``reports`` WHERE `board` = :id');
	$query->bindValue(':id', $board['uri'], PDO::PARAM_INT);
	$query->execute() or error(db_error($query));
	
	// Delete from table
	$query = prepare('DELETE FROM ``boards`` WHERE `uri` = :uri');
	$query->bindValue(':uri', $board['uri'], PDO::PARAM_INT);
	$query->execute() or error(db_error($query));
	
	$query = prepare("SELECT `board`, `post` FROM ``cites`` WHERE `target_board` = :board ORDER BY `board`");
	$query->bindValue(':board', $board['uri']);
	$query->execute() or error(db_error($query));
	while ($cite = $query->fetch(PDO::FETCH_ASSOC)) {
		if ($board['uri'] != $cite['board']) {
			if (!isset($tmp_board))
				$tmp_board = $board;
			openBoard($cite['board']);
			rebuildPost($cite['post']);
		}
	}
	
	if (isset($tmp_board))
		$board = $tmp_board;
	
	$query = prepare('DELETE FROM ``cites`` WHERE `board` = :board OR `target_board` = :board');
	$query->bindValue(':board', $board['uri']);
	$query->execute() or error(db_error($query));
	
	$query = prepare('DELETE FROM ``antispam`` WHERE `board` = :board');
	$query->bindValue(':board', $board['uri']);
	$query->execute() or error(db_error($query));
	
	// Remove board from users/permissions table
	$query = query('SELECT `id`,`boards` FROM ``mods``') or error(db_error());
	while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
		$user_boards = explode(',', $user['boards']);
		if (in_array($board['uri'], $user_boards)) {
			unset($user_boards[array_search($board['uri'], $user_boards)]);
			$_query = prepare('UPDATE ``mods`` SET `boards` = :boards WHERE `id` = :id');
			$_query->bindValue(':boards', implode(',', $user_boards));
			$_query->bindValue(':id', $user['id']);
			$_query->execute() or error(db_error($_query));
		}
	}
	
	// Delete entire board directory
	exec('rm -rf ' . $board['uri'] . '/');
	rrmdir('static/banners/' . $board['uri']);
	file_unlink("stylesheets/board/{$board['uri']}.css");
	// HAAAAAX
	if($config['dir']['img_root'] != '')
		rrmdir($config['dir']['img_root'] . $board['uri']);
	
	if ($config['cache']['enabled']) cache::delete('board_' . $board['uri']);
	
	_syslog(LOG_NOTICE, "Board deleted: {$board['uri']}");
	if ($d['mod']) {
		$query = prepare('DELETE FROM ``mods`` WHERE `username` = BINARY :id');
		$query->bindValue(':id', $d['mod']);
		$query->execute() or error(db_error($query));
	}
}
fclose($f);
}
cache::delete('all_boards_uri');
cache::delete('all_boards');
rebuildThemes('boards');
$query = query('DELETE FROM board_create WHERE uri NOT IN (SELECT uri FROM boards);') or error(db_error());
