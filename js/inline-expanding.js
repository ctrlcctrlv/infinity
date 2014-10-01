/*
 * inline-expanding.js
 * https://github.com/savetheinternet/Tinyboard/blob/master/js/inline-expanding.js
 *
 * Released under the MIT license
 * Copyright (c) 2012-2013 Michael Save <savetheinternet@tinyboard.org>
 * Copyright (c) 2013-2014 Marcin Łabanowski <marcin@6irc.net>
 *
 * Usage:
 *   // $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/inline-expanding.js';
 *
 */

onready(function(){
	if (window.Options && Options.get_tab("general")){
		var fitToScreen = (localStorage['fittoscreen']) ? true:false;
		var inline_expand_post = function() {
		var link = this.getElementsByTagName('a');
		var maxWidth = document.body.offsetWidth-(document.body.offsetWidth * 0.02);
		var maxHeight = document.documentElement.clientHeight;
		
			for (var i = 0; i < link.length; i++) {
				if (typeof link[i] == "object" && link[i].childNodes && typeof link[i].childNodes[0] !== 'undefined' && link[i].childNodes[0].src && link[i].childNodes[0].className.match(/post-image/) && !link[i].className.match(/file/)) {
					if (fitToScreen){
						fileInfo = $(link[i]).parent().children(".fileinfo").children(".unimportant").text();
						isSpoiler = (fileInfo.indexOf("Spoiler") > -1) ? true:false;
						imageD = ((isSpoiler) ? fileInfo.split(",")[2]:fileInfo.split(",")[1]);
						imageWidth = parseInt(imageD.split("x")[0]);
						imageHeight = parseInt(imageD.split("x")[1]);
						console.log(imageWidth, "X", imageHeight);
						link[i].childNodes[0].style.maxWidth = ((imageWidth > maxWidth) ? maxWidth+"px":'98%');
						link[i].childNodes[0].style.maxHeight = ((imageHeight > maxHeight) ? maxHeight+"px":'');
					} else {
						link[i].childNodes[0].style.maxWidth = '98%';
						link[i].childNodes[0].style.maxHeight = 'none';
					}
					link[i].onclick = function(e) {
						if (this.childNodes[0].className == 'hidden')
							return false;
						if (e.which == 2 || e.metaKey)
							return true;
						if (!this.dataset.src) {
							this.parentNode.removeAttribute('style');
							this.dataset.expanded = 'true';

							if (this.childNodes[0].tagName === 'CANVAS') {
								this.removeChild(this.childNodes[0]);
								this.childNodes[0].style.display = 'block';
							}

							this.dataset.src= this.childNodes[0].src;
							this.dataset.width = this.childNodes[0].style.width;
							this.dataset.height = this.childNodes[0].style.height;
							

							this.childNodes[0].src = this.href;
							this.childNodes[0].style.width = 'auto';
							this.childNodes[0].style.height = 'auto';
							this.childNodes[0].style.opacity = '0.4';
							this.childNodes[0].style.filter = 'alpha(opacity=40)';
							this.childNodes[0].onload = function() {
								this.style.opacity = '';
								delete this.style.filter;
							}
						} else {
							if (~this.parentNode.className.indexOf('multifile'))
								this.parentNode.style.width = (parseInt(this.dataset.width)+40)+'px';
							this.childNodes[0].src = this.dataset.src;
							this.childNodes[0].style.width = this.dataset.width;
							this.childNodes[0].style.height = this.dataset.height;
							delete this.dataset.expanded;
							delete this.dataset.src;
							delete this.childNodes[0].style.opacity;
							delete this.childNodes[0].style.filter;

							if (localStorage.no_animated_gif === 'true' && typeof unanimate_gif === 'function') {
								unanimate_gif(this.childNodes[0]);
							}
						}
						return false;
					}
				}
			}
		}

		if (window.jQuery) {
			$('div[id^="thread_"]').each(inline_expand_post);

			// allow to work with auto-reload.js, etc.
			$(document).on('new_post', function(e, post) {
				inline_expand_post.call(post);
			});
		} else {
			inline_expand_post.call(document);
		}
		
		
		
		var selector, event;

			selector = "#toggle-image-fittoscreen";
			event = "click";
			Options.extend_tab('general', '<div><label id=\"toggle-image-fittoscreen\"><input type=\"checkbox\"> fit expanded images to screen.</label></div>');
			$("#toggle-image-fittoscreen>input").prop("checked",fitToScreen);
			$(selector).on(event, function(){
				if ($(this).prop("checked")){
					fitToScreen = true;
					inline_expand_post.call(document);
					localStorage['fittoscreen'] = true;
				} else {
					fitToScreen = false;
					inline_expand_post.call(document);
					delete localStorage['fittoscreen'];
				}
			});
	}
	
});
