<?php

include "inc/functions.php";

$admin         = isset($mod["type"]) && $mod["type"]<=30;
$founding_date = "October 23, 2013";

if (php_sapi_name() == 'fpm-fcgi' && !$admin && count($_GET) == 0) {
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
$tagQueryGet    = $_GET;
$tagQueryTags   = isset($tagQueryGet['tags']) ? $tagQueryGet['tags'] : "";
unset($tagQueryGet['tags']);
$tagQueryGet['tags'] = $tagQueryTags;
$tag_query      = "/boards.php?" . http_build_query( $tagQueryGet ) . ($tagQueryTags != "" ? "+" : "");

/* Create and distribute page */
// buildJavascript();

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

$query = query('SELECT np.* FROM newsplus np INNER JOIN `posts_n` p ON np.thread=p.id WHERE np.dead IS FALSE ORDER BY p.bump DESC');
if ($query) {
	$newsplus = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
	$newsplus = false;
}

$searchArray = array(
		"config"         => $config,
		"boards"         => $boards,
		"tags"           => $tags,
		"search"         => $searchJson['search'],
		"languages"      => $config['languages'],
		
		"boards_total"   => $boards_total,
		"boards_public"  => $boards_public,
		"boards_hidden"  => $boards_hidden,
		"boards_omitted" => $boards_omitted,
		
		"posts_hour"     => $posts_hour,
		"posts_total"    => $posts_total,
		
		"founding_date"  => $founding_date,
		"page_updated"   => date('r'),
		
		"html_boards"    => $boardsHTML,
		"html_tags"      => $tagsHTML,
		"newsplus" => $newsplus
);

$searchHTML = Element("8chan/boards-index.html", $searchArray);

$pageHTML = Element("page.html", array(
		"title" => "8chan, the infinitely expanding imageboard",
		"config" => $config,
		"body"   => $searchHTML,
	)
);

$searchHTML2 = Element("8chan/boards-search.html", $searchArray);

$pageHTML2 = Element("page.html", array(
		"title" => "Boards on 8chan",
		"config" => $config,
		"body"   => $searchHTML2,
	)
);


// We only want to cache if this is not a dynamic form request.
// Otherwise, our information will be skewed by the search criteria.
if (php_sapi_name() == 'cli') {
	// Preserves the JSON output format of [{board},{board}].
	$nonAssociativeBoardList = array_values($response['boardsFull']);
	
	file_write("index.html", $pageHTML);
	file_write("boards.html", $pageHTML2);
	file_write("boards.json", json_encode($nonAssociativeBoardList));
	
	$topbar = array();
	foreach ($boards as $i => $b) {
		if (is_array($config['no_top_bar_boards']) && !in_array($b['uri'], $config['no_top_bar_boards'])) {
			$topbar[] = $b;
		}
	}
	
	file_write("boards-top20.json", json_encode(array_splice($topbar, 0, 48)));
}

echo $pageHTML;
