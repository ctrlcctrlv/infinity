<?php
require dirname(__FILE__) . '/inc/cli.php';

# edited_at column was using DATETIME when time column uses INT(11). This script solves that.

#$boards = listBoards(TRUE);
#$boards = array('tester');

/*foreach ($boards as $i => $b) {
	echo "Processing board $b...\n";
	query(sprintf('ALTER TABLE ``posts_%s`` ADD COLUMN edited_at_temp INT(11) DEFAULT NULL AFTER edited_at', $b));
	query(sprintf('UPDATE ``posts_%s`` SET edited_at_temp = IF(edited_at IS NOT NULL, UNIX_TIMESTAMP(edited_at), NULL)', $b));
	query(sprintf('ALTER TABLE ``posts_%s`` DROP COLUMN edited_at', $b));
	query(sprintf('ALTER TABLE ``posts_%s`` CHANGE edited_at_temp edited_at INT(11) DEFAULT NULL', $b));
}*/

$bans2fix = query('SELECT * FROM ``bans`` WHERE post IS NOT NULL');

while ($post = $bans2fix->fetch(PDO::FETCH_ASSOC)) {
	$p = json_decode($post['post'], true);
	if (!isset($p['edited_at']) || !$p['edited_at']) continue;
	$nea = ((string)strtotime($p['edited_at']));
	$p['edited_at'] = $nea;
	$query = prepare('UPDATE ``bans`` SET post = :post WHERE id = :id');
	$query->bindValue(':post', json_encode($p));
	$query->bindValue(':id', $post['id']);
	$query->execute() or error(db_error($query));
}

