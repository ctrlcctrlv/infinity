<?php
require dirname(__FILE__) . '/inc/cli.php';

# edited_at column was using DATETIME when time column uses INT(11). This script solves that.

$boards = listBoards(TRUE);
#$boards = array('test2');

foreach ($boards as $i => $b) {
	echo "Processing board $b...";
	query(sprintf('ALTER TABLE ``posts_%s`` ADD COLUMN cycle INT(1) NOT NULL AFTER locked', $b));
}
