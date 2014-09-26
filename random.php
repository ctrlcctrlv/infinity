<?php

include "inc/functions.php";

$boards = listBoards(TRUE);
$board = array_rand($boards);
header('Location: /'.$boards[$board]);
?>
