<?php

include "inc/functions.php";

$boards = listBoards();
$board = array_rand($boards);

header('Location: /'.$boards[$board]["uri"]);

?>