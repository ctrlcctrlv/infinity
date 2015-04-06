<?php

require_once "inc/functions.php";
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

global $config;

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

if (preg_match('!'.$config['board_regex'].'/'.$config['dir']['res'].'\d+\.html!u', $_SERVER['REQUEST_URI'])) {
	$return_link = '<a href="../index.html">[ Return ]</a>';
} else {
	$return_link = '';
}

$ad = Element("ad_top.html", array());

$page = <<<EOT
		<div style="text-align:center">$ad</div>
		<div class="ban">
			<p style="text-align:center"><img src="/static/404/{$errorimage}" style="width:100%"></p>
			<p style="text-align:center"><a href="/index.html">[ Home ]</a>{$return_link}</p>
		</div>

		<script type="text/javascript">
			if (localStorage.favorites) {
				var faves = JSON.parse(localStorage.favorites);

				$.each(faves, function(k, v) {
					if ((window.location.pathname === '/' + v + '/') || (window.location.pathname === '/' + v)) {
						faves.splice(k, 1);
						localStorage.favorites = JSON.stringify(faves);

						alert('As /' + v + '/ no longer exists, it has been removed from your favorites.');
					}
				})
			}
		</script>
EOT;

echo Element("page.html", array("config" => $config, "body" => $errorimage ? $page : "", "title" => "404 Not Found"));
