<?php

/*
*  Instance Configuration
*  ----------------------
*  Edit this file and not config.php for imageboard configuration.
*
*  You can copy values from config.php (defaults) and paste them here.
*/
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
	

	// Image shit
	$config['thumb_method'] = 'convert';
	$config['thumb_ext'] = 'jpg';
	$config['thumb_keep_animation_frames'] = 1;
	$config['show_ratio'] = true;
	//$config['allow_upload_by_url'] = true;
	$config['max_filesize'] = 1024 * 1024 * 8; // 8MB
	$config['spoiler_images'] = true;
	$config['image_reject_repost'] = true;
	$config['allowed_ext_files'][] = 'webm';
	$config['allowed_ext_files'][] = 'mp4';
	$config['webm']['use_ffmpeg'] = true;
	$config['webm']['allow_audio'] = true;
	$config['webm']['max_length'] = 60 * 30;

	// Mod shit
	$config['mod']['groups'][25] = 'GlobalVolunteer';
	$config['mod']['groups'][19] = 'BoardVolunteer';
	define_groups();
	$config['mod']['capcode'][BOARDVOLUNTEER] = array('Board Volunteer');
	$config['mod']['capcode'][MOD] = array('Board Owner');
	$config['mod']['capcode'][GLOBALVOLUNTEER] = array('Global Volunteer');
	$config['mod']['capcode'][ADMIN] = array('Admin', 'Global Volunteer');
	$config['custom_capcode']['Admin'] = array(
		'<span class="capcode" title="This post was written by the global 8chan administrator."> <i class="fa fa-wheelchair" style="color:blue;"></i> <span style="color:red">8chan Administrator</span></span>',
	);
	//$config['mod']['view_banlist'] = GLOBALVOLUNTEER;
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
	$config['additional_javascript'][] = 'js/jquery-ui.custom.min.js';
	$config['additional_javascript'][] = 'js/catalog.js';
	$config['additional_javascript'][] = 'js/captcha.js';
	$config['additional_javascript'][] = 'js/jquery.tablesorter.min.js';
	$config['additional_javascript'][] = 'js/options.js';
	$config['additional_javascript'][] = 'js/style-select.js';
	$config['additional_javascript'][] = 'js/options/general.js';
	$config['additional_javascript'][] = 'js/post-hover.js';
	$config['additional_javascript'][] = 'js/update_boards.js';
	$config['additional_javascript'][] = 'js/favorites.js';
	$config['additional_javascript'][] = 'js/show-op.js';
	$config['additional_javascript'][] = 'js/smartphone-spoiler.js';
	$config['additional_javascript'][] = 'js/inline-expanding.js';
	$config['additional_javascript'][] = 'js/show-backlinks.js';
	$config['additional_javascript'][] = 'js/webm-settings.js';
	$config['additional_javascript'][] = 'js/expand-video.js';
	$config['additional_javascript'][] = 'js/treeview.js';
	$config['additional_javascript'][] = 'js/expand-too-long.js';
	$config['additional_javascript'][] = 'js/settings.js';
	$config['additional_javascript'][] = 'js/hide-images.js';
	$config['additional_javascript'][] = 'js/expand-all-images.js';
	$config['additional_javascript'][] = 'js/local-time.js';
	$config['additional_javascript'][] = 'js/no-animated-gif.js';
	$config['additional_javascript'][] = 'js/expand.js';
	$config['additional_javascript'][] = 'js/auto-reload.js';
	$config['additional_javascript'][] = 'js/options/user-css.js';
	$config['additional_javascript'][] = 'js/options/user-js.js';
	$config['additional_javascript'][] = 'js/options/fav.js';
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
	$config['additional_javascript'][] = 'js/quick-reply.js';
	$config['additional_javascript'][] = 'js/show-own-posts.js';
	$config['additional_javascript'][] = 'js/youtube.js';
	$config['additional_javascript'][] = 'js/comment-toolbar.js';
	$config['additional_javascript'][] = 'js/catalog-search.js';
	$config['additional_javascript'][] = 'js/thread-stats.js';
	$config['additional_javascript'][] = 'js/quote-selection.js';
	$config['additional_javascript'][] = 'js/flag-previews.js';
	$config['additional_javascript'][] = 'js/post-menu.js';
	$config['additional_javascript'][] = 'js/post-filter.js';
	$config['additional_javascript'][] = 'js/fix-report-delete-submit.js';
	$config['additional_javascript'][] = 'js/image-hover.js';
	$config['additional_javascript'][] = 'js/auto-scroll.js';
	$config['additional_javascript'][] = 'js/twemoji/twemoji.js';
	$config['additional_javascript'][] = 'js/file-selector.js';
	// Oekaki (now depends on config.oekaki so can be in all scripts)
	$config['additional_javascript'][] = 'js/jquery-ui.custom.min.js';
	$config['additional_javascript'][] = 'js/wPaint/8ch.js';
	$config['additional_javascript'][] = 'js/wpaint.js';
	// Code tags (fix because we no longer have different scripts for each board)
	$config['additional_javascript'][] = 'js/code_tags/run_prettify.js';

	//$config['font_awesome_css'] = '/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css';
	
	$config['stylesheets']['Dark'] = 'dark.css';
	$config['stylesheets']['Photon'] = 'photon.css';
	$config['stylesheets']['Redchanit'] = 'redchanit.css';

	$config['stylesheets_board'] = true;
	$config['markup'][] = array("/^[ |\t]*==(.+?)==[ |\t]*$/m", "<span class=\"heading\">\$1</span>");
	$config['markup'][] = array("/\[spoiler\](.+?)\[\/spoiler\]/", "<span class=\"spoiler\">\$1</span>");
	$config['markup'][] = array("/~~(.+?)~~/", "<s>\$1</s>");
	$config['markup'][] = array("/__(.+?)__/", "<u>\$1</u>");
	$config['markup'][] = array("/###([^\s']+)###/", "<a href='/boards.html#\$1'>###\$1###</a>");

	$config['boards'] = array(array('<i class="fa fa-home" title="Home"></i>' => '/', '<i class="fa fa-tags" title="Boards"></i>' => '/boards.html', '<i class="fa fa-question" title="FAQ"></i>' => '/faq.html', '<i class="fa fa-random" title="Random"></i>' => '/random.php', '<i class="fa fa-plus" title="New board"></i>' => '/create.php', '<i class="fa fa-ban" title="Public ban list"></i>' => '/bans.html', '<i class="fa fa-search" title="Search"></i>' => '/search.php', '<i class="fa fa-cog" title="Manage board"></i>' => '/mod.php', '<i class="fa fa-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b', 'news+', 'boards'), array('operate', 'meta'), array('<i class="fa fa-twitter" title="Twitter"></i>'=>'https://twitter.com/infinitechan'));
	//$config['boards'] = array(array('<i class="fa fa-home" title="Home"></i>' => '/', '<i class="fa fa-tags" title="Boards"></i>' => '/boards.html', '<i class="fa fa-question" title="FAQ"></i>' => '/faq.html', '<i class="fa fa-random" title="Random"></i>' => '/random.php', '<i class="fa fa-plus" title="New board"></i>' => '/create.php', '<i class="fa fa-search" title="Search"></i>' => '/search.php', '<i class="fa fa-cog" title="Manage board"></i>' => '/mod.php', '<i class="fa fa-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b', 'meta', 'int'), array('v', 'a', 'tg', 'fit', 'pol', 'tech', 'mu', 'co', 'sp', 'boards'), array('<i class="fa fa-twitter" title="Twitter"></i>'=>'https://twitter.com/infinitechan'));

	$config['footer'][] = 'All posts on 8chan are the responsibility of the individual poster and not the administration of 8chan, pursuant to 47 U.S.C. &sect; 230.';
	$config['footer'][] = 'We have not been served any secret court orders and are not under any gag orders.';
	$config['footer'][] = 'To make a DMCA request or report illegal content, please email <a href="mailto:admin@8chan.co">admin@8chan.co</a>.';

	$config['search']['enable'] = true;

	$config['syslog'] = true;

	$config['hour_max_threads'] = 10;
	$config['filters'][] = array(
		'condition' => array(
			'custom' => 'max_posts_per_hour'
		),
		'action' => 'reject',
		'message' => 'On this board, to prevent raids the number of threads that can be created per hour is limited. Please try again later, or post in an existing thread.'
	);

$config['gzip_static'] = false;
$config['hash_masked_ip'] = true;
$config['force_subject_op'] = false;
$config['min_links'] = 0;
$config['min_body'] = 0;
$config['early_404'] = false;
$config['early_404_page'] = 5;
$config['early_404_replies'] = 10;
$config['cron_bans'] = true;
$config['mask_db_error'] = true;
$config['ban_appeals'] = true;
$config['show_sages'] = false;
$config['katex'] = false;
$config['enable_antibot'] = false;
$config['spam']['unicode'] = false;
$config['twig_cache'] = false;
$config['report_captcha'] = true;

$config['page_404'] = 'page_404';

// 8chan specific mod pages
require '8chan-mod-config.php';

// Load instance functions later on
require_once 'instance-functions.php';
	
// Load database credentials
require "secrets.php";
