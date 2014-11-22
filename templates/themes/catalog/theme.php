<?php
	require 'info.php';
	
	function catalog_build($action, $settings, $board) {
		global $config;
		
		// Possible values for $action:
		//	- all (rebuild everything, initialization)
		//	- news (news has been updated)
		//	- boards (board list changed)
		//	- post (a reply has been made)
		//	- post-thread (a thread has been made)
		
		if ($settings['all']) {
			$boards = listBoards(TRUE);
		} else {
			$boards = explode(' ', $settings['boards']);
		}
				
		if ($action == 'all') {
			foreach ($boards as $board) {
				$b = new Catalog();
				$b->build($settings, $board);
			}
		} elseif ($action == 'post-thread' || ($settings['update_on_posts'] && $action == 'post') || ($settings['update_on_posts'] && $action == 'post-delete') && (in_array($board, $boards) | $settings['all'])) {
			$b = new Catalog();
			$b->build($settings, $board);
		} elseif ($action == 'read_php') {
			$b = new Catalog();
			return $b->build($settings, $board, true);
		}
	}
	
	// Wrap functions in a class so they don't interfere with normal Tinyboard operations
	class Catalog {
		public function build($settings, $board_name, $return = false) {
			global $config, $board;
			
			if (!($config['cache']['enabled'] && $catalog_out = cache::get("catalog_{$board['uri']}"))) {;
			openBoard($board_name);
			
			$recent_images = array();
			$recent_posts = array();
			$stats = array();
			
                        $query = query(sprintf("SELECT *, `id` AS `thread_id`,
				(SELECT COUNT(`id`) FROM ``posts_%s`` WHERE `thread` = `thread_id`) AS `reply_count`,
				(SELECT SUM(`num_files`) FROM ``posts_%s`` WHERE `thread` = `thread_id` AND `num_files` IS NOT NULL) AS `image_count`,
				'%s' AS `board` FROM ``posts_%s`` WHERE `thread`  IS NULL ORDER BY `bump` DESC",
			$board_name, $board_name, $board_name, $board_name, $board_name)) or error(db_error());
			
			while ($post = $query->fetch(PDO::FETCH_ASSOC)) {
				$post['link'] = $config['root'] . $board['dir'] . $config['dir']['res'] . sprintf($config['file_page'], ($post['thread'] ? $post['thread'] : $post['id']));
				$post['board_name'] = $board['name'];

				if ($post['embed'] && preg_match('/^https?:\/\/(\w+\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9\-_]{10,11})(&.+)?$/i', $post['embed'], $matches)) {
					$post['youtube'] = $matches[2];
				}				

				if (isset($post['files']) && $post['files']) {
					$files = json_decode($post['files']);

					if ($files[0]) {
						if ($files[0]->file == 'deleted') {
							if (count($files) > 1) {
								foreach ($files as $file) {
									if (($file == $files[0]) || ($file->file == 'deleted')) continue;
									$post['file'] = $config['uri_thumb'] . $file->thumb;
								}

								if (empty($post['file'])) $post['file'] = $config['image_deleted'];
							}
							else {
								$post['file'] = $config['image_deleted'];
							}
						}
						else if($files[0]->thumb == 'spoiler') {
							$post['file'] = '/' . $config['spoiler_image'];
						}
						else {
							$post['file'] = $config['uri_thumb'] . $files[0]->thumb;
						}
					}
				}

				if (empty($post['image_count'])) $post['image_count'] = 0;
				$recent_posts[] = $post;
			}
			
			$required_scripts = array('js/jquery.min.js', 'js/jquery.mixitup.min.js', 'js/catalog.js');

			foreach($required_scripts as $i => $s) {
				if (!in_array($s, $config['additional_javascript']))
					$config['additional_javascript'][] = $s;
			}

			$catalog_out = Element('themes/catalog/catalog.html', Array(
				'settings' => $settings,
				'config' => $config,
				'boardlist' => createBoardlist(),
				'recent_images' => $recent_images,
				'recent_posts' => $recent_posts,
				'stats' => $stats,
				'board' => $board_name,
				'link' => $config['root'] . $board['dir']
			));

			cache::set("catalog_{$board['uri']}", $catalog_out);
			}

			if ($return) {
				return $catalog_out;
			} else {
				file_write($config['dir']['home'] . $board_name . '/catalog.html', $catalog_out);
			}
		}
	};
