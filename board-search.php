<?php

include "inc/functions.php";

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
	'lang' => false,
	'nsfw' => true,
	'tags' => false,
);

// Include NSFW boards?
if (isset( $_GET['nsfw'] )) {
	$search['nsfw'] = (bool) $_GET['nsfw'];
}

// Include what language (if the language is not blank and we recognize it)?
if (isset( $_GET['lang'] ) && isset($languages[$search['lang']])) {
	$search['lang'] = $_GET['lang'];
}

// Include what tag?
if (isset( $_GET['tags'] )) {
	$search['tags'] = $_GET['tags'];
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

// Loop through each board and record activity to it.
// We will also be weighing and building a tag list.
foreach ($response['boards'] as $boardUri => &$board) {
	$board['active'] = (int) $boardActivity['active'][ $boardUri ];
	$board['pph'] = (int) $boardActivity['average'][ $boardUri ];
	
	if (isset($board['tags']) && count($board['tags']) > 0) {
		foreach ($board['tags'] as $tag) {
			if (isset($response['tag'][$tag])) {
				$response['tag'][$tag] += $board['active'];
			}
			else {
				$response['tag'][$tag] = $board['active'];
			}
		}
	}
}

// Get the top most popular tags.
if (count($response['tag']) > 0) {
	// Sort by most active tags.
	arsort( $response['tag'] );
	// Get the first n most active tags.
	$response['tag'] = array_splice( $response['tag'], 0, 200 );
	
	$tagLightest = end( array_keys( $response['tag'] ) );
}


/* (Please) Respond */
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