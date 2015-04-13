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


/* Prefetch some information. */
$languages = array(
	"en",
	"es",
);


/* Determine search parameters from $_GET */
$search = array(
	'lang'  => false,
	'nsfw'  => true,
	'tags'  => false,
	'title' => false,
);

// Include NSFW boards?
if (isset( $_GET['sfw'] ) && $_GET['sfw'] != "") {
	$search['nsfw'] = !$_GET['sfw'];
}

// Include what language (if the language is not blank and we recognize it)?
if (isset( $_GET['lang'] ) && $_GET['lang'] != "" && isset($languages[$search['lang']])) {
	$search['lang'] = $_GET['lang'];
}

// Include what tag?
if (isset( $_GET['tags'] ) && $_GET['tags'] != "") {
	$search['tags'] = $_GET['tags'];
}
// Include what in the uri / title / subtitle?
if (isset( $_GET['title'] ) && $_GET['title'] != "") {
	$search['title'] = $_GET['title'];
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
	
	// Load board config.
	$boardConfig = loadBoardConfig( $board['uri'] );
	
	// Determine language/locale and tags.
	$boardLang = strtolower( array_slice( explode( "_", $boardConfig['locale'] ?: "" ), 0 )[0] ); // en_US -> en OR en -> en
	
	// Check against our config search options.
	if ( $search['lang'] !== false && $search['lang'] != $boardLang ) {
		continue;
	}
	
	$board['locale'] = $boardLang;
	
	$response['boards'][ $board['uri'] ] = $board;
}


/* Tag Fetching */
// (We have do this even if we're not filtering by tags so that we know what each board's tags are)

// Fetch all board tags for our boards.
$boardTags = fetchBoardTags( array_keys( $response['boards'] ) );

// Loop through each board and determine if there are tag matches.
foreach ($response['boards'] as $boardUri => &$board) {
	// If we are filtering by tag and there is no match, remove from the response.
	if ( $search['tags'] !== false && ( !isset( $boardTags[ $boardUri ] ) || !in_array( $search['tags'], $boardTags[ $boardUri ] )) ) {
		unset( $response['boards'][$boardUri] );
	}
	// If we aren't filtering / there is a match AND we have tags, set the tags.
	else if ( isset( $boardTags[ $boardUri ] ) && $boardTags[ $boardUri ] ) {
		$board['tags'] = $boardTags[ $boardUri ];
	}
	// Othrwise, just declare our tag array blank.
	else {
		$board['tags'] = array();
	}
}


/* Activity Fetching */
$boardActivity = fetchBoardActivity( array_keys( $response['boards'] ) );
$response['tags'] = array();

// Loop through each board and record activity to it.
// We will also be weighing and building a tag list.
foreach ($response['boards'] as $boardUri => &$board) {
	$board['active'] = (int) $boardActivity['active'][ $boardUri ];
	$board['pph']    = (int) $boardActivity['average'][ $boardUri ];
	
	if (isset($board['tags']) && count($board['tags']) > 0) {
		foreach ($board['tags'] as $tag) {
			if (isset($response['tags'][$tag])) {
				$response['tags'][$tag] += $board['active'];
			}
			else {
				$response['tags'][$tag] = $board['active'];
			}
		}
	}
}

// Sort boards by their popularity, then by their total posts.
$boardActivityValues   = array();
$boardTotalPostsValues = array();

foreach ($response['boards'] as $boardUri => &$board) {
	$boardActivityValues[$boardUri]   = (int) $board['active'];
	$boardTotalPostsValues[$boardUri] = (int) $board['posts_total']; 
}

array_multisort(
	$boardActivityValues, SORT_DESC, SORT_NUMERIC, // Sort by number of active posters
	$boardTotalPostsValues, SORT_DESC, SORT_NUMERIC, // Then, sort by total number of posts
	$response['boards']
);

// Get the top most popular tags.
if (count($response['tags']) > 0) {
	// Sort by most active tags.
	arsort( $response['tags'] );
	// Get the first n most active tags.
	$response['tags'] = array_splice( $response['tags'], 0, 200 );
	
	// $tagLightest = end( array_keys( $response['tag'] ) );
}


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