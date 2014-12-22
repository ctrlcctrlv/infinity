/*
 * relative-time.js
 *   Replaces the timestamps in posts to show 'x minutes/hours/days ago',
 *   while displaying the absolute time in tooltip 
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/relative-time.js';
 */
if (active_page == 'index' || active_page == 'thread') {
	onready(function () {
		'use strict';

		var selector, event_type;
		if (window.Options && Options.get_tab('general')) {
			selector = '#show-relative-time>input';
			event_type = 'change';
			Options.extend_tab('general', '<label id="show-relative-time"><input type="checkbox">' + _('Show relative time') + '</label>');
		} else {
			selector = '#show-relative-time';
			event_type = 'click';
			$('hr:first').before('<div id="show-relative-time" style="text-align:right"><a class="unimportant" href="javascript:void(0)">'+_('Show relative time')+'</a></div>');
		}

		$(selector).on(event_type, function() {
			if (localStorage.show_relative_time === 'true') {
				localStorage.show_relative_time = 'false';
			} else {
				localStorage.show_relative_time = 'true';
			}
		});


		function update() {
			var currentTime = Date.now();
			$('div.post time').each(function () {
				var postTime = new Date($(this).attr('datetime'));

				$(this)
					.text( timeDifference(currentTime, postTime.getTime()) )
					.attr('title', postTime.toLocaleString('en-GB', {
						weekday: 'short',
						day: 'numeric',
						month: 'numeric',
						year: 'numeric',
						hour: 'numeric',
						minute: 'numeric',
						second: 'numeric',
						hour12: false
					}));
			});
		}
		
		function timeDifference(current, previous) {

			var msPerMinute = 60 * 1000;
			var msPerHour = msPerMinute * 60;
			var msPerDay = msPerHour * 24;
			var msPerMonth = msPerDay * 30;
			var msPerYear = msPerDay * 365;

			var elapsed = current - previous;

			if (elapsed < msPerMinute) {
				return 'Just now';
			} else if (elapsed < msPerHour) {
				return Math.round(elapsed/msPerMinute) + (Math.round(elapsed/msPerMinute)<=1 ? ' minute ago':' minutes ago');
			} else if (elapsed < msPerDay ) {
				return Math.round(elapsed/msPerHour ) + (Math.round(elapsed/msPerHour)<=1 ? ' hour ago':' hours ago');
			} else if (elapsed < msPerMonth) {
				return Math.round(elapsed/msPerDay) + (Math.round(elapsed/msPerDay)<=1 ? ' day ago':' days ago');
			} else if (elapsed < msPerYear) {
				return Math.round(elapsed/msPerMonth) + (Math.round(elapsed/msPerMonth)<=1 ? ' month ago':' months ago');
			} else {
				return Math.round(elapsed/msPerYear ) + (Math.round(elapsed/msPerYear)<=1 ? ' year ago':' years ago');
			}
		}

		if (!localStorage.show_relative_time || localStorage.show_relative_time === 'false') {
			return;
		} else {
			$('#show-relative-time>input').attr('checked','checked');
			setInterval(update, 30000);
			$(document).on('new_post', update);
			update();
		}
	});
}
