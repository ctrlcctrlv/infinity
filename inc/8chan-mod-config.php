<?php
	$config['mod']['show_ip'] = GLOBALVOLUNTEER;
	$config['mod']['show_ip_less'] = BOARDVOLUNTEER;
	$config['mod']['manageusers'] = GLOBALVOLUNTEER;
	$config['mod']['noticeboard_post'] = GLOBALVOLUNTEER;
	$config['mod']['search'] = GLOBALVOLUNTEER;
	$config['mod']['clean_global'] = GLOBALVOLUNTEER;
	$config['mod']['view_notes'] = DISABLED;
	$config['mod']['create_notes'] = DISABLED;
	$config['mod']['edit_config'] = DISABLED;
	$config['mod']['debug_recent'] = ADMIN;
	$config['mod']['debug_antispam'] = ADMIN;
	$config['mod']['noticeboard_post'] = ADMIN;
	$config['mod']['modlog'] = GLOBALVOLUNTEER;
	$config['mod']['mod_board_log'] = MOD;
	$config['mod']['editpost'] = BOARDVOLUNTEER;
	$config['mod']['edit_banners'] = MOD;
	$config['mod']['edit_assets'] = MOD;
	$config['mod']['edit_flags'] = MOD;
	$config['mod']['edit_settings'] = MOD;
	$config['mod']['edit_volunteers'] = MOD;
	$config['mod']['edit_tags'] = MOD;
	$config['mod']['clean'] = BOARDVOLUNTEER;
	// new perms

	$config['mod']['ban'] = BOARDVOLUNTEER;
	$config['mod']['bandelete'] = BOARDVOLUNTEER;
	$config['mod']['unban'] = BOARDVOLUNTEER;
	$config['mod']['deletebyip'] = BOARDVOLUNTEER;
	$config['mod']['sticky'] = BOARDVOLUNTEER;
	$config['mod']['cycle'] = BOARDVOLUNTEER;
	$config['mod']['lock'] = BOARDVOLUNTEER;
	$config['mod']['postinlocked'] = BOARDVOLUNTEER;
	$config['mod']['bumplock'] = BOARDVOLUNTEER;
	$config['mod']['view_bumplock'] = BOARDVOLUNTEER;
	$config['mod']['bypass_field_disable'] = BOARDVOLUNTEER;
	$config['mod']['view_banlist'] = BOARDVOLUNTEER;
	$config['mod']['view_banstaff'] = BOARDVOLUNTEER;
	$config['mod']['public_ban'] = BOARDVOLUNTEER;
	$config['mod']['recent'] = BOARDVOLUNTEER;
	$config['mod']['ban_appeals'] = BOARDVOLUNTEER;
	$config['mod']['view_ban_appeals'] = BOARDVOLUNTEER;
	$config['mod']['view_ban'] = BOARDVOLUNTEER;
	$config['mod']['reassign_board'] = GLOBALVOLUNTEER;
	$config['mod']['move'] = GLOBALVOLUNTEER;
	$config['mod']['pm_all'] = GLOBALVOLUNTEER;
	$config['mod']['shadow_capcode'] = 'Global Volunteer';

	// Mod pages assignment
	$config['mod']['custom_pages']['/tags/(\%b)'] = '8_tags';
	$config['mod']['custom_pages']['/reassign/(\%b)'] = '8_reassign';
	$config['mod']['custom_pages']['/volunteers/(\%b)'] = '8_volunteers';
	$config['mod']['custom_pages']['/flags/(\%b)'] = '8_flags';
	$config['mod']['custom_pages']['/banners/(\%b)'] = '8_banners';
	$config['mod']['custom_pages']['/settings/(\%b)'] = '8_settings';
	$config['mod']['custom_pages']['/assets/(\%b)'] = '8_assets';
