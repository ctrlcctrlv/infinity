<?php

/*
*  Instance Configuration
*  ----------------------
*  Edit this file and not config.php for imageboard configuration.
*
*  You can copy values from config.php (defaults) and paste them here.
*/
require_once "htmlpurifier-4.5.0/library/HTMLPurifier.auto.php";


	$config['db']['server'] = 'localhost';
	$config['db']['database'] = '8chan';
	$config['db']['prefix'] = '';
	$config['db']['user'] = 'root';
	$config['db']['password'] = 'pVFM1N5K';


	$config['cookies']['mod'] = 'mod';
	$config['cookies']['salt'] = 'YTBmNTUzZThkYWY5ZjYxNjIzOGQxYj';

	$config['flood_time'] = 10;
	$config['flood_time_ip'] = 120;
	$config['flood_time_same'] = 30;
	$config['max_body'] = 1800;
	$config['reply_limit'] = 250;
	$config['max_links'] = 20;
	$config['max_filesize'] = 10485760;
	$config['thumb_width'] = 255;
	$config['thumb_height'] = 255;
	$config['max_width'] = 10000;
	$config['max_height'] = 10000;
	$config['threads_per_page'] = 10;
	$config['max_pages'] = 10;
	$config['threads_preview'] = 5;
	$config['root'] = '/';
	$config['secure_trip_salt'] = 'OGNkMjQ4MGM5MDFkYmVhYWFhOGYwOG';

	// Image shit
	$config['thumb_method'] = 'gm';
	$config['show_ratio'] = true;
	$config['allow_upload_by_url'] = true;
	$config['max_filesize'] = 1024 * 1024; // 10MB
	$config['disable_images'] = false; 
	$config['spoiler_images'] = true;

	// Mod shit
	$config['mod']['groups'][25] = 'Supermod';
	define_groups();
	$config['mod']['capcode'][MOD] = array('Board Moderator');
	$config['mod']['capcode'][SUPERMOD] = array('Global Moderator');
	//$config['mod']['view_banlist'] = SUPERMOD;
	$config['mod']['manageusers'] = SUPERMOD;
	$config['mod']['noticeboard_post'] = SUPERMOD;
	$config['mod']['search'] = SUPERMOD;
	$config['mod']['debug_recent'] = ADMIN;
	$config['mod']['debug_antispam'] = ADMIN;

	// Board shit
	//$config['default_stylesheet'] = array('Notsuba', 'notsuba.css');
	$config['additional_javascript'][] = 'js/jquery.min.js';
	$config['additional_javascript'][] = 'js/jquery.tablesorter.min.js';
	$config['additional_javascript'][] = 'js/update_boards.js';
	
	$config['stylesheets_board'] = true;
	$config['markup'][] = array("/^[ |\t]*==(.+?)==[ |\t]*$/m", "<span class=\"heading\">\$1</span>");
	$config['markup'][] = array("/\[spoiler\](.+?)\[\/spoiler\]/", "<span class=\"spoiler\">\$1</span>");

	$config['boards'] = array(array('<i class="icon-home" title="Home"></i>' => '/', '<i class="icon-tags" title="Boards"></i>' => '/boards.php', '<i class="icon-question" title="FAQ"></i>' => '/faq.html', '<i class="icon-random" title="Random"></i>' => '/random.php', '<i class="icon-cog" title="Manage board"></i>' => '/mod.php', '<i class="icon-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b' => '/b', 'meta' => '/meta'));

	//$config['debug'] = true;

	if (!function_exists('prettify_textarea')){
		function prettify_textarea($s){
			return str_replace("\t", '&#09;', str_replace("\n", '&#13;&#10;', $s));
		}
	}

	if (!function_exists('purify')){
		function purify($s){
			$config = HTMLPurifier_Config::createDefault();
			$purifier = new HTMLPurifier($config);
			$clean_html = $purifier->purify($s);
			return $clean_html;
		}
	}

	$config['mod']['custom_pages']['/settings/(\%b)'] = function($b) {
		global $config, $mod;

		if (!in_array($b, $mod['boards']) and $mod['boards'][0] != '*')
			error($config['error']['noaccess']);
	
		if (!openBoard($b))
			error("Could not open board!");

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$title = $_POST['title'];
			$subtitle = $_POST['subtitle'];
			$country_flags = isset($_POST['country_flags']) ? 'true' : 'false';
			$field_disable_name = isset($_POST['field_disable_name']) ? 'true' : 'false';
			$enable_embedding = isset($_POST['enable_embedding']) ? 'true' : 'false';
			$force_image_op = isset($_POST['force_image_op']) ? 'true' : 'false';
			$disable_images = isset($_POST['disable_images']) ? 'true' : 'false';
			$poster_ids = isset($_POST['poster_ids']) ? 'true' : 'false';
			$code_tags = isset($_POST['code_tags']) ? '$config[\'additional_javascript\'][] = \'js/code_tags/run_prettify.js\';$config[\'markup\'][] = array("/\[code\](.+?)\[\/code\]/ms", "<code><pre class=\'prettyprint\' style=\'display:inline-block\'>\$1</pre></code>");' : '';
			$mathjax = isset($_POST['mathjax']) ? '$config[\'mathjax\'] = true;$config[\'additional_javascript\'][] = \'js/mathjax-MathJax-727332c/MathJax.js?config=TeX-AMS_HTML-full\';' : '';
			$anonymous = base64_encode($_POST['anonymous']);
			$blotter = base64_encode(purify(html_entity_decode($_POST['blotter'])));

			if (!(strlen($title) < 40))
				error('Invalid title');
			if (!(strlen($subtitle) < 200))
				error('Invalid subtitle');

			$query = prepare('UPDATE ``boards`` SET `title` = :title, `subtitle` = :subtitle WHERE `uri` = :uri');
			$query->bindValue(':title', $title);
			$query->bindValue(':subtitle', $subtitle);
			$query->bindValue(':uri', $b);
			$query->execute() or error(db_error($query));

			$config_file = <<<EOT
<?php
\$config['country_flags'] = $country_flags;
\$config['field_disable_name'] = $field_disable_name;
\$config['enable_embedding'] = $enable_embedding;
\$config['force_image_op'] = $force_image_op;
\$config['disable_images'] = $disable_images;
\$config['poster_ids'] = $poster_ids;
\$config['anonymous'] = base64_decode('$anonymous');
\$config['blotter'] = base64_decode('$blotter');
\$config['stylesheets']['Custom'] = 'board/$b.css';
\$config['default_stylesheet'] = array('Custom', \$config['stylesheets']['Custom']);
$code_tags
$mathjax
if (\$config['disable_images'])
	\$config['max_pages'] = 10000;
EOT;

			file_write($b.'/config.php', $config_file);
			file_write('stylesheets/board/'.$b.'.css', $_POST['css']);
			file_write($b.'/rules.html', Element('page.html', array('title'=>'Rules', 'subtitle'=>'', 'config'=>$config, 'body'=>'<div class="ban">'.purify($_POST['rules']).'</div>')));
			file_write($b.'/rules.txt', $_POST['rules']);
		}

		$query = prepare('SELECT * FROM boards WHERE uri = :board');
		$query->bindValue(':board', $b);
		$query->execute() or error(db_error($query));
		$board = $query->fetchAll()[0];

		$rules = @file_get_contents($board['uri'] . '/rules.txt');
		$css = @file_get_contents('stylesheets/board/' . $board['uri'] . '.css');
	
		openBoard($b);

		buildIndex();

		mod_page(_('Board configuration'), 'mod/settings.html', array('board'=>$board, 'rules'=>prettify_textarea($rules), 'css'=>prettify_textarea($css), 'token'=>make_secure_link_token('settings/'.$board['uri'])));
	};
