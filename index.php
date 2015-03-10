<?php
include 'inc/functions.php';
$query = query('SELECT np.* FROM newsplus np INNER JOIN `posts_news+` p ON np.thread=p.id WHERE np.dead IS FALSE ORDER BY p.bump DESC');
$newsplus = $query->fetchAll(PDO::FETCH_ASSOC);

$index = Element("8chan/index.html", array("config" => $config, "newsplus" => $newsplus));
file_write('index.html', $index);
