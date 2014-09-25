<?php

include "inc/functions.php";

$boards = listBoards(true);
$board = array_rand($boards);

header('Location: /'.$boards[$board]);

?>
