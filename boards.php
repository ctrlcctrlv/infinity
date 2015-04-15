<?php

include "inc/functions.php";
include "inc/countries.php";

$admin         = isset($mod["type"]) && $mod["type"]<=30;
$founding_date = "October 23, 2013";

if (php_sapi_name() == 'fpm-fcgi' && !$admin) {
	error('Cannot be run directly.');
}

/* Build parameters for page */
$searchJson = include "board-search.php";
$boards     = array();
$tags       = array();

if (count($searchJson)) {
	if (isset($searchJson['boards'])) {
		$boards = $searchJson['boards'];
	}
	if (isset($searchJson['tagWeight'])) {
		$tags   = $searchJson['tagWeight'];
	}
}

$boardQuery = prepare("SELECT COUNT(1) AS 'boards_total', SUM(indexed) AS 'boards_public', SUM(posts_total) AS 'posts_total' FROM ``boards``");
$boardQuery->execute() or error(db_error($tagQuery));
$boardResult = $boardQuery->fetchAll(PDO::FETCH_ASSOC)[0];

$boards_total   = number_format( $boardResult['boards_total'], 0 );
$boards_public  = number_format( $boardResult['boards_public'], 0 );
$boards_hidden  = number_format( $boardResult['boards_total'] - $boardResult['boards_public'], 0 );
$boards_omitted = (int) $searchJson['omitted'];

$posts_hour     = number_format( fetchBoardActivity(), 0 );
$posts_total    = number_format( $boardResult['posts_total'], 0 );

// This incredibly stupid looking chunk of code builds a query string using existing information.
// It's used to make clickable tags for users without JavaScript for graceful degredation.
// Because of how it orders tags, what you end up with is a prefix that always ends in tags=x+
// ?tags= or ?sfw=1&tags= or ?title=foo&tags=bar+ - etc
$tagQueryGet = $_GET;
$tagQueryTags = isset($tagQueryGet['tags']) ? $tagQueryGet['tags'] : "";
unset($tagQueryGet['tags']);
$tagQueryGet['tags'] = $tagQueryTags;
$tag_query      = "?" . http_build_query( $tagQueryGet ) . ($tagQueryTags != "" ? "+" : "");

/* Create and distribute page */
$config['additional_javascript'] = array(
	'js/jquery.min.js',
	'js/board-directory.js'
);

$boardsHTML = Element("8chan/boards-table.html", array(
		"config"         => $config,
		"boards"         => $boards,
		"tag_query"      => $tag_query,
		
	)
);

$tagsHTML = Element("8chan/boards-tags.html", array(
		"config"         => $config,
		"tags"           => $tags,
		"tag_query"      => $tag_query,
		
	)
);

$searchHTML = Element("8chan/boards-search.html", array(
		"config"         => $config,
		"boards"         => $boards,
		"tags"           => $tags,
		"search"         => $searchJson['search'],
		
		"boards_total"   => $boards_total,
		"boards_public"  => $boards_public,
		"boards_hidden"  => $boards_hidden,
		"boards_omitted" => $boards_omitted,
		
		"posts_hour"     => $posts_hour,
		"posts_total"    => $posts_total,
		
		"founding_date"  => $founding_date,
		"page_updated"   => date('r'),
		"uptime"         => shell_exec('uptime -p'),
		
		"html_boards"    => $boardsHTML,
		"html_tags"      => $tagsHTML
	)
);

$pageHTML = Element("page.html", array(
		"config" => $config,
		"body"   => $searchHTML,
		"title"  => "Boards on &infin;chan"
	)
);

file_write("boards.html", $pageHTML);
echo $pageHTML;