#!/usr/bin/php
<?php
/*
 *  delete-stray-images.php - there was a period when undoImage() was not working at all. This meant that
 *  if an error occured while uploading an image, the uploaded images might not have been deleted.
 *
 *  This script iterates through every board and deletes any stray files in src/ or thumb/ that don't
 *  exist in the database.
 *
 */

require dirname(__FILE__) . '/inc/cli.php';

$boards = array('wx');

foreach ($boards as $b) {
	openBoard($b);
	echo "/{$board['uri']}/... ";
	
	$query = query(sprintf("SELECT `files` FROM ``posts_%s`` WHERE `files` IS NOT NULL", $board['uri']));
	$valid_src = array();
	$valid_thumb = array();
	
	while ($post = $query->fetch(PDO::FETCH_ASSOC)) {
		$files = json_decode($post['files'], true);
		if (!$files or !is_array($files)) continue;
		foreach ($files as $i => $f) {
			if (!isset($f['file']) or !isset($f['thumb'])) continue;
			$valid_src[] = $f['file'];
			$valid_thumb[] = $f['thumb'];
		}
	}
	
	$files_src = array_map('basename', glob($board['dir'] . $config['dir']['img'] . '*'));
	$files_thumb = array_map('basename', glob($board['dir'] . $config['dir']['thumb'] . '*'));
	
	$stray_src = array_diff($files_src, $valid_src);
	$stray_thumb = array_diff($files_thumb, $valid_thumb);
	
	$stats = array(
		'deleted' => 0,
		'size' => 0
	);
	//print_r($valid_src); print_r($stray_src); die();
	
	foreach ($stray_src as $src) {
		$stats['deleted']++;
		$size = filesize($board['dir'] . $config['dir']['img'] . $src);
		$stats['size'] += $size;
		echo "Will delete " . $board['dir'] . $config['dir']['img'] . $src . " ($size)\n";
		if (!file_unlink($board['dir'] . $config['dir']['img'] . $src)) {
			$er = error_get_last();
			die("error: " . $er['message'] . "\n");
		}
	}
		
	foreach ($stray_thumb as $thumb) {
		$stats['deleted']++;
		$size = filesize($board['dir'] . $config['dir']['thumb'] . $thumb);
		$stats['size'] += $size;
		echo "Will delete " . $board['dir'] . $config['dir']['thumb'] . $thumb . " ($size)\n";
		if (!file_unlink($board['dir'] . $config['dir']['thumb'] . $thumb)) {
			$er = error_get_last();
			die("error: " . $er['message'] . "\n");
		}
	}
	
	echo sprintf("deleted %s files (%s)\n", $stats['deleted'], format_bytes($stats['size']));
}
