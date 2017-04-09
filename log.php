<?php
include 'inc/functions.php';
include 'inc/mod/pages.php';

function outputError($errCode, $errString) {
  http_response_code($errCode);
  error ($errString);
  return 0;
}

if (!isset($_GET['board'])){
  outputError(400, 'No input.');
}

if (!preg_match("/{$config['board_regex']}/u", $_GET['board'])) {
  outputError(400, 'Bad input.');
}

if (!openBoard($_GET['board'])) {
  outputError(404, 'No Board.');
}

if ($board['public_logs'] == 0) {
  outputError(403, 'This board has public logs disabled. Ask the board owner to enable it.');
}

// Pagination starts at page 1 by default
$page = 1;
// Names are hidden by default
$hideNames = false;

// Show names if board is set for public logs
if ($board['public_logs'] == 2) {
  $hideNames = true;
}

// Set pagination if set in query string
if (isset($_GET['page'])) {
  $page = (int)$_GET['page'];
}

// Output mod board log
mod_board_log($board['uri'], $page, $hideNames, true);
