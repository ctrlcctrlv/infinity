<?php

include "inc/functions.php";
include "inc/mod/auth.php";
include "inc/countries.php";

$admin = isset($mod["type"]) && $mod["type"]<=30;

if (php_sapi_name() == 'fpm-fcgi' && !$admin) {
	error('Cannot be run directly.');
}
$boards = listBoards();

$total_posts_hour = 0;
$total_posts = 0;

foreach ($boards as $i => $board) {
	$query = prepare("
	SELECT
	  (SELECT coalesce((SELECT max(`id`) FROM ``posts`` WHERE `board` = :board),0)) max,
		(SELECT COUNT(*) FROM ``posts`` WHERE `board` = :board AND FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 1 DAY)) ppd,
		(SELECT COUNT(*) FROM ``posts`` WHERE `board` = :board AND FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 1 HOUR)) pph,
		(SELECT count(id) FROM ``posts`` WHERE `board` = :board) count,
    (SELECT COUNT(DISTINCT ip) FROM ``posts`` WHERE `board` = :board AND FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 3 DAY)) uniq_ip
	FROM ``posts``
	WHERE `board` = :board");
	$query->bindValue(':board', $board['uri']);
  $pdo->beginTransaction();
	$query->execute() or error(db_error($query));
  $pdo->commit();
	$r = $query->fetch(PDO::FETCH_ASSOC);

	$pph = $r['pph'];
	$ppd = $r['ppd'];

	$total_posts_hour += $pph;
	$total_posts += $r['max'];

	$boards[$i]['pph'] = $pph;
	$boards[$i]['ppd'] = $ppd;
	$boards[$i]['max'] = $r['max'];
	$boards[$i]['uniq_ip'] = $r['uniq_ip'];
}

usort($boards, 
function ($a, $b) { 
	$x = $b['uniq_ip'] - $a['uniq_ip']; 
	if ($x) { return $x; 
	//} else { return strcmp($a['uri'], $b['uri']); }
	} else { return $b['max'] - $a['max']; }
});

$hidden_boards_total = 0;
$rows = array();
foreach ($boards as $i => &$board) {
	$board_config = @file_get_contents($board['uri'].'/config.php');
	$boardCONFIG = array();
	if ($board_config && $board['uri'] !== 'int') {
		$board_config = str_replace('$config', '$boardCONFIG', $board_config);
		$board_config = str_replace('<?php', '', $board_config);
		eval($board_config);
	}
	$showboard = $board['indexed'];
	$locale = isset($boardCONFIG['locale'])?$boardCONFIG['locale']:'en';

	$board['title'] = utf8tohtml($board['title']);
	$locale_arr = explode('_', $locale);
	$locale_short = isset($locale_arr[1]) ? strtolower($locale_arr[1]) : strtolower($locale_arr[0]);
	$locale_short = str_replace('.utf-8', '', $locale_short);
	$country = get_country($locale_short);
	if ($board['uri'] === 'int') {$locale_short = 'eo'; $locale = 'eo'; $country = 'Esperanto';}

	$board['img'] = "<img class=\"flag flag-$locale_short\" src=\"/static/blank.gif\" style=\"width:16px;height:11px;\" alt=\"$country\" title=\"$country\">";

	if ($showboard || $admin) {
		if (!$showboard) {
			$lock = ' <i class="fa fa-lock" title="No index"></i>';
		} else {
			$lock = '';
		}
		$board['ago'] = human_time_diff(strtotime($board['time']));
	} else {
		unset($boards[$i]);
		$hidden_boards_total += 1;
	}
}

$n_boards = sizeof($boards);
$t_boards = $hidden_boards_total + $n_boards;

$config['additional_javascript'] = array('js/jquery.min.js', 'js/jquery.tablesorter.min.js');
$body = Element("8chan/boards.html", array("config" => $config, "n_boards" => $n_boards, "t_boards" => $t_boards, "hidden_boards_total" => $hidden_boards_total, "total_posts" => $total_posts, "total_posts_hour" => $total_posts_hour, "boards" => $boards, "last_update" => date('r'), "uptime_p" => shell_exec('uptime -p')));

$html = Element("page.html", array("config" => $config, "body" => $body, "title" => "Boards on &infin;chan"));
if ($admin) {
	echo $html;
} else {
	file_write("boards.json", json_encode($boards));
	file_write("boards.html", $html);
	echo 'Done';
}

