<?php

include "inc/functions.php";
include "inc/cache.php";

if(!file_exists("404/"))
	mkdir("404/");

$cache = new Cache;
$cache->init();

if($cache->get("404glob") == false){
$files = glob("404/*.*");
$cache->set("404glob", $files);
}else
$files = $cache->get("404glob");

if(count($files) == 0)
	$errorimage = "";
else
	$errorimage = $files[array_rand($files)];

$page = <<<EOT
	<center>
		<div class="ban">
			<h2>404 Not Found</h2>
			
			<img src="{$errorimage}" style="width: 700px;">
		</div>
	</center>
EOT;

echo Element("page.html", array("config" => $config, "body" => $page, "title" => ""));
