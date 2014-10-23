<?php

/*
*  Instance Configuration
*  ----------------------
*  Edit this file and not config.php for imageboard configuration.
*
*  You can copy values from config.php (defaults) and paste them here.
*/
	require_once "lib/htmlpurifier-4.5.0/library/HTMLPurifier.auto.php";
	require_once "8chan-functions.php";

	// Note - you may want to change some of these in secrets.php instead of here
	// See the secrets.example.php file
	$config['db']['server'] = 'localhost';
	$config['db']['database'] = '8chan';
	$config['db']['prefix'] = '';
	$config['db']['user'] = 'root';
	$config['db']['password'] = '';
	$config['timezone'] = 'UTC';
	$config['cache']['enabled'] = 'apc';


	$config['cookies']['mod'] = 'mod';
	$config['cookies']['salt'] = '';

	$config['spam']['hidden_inputs_max_pass'] = 128;
	$config['spam']['hidden_inputs_expire'] = 60 * 60 * 4; // three hours

	$config['flood_time'] = 5;
	$config['flood_time_ip'] = 30;
	$config['flood_time_same'] = 2;
	$config['max_body'] = 5000;
	$config['reply_limit'] = 300;
	$config['thumb_width'] = 255;
	$config['thumb_height'] = 255;
	$config['max_width'] = 10000;
	$config['max_height'] = 10000;
	$config['threads_per_page'] = 15;
	$config['max_pages'] = 15;
	$config['threads_preview'] = 5;
	$config['root'] = '/';
	$config['secure_trip_salt'] = '';
	$config['always_noko'] = true;
	$config['allow_no_country'] = true;
	$config['thread_subject_in_title'] = true;
	$config['spam']['hidden_inputs_max_pass'] = 128;
	$config['ayah_enabled'] = true;

	// Load database credentials
	require "secrets.php";

	// Image shit
	$config['thumb_method'] = 'gm+gifsicle';
	$config['thumb_ext'] = '';
	$config['thumb_keep_animation_frames'] = 100;
	$config['show_ratio'] = true;
	//$config['allow_upload_by_url'] = true;
	$config['max_filesize'] = 1024 * 1024 * 8; // 8MB
	$config['disable_images'] = false; 
	$config['spoiler_images'] = true;
	$config['image_reject_repost'] = true;
	$config['allowed_ext_files'][] = 'webm';
	$config['webm']['use_ffmpeg'] = true;
	$config['webm']['allow_audio'] = true;
	$config['webm']['max_length'] = 60 * 15;

	// Mod shit
	$config['mod']['groups'][25] = 'Supermod';
	define_groups();
	$config['mod']['capcode'][MOD] = array('Board Volunteer');
	$config['mod']['capcode'][SUPERMOD] = array('Global Volunteer');
	$config['custom_capcode']['Admin'] = array(
		'<span class="capcode" style="color:blue;font-weight:bold"> <i class="fa fa-wheelchair"></i> %s</span>',
	);
	$config['custom_capcode']['Bear'] = array(
		'<span class="capcode" style="color:brown;font-weight:bold"> <img src="/static/paw.svg" height="12" width="12"> %s</span>',
	);
	//$config['mod']['view_banlist'] = SUPERMOD;
	$config['mod']['show_ip'] = SUPERMOD;
	$config['mod']['show_ip_less'] = MOD;
	$config['mod']['manageusers'] = SUPERMOD;
	$config['mod']['noticeboard_post'] = SUPERMOD;
	$config['mod']['search'] = SUPERMOD;
	$config['mod']['debug_recent'] = ADMIN;
	$config['mod']['debug_antispam'] = ADMIN;
	$config['mod']['modlog'] = SUPERMOD;
	$config['mod']['editpost'] = MOD;
	$config['mod']['edit_banners'] = MOD;
	$config['mod']['edit_flags'] = MOD;
	$config['mod']['edit_settings'] = MOD;
	$config['mod']['recent_reports'] = 65535;
	$config['mod']['ip_less_recentposts'] = 75;
	$config['ban_show_post'] = true;

	// Board shit
	$config['max_links'] = 40;
	$config['poster_id_length'] = 6;
	$config['ayah_enabled'] = false;
	$config['cbRecaptcha'] = true;
	$config['url_banner'] = '/banners.php';
	$config['additional_javascript_compile'] = true;
	//$config['default_stylesheet'] = array('Notsuba', 'notsuba.css');
	$config['additional_javascript'][] = 'js/jquery.min.js';
	$config['additional_javascript'][] = 'js/jquery.mixitup.min.js';
	$config['additional_javascript'][] = 'js/catalog.js';
	$config['additional_javascript'][] = 'js/jquery.tablesorter.min.js';
	$config['additional_javascript'][] = 'js/options.js';
	$config['additional_javascript'][] = 'js/style-select.js';
	$config['additional_javascript'][] = 'js/options/general.js';
	$config['additional_javascript'][] = 'js/post-hover.js';
	$config['additional_javascript'][] = 'js/update_boards.js';
	$config['additional_javascript'][] = 'js/favorites.js';
	$config['additional_javascript'][] = 'js/show-op.js';
	$config['additional_javascript'][] = 'js/hide-threads.js';
	$config['additional_javascript'][] = 'js/smartphone-spoiler.js';
	$config['additional_javascript'][] = 'js/inline-expanding.js';
	$config['additional_javascript'][] = 'js/show-backlinks.js';
	$config['additional_javascript'][] = 'js/webm-settings.js';
	$config['additional_javascript'][] = 'js/expand-video.js';
	$config['additional_javascript'][] = 'js/treeview.js';
	$config['additional_javascript'][] = 'js/quick-post-controls.js';
	$config['additional_javascript'][] = 'js/expand-too-long.js';
	$config['additional_javascript'][] = 'js/settings.js';
	$config['additional_javascript'][] = 'js/fix-report-delete-submit.js';
	$config['additional_javascript'][] = 'js/hide-images.js';
	$config['additional_javascript'][] = 'js/expand-all-images.js';
	$config['additional_javascript'][] = 'js/local-time.js';
	$config['additional_javascript'][] = 'js/no-animated-gif.js';
	$config['additional_javascript'][] = 'js/expand.js';
	$config['additional_javascript'][] = 'js/titlebar-notifications.js';
	$config['additional_javascript'][] = 'js/auto-reload.js';
	$config['additional_javascript'][] = 'js/quick-reply.js';
	$config['additional_javascript'][] = 'js/options/user-css.js';
	$config['additional_javascript'][] = 'js/options/user-js.js';
	$config['additional_javascript'][] = 'js/forced-anon.js';
	$config['additional_javascript'][] = 'js/toggle-locked-threads.js';
	$config['additional_javascript'][] = 'js/toggle-images.js';
	$config['additional_javascript'][] = 'js/mobile-style.js';
	$config['additional_javascript'][] = 'js/id_highlighter.js';
	$config['additional_javascript'][] = 'js/id_colors.js';
	$config['additional_javascript'][] = 'js/inline.js';
	$config['additional_javascript'][] = 'js/infinite-scroll.js';
	$config['additional_javascript'][] = 'js/download-original.js';
	$config['additional_javascript'][] = 'js/thread-watcher.js';
	$config['additional_javascript'][] = 'js/ajax.js';
	$config['additional_javascript'][] = 'js/show-own-posts.js';

	//$config['font_awesome_css'] = '/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css';
	
	$config['stylesheets']['Dark'] = 'dark.css';
	$config['stylesheets']['Photon'] = 'photon.css';

	$config['stylesheets_board'] = true;
	$config['markup'][] = array("/^[ |\t]*==(.+?)==[ |\t]*$/m", "<span class=\"heading\">\$1</span>");
	$config['markup'][] = array("/\[spoiler\](.+?)\[\/spoiler\]/", "<span class=\"spoiler\">\$1</span>");
	$config['markup'][] = array("/~~(.+?)~~/", "<s>\$1</s>");
	$config['markup'][] = array("/__(.+?)__/", "<u>\$1</u>");

	$config['boards'] = array(array('<i class="fa fa-home" title="Home"></i>' => '/', '<i class="fa fa-tags" title="Boards"></i>' => '/boards.html', '<i class="fa fa-question" title="FAQ"></i>' => '/faq.html', '<i class="fa fa-random" title="Random"></i>' => '/random.php', '<i class="fa fa-plus" title="New board"></i>' => '/create.php', '<i class="fa fa-ban" title="Public ban list"></i>' => '/bans.html', '<i class="fa fa-search" title="Search"></i>' => '/search.php', '<i class="fa fa-cog" title="Manage board"></i>' => '/mod.php', '<i class="fa fa-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b', 'meta', 'int'), array('<i class="fa fa-twitter" title="Twitter"></i>'=>'https://twitter.com/infinitechan'));
	//$config['boards'] = array(array('<i class="fa fa-home" title="Home"></i>' => '/', '<i class="fa fa-tags" title="Boards"></i>' => '/boards.html', '<i class="fa fa-question" title="FAQ"></i>' => '/faq.html', '<i class="fa fa-random" title="Random"></i>' => '/random.php', '<i class="fa fa-plus" title="New board"></i>' => '/create.php', '<i class="fa fa-search" title="Search"></i>' => '/search.php', '<i class="fa fa-cog" title="Manage board"></i>' => '/mod.php', '<i class="fa fa-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b', 'meta', 'int'), array('v', 'a', 'tg', 'fit', 'pol', 'tech', 'mu', 'co', 'sp', 'boards'), array('<i class="fa fa-twitter" title="Twitter"></i>'=>'https://twitter.com/infinitechan'));

	$config['footer'][] = 'Contribute to 8chan.co development at <a href="https://github.com/ctrlcctrlv/8chan">github</a>';
	$config['footer'][] = 'To make a DMCA request or report illegal content, please email <a href="mailto:admin@8chan.co">admin@8chan.co</a> or use the "Global Report" functionality on every page.';

	$config['search']['enable'] = true;

//$config['debug'] = true;
	$config['syslog'] = true;

	$config['wordfilters'][] = array('\rule', ''); // 'true' means it's a regular expression

	if (!function_exists('prettify_textarea')){
		function prettify_textarea($s){
			return str_replace("\t", '&#09;', str_replace("\n", '&#13;&#10;', htmlentities($s)));
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

	$config['mod']['custom_pages']['/flags/(\%b)'] = function($b) {
		global $config, $mod, $board;
		require_once 'inc/image.php';

		if (!hasPermission($config['mod']['edit_flags'], $b))
			error($config['mod']['noaccess']);

		if (!openBoard($b))
			error("Could not open board!");

		$dir = 'static/custom-flags/'.$b;

		if (!is_dir($dir)){
			mkdir($dir, 0777, true);
		}
	
		if (isset($_FILES['file'])){
			$upload = $_FILES['file']['tmp_name'];
			$banners = array_diff(scandir($dir), array('..', '.'));

			if (!is_readable($upload))
				error($config['error']['nomove']);

			$id = time() . substr(microtime(), 2, 3);
			$extension = strtolower(mb_substr($_FILES['file']['name'], mb_strrpos($_FILES['file']['name'], '.') + 1));

			if ($extension != 'png') {
				error(_('Flags must be in PNG format.'));
			}
	
			if (filesize($upload) > 48000){
				error(_('File too large!'));
			}

			if (!$size = @getimagesize($upload)) {
				error($config['error']['invalidimg']);
			}

			if ($size[0] != 16 or $size[1] != 11){
				error(_('Image wrong size!'));
			}
			if (sizeof($banners) >= 100) {
				error(_('Too many flags.'));
			}

			copy($upload, "$dir/$id.$extension");
		}

		if (isset($_POST['delete'])){
			foreach ($_POST['delete'] as $i => $d){
				if (!preg_match('/[0-9+]\.(png|jpeg|jpg|gif)/', $d)){
					error('Nice try.');
				}
				unlink("$dir/$d");
			}
		}

		$banners = array_diff(scandir($dir), array('..', '.'));
		mod_page(_('Edit banners'), 'mod/banners.html', array('board'=>$board,'banners'=>$banners,'token'=>make_secure_link_token('banners/'.$board['uri'])));
	};

	$config['mod']['custom_pages']['/banners/(\%b)'] = function($b) {
		global $config, $mod, $board;
		require_once 'inc/image.php';

		if (!hasPermission($config['mod']['edit_banners'], $b))
			error($config['error']['noaccess']);
	
		if (!openBoard($b))
			error("Could not open board!");

		$dir = 'static/banners/'.$b;

		if (!is_dir($dir)){
			mkdir($dir, 0777, true);
		}


		if (isset($_FILES['file'])){
			$upload = $_FILES['file']['tmp_name'];
			$banners = array_diff(scandir($dir), array('..', '.'));

			if (!is_readable($upload))
				error($config['error']['nomove']);

			$id = time() . substr(microtime(), 2, 3);
			$extension = strtolower(mb_substr($_FILES['file']['name'], mb_strrpos($_FILES['file']['name'], '.') + 1));

			if (!in_array($extension, array('jpg','jpeg','png','gif'))){
				error('Not an image extension.');
			}
	
			if (filesize($upload) > 512000){
				error('File too large!');
			}

			if (!$size = @getimagesize($upload)) {
				error($config['error']['invalidimg']);
			}

			if ($size[0] != 300 or $size[1] != 100){
				error('Image wrong size!');
			}
			if (sizeof($banners) >= 50) {
				error('Too many banners.');
			}

			copy($upload, "$dir/$id.$extension");
		}

		if (isset($_POST['delete'])){
			foreach ($_POST['delete'] as $i => $d){
				if (!preg_match('/[0-9+]\.(png|jpeg|jpg|gif)/', $d)){
					error('Nice try.');
				}
				unlink("$dir/$d");
			}
		}

		$banners = array_diff(scandir($dir), array('..', '.'));
		mod_page(_('Edit banners'), 'mod/banners.html', array('board'=>$board,'banners'=>$banners,'token'=>make_secure_link_token('banners/'.$board['uri'])));

	};

	$config['mod']['custom_pages']['/settings/(\%b)'] = function($b) {
		global $config, $mod;

		if (!in_array($b, $mod['boards']) and $mod['boards'][0] != '*')
			error($config['error']['noaccess']);
	
		if (!openBoard($b))
			error("Could not open board!");

		$possible_languages = array_diff(scandir('inc/locale/'), array('..', '.', '.tx', 'README.md'));

		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$title = $_POST['title'];
			$subtitle = $_POST['subtitle'];
			$country_flags = isset($_POST['country_flags']) ? 'true' : 'false';
			$field_disable_name = isset($_POST['field_disable_name']) ? 'true' : 'false';
			$enable_embedding = isset($_POST['enable_embedding']) ? 'true' : 'false';
			$force_image_op = isset($_POST['force_image_op']) ? 'true' : 'false';
			$disable_images = isset($_POST['disable_images']) ? 'true' : 'false';
			$poster_ids = isset($_POST['poster_ids']) ? 'true' : 'false';
			$show_sages = isset($_POST['show_sages']) ? 'true' : 'false';
			$auto_unicode = isset($_POST['auto_unicode']) ? 'true' : 'false';
			$allow_roll = isset($_POST['allow_roll']) ? 'true' : 'false';
			$image_reject_repost = isset($_POST['image_reject_repost']) ? 'true' : 'false';
			$allow_delete = isset($_POST['allow_delete']) ? 'true' : 'false';
			$allow_flash = isset($_POST['allow_flash']) ? '$config[\'allowed_ext_files\'][] = \'swf\';' : '';
			$code_tags = isset($_POST['code_tags']) ? '$config[\'additional_javascript\'][] = \'js/code_tags/run_prettify.js\';$config[\'markup\'][] = array("/\[code\](.+?)\[\/code\]/ms", "<code><pre class=\'prettyprint\' style=\'display:inline-block\'>\$1</pre></code>");' : '';
			$katex = isset($_POST['katex']) ? '$config[\'katex\'] = true;$config[\'additional_javascript\'][] = \'js/katex/katex.min.js\'; $config[\'markup\'][] = array("/\[tex\](.+?)\[\/tex\]/ms", "<span class=\'tex\'>\$1</span>"); $config[\'additional_javascript\'][] = \'js/katex-enable.js\';' : '';
$oekaki_js = <<<OEKAKI
    \$config['additional_javascript'][] = 'js/jquery-ui.custom.min.js';
    \$config['additional_javascript'][] = 'js/wPaint/lib/wColorPicker.min.js';
    \$config['additional_javascript'][] = 'js/wPaint/wPaint.min.js';
    \$config['additional_javascript'][] = 'js/wPaint/plugins/main/wPaint.menu.main.min.js';
    \$config['additional_javascript'][] = 'js/wPaint/plugins/text/wPaint.menu.text.min.js';
    \$config['additional_javascript'][] = 'js/wPaint/plugins/shapes/wPaint.menu.main.shapes.min.js';
    \$config['additional_javascript'][] = 'js/wPaint/plugins/file/wPaint.menu.main.file.min.js';
    \$config['additional_javascript'][] = 'js/wpaint.js';
    \$config['additional_javascript'][] = 'js/upload-selection.js';
OEKAKI;
			$oekaki = isset($_POST['oekaki']) ? $oekaki_js : '';
			if ($_POST['locale'] !== 'en' && in_array($_POST['locale'], $possible_languages)) {
				$locale = "\$config['locale'] = '{$_POST['locale']}.UTF-8';";
			} else {
				$locale = '';
			} 

			if (isset($_POST['max_images']) && (int)$_POST['max_images'] && (int)$_POST['max_images'] <= 5) {
				$_POST['max_images'] = (int)$_POST['max_images'];
				$multiimage = "\$config['max_images'] = {$_POST['max_images']};
					   \$config['additional_javascript'][] = 'js/multi-image.js';";
			} else {
				$multiimage = '';
			} 

			$anonymous = base64_encode($_POST['anonymous']);
			$blotter = base64_encode(purify(html_entity_decode($_POST['blotter'])));
			$add_to_config = @file_get_contents($b.'/extra_config.php');
			$replace = '';

			if (isset($_POST['replace'])) {
				if (count($_POST['replace']) == count($_POST['with'])) {
					foreach ($_POST['replace'] as $i => $r ) {
						if ($r !== '') {
							$w = $_POST['with'][$i];
							$replace .= '$config[\'wordfilters\'][] = array(base64_decode(\'' . base64_encode($r) . '\'), base64_decode(\'' . base64_encode($w) . '\'));';
						}
					}
				}
			}

			if (!(strlen($title) < 40))
				error('Invalid title');
			if (!(strlen($subtitle) < 200))
				error('Invalid subtitle');

			$query = prepare('UPDATE ``boards`` SET `title` = :title, `subtitle` = :subtitle, `indexed` = :indexed, `public_bans` = :public_bans, `8archive` = :8archive WHERE `uri` = :uri');
			$query->bindValue(':title', $title);
			$query->bindValue(':subtitle', $subtitle);
			$query->bindValue(':uri', $b);
			$query->bindValue(':indexed', !isset($_POST['meta_noindex']));
			$query->bindValue(':public_bans', isset($_POST['public_bans']));
			$query->bindValue(':8archive', isset($_POST['8archive']));
			$query->execute() or error(db_error($query));

			$config_file = <<<EOT
<?php
\$config['file_script'] = '$b/main.js';
\$config['country_flags'] = $country_flags;
\$config['field_disable_name'] = $field_disable_name;
\$config['enable_embedding'] = $enable_embedding;
\$config['force_image_op'] = $force_image_op;
\$config['disable_images'] = $disable_images;
\$config['poster_ids'] = $poster_ids;
\$config['show_sages'] = $show_sages;
\$config['auto_unicode'] = $auto_unicode;
\$config['allow_roll'] = $allow_roll;
\$config['image_reject_repost'] = $image_reject_repost;
\$config['allow_delete'] = $allow_delete;
\$config['anonymous'] = base64_decode('$anonymous');
\$config['blotter'] = base64_decode('$blotter');
\$config['stylesheets']['Custom'] = 'board/$b.css';
\$config['default_stylesheet'] = array('Custom', \$config['stylesheets']['Custom']);
$code_tags $katex $oekaki $replace $multiimage $allow_flash
if (\$config['disable_images'])
	\$config['max_pages'] = 10000;

$locale
$add_to_config
EOT;

			$query = query('SELECT `uri`, `title`, `subtitle` FROM ``boards`` WHERE `8archive` = TRUE');
			file_write('8archive.json', json_encode($query->fetchAll(PDO::FETCH_ASSOC)));
			file_write($b.'/config.php', $config_file);
			file_write('stylesheets/board/'.$b.'.css', $_POST['css']);
			file_write($b.'/rules.html', Element('page.html', array('title'=>'Rules', 'subtitle'=>'', 'config'=>$config, 'body'=>'<div class="ban">'.purify($_POST['rules']).'</div>')));
			file_write($b.'/rules.txt', $_POST['rules']);

			$_config = $config;

			openBoard($b);

			// be smarter about rebuilds...only some changes really require us to rebuild all threads
			if ($_config['blotter'] != $config['blotter'] || $_config['field_disable_name'] != $config['field_disable_name'] || $_config['show_sages'] != $config['show_sages']) {
				buildIndex();
				$query = query(sprintf("SELECT `id` FROM ``posts_%s`` WHERE `thread` IS NULL", $b)) or error(db_error());
				while ($post = $query->fetch(PDO::FETCH_ASSOC)) {
					buildThread($post['id']);
				}
			}
		
			buildJavascript();

			modLog('Edited board settings', $b);
		}

		$query = prepare('SELECT * FROM boards WHERE uri = :board');
		$query->bindValue(':board', $b);
		$query->execute() or error(db_error($query));
		$board = $query->fetchAll()[0];

		$rules = @file_get_contents($board['uri'] . '/rules.txt');
		$css = @file_get_contents('stylesheets/board/' . $board['uri'] . '.css');
	
		openBoard($b);

		rebuildThemes('bans');

		if ($config['cache']['enabled']) 
			cache::delete('board_' . $board['uri']);
			cache::delete('all_boards');

		mod_page(_('Board configuration'), 'mod/settings.html', array('board'=>$board, 'rules'=>prettify_textarea($rules), 'css'=>prettify_textarea($css), 'token'=>make_secure_link_token('settings/'.$board['uri']), 'languages'=>$possible_languages));
	};
	$config['embedding'] = array(
		array(
			'/^https?:\/\/(\w+\.)?youtube\.com\/watch\?v=([a-zA-Z0-9\-_]{10,11})(&.+)?$/i',
			'<iframe style="float: left;margin: 10px 20px;" width="%%tb_width%%" height="%%tb_height%%" frameborder="0" id="ytplayer" type="text/html" src="https://www.youtube.com/embed/$2"></iframe>'
		),
		array(
			'/^https?:\/\/(\w+\.)?vimeo\.com\/(\d{2,10})(\?.+)?$/i',
			'<object style="float: left;margin: 10px 20px;" width="%%tb_width%%" height="%%tb_height%%"><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="https://vimeo.com/moogaloop.swf?clip_id=$2&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=00adef&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" /><embed src="https://vimeo.com/moogaloop.swf?clip_id=$2&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=00adef&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="%%tb_width%%" height="%%tb_height%%"></embed></object>'
		),
		array(
			'/^https?:\/\/(\w+\.)?dailymotion\.com\/video\/([a-zA-Z0-9]{2,10})(_.+)?$/i',
			'<object style="float: left;margin: 10px 20px;" width="%%tb_width%%" height="%%tb_height%%"><param name="movie" value="https://www.dailymotion.com/swf/video/$2"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><param name="wmode" value="transparent"></param><embed type="application/x-shockwave-flash" src="https://www.dailymotion.com/swf/video/$2" width="%%tb_width%%" height="%%tb_height%%" wmode="transparent" allowfullscreen="true" allowscriptaccess="always"></embed></object>'
		),
		array(
			'/^https?:\/\/(\w+\.)?metacafe\.com\/watch\/(\d+)\/([a-zA-Z0-9_\-.]+)\/(\?.+)?$/i',
			'<div style="float:left;margin:10px 20px;width:%%tb_width%%px;height:%%tb_height%%px"><embed flashVars="playerVars=showStats=no|autoPlay=no" src="https://www.metacafe.com/fplayer/$2/$3.swf" width="%%tb_width%%" height="%%tb_height%%" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_$2" pluginspage="https://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></div>'
		),
		array(
			'/^https?:\/\/video\.google\.com\/videoplay\?docid=(\d+)([&#](.+)?)?$/i',
			'<embed src="https://video.google.com/googleplayer.swf?docid=$1&hl=en&fs=true" style="width:%%tb_width%%px;height:%%tb_height%%px;float:left;margin:10px 20px" allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash"></embed>'
		),
		array(
			'/^https?:\/\/(\w+\.)?vocaroo\.com\/i\/([a-zA-Z0-9]{2,15})$/i',
			'<object style="float: left;margin: 10px 20px;" width="148" height="44"><param name="movie" value="https://vocaroo.com/player.swf?playMediaID=$2&autoplay=0"></param><param name="wmode" value="transparent"></param><embed src="https://vocaroo.com/player.swf?playMediaID=$2&autoplay=0" width="148" height="44" wmode="transparent" type="application/x-shockwave-flash"></embed></object>'
		)
	);

$config['gzip_static'] = false;
