<?php
	function mod_8_tags ($b) {
		global $board, $config;

		if (!openBoard($b))
			error("Could not open board!");

		if (!hasPermission($config['mod']['edit_tags'], $b))
			error($config['error']['noaccess']);

		if (isset($_POST['tags'])) {
			if (sizeof($_POST['tags']) > 5)
				error(_('Too many tags.'));

			$delete = prepare('DELETE FROM ``board_tags`` WHERE uri = :uri');
			$delete->bindValue(':uri', $b);
			$delete->execute();

			foreach ($_POST['tags'] as $i => $tag) {
				if ($tag) {
					if (strlen($tag) > 255)
						continue;

					$insert = prepare('INSERT INTO ``board_tags``(uri, tag) VALUES (:uri, :tag)');
					$insert->bindValue(':uri', $b);
					$insert->bindValue(':tag', utf8tohtml($tag));
					$insert->execute();
				}
			}

			$update = prepare('UPDATE ``boards`` SET sfw = :sfw WHERE uri = :uri');
			$update->bindValue(':uri', $b);
			$update->bindValue(':sfw', isset($_POST['sfw']));
			$update->execute();
		}
		$query = prepare('SELECT * FROM ``board_tags`` WHERE uri = :uri');
		$query->bindValue(':uri', $b);
		$query->execute();

		$tags = $query->fetchAll();

		$query = prepare('SELECT `sfw` FROM ``boards`` WHERE uri = :uri');
		$query->bindValue(':uri', $b);
		$query->execute();

		$sfw = $query->fetchColumn();

		mod_page(_('Edit tags'), 'mod/tags.html', array('board'=>$board,'token'=>make_secure_link_token('tags/'.$board['uri']), 'tags'=>$tags, 'sfw'=>$sfw));
	}

	function mod_8_reassign($b) {
		global $board, $config;

		if (!openBoard($b))
			error("Could not open board!");

		if (!hasPermission($config['mod']['reassign_board'], $b))
			error($config['error']['noaccess']);

		$query = query("SELECT id, username FROM mods WHERE boards = '$b' AND type = 20");
		$mods = $query->fetchAll();

		if (!$mods) {
			error('No mods?');
		}

		$password = base64_encode(openssl_random_pseudo_bytes(9));
		$salt = generate_salt();
		$hashed = hash('sha256', $salt . sha1($password));

		$query = prepare('UPDATE ``mods`` SET `password` = :hashed, `salt` = :salt WHERE BINARY username = :mod');
		$query->bindValue(':hashed', $hashed);
		$query->bindValue(':salt', $salt);
		$query->bindValue(':mod', $mods[0]['username']);
		$query->execute();

		$body = "Thanks for your interest in this board. Kindly find the username and password below. You can login at https://8ch.net/mod.php.<br>Username: {$mods[0]['username']}<br>Password: {$password}<br>Thanks for using 8chan!";

		modLog("Reassigned board /$b/");
		
		mod_page(_('Edit reassign'), 'blank.html', array('board'=>$board,'token'=>make_secure_link_token('reassign/'.$board['uri']),'body'=>$body));
	}

	function mod_8_volunteers($b) {
		global $board, $config, $pdo;
		if (!hasPermission($config['mod']['edit_volunteers'], $b))
			error($config['error']['noaccess']);

		if (!openBoard($b))
			error("Could not open board!");

		if (isset($_POST['username'], $_POST['password'])) {
			$query = prepare('SELECT * FROM ``mods`` WHERE type = 19 AND boards = :board');
			$query->bindValue(':board', $b);
			$query->execute() or error(db_error($query));
			$count = $query->rowCount();
			$query = prepare('SELECT `username` FROM ``mods``');
			$query->execute() or error(db_error($query));
			$volunteers = $query->fetchAll(PDO::FETCH_ASSOC);

			if ($_POST['username'] == '')
				error(sprintf($config['error']['required'], 'username'));
			if ($_POST['password'] == '')
				error(sprintf($config['error']['required'], 'password'));
			if (!preg_match('/^[a-zA-Z0-9._]{1,30}$/', $_POST['username']))
				error(_('Invalid username'));

			if ($count > 10) {
				error(_('Too many board volunteers!'));
			}

			foreach ($volunteers as $i => $v) {
				if (strtolower($_POST['username']) == strtolower($v['username'])) {
					error(_('Refusing to create a volunteer with the same username as an existing one.'));
				}
			}

			$salt = generate_salt();
			$password = hash('sha256', $salt . sha1($_POST['password']));
			
			$query = prepare('INSERT INTO ``mods`` VALUES (NULL, :username, :password, :salt, 19, :board)');
			$query->bindValue(':username', $_POST['username']);
			$query->bindValue(':password', $password);
			$query->bindValue(':salt', $salt);
			$query->bindValue(':board', $b);
			$query->execute() or error(db_error($query));
			
			$userID = $pdo->lastInsertId();


			modLog('Created a new volunteer: ' . utf8tohtml($_POST['username']) . ' <small>(#' . $userID . ')</small>');
		}

		if (isset($_POST['delete'])){
			foreach ($_POST['delete'] as $i => $d){
				$query = prepare('SELECT * FROM ``mods`` WHERE id = :id');
				$query->bindValue(':id', $d);
				$query->execute() or error(db_error($query));
				
				$result = $query->fetch(PDO::FETCH_ASSOC);

				if (!$result) {
					error(_('Volunteer does not exist!'));
				}

				if ($result['boards'] != $b || $result['type'] != BOARDVOLUNTEER) {
					error($config['error']['noaccess']);
				}

				$query = prepare('DELETE FROM ``mods`` WHERE id = :id');
				$query->bindValue(':id', $d);
				$query->execute() or error(db_error($query));
			}
		}

		$query = prepare('SELECT * FROM ``mods`` WHERE type = 19 AND boards = :board');
		$query->bindValue(':board', $b);
		$query->execute() or error(db_error($query));
		$volunteers = $query->fetchAll(PDO::FETCH_ASSOC);
			
		mod_page(_('Edit volunteers'), 'mod/volunteers.html', array('board'=>$board,'token'=>make_secure_link_token('volunteers/'.$board['uri']),'volunteers'=>$volunteers));
	
	}

	function mod_8_flags($b) {
		global $config, $mod, $board;
		require_once 'inc/image.php';
		if (!hasPermission($config['mod']['edit_flags'], $b))
			error($config['error']['noaccess']);

		if (!openBoard($b))
			error("Could not open board!");

		if (file_exists("$b/flags.ser"))
			$config['user_flags'] = unserialize(file_get_contents("$b/flags.ser"));

		$dir = 'static/custom-flags/'.$b;

		if (!is_dir($dir)){
			mkdir($dir, 0777, true);
		}

		function handle_file($id = false, $description, $b, $dir) {
			global $config;

			if (!isset($description) and $description)
				error(_('You must enter a flag description!'));

			if (strlen($description) > 255)
				error(_('Flag description too long!'));
	
			if ($id) {
				$f = 'flag-'.$id;
			} else { 
				$f = 'file';
				$id = time() . substr(microtime(), 2, 3);
			}

			$upload = $_FILES[$f]['tmp_name'];
			$banners = array_diff(scandir($dir), array('..', '.'));

			if (!is_readable($upload))
				error($config['error']['nomove']);

			$extension = strtolower(mb_substr($_FILES[$f]['name'], mb_strrpos($_FILES[$f]['name'], '.') + 1));

			if ($extension != 'png') {
				error(_('Flags must be in PNG format.'));
			}
	
			if (filesize($upload) > 48000){
				error(_('File too large!'));
			}

			if (!$size = @getimagesize($upload)) {
				error($config['error']['invalidimg']);
			}

			if ($size[0] > 20 or $size[0] < 11 or $size[1] > 16 or $size[1] < 11){
				error(_('Image wrong size!'));
			}
			if (sizeof($banners) > 256) {
				error(_('Too many flags.'));
			}

			copy($upload, "$dir/$id.$extension");
			purge("$dir/$id.$extension");
			$config['user_flags'][$id] = utf8tohtml($description);
			file_write($b.'/flags.ser', serialize($config['user_flags']));
		}
	
		// Handle a new flag, if any.
		if (isset($_FILES['file'])){
			handle_file(false, $_POST['description'], $b, $dir);
		}

		// Handle edits to existing flags.
		foreach ($_FILES as $k => $a) {
			if (empty($_FILES[$k]['tmp_name'])) continue;

			if (preg_match('/^flag-(\d+)$/', $k, $matches)) {
				$id = (int)$matches[1];
				if (!isset($_POST['description-'.$id])) continue;

				if (isset($config['user_flags'][$id])) {
					handle_file($id, $_POST['description-'.$id], $b, $dir);
				}
			}
		}

		// Description just changed, flag not edited.
		foreach ($_POST as $k => $v) {
			if (!preg_match('/^description-(\d+)$/', $k, $matches)) continue;
			$id = (int)$matches[1];
			if (!isset($_POST['description-'.$id])) continue;

			$description = $_POST['description-'.$id];

			if (strlen($description) > 255)
				error(_('Flag description too long!'));
			$config['user_flags'][$id] = utf8tohtml($description);
			file_write($b.'/flags.ser', serialize($config['user_flags']));
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$flags = <<<FLAGS
<?php
\$config['country_flags'] = false;
\$config['country_flags_condensed'] = false;
\$config['user_flag'] = true;
\$config['uri_flags'] = '/static/custom-flags/$b/%s.png';
\$config['flag_style'] = '';
\$config['user_flags'] = unserialize(file_get_contents('$b/flags.ser'));
FLAGS;

	                if ($config['cache']['enabled']) {
	                        cache::delete('config_' . $b);
	                        cache::delete('events_' . $b);
			}

			file_write($b.'/flags.php', $flags);
		}


		if (isset($_POST['delete'])){
			foreach ($_POST['delete'] as $i => $d){
				if (!preg_match('/[0-9+]/', $d)){
					error('Nice try.');
				}
				unlink("$dir/$d.png");
				$id = explode('.', $d)[0];
				unset($config['user_flags'][$id]);
				file_write($b.'/flags.ser', serialize($config['user_flags']));
			}
		}

		if (isset($_POST['alphabetize'])) {
			asort($config['user_flags'], SORT_NATURAL | SORT_FLAG_CASE);
			file_write($b.'/flags.ser', serialize($config['user_flags']));
		}

		$banners = array_diff(scandir($dir), array('..', '.'));
		mod_page(_('Edit flags'), 'mod/flags.html', array('board'=>$board,'banners'=>$banners,'token'=>make_secure_link_token('banners/'.$board['uri'])));
	}

	function mod_8_banners($b) {
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

	}

	function mod_8_settings($b) {
		global $config, $mod;

		//if ($b === 'infinity' && $mod['type'] !== ADMIN)
		//	error('Settings temporarily disabled for this board.');

		if (!in_array($b, $mod['boards']) and $mod['boards'][0] != '*')
			error($config['error']['noaccess']);

		if (!hasPermission($config['mod']['edit_settings'], $b))
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
			$strip_combining_chars = isset($_POST['strip_combining_chars']) ? 'true' : 'false';
			$allow_roll = isset($_POST['allow_roll']) ? 'true' : 'false';
			$image_reject_repost = isset($_POST['image_reject_repost']) ? 'true' : 'false';
			$image_reject_repost_in_thread = isset($_POST['image_reject_repost_in_thread']) ? 'true' : 'false';
			$early_404 = isset($_POST['early_404']) ? 'true' : 'false';
			$allow_delete = isset($_POST['allow_delete']) ? 'true' : 'false';
			$allow_flash = isset($_POST['allow_flash']) ? '$config[\'allowed_ext_files\'][] = \'swf\';' : '';
			$allow_pdf = isset($_POST['allow_pdf']) ? '$config[\'allowed_ext_files\'][] = \'pdf\';' : '';
			$code_tags = isset($_POST['code_tags']) ? '$config[\'additional_javascript\'][] = \'js/code_tags/run_prettify.js\';$config[\'markup\'][] = array("/\[code\](.+?)\[\/code\]/ms", "<code><pre class=\'prettyprint\' style=\'display:inline-block\'>\$1</pre></code>");' : '';
			$katex = isset($_POST['katex']) ? '$config[\'katex\'] = true;$config[\'additional_javascript\'][] = \'js/katex/katex.min.js\'; $config[\'markup\'][] = array("/\[tex\](.+?)\[\/tex\]/ms", "<span class=\'tex\'>\$1</span>"); $config[\'additional_javascript\'][] = \'js/katex-enable.js\';' : '';
			$user_flags = isset($_POST['user_flags']) ? "if (file_exists('$b/flags.php')) { include 'flags.php'; }\n" : '';
			$captcha = isset($_POST['captcha']) ? 'true' : 'false';
			$force_subject_op = isset($_POST['force_subject_op']) ? 'true' : 'false';
			$force_flag = isset($_POST['force_flag']) ? 'true' : 'false';
			$tor_posting = isset($_POST['tor_posting']) ? 'true' : 'false';
			$new_thread_capt = isset($_POST['new_thread_capt']) ? 'true' : 'false';
			$oekaki = isset($_POST['oekaki']) ? 'true' : 'false';
			
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
			$blotter = base64_encode(purify_html(html_entity_decode($_POST['blotter'])));
			$add_to_config = @file_get_contents($b.'/extra_config.php');
			$replace = '';

			if (isset($_POST['replace'])) {
				if (sizeof($_POST['replace']) > 200 || sizeof($_POST['with']) > 200) {
					error(_('Sorry, max 200 wordfilters allowed.'));
				}
				if (count($_POST['replace']) == count($_POST['with'])) {
					foreach ($_POST['replace'] as $i => $r ) {
						if ($r !== '') {
							$w = $_POST['with'][$i];
							
							if (strlen($w) > 255) {
								error(sprintf(_('Sorry, %s is too long. Max replacement is 255 characters'), utf8tohtml($w)));
							}

							$replace .= '$config[\'wordfilters\'][] = array(base64_decode(\'' . base64_encode($r) . '\'), base64_decode(\'' . base64_encode($w) . '\'));';
						}
					}
				}
				if (is_billion_laughs($_POST['replace'], $_POST['with'])) {
					error(_('Wordfilters may not wordfilter previous wordfilters. For example, if a filters to bb and b filters to cc, that is not allowed.'));
				}
			}

			if (isset($_POST['hour_max_threads']) && in_array($_POST['hour_max_threads'], ['10', '25', '50', '100'])) {
				$hour_max_threads = $_POST['hour_max_threads'];	
			} else {
				$hour_max_threads = 'false';
			}

			if (isset($_POST['max_pages'])) {
				$mp = (int)$_POST['max_pages'];
				if ($mp > 25 || $mp < 2) {
					$max_pages = 15;
				} else {
					$max_pages = $mp;
				}
			} else {
				$max_pages = 15;
			}			

			if (isset($_POST['reply_limit'])) {
				$rl = (int)$_POST['reply_limit'];
				if ($rl > 750 || $rl < 250 || $rl % 25) {
					$reply_limit = 250;
				} else {
					$reply_limit = $rl;
				}
			} else {
				$reply_limit = 250;
			}

			if (isset($_POST['max_newlines'])) {
				$mn = (int)$_POST['max_newlines'];
				if ($mn < 20 || $mn > 300) {
					$max_newlines = 0;
				} else {
					$max_newlines = $mn;
				}
			} else {
				$max_newlines = $mn;
			}

			if (!(strlen($title) < 40))
				error('Invalid title');
			if (!(strlen($subtitle) < 200))
				error('Invalid subtitle');

			$query = prepare('UPDATE ``boards`` SET `title` = :title, `subtitle` = :subtitle, `indexed` = :indexed, `public_bans` = :public_bans, `public_logs` = :public_logs, `8archive` = :8archive WHERE `uri` = :uri');
			$query->bindValue(':title', $title);
			$query->bindValue(':subtitle', $subtitle);
			$query->bindValue(':uri', $b);
			$query->bindValue(':indexed', !isset($_POST['meta_noindex']));
			$query->bindValue(':public_bans', isset($_POST['public_bans']));
			$query->bindValue(':public_logs', (int)$_POST['public_logs']);
			$query->bindValue(':8archive', isset($_POST['8archive']));
			$query->execute() or error(db_error($query));

			$config_file = <<<EOT
<?php
\$config['country_flags'] = $country_flags;
\$config['field_disable_name'] = $field_disable_name;
\$config['enable_embedding'] = $enable_embedding;
\$config['force_image_op'] = $force_image_op;
\$config['disable_images'] = $disable_images;
\$config['poster_ids'] = $poster_ids;
\$config['show_sages'] = $show_sages;
\$config['auto_unicode'] = $auto_unicode;
\$config['strip_combining_chars'] = $strip_combining_chars;
\$config['allow_roll'] = $allow_roll;
\$config['image_reject_repost'] = $image_reject_repost;
\$config['image_reject_repost_in_thread'] = $image_reject_repost_in_thread;
\$config['early_404'] = $early_404;
\$config['allow_delete'] = $allow_delete;
\$config['anonymous'] = base64_decode('$anonymous');
\$config['blotter'] = base64_decode('$blotter');
\$config['stylesheets']['Custom'] = 'board/$b.css';
\$config['default_stylesheet'] = array('Custom', \$config['stylesheets']['Custom']);
\$config['captcha']['enabled'] = $captcha;
\$config['force_subject_op'] = $force_subject_op;
\$config['force_flag'] = $force_flag;
\$config['tor_posting'] = $tor_posting;
\$config['new_thread_capt'] = $new_thread_capt;
\$config['hour_max_threads'] = $hour_max_threads;
\$config['reply_limit'] = $reply_limit;
\$config['max_pages'] = $max_pages;
\$config['max_newlines'] = $max_newlines;
\$config['oekaki'] = $oekaki;
$code_tags $katex $replace $multiimage $allow_flash $allow_pdf $user_flags
if (\$config['disable_images'])
	\$config['max_pages'] = 10000;

$locale
$add_to_config
EOT;

			// Clean up our CSS...no more expression() or off-site URLs.
			$clean_css = preg_replace('/expression\s*\(/', '', $_POST['css']);
	
			$matched = array();

			preg_match_all("#{$config['link_regex']}#im", $clean_css, $matched);
			
			if (isset($matched[0])) {
				foreach ($matched[0] as $match) {
					$match_okay = false;
					foreach ($config['allowed_offsite_urls'] as $allowed_url) {
						if (strpos($match, $allowed_url) !== false && strpos($match, '#') === false) {
							$match_okay = true;
						}
					}
					if ($match_okay !== true) {
						error(sprintf(_("Off-site link \"%s\" is not allowed in the board stylesheet"), $match));
					}
				}
			}
			
			//Filter out imports from sites with potentially unsafe content
			$match_imports = '@import[^;]*';
			$matched = array();
			preg_match_all("#$match_imports#im", $clean_css, $matched);
			
			$unsafe_import_urls = array('https://a.pomf.se/');
			
			if (isset($matched[0])) {
				foreach ($matched[0] as $match) {
					$match_okay = true;
					foreach ($unsafe_import_urls as $unsafe_import_url) {
						if (strpos($match, $unsafe_import_url) !== false && strpos($match, '#') === false) {
							$match_okay = false;
						}
					}
					if ($match_okay !== true) {
						error(sprintf(_("Potentially unsafe import \"%s\" is not allowed in the board stylesheet"), $match));
					}
				}
			}

			$query = query('SELECT `uri`, `title`, `subtitle` FROM ``boards`` WHERE `8archive` = TRUE');
			file_write('8archive.json', json_encode($query->fetchAll(PDO::FETCH_ASSOC)));
			file_write($b.'/config.php', $config_file);
			file_write('stylesheets/board/'.$b.'.css', $clean_css);

			$_config = $config;
			unset($config['wordfilters']);

			// Faster than openBoard and bypasses cache...we're trusting the PHP output
			// to be safe enough to run with every request, we can eval it here.
			eval(str_replace('flags.php', "$b/flags.php", preg_replace('/^\<\?php$/m', '', $config_file)));
			// czaks: maybe reconsider using it, now that config is cached?

			// be smarter about rebuilds...only some changes really require us to rebuild all threads
			if ($_config['captcha']['enabled'] != $config['captcha']['enabled']
			 || $_config['new_thread_capt'] != $config['new_thread_capt'] /*New thread captcha - if toggling "enable captcha" requires this, toggling new thread capt does too, I guess.*/
			 || $_config['captcha']['extra'] != $config['captcha']['extra']
			 || $_config['blotter'] != $config['blotter']
			 || $_config['field_disable_name'] != $config['field_disable_name']
			 || $_config['show_sages'] != (isset($config['show_sages']) && $config['show_sages'])) {
				buildIndex();
				$query = query(sprintf("SELECT `id` FROM ``posts_%s`` WHERE `thread` IS NULL", $b)) or error(db_error());
				while ($post = $query->fetch(PDO::FETCH_ASSOC)) {
					buildThread($post['id']);
				}
			}
		
			modLog('Edited board settings', $b);
		}

		$query = prepare('SELECT * FROM boards WHERE uri = :board');
		$query->bindValue(':board', $b);
		$query->execute() or error(db_error($query));
		$board = $query->fetchAll()[0];

		// Clean the cache
		if ($config['cache']['enabled']) {
			cache::delete('board_' . $board['uri']);
			cache::delete('all_boards');

			cache::delete('config_' . $board['uri']);
			cache::delete('events_' . $board['uri']);
			unlink('tmp/cache/locale_' . $board['uri']);
		}
 
		$css = @file_get_contents('stylesheets/board/' . $board['uri'] . '.css');
	
		mod_page(_('Board configuration'), 'mod/settings.html', array('board'=>$board, 'css'=>prettify_textarea($css), 'token'=>make_secure_link_token('settings/'.$board['uri']), 'languages'=>$possible_languages,'allowed_urls'=>$config['allowed_offsite_urls']));
	}
