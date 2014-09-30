<?php
// A script to recount bumps to recover from a last-page-bump attack
// or to be run after the KusabaX Migration.

require dirname(__FILE__) . '/inc/cli.php';

if (!isset ($argv[1])) {
	die("Usage: tools/recount-bumps.php board_uri\n");
}
$board = $argv[1];

$q = query(sprintf("SELECT `id_for_board`, `bump`, `time` FROM ``posts``
                    WHERE `board` = '%s' AND `thread` IS NULL", $board));
while ($val = $q->fetch()) {
        $lc = prepare(sprintf('SELECT MAX(`time`) AS `aq` FROM ``posts``
                               WHERE `board` = "%s" AND ((`thread` = :thread and
			       `email` != "sage" ) OR `id_for_board` = :thread', $board));
		
	$lc->bindValue(":thread", $val['id_for_board']);
	$lc->execute();

	$f = $lc->fetch();
        if ($val['bump'] != $f['aq']) {
                $query = prepare("UPDATE ``posts`` SET `bump`=:bump
	                                  WHERE `board` = :board AND `id_for_board`=:id_for_board");
		$query->bindValue(":bump", $f['aq']);
		$query->bindValue(":board", $board);
		$query->bindValue(":id_for_board", $val['id_for_board']);
                echo("Thread $val[id_for_board] - to be $val[bump] -> $f[aq]\n");
        }
	else {
		echo("Thread $val[id_for_board] ok\n");
	}
}

echo("done\n");
