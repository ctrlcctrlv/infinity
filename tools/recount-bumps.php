<?php
// A script to recount bumps to recover from a last-page-bump attack
// or to be run after the KusabaX Migration.

require dirname(__FILE__) . '/inc/cli.php';

if (!isset ($argv[1])) {
	die("Usage: tools/recount-bumps.php board_uri\n");
}
$board = $argv[1];

$q = prepare("SELECT `id`, `bump`, `time` FROM ``posts``
                    WHERE `board` = :board AND `thread` IS NULL");
$q->bindValue(':board', $board);
$q->execute();
while ($val = $q->fetch()) {
        $lc = prepare('SELECT MAX(`time`) AS `aq` FROM ``posts``
                               WHERE `board` = :board AND ((`thread` = :thread and
                               `email` != "sage" ) OR `id` = :thread');
        $lc->bindValue(':board', $board);
		
	$lc->bindValue(":thread", $val['id']);
	$lc->execute();

	$f = $lc->fetch();
        if ($val['bump'] != $f['aq']) {
                $query = prepare("UPDATE ``posts`` SET `bump`=:bump
	                                  WHERE `board` = :board AND `id`=:id");
		$query->bindValue(":bump", $f['aq']);
		$query->bindValue(":board", $board);
		$query->bindValue(":id", $val['id']);
                echo("Thread $val[id] - to be $val[bump] -> $f[aq]\n");
        }
	else {
		echo("Thread $val[id] ok\n");
	}
}

echo("done\n");
