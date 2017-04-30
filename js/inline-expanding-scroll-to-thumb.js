/*
 * inline-expanding-scroll-to-thumb.js
 * After minimizing expanded images, scroll back to post if it isn't in view
 *
 * Usage:
 *   // $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/inline-expanding-scroll-to-thumb.js';
 *
 */

if (active_page == 'thread' || active_page == 'index') {
	$(document).ready(function (){
		var padding = 5;
		var boardlist = $('.boardlist')[0];
		//deal with the fixed menus of differnt boards
		if ($(boardlist).css('position') == 'fixed') {
			padding += boardlist.getBoundingClientRect().height;
		}
		$('form[name="postcontrols"]').on('click', '.post-image', function (e) {
			if ($(e.target).parent().attr('data-expanded') != 'true') {
				var post_body = $(e.target).parentsUntil('form > div').last();
				var still_open = post_body.find('.post-image').filter(function (){return $(this).parent().attr('data-expanded') == 'true'}).length;
				
				//incase of multiple expanded images in the same post
				if (still_open > 0) {
					if (e.target.getBoundingClientRect().top - padding < 0)
						$('body').scrollTop($(e.target).parent().parent().offset().top - padding);
				} else {
					if (post_body[0].getBoundingClientRect().top - padding < 0)
						$('body').scrollTop(post_body.offset().top - padding);
				}
			}
		});
	});
}