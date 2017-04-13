<?php
include 'inc/functions.php';

function outputError($errCode, $errString) {
  http_response_code($errCode);
  error ($errString);
  return 0;
}

$globalExists = isset($_GET['global']);
$postExists   = isset($_GET['post']);
$boardExists  = isset($_GET['board']);

if (!$postExists){
  outputError(400, "No input.");
}

if (!$boardExists){
  outputError(400, "No input.");
}

$post  = $_GET['post'];
$board = $_GET['board'];

if (!preg_match('/^delete_\d+$/', $post)){
  outputError(400, "Bad input.");
}

if (!openBoard($board)){
  outputError(404, "No board.");
}

if ($config['report_captcha']) {
  $captcha = generate_captcha($config['captcha']['extra']);
} else {
  $captcha = null;
}

$body = Element('report.html', ['global' => $globalExists, 'post' => $post, 'board' => $board, 'captcha' => $captcha, 'config' => $config]);
echo Element('page.html', ['config' => $config, 'body' => $body]);
