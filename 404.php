<?php

include "inc/functions.php";

$dir = "static/404/";

if (!is_dir($dir))
	mkdir($dir);

if ($config['cache']['enabled']) {
	$files = cache::get('notfound_files');
}

if (!isset($files) or !$files) {
	$files = array_diff(scandir($dir), array('..', '.'));

	if ($config['cache']['enabled']) {
		cache::set('notfound_files', $files);
	}
}

if (count($files) == 0) {
	$errorimage = false;
} else {
	$errorimage = $files[array_rand($files)];
}

$page = <<<EOT
		<div class="ban">
			<p style="text-align:center"><img src="/static/404/{$errorimage}" style="width:100%"></p>
		</div>
EOT;

echo Element("page.html", array("config" => $config, "body" => $errorimage ? $page : "", "title" => "404 Not Found"));
