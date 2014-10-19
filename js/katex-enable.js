/*
 * katex-enable.js - LaTeX support
 *
 * Copyright (c) 2014 Fredrick Brennan <admin@8chan.co>
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/katex-enable.js';
 */
/*
function render_katex(el) {
	try {
		katex.render($(el).text(), el)
	} catch (e) {
		$(el).text(_('Error: Invalid LaTeX syntax.')).css('color','red');
	}
}

if (active_page == 'thread' || active_page == 'index') {
	$(document).ready(function(){
		var katex_enable = function() {
			$('.tex').each(function(k, v) {
				render_katex(v);
			});
		}
		katex_enable();

		$(document).on('new_post', function(e, post) {
			$(post).find('.tex').each(function(k, v) {
				render_katex(v);
			});
		});
	});
}
*/
