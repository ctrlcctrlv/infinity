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
	$config['mod']['groups'][25] = 'GlobalVolunteer';
	$config['mod']['groups'][19] = 'BoardVolunteer';
	define_groups();
	$config['mod']['capcode'][BOARDVOLUNTEER] = array('Board Volunteer');
	$config['mod']['capcode'][MOD] = array('Board Owner');
	$config['mod']['capcode'][GLOBALVOLUNTEER] = array('Global Volunteer');
	$config['custom_capcode']['Admin'] = array(
		'<span class="capcode" style="color:blue;font-weight:bold"> <i class="fa fa-wheelchair"></i> %s</span>',
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
	$config['additional_javascript'][] = 'js/youtube.js';
	$config['additional_javascript'][] = 'js/comment-toolbar.js';

	//$config['font_awesome_css'] = '/netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css';
	
	$config['stylesheets']['Dark'] = 'dark.css';
	$config['stylesheets']['Photon'] = 'photon.css';

	$config['stylesheets_board'] = true;
	$config['markup'][] = array("/^[ |\t]*==(.+?)==[ |\t]*$/m", "<span class=\"heading\">\$1</span>");
	$config['markup'][] = array("/\[spoiler\](.+?)\[\/spoiler\]/", "<span class=\"spoiler\">\$1</span>");
	$config['markup'][] = array("/~~(.+?)~~/", "<s>\$1</s>");
	$config['markup'][] = array("/__(.+?)__/", "<u>\$1</u>");

	$config['boards'] = array(array('<i class="fa fa-home" title="Home"></i>' => '/', '<i class="fa fa-tags" title="Boards"></i>' => '/boards.html', '<i class="fa fa-question" title="FAQ"></i>' => '/faq.html', '<i class="fa fa-random" title="Random"></i>' => '/random.php', '<i class="fa fa-plus" title="New board"></i>' => '/create.php', '<i class="fa fa-ban" title="Public ban list"></i>' => '/bans.html', '<i class="fa fa-search" title="Search"></i>' => '/search.php', '<i class="fa fa-cog" title="Manage board"></i>' => '/mod.php', '<i class="fa fa-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b', 'meta'), array('<i class="fa fa-twitter" title="Twitter"></i>'=>'https://twitter.com/infinitechan'));
	//$config['boards'] = array(array('<i class="fa fa-home" title="Home"></i>' => '/', '<i class="fa fa-tags" title="Boards"></i>' => '/boards.html', '<i class="fa fa-question" title="FAQ"></i>' => '/faq.html', '<i class="fa fa-random" title="Random"></i>' => '/random.php', '<i class="fa fa-plus" title="New board"></i>' => '/create.php', '<i class="fa fa-search" title="Search"></i>' => '/search.php', '<i class="fa fa-cog" title="Manage board"></i>' => '/mod.php', '<i class="fa fa-quote-right" title="Chat"></i>' => 'https://qchat.rizon.net/?channels=#8chan'), array('b', 'meta', 'int'), array('v', 'a', 'tg', 'fit', 'pol', 'tech', 'mu', 'co', 'sp', 'boards'), array('<i class="fa fa-twitter" title="Twitter"></i>'=>'https://twitter.com/infinitechan'));

	$config['footer'][] = 'Contribute to 8chan.co development at <a href="https://github.com/ctrlcctrlv/8chan">github</a>';
	$config['footer'][] = 'To make a DMCA request or report illegal content, please email <a href="mailto:admin@8chan.co">admin@8chan.co</a> or use the "Global Report" functionality on every page.';

	$config['search']['enable'] = true;

//$config['debug'] = true;
	$config['syslog'] = true;

	$config['wordfilters'][] = array('\rule', ''); // 'true' means it's a regular expression


	$config['embedding'] = array(
		array(
			'/^https?:\/\/(?:\w+\.)?(?:youtube\.com\/watch\?|youtu\.be\/)(?:(?:&?v=)?([a-zA-Z0-9\-_]{10,11})\??|&?(start=\d*)|&?(end=\d*)|(?:&?[^&]+))*$/i',
			$config['youtube_js_html']
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
// 8chan specific mod pages
require '8chan-mod-pages.php';
	
