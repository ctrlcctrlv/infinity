<?php
require_once 'inc/functions.php';

$base=substr(dirname(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME'])), 1);
//echo "base[$base]<br>\n";

$path=trim($_SERVER['REQUEST_URI'], '/');
$path=str_replace($base.'/', '', $path);
if (file_exists($path) && !is_dir($path)) {
	if (strpos($path, 'css')!==false) {
		header('Content-type: text/css');
	}
	readfile($path);
	exit();
}
if (is_dir($path)) {
	$path.='/index.html';
}
$key='vichan_filecache_'.$path;
echo Cache::get($key);
?>
