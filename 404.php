<?php

include "inc/functions.php";

if(!file_exists("404/"))
	mkdir("404/");

$files = glob("404/*.*");

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
