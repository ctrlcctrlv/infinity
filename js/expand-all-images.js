/*
 * expand-all-images.js
 * https://github.com/savetheinternet/Tinyboard/blob/master/js/expand-all-images.js
 *
 * Adds an "Expand all images" button to the top of the page.
 *
 * Released under the MIT license
 * Copyright (c) 2012-2013 Michael Save <savetheinternet@tinyboard.org>
 * Copyright (c) 2013-2014 Marcin Łabanowski <marcin@6irc.net>
 * Copyright (c) 2014 sinuca <#55ch@rizon.net>
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/inline-expanding.js';
 *   $config['additional_javascript'][] = 'js/expand-all-images.js';
 *
 */

if (active_page == 'ukko' || active_page == 'thread' || active_page == 'index')
onready(function(){
	var expandDong = false;
	$('hr:first').before('<div id="expand-all-images" style="text-align:right"><a class="unimportant" href="javascript:void(0)"></a></div>');
	$('div#expand-all-images a')
		.text(_('Expand all images'))
		.click(function() {
			if (!expandDong){
				$(this).text("Shrink all images");
				expandDong = true;
				$('a img.post-image').each(function() {
					if (!$(this).parent()[0].dataset.expanded)
						$(this).parent().click();
				});
			} else {	
				$(this).text("Expand all images");
				expandDong = false;
				$('a img.post-image').each(function() {
					if ($(this).parent()[0].dataset.expanded)
						$(this).parent().click();
				});
			}



		});
		
		$(document).on('new_post', function(e, post) {
			if (expandDong){
			console.log(post);
				$(post).find(".post-image").parent().click();
			}
        });
});
