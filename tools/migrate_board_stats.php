<?php
require dirname(__FILE__) . '/inc/cli.php';

/* Convert AI value to colun value for ez access */
// Add column `posts_total` to `boards`.
// This can potentially error if ran multiple times.. but that shouldn't kill the script
echo "Altering `boards` to add `posts_total`...\n";
query( "ALTER TABLE `boards` ADD COLUMN `posts_total` INT(11) UNSIGNED NOT NULL DEFAULT 0" );

// Set the value for posts_total for each board.
echo "Updating `boards` to include `posts_total` values...\n";
$tablePrefix = "{$config['db']['prefix']}posts_";

$aiQuery = prepare("SELECT `TABLE_NAME`, `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = \"{$config['db']['database']}\"");
$aiQuery->execute() or error(db_error($aiQuery));
$aiResult = $aiQuery->fetchAll(PDO::FETCH_ASSOC);

foreach ($aiResult as $aiRow) {
	$uri   = str_replace( $tablePrefix, "", $aiRow['TABLE_NAME'] );
	$posts = (int)($aiRow['AUTO_INCREMENT'] - 1); // Don't worry! The column is unsigned. -1 becomes 0.
	
	echo "   {$uri} has {$posts} post".($posts!=1?"s":"")."\n";
	query( "UPDATE `boards` SET `posts_total`={$posts} WHERE `uri`=\"{$uri}\";" );
}

unset( $aiQuery, $aiResult, $uri, $posts );

/* Add statistics table and transmute post information to that */
// Add `board_stats`
echo "Adding `board_stats` ...\n";
query(
	"CREATE TABLE IF NOT EXISTS ``board_stats`` (
		`stat_uri` VARCHAR(58) NOT NULL,
		`stat_hour` INT(11) UNSIGNED NOT NULL,
		`post_count` INT(11) UNSIGNED NULL,
		`post_id_array` TEXT NULL,
		`author_ip_count` INT(11) UNSIGNED NULL,
		`author_ip_array` TEXT NULL,
		PRIMARY KEY (`stat_uri`, `stat_hour`)
	);"
);

$boards = listBoards();

echo "Translating posts to stats ...\n";
foreach ($boards as $board) {
	$postQuery = prepare("SELECT `id`, `time`, `ip` FROM ``posts_{$board['uri']}``");
	$postQuery->execute() or error(db_error($postQuery));
	$postResult = $postQuery->fetchAll(PDO::FETCH_ASSOC);
	
	// Determine the number of posts for each hour.
	$postHour = array();
	
	foreach ($postResult as $post) {
		// Winds back timestamp to last hour. (1428947438  -> 1428944400)
		$postHourTime = (int)($post['time'] / 3600) * 3600;
		
		if (!isset($postHour[ $postHourTime ])) {
			$postHour[ $postHourTime ] = array();
		}
		
		$postDatum = &$postHour[ $postHourTime ];
		
		// Add to post count.
		if (!isset($postDatum['post_count'])) {
			$postDatum['post_count'] = 1;
		}
		else {
			++$postDatum['post_count'];
		}
		
		// Add to post id array.
		if (!isset($postDatum['post_id_array'])) {
			$postDatum['post_id_array'] = array( (int)$post['id'] );
		}
		else {
			$postDatum['post_id_array'][] = (int)$post['id'];
		}
		
		// Add to ip array.
		if (!isset($postDatum['author_ip_array'])) {
			$postDatum['author_ip_array'] = array();
		}
		
		$postDatum['author_ip_array'][ less_ip( $post['ip'] ) ] = 1;
		
		unset( $postHourTime );
	}
	
	// Prep data for insert.
	foreach ($postHour as $postHourTime => &$postHourData) {
		$postDatum = &$postHour[ $postHourTime ];
		
		// Serialize arrays for TEXT insert.
		$postDatum['post_id_array']   = str_replace( "\"", "\\\"", serialize( $postDatum['post_id_array'] ) );
		$postDatum['author_ip_count'] = count( array_keys( $postDatum['author_ip_array'] ) );
		$postDatum['author_ip_array'] = str_replace( "\"", "\\\"", serialize( array_keys( $postDatum['author_ip_array'] ) ) );
	}
	
	// Bash this shit together into a set of insert statements.
	$statsInserts = array();
	
	foreach ($postHour as $postHourTime => $postHourData) {
		$statsInserts[] = "(\"{$board['uri']}\", \"{$postHourTime}\", \"{$postHourData['post_count']}\", \"{$postHourData['post_id_array']}\", \"{$postHourData['author_ip_count']}\", \"{$postHourData['author_ip_array']}\" )";
	}
	
	if (count($statsInserts) > 0) {
		$statsInsert = "VALUES" . implode( ", ", $statsInserts );
		echo "   {$board['uri']} is building " . count($statsInserts) . " stat rows.\n";
		
		// Insert this data into our statistics table.
		$postStatQuery = prepare(
			"REPLACE INTO ``board_stats`` (stat_uri, stat_hour, post_count, post_id_array, author_ip_count, author_ip_array) {$statsInsert}"
		);
		$postStatQuery->execute() or error(db_error($postStatQuery));
	}
	else {
		echo "   {$board['uri']} has no posts!\n";
	}
	
	unset( $postQuery, $postResult, $postStatQuery, $postHour, $statsInserts, $statsInsert );
}


echo "Done! ^^;";