/*
 * quick-posts-controls.js
 * https://github.com/savetheinternet/Tinyboard/blob/master/js/quick-posts-controls.js
 *
 * Released under the MIT license
 * Copyright (c) 2012 Michael Save <savetheinternet@tinyboard.org>
 * Copyright (c) 2013 undido <firekid109@hotmail.com>
 * Copyright (c) 2013-2014 Marcin Łabanowski <marcin@6irc.net>
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/quick-post-controls.js';
 *
 */

$(document).ready(function(){
	// Bottom of the page quick reply function
	$("#thread-quick-reply").show();
	$("#link-quick-reply").on( 'click', function(event) {
		event.preventDefault();
		$(window).trigger('cite', ['']);
		return false;
	} );
} );
