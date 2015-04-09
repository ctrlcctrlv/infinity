<?php
require dirname(__FILE__) . '/inc/cli.php';

$query = prepare('SELECT * FROM bans');
$query->execute() or error(db_error($query));
$num_bans = $query->rowCount();
$iter = 0;

while ($ban = $query->fetch(PDO::FETCH_ASSOC)) {
	$iter++;

	if (!$ban['post'])
		continue;

	$match_urls = '(?xi)\b((?:https?://|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))';

	$matched = array();

	$post = json_decode($ban['post']);

	preg_match_all("#$match_urls#im", $post->body_nomarkup, $matched);

	if (!isset($matched[0]) || !$matched[0])
		continue;

	$post->body = str_replace($matched[0], '###Link-Removed###', $post->body);
	$post->body_nomarkup = str_replace($matched[0], '###Link-Removed###', $post->body_nomarkup);

	$update = prepare('UPDATE ``bans`` SET `post` = :post WHERE `id` = :id');
	$update->bindValue(':post', json_encode($post)); 
	$update->bindValue(':id', $ban['id']); 
	$update->execute() or error(db_error($update));
	echo "Processed $iter/$num_bans\n";
}
