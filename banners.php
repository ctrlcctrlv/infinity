<?php

include 'inc/functions.php';

header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Expires: 0");
if (!isset($_GET['board'])) {
	header("Location: static/8chan banner.png");
	die();
}
$b = $_GET['board'];
if (!preg_match('/^[a-z0-9]{1,10}$/', $b))
	header("Location: static/8chan banner.png");

$dir = 'static/banners/'.$b;

if (is_dir($dir)) {
	$banners = array_diff(scandir($dir), array('..', '.'));
	$r = array_rand($banners);
} else {
	$banners = array();
}

if (!empty($banners)) {
	header("Location: $dir/{$banners[$r]}");
} else {
	header("Location: static/8chan banner.png");
}
