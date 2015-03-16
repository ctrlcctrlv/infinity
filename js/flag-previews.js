/*
 * flag-previews.js - Preview board flags
 *
 * Copyright (c) 2014 Fredrick Brennan <admin@8chan.co>
 *
*/

$(document).on('ready', function() {
	var flag_previews = function() {
		if (!$('.flag_preview').length) $('[name=user_flag]').after('<img class="flag_preview">');
		if (!$(this).val()) {
			return $('.flag_preview').remove();
		}

		$('.flag_preview').attr('src', configRoot + "static/custom-flags/" + board_name + "/" + $(this).val() + '.png');
	}

	$('[name=user_flag]').on('change', flag_previews);
	$(window).on('quick-reply', function(){$('[name=user_flag]').on('change', flag_previews)});
});
