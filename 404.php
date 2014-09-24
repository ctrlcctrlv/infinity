<?php

include "inc/functions.php";

$errorimages = array("http://www.submitawebsite.com/blog/wp-content/uploads/2010/06/404.png",
					"http://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Georgia_404.svg/750px-Georgia_404.svg.png");

$errorimage = $errorimages[array_rand($errorimages)];

$page = <<<EOT
	<div class="ban">
		<h2 style="text-align: center">404 Not Found</h2>
		
		<img src="{$errorimage}" style="width: 700px;">
	</div>
EOT;

echo Element("page.html", array("config" => $config, "body" => $page, "title" => ""));
