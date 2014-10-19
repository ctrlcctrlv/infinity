[1mdiff --git a/inc/instance-config.php b/inc/instance-config.php[m
[1mindex 699d1a1..475f3ed 100644[m
[1m--- a/inc/instance-config.php[m
[1m+++ b/inc/instance-config.php[m
[36m@@ -18,15 +18,15 @@[m
 	$config['db']['user'] = 'root';[m
 	$config['db']['password'] = '';[m
 	$config['timezone'] = 'UTC';[m
[31m-	$config['cache']['enabled'] = 'apc';[m
[31m-[m
[31m-[m
[32m+[m	[32m$config['cache']['enabled'] = false;[m
[32m+[m[41m	[m
[32m+[m[41m	[m
 	$config['cookies']['mod'] = 'mod';[m
 	$config['cookies']['salt'] = '';[m
[31m-[m
[32m+[m[41m	[m
 	$config['spam']['hidden_inputs_max_pass'] = 128;[m
 	$config['spam']['hidden_inputs_expire'] = 60 * 60 * 4; // three hours[m
[31m-[m
[32m+[m[41m	[m
 	$config['flood_time'] = 5;[m
 	$config['flood_time_ip'] = 30;[m
 	$config['flood_time_same'] = 2;[m
[36m@@ -46,10 +46,10 @@[m
 	$config['thread_subject_in_title'] = true;[m
 	$config['spam']['hidden_inputs_max_pass'] = 128;[m
 	$config['ayah_enabled'] = true;[m
[31m-[m
[32m+[m[41m	[m
 	// Load database credentials[m
 	require "secrets.php";[m
[31m-[m
[32m+[m[41m	[m
 	// Image shit[m
 	$config['thumb_method'] = 'gm+gifsicle';[m
 	$config['thumb_ext'] = '';[m
[1mdiff --git a/install.php b/install.php[m
[1mindex 8b3815a..07e94e7 100644[m
[1m--- a/install.php[m
[1m+++ b/install.php[m
[36m@@ -579,7 +579,8 @@[m [mif ($step == 0) {[m
 	</p>';[m
 	[m
 	echo Element('page.html', $page);[m
[31m-} elseif ($step == 1) {[m
[32m+[m[32m}[m
[32m+[m[32melseif ($step == 1) {[m
 	$page['title'] = 'Pre-installation test';[m
 	[m
 	$can_exec = true;[m
[36m@@ -761,7 +762,8 @@[m [mif ($step == 0) {[m
 		'title' => 'Checking environment',[m
 		'config' => $config[m
 	));[m
[31m-} elseif ($step == 2) {[m
[32m+[m[32m}[m
[32m+[m[32melseif ($step == 2) {[m
 	// Basic config[m
 	$page['title'] = 'Configuration';[m
 	[m
[36m@@ -775,7 +777,8 @@[m [mif ($step == 0) {[m
 		'title' => 'Configuration',[m
 		'config' => $config[m
 	));[m
[31m-} elseif ($step == 3) {[m
[32m+[m[32m}[m
[32m+[m[32melseif ($step == 3) {[m
 	$instance_config = [m
 '<?php[m
 [m
[36m@@ -814,7 +817,8 @@[m [mif ($step == 0) {[m
 	[m
 	if (@file_put_contents('inc/instance-config.php', $instance_config)) {[m
 		header('Location: ?step=4', true, $config['redirect_http']);[m
[31m-	} else {[m
[32m+[m	[32m}[m
[32m+[m	[32melse {[m
 		$page['title'] = 'Manual installation required';[m
 		$page['body'] = '[m
 			<p>I couldn\'t write to <strong>inc/instance-config.php</strong> with the new configuration, probably due to a permissions error.</p>[m
[36m@@ -826,7 +830,8 @@[m [mif ($step == 0) {[m
 		';[m
 		echo Element('page.html', $page);[m
 	}[m
[31m-} elseif ($step == 4) {[m
[32m+[m[32m}[m
[32m+[m[32melseif ($step == 4) {[m
 	// SQL installation[m
 	[m
 	buildJavascript();[m
[36m@@ -846,11 +851,15 @@[m [mif ($step == 0) {[m
 	[m
 	$sql_errors = '';[m
 	foreach ($queries as $query) {[m
[31m-		if ($mysql_version < 50503)[m
[32m+[m		[32mif ($mysql_version < 50503) {[m
 			$query = preg_replace('/(CHARSET=|CHARACTER SET )utf8mb4/', '$1utf8', $query);[m
[32m+[m		[32m}[m
[32m+[m[41m		[m
 		$query = preg_replace('/^([\w\s]*)`([0-9a-zA-Z$_\x{0080}-\x{FFFF}]+)`/u', '$1``$2``', $query);[m
[31m-		if (!query($query))[m
[32m+[m[41m		[m
[32m+[m		[32mif (!query($query)) {[m
 			$sql_errors .= '<li>' . db_error() . '</li>';[m
[32m+[m		[32m}[m
 	}[m
 	[m
 	$page['title'] = 'Installation complete';[m
[36m@@ -858,7 +867,8 @@[m [mif ($step == 0) {[m
 	[m
 	if (!empty($sql_errors)) {[m
 		$page['body'] .= '<div class="ban"><h2>SQL errors</h2><p>SQL errors were encountered when trying to install the database. This may be the result of using a database which is already occupied with a vichan installation; if so, you can probably ignore this.</p><p>The errors encountered were:</p><ul>' . $sql_errors . '</ul><p><a href="?step=5">Ignore errors and complete installation.</a></p></div>';[m
[31m-	} else {[m
[32m+[m	[32m}[m
[32m+[m	[32melse {[m
 		$boards = listBoards();[m
 		foreach ($boards as &$_board) {[m
 			setupBoard($_board);[m
[36m@@ -866,13 +876,11 @@[m [mif ($step == 0) {[m
 		}[m
 		[m
 		file_write($config['has_installed'], VERSION);[m
[31m-		/*if (!file_unlink(__FILE__)) {[m
[31m-			$page['body'] .= '<div class="ban"><h2>Delete install.php!</h2><p>I couldn\'t remove <strong>install.php</strong>. You will have to remove it manually.</p></div>';[m
[31m-		}*/[m
 	}[m
 	[m
 	echo Element('page.html', $page);[m
[31m-} elseif ($step == 5) {[m
[32m+[m[32m}[m
[32m+[m[32melseif ($step == 5) {[m
 	$page['title'] = 'Installation complete';[m
 	$page['body'] = '<p style="text-align:center">Thank you for using vichan. Please remember to report any bugs you discover.</p>';[m
 	[m
[1mdiff --git a/templates/8chan/index.html b/templates/8chan/index.html[m
[1mindex 459e7e8..4f25473 100644[m
[1m--- a/templates/8chan/index.html[m
[1m+++ b/templates/8chan/index.html[m
[36m@@ -223,7 +223,7 @@[m
     </style>[m
   </head>[m
 [m
[31m-  <body>[m
[32m+[m[32m  <body class="8chan index">[m
 [m
     <div id="main">[m
 [m
[1mdiff --git a/templates/generic_page.html b/templates/generic_page.html[m
[1mindex 0fe1f9c..4cd3116 100644[m
[1m--- a/templates/generic_page.html[m
[1m+++ b/templates/generic_page.html[m
[36m@@ -6,7 +6,7 @@[m
 	<title>{{ board.url }} - {{ board.name }}</title>[m
 	{% endblock %}[m
 </head>[m
[31m-<body>	[m
[32m+[m[32m<body class="8chan {% if mod %}is-moderator{% else %}is-not-moderator{% endif %}" data-stylesheet="{% if config.default_stylesheet.1 != '' and not mod %}{{ config.default_stylesheet.1 }}{% else %}default{% endif %}">[m
 	{{ boardlist.top }}[m
 	{% if pm %}<div class="top_notice">You have <a href="?/PM/{{ pm.id }}">an unread PM</a>{% if pm.waiting > 0 %}, plus {{ pm.waiting }} more waiting{% endif %}.</div><hr />{% endif %}[m
 	{% if config.url_banner %}<img class="banner" src="{{ config.url_banner }}" {% if config.banner_width or config.banner_height %}style="{% if config.banner_width %}width:{{ config.banner_width }}px{% endif %};{% if config.banner_width %}height:{{ config.banner_height }}px{% endif %}" {% endif %}alt="" />{% endif %}[m
[1mdiff --git a/templates/index.html b/templates/index.html[m
[1mindex aae87c1..5638fd6 100644[m
[1m--- a/templates/index.html[m
[1m+++ b/templates/index.html[m
[36m@@ -14,7 +14,7 @@[m
 	{% include 'header.html' %}[m
 	<title>{{ board.url }} - {{ board.title|e }}</title>[m
 </head>[m
[31m-<body>	[m
[32m+[m[32m<body class="8chan {% if mod %}is-moderator{% else %}is-not-moderator{% endif %}" data-stylesheet="{% if config.default_stylesheet.1 != '' and not mod %}{{ config.default_stylesheet.1 }}{% else %}default{% endif %}">[m
 	{{ boardlist.top }}[m
 	[m
 	{% if pm %}<div class="top_notice">You have <a href="?/PM/{{ pm.id }}">an unread PM</a>{% if pm.waiting > 0 %}, plus {{ pm.waiting }} more waiting{% endif %}.</div><hr />{% endif %}[m
[1mdiff --git a/templates/main.js b/templates/main.js[m
[1mindex 1ec4d26..d100f93 100644[m
[1m--- a/templates/main.js[m
[1m+++ b/templates/main.js[m
[36m@@ -73,11 +73,6 @@[m [mvar saved = {};[m
 [m
 [m
 var selectedstyle = '{% endraw %}{{ config.default_stylesheet.0|addslashes }}{% raw %}';[m
[31m-/*var styles = {[m
[31m-	{% endraw %}[m
[31m-	{% for stylesheet in stylesheets %}{% raw %}'{% endraw %}{{ stylesheet.name|addslashes }}{% raw %}' : '{% endraw %}{{ stylesheet.uri|addslashes }}{% raw %}',[m
[31m-	{% endraw %}{% endfor %}{% raw %}[m
[31m-};*/[m
 var board_name = false;[m
 [m
 function changeStyle(styleName, link) {[m
[36m@@ -92,36 +87,78 @@[m [mfunction changeStyle(styleName, link) {[m
 	{% endif %}[m
 	{% raw %}[m
 	[m
[31m-	if (!document.getElementById('stylesheet')) {[m
[31m-		var s = document.createElement('link');[m
[31m-		s.rel = 'stylesheet';[m
[31m-		s.type = 'text/css';[m
[31m-		s.id = 'stylesheet';[m
[32m+[m	[32m// Find the <dom> for the stylesheet. May be nothing.[m
[32m+[m	[32mvar domStylesheet = document.getElementById('stylesheet');[m
[32m+[m	[32m// Determine if this stylesheet is the default.[m
[32m+[m	[32mvar setToDefault  = ( styles[styleName] == "" || styles[styleName] == "/stylesheets/" );[m
[32m+[m	[32m// Turn "Yotsuba B" to "yotsuba_b"[m[41m [m
[32m+[m	[32mvar attributeName = styleName.replace(/[^a-z0-9_\-]/gi, '_').toLowerCase();[m
[32m+[m[41m	[m
[32m+[m	[32mif( !domStylesheet && !setToDefault ) {[m
[32m+[m		[32mdomStylesheet = document.createElement('link');[m
[32m+[m		[32mdomStylesheet.rel = 'stylesheet';[m
[32m+[m		[32mdomStylesheet.type = 'text/css';[m
[32m+[m		[32mdomStylesheet.id = 'stylesheet';[m
[32m+[m[41m		[m
 		var x = document.getElementsByTagName('head')[0];[m
[31m-		x.appendChild(s);[m
[32m+[m		[32mx.appendChild(domStylesheet);[m
 	}[m
[31m-[m
[31m-	{% endraw %}[m
[31m-	var root = "{{ config.root }}";[m
[31m-	{% raw %}[m
[31m-	root = root.replace(/\/$/, "");[m
[31m-	[m
[31m-	document.getElementById('stylesheet').href = root + styles[styleName];[m
[31m-	selectedstyle = styleName;[m
 	[m
[31m-	if (document.getElementsByClassName('styles').length != 0) {[m
[31m-		var styleLinks = document.getElementsByClassName('styles')[0].childNodes;[m
[31m-		for (var i = 0; i < styleLinks.length; i++) {[m
[31m-			styleLinks[i].className = '';[m
[32m+[m	[32mif( !setToDefault ) {[m
[32m+[m		[32m{% endraw %}[m
[32m+[m		[32mvar root = "{{ config.root }}";[m
[32m+[m		[32m{% raw %}[m
[32m+[m		[32mroot = root.replace(/\/$/, "");[m
[32m+[m[41m		[m
[32m+[m		[32mdomStylesheet.href = root + styles[styleName];[m
[32m+[m		[32mselectedstyle = styleName;[m
[32m+[m[41m		[m
[32m+[m		[32mif (document.getElementsByClassName('styles').length != 0) {[m
[32m+[m			[32mvar styleLinks = document.getElementsByClassName('styles')[0].childNodes;[m
[32m+[m			[32mfor (var i = 0; i < styleLinks.length; i++) {[m
[32m+[m				[32mstyleLinks[i].className = '';[m
[32m+[m			[32m}[m
 		}[m
[32m+[m[41m		[m
[32m+[m		[32mif (link) {[m
[32m+[m			[32mlink.className = 'selected';[m
[32m+[m		[32m}[m
[32m+[m	[32m}[m
[32m+[m	[32melse if( domStylesheet ) {[m
[32m+[m		[32mdomStylesheet.parentNode.removeChild( domStylesheet );[m
 	}[m
 	[m
[31m-	if (link) {[m
[31m-		link.className = 'selected';[m
[32m+[m	[32m// Fix the classes on the body tag.[m
[32m+[m	[32mvar body = document.getElementsByTagName('body')[0];[m
[32m+[m[41m	[m
[32m+[m	[32mif( body ) {[m
[32m+[m		[32mvar bodyClasses = document.getElementsByTagName('body')[0].getAttribute('class').split(" ");[m
[32m+[m		[32mvar bodyClassesNew = [];[m
[32m+[m[41m		[m
[32m+[m		[32mfor( i = 0; i < bodyClasses.length; ++i ) {[m
[32m+[m			[32mvar bodyClass = bodyClasses[ i ];[m
[32m+[m[41m			[m
[32m+[m			[32m// null class from a double-space.[m
[32m+[m			[32mif( bodyClass == "" ) {[m
[32m+[m				[32mcontinue;[m
[32m+[m			[32m}[m
[32m+[m[41m			[m
[32m+[m			[32mif( bodyClass.indexOf( "stylesheet-" ) == 0 ) {[m
[32m+[m				[32mcontinue;[m
[32m+[m			[32m}[m
[32m+[m[41m			[m
[32m+[m			[32mbodyClassesNew.push( bodyClass );[m
[32m+[m		[32m}[m
[32m+[m[41m		[m
[32m+[m		[32m// Add stylesheet-yotsuba_b at the end.[m
[32m+[m		[32mbodyClassesNew.push( "stylesheet-" + attributeName );[m
[32m+[m		[32mbody.setAttribute( 'class', bodyClassesNew.join(" ") );[m
[32m+[m		[32mbody.setAttribute( 'data-stylesheet', attributeName );[m
 	}[m
 	[m
[31m-	if (typeof $ != 'undefined')[m
[32m+[m	[32mif (typeof $ != 'undefined') {[m
 		$(window).trigger('stylesheet', styleName);[m
[32m+[m	[32m}[m
 }[m
 [m
 [m
[36m@@ -167,7 +204,7 @@[m [mfunction init_stylechooser() {[m
 			}[m
 		}[m
 	}[m
[31m-	{% endraw%}[m
[32m+[m	[32m{% endraw %}[m
 {% else %}[m
 	{% raw %}[m
 	if (localStorage.stylesheet) {[m
[36m@@ -185,10 +222,13 @@[m [mfunction init_stylechooser() {[m
 [m
 function get_cookie(cookie_name) {[m
 	var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)');[m
[31m-	if (results)[m
[32m+[m[41m	[m
[32m+[m	[32mif (results) {[m
 		return (unescape(results[2]));[m
[31m-	else[m
[32m+[m	[32m}[m
[32m+[m	[32melse {[m
 		return null;[m
[32m+[m	[32m}[m
 }[m
 [m
 function highlightReply(id) {[m
[1mdiff --git a/templates/mod/dashboard.html b/templates/mod/dashboard.html[m
[1mindex 4f76625..87e67c8 100644[m
[1m--- a/templates/mod/dashboard.html[m
[1m+++ b/templates/mod/dashboard.html[m
[36m@@ -1,40 +1,5 @@[m
[31m-<fieldset>[m
[31m-	<legend>{% trans 'Boards' %}</legend>[m
[31m-	[m
[31m-	<ul>[m
[31m-		{% for board in boards %}[m
[31m-		{% if board.uri in mod.boards or mod.boards[0] == '*' %}[m
[31m-			<li>[m
[31m-				<a href="?/{{ config.board_path|sprintf(board.uri) }}{{ config.file_index }}">{{ config.board_abbreviation|sprintf(board.uri) }}</a>[m
[31m-					 - [m
[31m-				{{ board.title|e }}[m
[31m-				{% if board.subtitle %}[m
[31m-					<small>&mdash; [m
[31m-						{% if config.allow_subtitle_html %}[m
[31m-							{{ board.subtitle }}[m
[31m-						{% else %}[m
[31m-							{{ board.subtitle|e }}[m
[31m-						{% endif %}[m
[31m-					</small>[m
[31m-[m
[31m-				{% endif %}[m
[31m-				{% if mod.type == "20" %}[m
[31m-					<a href="?/settings/{{ board.uri }}"><small>[{% trans 'settings' %}]</small></a>[m
[31m-				{% endif %}[m
[31m-				{% if mod|hasPermission(config.mod.manageboards) %}[m
[31m-					 <a href="?/edit/{{ board.uri }}"><small>[{% trans 'edit' %}]</small></a>[m
[31m-				{% endif %}[m
[31m-			</li>[m
[31m-		{% endif %}[m
[31m-		{% endfor %}[m
[31m-		[m
[31m-		{% if mod|hasPermission(config.mod.newboard) %}[m
[31m-			<li style="margin-top:15px"><a href="?/new-board"><strong>{% trans 'Create new board' %}</strong></a></li>[m
[31m-		{% endif %}[m
[31m-	</ul>[m
[31m-</fieldset>[m
[31m-[m
[31m-<fieldset>[m
[32m+[m[32m<!-- Messages -->[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-messages">[m
 	<legend>{% trans 'Messages' %}</legend>[m
 	<ul>[m
 		{% if mod|hasPermission(config.mod.noticeboard) %}[m
[36m@@ -78,7 +43,8 @@[m
 	</ul>[m
 </fieldset>[m
 [m
[31m-<fieldset>[m
[32m+[m[32m<!-- Administration -->[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-messages">[m
 	<legend>{% trans 'Administration' %}</legend>[m
 	[m
 	<ul>[m
[36m@@ -123,57 +89,99 @@[m
 	</ul>[m
 </fieldset>[m
 [m
[32m+[m[32m<!-- Search -->[m
 {% if mod|hasPermission(config.mod.search) %}[m
[31m-	<fieldset>[m
[31m-		<legend>{% trans 'Search' %}</legend>[m
[31m-		[m
[31m-		<ul>[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-search">[m
[32m+[m	[32m<legend>{% trans 'Search' %}</legend>[m
[32m+[m[41m	[m
[32m+[m	[32m<ul>[m
[32m+[m		[32m<li>[m
[32m+[m			[32m{% include 'mod/search_form.html' %}[m
[32m+[m		[32m</li>[m
[32m+[m	[32m</ul>[m
[32m+[m[32m</fieldset>[m
[32m+[m[32m{%  endif %}[m
[32m+[m
[32m+[m[32m<!-- Boards -->[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-boards">[m
[32m+[m	[32m<legend>{% trans 'Boards' %}</legend>[m
[32m+[m[41m	[m
[32m+[m	[32m<ul>[m
[32m+[m		[32m{% for board in boards %}[m
[32m+[m		[32m{% if board.uri in mod.boards or mod.boards[0] == '*' %}[m
 			<li>[m
[31m-				{% include 'mod/search_form.html' %}[m
[32m+[m				[32m<a href="?/{{ config.board_path|sprintf(board.uri) }}{{ config.file_index }}">{{ config.board_abbreviation|sprintf(board.uri) }}</a>[m
[32m+[m					[32m -[m[41m [m
[32m+[m				[32m{{ board.title|e }}[m
[32m+[m				[32m{% if board.subtitle %}[m
[32m+[m					[32m<small>&mdash;[m[41m [m
[32m+[m						[32m{% if config.allow_subtitle_html %}[m
[32m+[m							[32m{{ board.subtitle }}[m
[32m+[m						[32m{% else %}[m
[32m+[m							[32m{{ board.subtitle|e }}[m
[32m+[m						[32m{% endif %}[m
[32m+[m					[32m</small>[m
[32m+[m
[32m+[m				[32m{% endif %}[m
[32m+[m				[32m{% if mod.type == "20" %}[m
[32m+[m					[32m<a href="?/settings/{{ board.uri }}"><small>[{% trans 'settings' %}]</small></a>[m
[32m+[m				[32m{% endif %}[m
[32m+[m				[32m{% if mod|hasPermission(config.mod.manageboards) %}[m
[32m+[m					[32m <a href="?/edit/{{ board.uri }}"><small>[{% trans 'edit' %}]</small></a>[m
[32m+[m				[32m{% endif %}[m
 			</li>[m
[31m-		</ul>[m
[31m-	</fieldset>[m
[31m-{%  endif %}[m
[32m+[m		[32m{% endif %}[m
[32m+[m		[32m{% endfor %}[m
[32m+[m[41m		[m
[32m+[m		[32m{% if mod|hasPermission(config.mod.newboard) %}[m
[32m+[m			[32m<li style="margin-top:15px"><a href="?/new-board"><strong>{% trans 'Create new board' %}</strong></a></li>[m
[32m+[m		[32m{% endif %}[m
[32m+[m	[32m</ul>[m
[32m+[m[32m</fieldset>[m
 [m
[32m+[m[32m<!-- Misc -->[m
 {% if config.mod.dashboard_links|count %}[m
[31m-	<fieldset>[m
[31m-		<legend>{% trans 'Other' %}</legend>[m
[31m-	[m
[31m-		<ul>[m
[31m-			{% for label,link in config.mod.dashboard_links %}[m
[31m-				<li><a href="{{ link }}">{{ label }}</a></li>[m
[31m-			{% endfor %}[m
[31m-		</ul>[m
[31m-	</fieldset>[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-misc">[m
[32m+[m	[32m<legend>{% trans 'Other' %}</legend>[m
[32m+[m
[32m+[m	[32m<ul>[m
[32m+[m		[32m{% for label,link in config.mod.dashboard_links %}[m
[32m+[m			[32m<li><a href="{{ link }}">{{ label }}</a></li>[m
[32m+[m		[32m{% endfor %}[m
[32m+[m	[32m</ul>[m
[32m+[m[32m</fieldset>[m
 {% endif %}[m
 [m
[32m+[m[32m<!-- Debug Information -->[m
 {% if config.debug %}[m
[31m-	<fieldset>[m
[31m-		<legend>{% trans 'Debug' %}</legend>[m
[31m-		<ul>[m
[31m-			<li><a href="?/debug/antispam">{% trans 'Anti-spam' %}</a></li>[m
[31m-			<li><a href="?/debug/recent">{% trans 'Recent posts' %}</a></li>[m
[31m-			{% if mod|hasPermission(config.mod.debug_sql) %}[m
[31m-				<li><a href="?/debug/sql">{% trans 'SQL' %}</a></li>[m
[31m-			{% endif %}[m
[31m-		</ul>[m
[31m-	</fieldset>[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-debug">[m
[32m+[m	[32m<legend>{% trans 'Debug' %}</legend>[m
[32m+[m	[32m<ul>[m
[32m+[m		[32m<li><a href="?/debug/antispam">{% trans 'Anti-spam' %}</a></li>[m
[32m+[m		[32m<li><a href="?/debug/recent">{% trans 'Recent posts' %}</a></li>[m
[32m+[m		[32m{% if mod|hasPermission(config.mod.debug_sql) %}[m
[32m+[m			[32m<li><a href="?/debug/sql">{% trans 'SQL' %}</a></li>[m
[32m+[m		[32m{% endif %}[m
[32m+[m	[32m</ul>[m
[32m+[m[32m</fieldset>[m
 {% endif %}[m
 [m
[32m+[m[32m<!-- Update -->[m
 {% if newer_release %}[m
[31m-	<fieldset>[m
[31m-		<legend>Update</legend>[m
[31m-		<ul>[m
[31m-			<li>[m
[31m-				A newer version of Tinyboard [m
[31m-				(<strong>v{{ newer_release.massive }}.{{ newer_release.major }}.{{ newer_release.minor }}</strong>) is available! [m
[31m-				See <a href="http://tinyboard.org">http://tinyboard.org/</a> for upgrade instructions.[m
[31m-			</li>[m
[31m-		</ul>[m
[31m-	</fieldset>[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-update">[m
[32m+[m	[32m<legend>Update</legend>[m
[32m+[m	[32m<ul>[m
[32m+[m		[32m<li>[m
[32m+[m			[32mA newer version of Tinyboard[m[41m [m
[32m+[m			[32m(<strong>v{{ newer_release.massive }}.{{ newer_release.major }}.{{ newer_release.minor }}</strong>) is available![m[41m [m
[32m+[m			[32mSee <a href="http://tinyboard.org">http://tinyboard.org/</a> for upgrade instructions.[m
[32m+[m		[32m</li>[m
[32m+[m	[32m</ul>[m
[32m+[m[32m</fieldset>[m
 {% endif %}[m
 [m
[31m-<fieldset>[m
[32m+[m[32m<!-- Account Actions -->[m
[32m+[m[32m<fieldset class="mod-dash mod-dash-set mod-dash-account">[m
 	<legend>{% trans 'User account' %}</legend>[m
 	[m
 	<ul>[m
[1mdiff --git a/templates/page.html b/templates/page.html[m
[1mindex b136566..a3916b6 100644[m
[1m--- a/templates/page.html[m
[1m+++ b/templates/page.html[m
[36m@@ -6,9 +6,10 @@[m
 		active_page = "page";[m
 	</script>[m
 	{% include 'header.html' %}[m
[32m+[m	[32m{% if mod %}{% include 'mod/header.html' %}{% endif %}[m
 	<title>{{ title }}</title>[m
 </head>[m
[31m-<body>[m
[32m+[m[32m<body class="8chan {% if mod %}is-moderator{% else %}is-not-moderator{% endif %} stylesheet-{% if config.default_stylesheet.1 != '' and not mod %}{{ config.default_stylesheet.1 }}{% else %}default{% endif %}">[m
 	{% if pm %}<div class="top_notice">You have <a href="?/PM/{{ pm.id }}">an unread PM</a>{% if pm.waiting > 0 %}, plus {{ pm.waiting }} more waiting{% endif %}.</div><hr>{% endif %}[m
 	<header>[m
 		<h1>{{ title }}</h1>[m
[1mdiff --git a/templates/themes/basic/index.html b/templates/themes/basic/index.html[m
[1mindex 3376a68..0ec18f9 100644[m
[1m--- a/templates/themes/basic/index.html[m
[1m+++ b/templates/themes/basic/index.html[m
[36m@@ -11,7 +11,7 @@[m
 	{% if config.default_stylesheet.1 != '' %}<link rel="stylesheet" type="text/css" id="stylesheet" href="{{ config.uri_stylesheets }}{{ config.default_stylesheet.1 }}">{% endif %}[m
 	{% if config.font_awesome %}<link rel="stylesheet" href="{{ config.root }}{{ config.font_awesome_css }}">{% endif %}[m
 </head>[m
[31m-<body>[m
[32m+[m[32m<body class="8chan {% if mod %}is-moderator{% else %}is-not-moderator{% endif %}" data-stylesheet="{% if config.default_stylesheet.1 != '' and not mod %}{{ config.default_stylesheet.1 }}{% else %}default{% endif %}">[m
 	{{ boardlist.top }}[m
 	<header>[m
 		<h1>{{ settings.title }}</h1>[m
[1mdiff --git a/templates/thread.html b/templates/thread.html[m
[1mindex af2a6e0..a3ace9b 100644[m
[1m--- a/templates/thread.html[m
[1m+++ b/templates/thread.html[m
[36m@@ -10,7 +10,7 @@[m
 	{% include 'header.html' %}[m
 	<title>{{ board.url }} - {% if config.thread_subject_in_title and thread.subject %}{{ thread.subject }}{% else %}{{ board.title|e }}{% endif %}</title>[m
 </head>[m
[31m-<body>[m
[32m+[m[32m<body class="8chan {% if mod %}is-moderator{% else %}is-not-moderator{% endif %}" data-stylesheet="{% if config.default_stylesheet.1 != '' and not mod %}{{ config.default_stylesheet.1 }}{% else %}default{% endif %}">[m
 	{{ boardlist.top }}[m
 	{% if pm %}<div class="top_notice">You have <a href="?/PM/{{ pm.id }}">an unread PM</a>{% if pm.waiting > 0 %}, plus {{ pm.waiting }} more waiting{% endif %}.</div><hr />{% endif %}[m
 	{% if config.url_banner %}<img class="board_image" src="{{ config.url_banner }}?board={{ board.uri }}" {% if config.banner_width or config.banner_height %}style="{% if config.banner_width %}width:{{ config.banner_width }}px{% endif %};{% if config.banner_width %}height:{{ config.banner_height }}px{% endif %}" {% endif %}alt="" />{% endif %}[m
