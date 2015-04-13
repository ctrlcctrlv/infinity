<?php

include "inc/functions.php"; // October 23, 2013
include "inc/countries.php";

$admin = isset($mod["type"]) && $mod["type"]<=30;

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

/* $query = prepare(sprintf("
SELECT IFNULL(MAX(id),0) max,
(SELECT COUNT(*) FROM ``posts_%s`` WHERE FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 1 HOUR)) pph,
(SELECT COUNT(DISTINCT ip) FROM ``posts_%s`` WHERE FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 3 DAY)) uniq_ip
 FROM ``posts_%s``
", $board['uri'], $board['uri'], $board['uri'], $board['uri'], $board['uri']));
	$query->execute() or error(db_error($query));
	$r = $query->fetch(PDO::FETCH_ASSOC); */

$boardQuery = prepare("SELECT COUNT(1) AS 'boards_total', COUNT(indexed) AS 'boards_public' FROM ``boards``");
$boardQuery->execute() or error(db_error($tagQuery));
$boardResult = $boardQuery->fetchAll(PDO::FETCH_ASSOC)[0];

$boards_total  = $boardResult['boards_total'];
$boards_public = $boardResult['boards_public'];
$boards_hidden = $boardResult['boards_total'] - $boardResult['boards_public'];

$posts_hour    = 0;
$posts_total   = 0;

/* Create and distribute page */
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
		"config"        => $config,
		
		"boards_total"  => $boards_total,
		"boards_public" => $boards_public,
		"boards_hidden" => $boards_hidden,
		
		"posts_hour"    => $posts_hour,
		"posts_total"   => $posts_total,
		
		"page_updated"  => date('r'),
		"uptime"        => shell_exec('uptime -p'),
		
		"html_boards"   => $boardsHTML,
		"html_tags"     => $tagsHTML
	)
);

$config['additional_javascript'] = array(
	'js/jquery.min.js',
	'js/board-directory.js'
);

$pageHTML = Element("page.html", array(
		"config" => $config,
		"body"   => $searchHTML,
		"title"  => "Boards on &infin;chan"
	)
);

file_write("boards.html", $pageHTML);
echo $pageHTML;