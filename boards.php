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
	if (isset($searchJson['tags'])) {
		$tags   = $searchJson['tags'];
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

/* Create and distribute page */
$config['additional_javascript'] = array(
	'js/jquery.min.js',
	'js/board-directory.js'
);

$boardsHTML = Element("8chan/boards-table.html", array(
		"config"        => $config,
		"boards"        => $boards,
	)
);

$tagsHTML = Element("8chan/boards-tags.html", array(
		"config"        => $config,
		"tags"          => $tags,
	)
);

$searchHTML = Element("8chan/boards-search.html", array(
		"config"         => $config,
		
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