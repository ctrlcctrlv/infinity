<?php
include 'inc/functions.php';

if (!isset($_GET['board']) || !preg_match("/{$config['board_regex']}/u", $_GET['board'])) {
	http_response_code(400);
	error('Bad board.');
}
if (!openBoard($_GET['board'])) {
	http_response_code(404);
	error('No board.');
}

header('Content-Type: application/json');
$safe_config['title'] = $board['title'];
$safe_config['subtitle'] = $board['subtitle'];
$safe_config['indexed'] = ($board['indexed'] == "1");
$safe_config['country_flags'] = $config['country_flags'];
$safe_config['field_disable_name'] = $config['field_disable_name'];
$safe_config['enable_embedding'] = $config['enable_embedding'];
$safe_config['force_image_op'] = $config['force_image_op'];
$safe_config['disable_images'] = $config['disable_images'];
$safe_config['poster_ids'] = $config['poster_ids'];
$safe_config['show_sages'] = $config['show_sages'];
$safe_config['auto_unicode'] = $config['auto_unicode'];
$safe_config['strip_combining_chars'] = $config['strip_combining_chars'];
$safe_config['allow_roll'] = $config['allow_roll'];
$safe_config['image_reject_repost'] = $config['image_reject_repost'];
$safe_config['image_reject_repost_in_thread'] = $config['image_reject_repost_in_thread'];
$safe_config['early_404'] = $config['early_404'];
$safe_config['allow_delete'] = $config['allow_delete'];
$safe_config['anonymous'] = $config['anonymous'];
$safe_config['blotter'] = $config['blotter'];
$safe_config['stylesheets'] = $config['stylesheets'];
$safe_config['default_stylesheet'] = $config['default_stylesheet'];
$safe_config['captcha'] = $config['captcha'];
$safe_config['force_subject_op'] = $config['force_subject_op'];
$safe_config['tor_posting'] = $config['tor_posting'];
$safe_config['new_thread_capt'] = $config['new_thread_capt'];
$safe_config['hour_max_threads'] = $config['hour_max_threads'];
$safe_config['disable_images'] = $config['disable_images'];
$safe_config['locale'] = $config['locale'];
$safe_config['allowed_ext_files'] = $config['allowed_ext_files'];
$safe_config['allowed_ext'] = $config['allowed_ext'];
$safe_config['user_flags'] = $config['user_flags'];
$safe_config['wordfilters'] = $config['wordfilters'];
$safe_config['latex'] = $config['katex'];
$safe_config['code_tags'] = in_array('js/code_tags/run_prettify.js', $config['additional_javascript']);
$safe_config['max_pages'] = $config['max_pages'];
$safe_config['max_newlines'] = $config['max_newlines'];
$safe_config['reply_limit'] = $config['reply_limit'];


echo json_encode($safe_config);
