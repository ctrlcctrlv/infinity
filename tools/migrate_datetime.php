<?php
require dirname(__FILE__) . '/inc/cli.php';

# edited_at column was using DATETIME when time column uses INT(11). This script solves that.

$boards = listBoards(TRUE);
#$boards = array('test2');

foreach ($boards as $i => $b) {
	query(sprintf('ALTER TABLE ``posts_%s`` ADD COLUMN edited_at_temp INT(11) DEFAULT NULL AFTER edited_at', $b));
	query(sprintf('UPDATE ``posts_%s`` SET edited_at_temp = IF(edited_at IS NOT NULL, UNIX_TIMESTAMP(edited_at), NULL)', $b));
	query(sprintf('ALTER TABLE ``posts_%s`` DROP COLUMN edited_at', $b));
	query(sprintf('ALTER TABLE ``posts_%s`` CHANGE edited_at_temp edited_at INT(11) DEFAULT NULL', $b));
}
