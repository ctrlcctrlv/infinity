<?php

// We want to return a value if we're included.
// Otherwise, we will be printing a JSON object-array.
$Included = defined("TINYBOARD");
if (!$Included) {
	include "inc/functions.php";
}

$CanViewUnindexed = isset($mod["type"]) && $mod["type"] <= GlobalVolunteer;


/* The expected output of this page is JSON. */
$response = array();


/* Determine search parameters from $_GET */
$search = array(
	'lang'  => false,
	'nsfw'  => true,
	'page'  => 0,
	'tags'  => false,
	'time'  => ( (int)( time() / 3600 ) * 3600 ) - 3600,
	'title' => false,
	
	'index' => count( $_GET ) == 0,
);

// Include NSFW boards?
if (isset( $_GET['sfw'] ) && $_GET['sfw'] != "") {
	$search['nsfw'] = !$_GET['sfw'];
}

// Bringing up more results
if (isset( $_GET['page'] ) && $_GET['page'] != "") {
	$search['page'] = (int) $_GET['page'];
	
	if ($search['page'] < 0) {
		$search['page'] = 0;
	}
}

// Include what language (if the language is not blank and we recognize it)?
if (isset( $_GET['lang'] ) && $_GET['lang'] != "" && isset($config['languages'][$_GET['lang']])) {
	$search['lang'] = $_GET['lang'];
}

// Include what tag?
if (isset( $_GET['tags'] ) && $_GET['tags'] != "") {
	if (!is_array($_GET['tags'])) {
		$search['tags'] = explode( " ", (string) $_GET['tags'] );
	}
	else {
		$search['tags'] = $_GET['tags'];
	}
	
	$search['tags'] = array_splice( $search['tags'], 0, 5 );
	
	foreach ($search['tags'] as &$tag)
	{
		$tag = strtolower( $tag );
	}
}

// What time range?
if (isset( $_GET['time'] ) && is_numeric( $_GET['time'] ) ) {
	$search['time'] = ( (int)( $_GET['time'] / 3600 ) * 3600 );
}

// Include what in the uri / title / subtitle?
if (isset( $_GET['title'] ) && $_GET['title'] != "") {
	$search['title'] = strtolower( $_GET['title'] );
}

/* Search boards */
$boards = listBoards();
$response['boards'] = array();

// Loop through our available boards and filter out inapplicable ones based on standard filtering.
foreach ($boards as $board) {
	// Checks we can do without looking at config.
	if (
		// Indexed, or we are staff,
		( $CanViewUnindexed !== true && !$board['indexed'] )
		// Not filtering NSFW, or board is SFW.
		|| ( $search['nsfw'] !== true && $board['sfw'] != 1 )
	) {
		continue;
	}
	
	// Are we searching by title?
	if ($search['title'] !== false) {
		// This checks each component of the board's identity against our search terms.
		// The weight determines order.
		// "left" would match /leftypol/ and /nkvd/ which has /leftypol/ in the title.
		// /leftypol/ would always appear above it but it would match both.
		if (strpos("/{$board['uri']}/", $search['title']) !== false) {
			$board['weight'] = 30;
		}
		else if (strpos(strtolower($board['title']), $search['title']) !== false) {
			$board['weight'] = 20;
		}
		else if (strpos(strtolower($board['subtitle']), $search['title']) !== false) {
			$board['weight'] = 10;
		}
		else {
			continue;
		}
		
		unset( $boardTitleString );
	}
	else {
		$board['weight'] = 0;
	}
	
	// Load board config.
	$boardConfig = loadBoardConfig( $board['uri'] );
	
	// Determine language/locale and tags.
	$boardLang = strtolower( array_slice( explode( "_", $boardConfig['locale'] ?: "" ), 0 )[0] ); // en_US -> en OR en -> en
	
	// Check against our config search options.
	if ($search['lang'] !== false && $search['lang'] != $boardLang) {
		continue;
	}
	
	if (isset($config['languages'][$boardLang])) {
		$board['locale'] = $config['languages'][$boardLang];
	}
	else {
		$board['locale'] = $boardLang;
	}
	
	$response['boards'][ $board['uri'] ] = $board;
}

unset( $boards );

/* Tag Fetching */
// (We have do this even if we're not filtering by tags so that we know what each board's tags are)

// Fetch all board tags for our boards.
$boardTags = fetchBoardTags( array_keys( $response['boards'] ) );

// Loop through each board and determine if there are tag matches.
foreach ($response['boards'] as $boardUri => &$board) {
	// If we are filtering by tag and there is no match, remove from the response.
	if ( $search['tags'] !== false && ( !isset( $boardTags[ $boardUri ] ) || count(array_intersect($search['tags'], $boardTags[ $boardUri ])) !== count($search['tags']) ) ) {
		unset( $response['boards'][$boardUri] );
		continue;
	}
	// If we aren't filtering / there is a match AND we have tags, set the tags.
	else if ( isset( $boardTags[ $boardUri ] ) && $boardTags[ $boardUri ] ) {
		$board['tags'] = $boardTags[ $boardUri ];
	}
	// Othrwise, just declare our tag array blank.
	else {
		$board['tags'] = array();
	}
	
	// Legacy support for API readers.
	$board['max'] = &$board['posts_total'];
}

unset( $boardTags );


/* Activity Fetching */
$boardActivity = fetchBoardActivity( array_keys( $response['boards'] ), $search['time'], true );

// Loop through each board and record activity to it.
// We will also be weighing and building a tag list.
foreach ($response['boards'] as $boardUri => &$board) {
	$board['active'] = 0;
	$board['pph']    = 0;
	$board['ppd']    = 0;
	
	if (isset($boardActivity['active'][ $boardUri ])) {
		$board['active'] = (int) $boardActivity['active'][ $boardUri ];
	}
	if (isset($boardActivity['average'][ $boardUri ])) {
		$precision = 0;
		
		if ($boardActivity['average'][ $boardUri ] > 0 && $boardActivity['average'][ $boardUri ] < 10) {
			$precision = 1;
		}
		
		$board['pph_average'] = round( $boardActivity['average'][ $boardUri ], $precision );
		$board['pph'] = (int) $boardActivity['last'][ $boardUri ];
		$board['ppd'] = round( $boardActivity['today'][ $boardUri ], $precision );
		
		unset( $precision );
	}
}

// Sort boards by their popularity, then by their total posts.
$boardActivityValues   = array();
$boardTotalPostsValues = array();
$boardWeightValues     = array();

foreach ($response['boards'] as $boardUri => &$board) {
	$boardActivityValues[$boardUri]   = (int) $board['active'];
	$boardTotalPostsValues[$boardUri] = (int) $board['posts_total']; 
	$boardWeightValues[$boardUri]     = (int) $board['weight']; 
}

array_multisort(
	$boardWeightValues, SORT_DESC, SORT_NUMERIC, // Sort by weight
	$boardActivityValues, SORT_DESC, SORT_NUMERIC, // Sort by number of active posters
	$boardTotalPostsValues, SORT_DESC, SORT_NUMERIC, // Then, sort by total number of posts
	$response['boards']
);

if (php_sapi_name() == 'cli') {
	$response['boardsFull'] = $response['boards'];
}

$boardLimit = $search['index'] ? 50 : 100;

$response['omitted'] = count( $response['boards'] ) - $boardLimit;
$response['omitted'] = $response['omitted'] < 0 ? 0 : $response['omitted'];
$response['boards']  = array_splice( $response['boards'], $search['page'], $boardLimit );
$response['order']   = array_keys( $response['boards'] );


// Loop through the truncated array to compile tags.
$response['tags'] = array();
$tagUsage = array( 'boards' => array(), 'users' => array() );

foreach ($response['boards'] as $boardUri => &$board) {
	if (isset($board['tags']) && count($board['tags']) > 0) {
		foreach ($board['tags'] as $tag) {
			if (!isset($tagUsage['boards'][$tag])) {
				$tagUsage['boards'][$tag] = 0;
			}
			if (!isset($tagUsage['users'][$tag])) {
				$tagUsage['users'][$tag] = 0;
			}
			
			$response['tags'][$tag] = true;
			++$tagUsage['boards'][$tag];
			$tagUsage['users'][$tag] += $board['active'];
		}
	}
}

// Get the top most popular tags.
if (count($response['tags']) > 0) {
	arsort( $tagUsage['boards'] );
	arsort( $tagUsage['users'] );
	
	array_multisort(
		$tagUsage['boards'], SORT_DESC, SORT_NUMERIC,
		$tagUsage['users'], SORT_DESC, SORT_NUMERIC,
		$response['tags']
	);
	
	// Get the first n most active tags.
	$response['tags'] = array_splice( $response['tags'], 0, 100 );
	$response['tagOrder'] = array_keys( $response['tags'] );
	$response['tagWeight'] = array();
	
	$tagsMostUsers  = max( $tagUsage['users'] );
	$tagsLeastUsers = min( $tagUsage['users'] );
	$tagsAvgUsers   = array_sum( $tagUsage['users'] ) / count( $tagUsage['users'] );
	
	$weightDepartureFurthest = 0;
	
	foreach ($tagUsage['users'] as $tagUsers) {
		$weightDeparture = abs( $tagUsers - $tagsAvgUsers );
		
		if( $weightDeparture > $weightDepartureFurthest ) {
			$weightDepartureFurthest = $weightDeparture;
		}
	}
	
	foreach ($tagUsage['users'] as $tagName => $tagUsers) {
		if ($weightDepartureFurthest != 0) {
			$weightDeparture = abs( $tagUsers - $tagsAvgUsers );
			$response['tagWeight'][$tagName] = 75 + round( 100 * ( $weightDeparture / $weightDepartureFurthest ), 0);
		}
		else {
			$response['tagWeight'][$tagName] = 100;
		}
	}
}

/* Include our interpreted search terms. */
$response['search'] = $search;

/* (Please) Respond */
if (!$Included) {
	$json = json_encode( $response );
	
	// Error Handling
	switch (json_last_error()) {
		case JSON_ERROR_NONE:
			$jsonError = false;
			break;
		case JSON_ERROR_DEPTH:
			$jsonError = 'Maximum stack depth exceeded';
			break;
		case JSON_ERROR_STATE_MISMATCH:
			$jsonError = 'Underflow or the modes mismatch';
			break;
		case JSON_ERROR_CTRL_CHAR:
			$jsonError = 'Unexpected control character found';
			break;
		case JSON_ERROR_SYNTAX:
			$jsonError = 'Syntax error, malformed JSON';
			break;
		case JSON_ERROR_UTF8:
			$jsonError = 'Malformed UTF-8 characters, possibly incorrectly encoded';
			break;
		default:
			$jsonError = 'Unknown error';
			break;
	}
	
	if ($jsonError) {
		$json = "{\"error\":\"{$jsonError}\"}";
	}
	
	// Successful output
	echo $json;
}
else {
	return $response;
}