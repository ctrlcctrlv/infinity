<?php
include 'inc/functions.php';
if (php_sapi_name() == 'fpm-fcgi') {
	error('Cannot be run directly.');
}
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

	$query = query("SELECT id, username FROM mods WHERE boards = '$board' AND type = 20");
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
			if ($crt) $last_activity_date->setTimestamp($crt[0]);
			$last_mod_date = false;
		}
	}
	
	if (($last_activity_date < $ago or ($last_mod_date and $last_mod_date < $mod_ago))) {
		return array($last_activity_date, $last_mod_date, $mods);
	}
	else {
		return false;
	}
}
$q = query("SELECT uri FROM boards");
$boards = $q->fetchAll(PDO::FETCH_COLUMN);
$delete = array();
foreach($boards as $board) {
	$last_activity = last_activity($board);

	if ($last_activity) {
		list($last_activity_date, $last_mod_date, $mods) = $last_activity;

		$last_mod_f = $last_mod_date ? $last_mod_date->format('Y-m-d H:i:s') : '<em>never</em>';
		$last_activity_f = $last_activity_date ? $last_activity_date->format('Y-m-d H:i:s') : '<em>never</em>';
		$delete[] = array('board' => $board, 'last_activity_date' => $last_activity_f, 'last_mod' => $last_mod_date, 'last_mod_f' => $last_mod_f);
	}
}
$body = Element("8chan/claim.html", array("config" => $config, "delete" => $delete));
file_write("claim.html", Element("page.html", array("config" => $config, "body" => $body, "title" => _("Claim"), "subtitle" => _("Take deserted boards back from their owners"))));
