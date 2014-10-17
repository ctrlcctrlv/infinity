<?php

/*
*  Instance Configuration
*  ----------------------
*  Edit this file and not config.php for imageboard configuration.
*
*  You can copy values from config.php (defaults) and paste them here.
*/


	$config['db']['server'] = '127.0.0.1:3307';
	$config['db']['database'] = 'chan';
	$config['db']['prefix'] = '';
	$config['db']['user'] = 'chan';
	$config['db']['password'] = '';


	$config['cookies']['mod'] = 'mod';
	$config['cookies']['salt'] = 'OTRkMWRlYmRlZmE2NGZkNmU5YThkZW';

	$config['flood_time'] = 5;
	$config['flood_time_ip'] = 30;
	$config['flood_time_same'] = 2;
	$config['max_body'] = 5000;
	$config['reply_limit'] = 300;
	$config['max_links'] = 40;
	$config['max_filesize'] = 8388608;
	$config['thumb_width'] = 255;
	$config['thumb_height'] = 255;
	$config['max_width'] = 10000;
	$config['max_height'] = 10000;
	$config['threads_per_page'] = 15;
	$config['max_pages'] = 15;
	$config['threads_preview'] = 5;
	$config['root'] = '/8chan/';
	$config['secure_trip_salt'] = 'ZjJmMzg1MzY4MWU3Y2UyNzkxYmQyNW';

