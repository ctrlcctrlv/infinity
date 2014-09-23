<?php

include "inc/functions.php";
include "inc/mod/auth.php";
$admin = isset($mod["type"]) && $mod["type"]<=30;

if (php_sapi_name() == 'fpm-fcgi' && !$admin) {
	error('Cannot be run directly.');
}
$boards = listBoards();

$body = <<<CSS
<style>
th.header {
    background-image: url(/static/bg.gif); 
    cursor: pointer; 
    background-repeat: no-repeat; 
    background-position: center right; 
    padding-left: 20px; 
    margin-left: -1px; 
}
th.headerSortUp { 
    background-image: url(/static/asc.gif); 
} 
th.headerSortDown { 
    background-image: url(/static/desc.gif); 
} 
.flag-eo {
    background-image: url(/static/eo.png);
}
.flag-en {
    background-image: url(/static/en.png);
}
</style>
CSS;
$body .= '<table class="modlog" style="width:auto"><thead><tr><th>L</th><th>Board</th><th>Posts in last hour</th><th>Total posts</th><th>Created</th></thead></tr><tbody>';
$total_posts_hour = 0;
$total_posts = 0;

foreach ($boards as $i => $board) {

	//$query = prepare(sprintf("SELECT (SELECT MAX(id) from ``posts_%s``) AS max, (SELECT MAX(id) FROM ``posts_%s`` WHERE FROM_UNIXTIME(time) < DATE_SUB(NOW(), INTERVAL 1 HOUR)) AS oldmax, (SELECT MAX(id) from ``posts_%s``) AS max_d, (SELECT MAX(id) FROM ``posts_%s`` WHERE FROM_UNIXTIME(time) < DATE_SUB(NOW(), INTERVAL 1 DAY)) AS oldmax_d, (SELECT count(id) FROM ``posts_%s``) AS count;", $board['uri'], $board['uri'], $board['uri'], $board['uri'], $board['uri']));

	$query = prepare(sprintf("
SELECT MAX(id) max, (SELECT COUNT(*) FROM ``posts_%s`` WHERE FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 1 DAY)) ppd, 
(SELECT COUNT(*) FROM ``posts_%s`` WHERE FROM_UNIXTIME(time) > DATE_SUB(NOW(), INTERVAL 1 HOUR)) pph,
(SELECT count(id) FROM ``posts_%s``) count FROM ``posts_%s``
", $board['uri'], $board['uri'], $board['uri'], $board['uri']));
	$query->execute() or error(db_error($query));
	$r = $query->fetch(PDO::FETCH_ASSOC);

	$pph = $r['pph'];
	$ppd = $r['ppd'];

	$total_posts_hour += $pph;
	$total_posts += $r['max'];

	$boards[$i]['pph'] = $pph;
	$boards[$i]['ppd'] = $ppd;
	$boards[$i]['max'] = $r['max'];
}

usort($boards, 
function ($a, $b) { 
	$x = $b['ppd'] - $a['ppd']; 
	if ($x) { return $x; 
	//} else { return strcmp($a['uri'], $b['uri']); }
	} else { return $b['max'] - $a['max']; }
});

$hidden_boards_total = 0;
foreach ($boards as $i => &$board) {
	$board_config = @file_get_contents($board['uri'].'/config.php');
	$boardCONFIG = array();
	if ($board_config && $board['uri'] !== 'int') {
		$board_config = str_replace('$config', '$boardCONFIG', $board_config);
		$board_config = str_replace('<?php', '', $board_config);
		eval($board_config);
		$showboard = (!isset($boardCONFIG['meta_noindex']) || !$boardCONFIG['meta_noindex']);
	}
	$locale = isset($boardCONFIG['locale'])?$boardCONFIG['locale']:'en';

	$board['title'] = htmlentities(utf8tohtml($board['title']));
	$locale_arr = explode('_', $locale);
	$locale_short = isset($locale_arr[1]) ? strtolower($locale_arr[1]) : strtolower($locale_arr[0]);
	$locale_short = str_replace('.utf-8', '', $locale_short);
	if ($board['uri'] === 'int') {$locale_short = 'eo'; $locale = 'eo';}

	$img = "<img class=\"flag flag-$locale_short\" src=\"/static/blank.gif\" style=\"width:16px;height:11px;\" alt=\"$locale\" title=\"$locale\">";

	if ($showboard || $admin) {
		if (!$showboard) {
			$lock = ' <i class="fa fa-lock" title="No index"></i>';
		} else {
			$lock = '';
		}
		$board['ago'] = human_time_diff(strtotime($board['time']));
		$body .= "<tr><td>$img</td><td><a href='/{$board['uri']}/' title=\"{$board['title']}\">/{$board['uri']}/</a>$lock</td><td style='text-align:right'>{$board['pph']}</td><td style='text-align:right'>{$board['max']}</td><td>{$board['time']} ({$board['ago']} ago)</td></tr>";
	} else {
		unset($boards[$i]);
		$hidden_boards_total += 1;
	}
}

$body .= <<<FOOTER
</tbody></table><script>
    /*$.tablesorter.addParser({ 
        id: 'flags', 
        is: function(s) { 
            return false; 
        }, 
        format: function(s) { 
            return 0; 
        }, 
        type: 'text' 
    }); */
     
    $(function() { 
$('table').tablesorter({sortList: [[2,1]], 
textExtraction: function(node) {
	childNode = node.childNodes[0];
	if (!childNode) { return node.innerHTML; }
	if (childNode.tagName == 'IMG') {
		return childNode.getAttribute('class');
	} else {
		return (childNode.innerHTML ? childNode.innerHTML : childNode.textContent);
	}
}
});
    }); 
</script>
FOOTER;

$n_boards = sizeof($boards);
$t_boards = $hidden_boards_total + $n_boards;

$body = "<p style='text-align:center'>There are currently <strong>{$n_boards}</strong> boards + <strong>$hidden_boards_total</strong> unindexed boards = <strong>$t_boards</strong> total boards. Site-wide, {$total_posts_hour} posts have been made in the last hour, with {$total_posts} being made on all active boards since October 23, 2013.</p>" . $body;

//date_default_timezone_set('UTC');
$body .= "<p style='text-align:center'><em>Page last updated: ".date('r')."</em></p>";
$body .= "<p style='text-align:center'>".shell_exec('uptime -p')." without interruption</p>";

$config['additional_javascript'] = array('js/jquery.min.js', 'js/jquery.tablesorter.min.js');
$html = Element("page.html", array("config" => $config, "body" => $body, "title" => "Boards on &infin;chan"));
if ($admin) {
	echo $html;
} else {
	file_write("boards.json", json_encode($boards));
	file_write("boards.html", $html);
	echo 'Done';
}

