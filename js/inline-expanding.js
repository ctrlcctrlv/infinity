/*
 * inline-expanding.js
 * https://github.com/savetheinternet/Tinyboard/blob/master/js/inline-expanding.js
 *
 * Released under the MIT license
 * Copyright (c) 2012-2013 Michael Save <savetheinternet@tinyboard.org>
 * Copyright (c) 2013-2014 Marcin Łabanowski <marcin@6irc.net>
 * Copyright (c) 2014 Undido
 * Usage:
 *   // $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/inline-expanding.js';
 *
 */

onready(function(){
	var fitToScreen = (localStorage.fittoscreen) ? true:false;	
	var image_on_load = function() {
		this.style.opacity = '';
		delete this.style.filter;
	};
			
	var image_on_click = function(e) {
		var childNode = this.childNodes[0];
		if (childNode.className == 'hidden')
			return false;
			
		if (e.which == 2 || e.metaKey || e.ctrlKey)
			return true;
			
		if (!this.dataset.src) {
			this.parentNode.removeAttribute('style');
			this.dataset.expanded = 'true';
			if (childNode.tagName === 'CANVAS') {
				this.removeChild(childNode);
				//redefine childNode as we deleted the previously selected one
				childNode = this.childNodes[0];
				childNode.style.display = 'block';
			}

			this.dataset.src= childNode.src;
			this.dataset.width = childNode.style.width;
			this.dataset.height = childNode.style.height;
				

			childNode.src = this.href;
			childNode.style.width = 'auto';
			childNode.style.height = 'auto';
			childNode.style.opacity = '0.4';
			childNode.style.filter = 'alpha(opacity=40)';
			childNode.onload = image_on_load;
		} else {
			if (this.parentNode.className.indexOf('multifile'))
				this.parentNode.style.width = (parseInt(this.dataset.width)+40)+'px';
				
			childNode.src = this.dataset.src;
			childNode.style.width = this.dataset.width;
			childNode.style.height = this.dataset.height;
			delete this.dataset.expanded;
			delete this.dataset.src;
			delete childNode.style.opacity;
			delete childNode.style.filter;

			if (localStorage.no_animated_gif === 'true' && typeof unanimate_gif === 'function') {
				unanimate_gif(childNode);
			}
		}
		return false;
	};

	var inline_expand_post = function() {
		var boardlistHeight = 0;
		var link = this.getElementsByTagName('a');
		//check to see if 8chan's first boardlist position is set to fixed so we can subtract it from maxheight
		if ($(".boardlist:first").css("position") === "fixed"){
			boardlistHeight = $(".boardlist:first").height();
			
			//css borders pixel width will add to the element's height so we must account for the difference!
			boardlistHeight += parseInt($(".boardlist:first").css("border-top-width"));
			boardlistHeight += parseInt($(".boardlist:first").css("border-bottom-width"));
		}
		var maxWidth = document.body.offsetWidth-(document.body.offsetWidth * 0.06);
		var maxHeight = document.documentElement.clientHeight - boardlistHeight;			
			for (var i = 0; i < link.length; i++) {
				if (typeof link[i] == "object" && link[i].childNodes && typeof link[i].childNodes[0] !== 'undefined' && (link[i].childNodes[0].src ? true:(link[i].childNodes[0].tagName === "CANVAS")) == true && link[i].childNodes[0].className.match(/post-image/) && !link[i].className.match(/file/)) {
					if (fitToScreen){
						var fileInfo = $(link[i]).parent().children(".fileinfo").children(".unimportant").text();
						var isSpoiler = (fileInfo.indexOf("Spoiler") > -1);
						var imageDimensions = ((isSpoiler) ? fileInfo.split(",")[2]:fileInfo.split(",")[1]);
						var imageWidth = parseInt(imageDimensions.split("x")[0]);
						var imageHeight = parseInt(imageDimensions.split("x")[1]);
						link[i].childNodes[0].style.maxWidth = ((imageWidth > maxWidth) ? maxWidth+"px":'94%');
						link[i].childNodes[0].style.maxHeight = ((imageHeight > maxHeight) ? maxHeight+"px":'');
					} else {
						link[i].childNodes[0].style.maxWidth = '94%';
						link[i].childNodes[0].style.maxHeight = 'none';
					}
					link[i].onclick = image_on_click;
				}
			}
	};

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
	if (window.Options && Options.get_tab("general")){
		selector = "#toggle-image-fittoscreen";
		event = "change";
		
		Options.extend_tab('general', '<div><label id="toggle-image-fittoscreen"><input type="checkbox"> ' + _("Fit expanded images to screen") + '</label></div>');
		$("#toggle-image-fittoscreen>input").prop("checked",fitToScreen);
	} else {
		selector = "#toggle-image-fittoscreen";
		event = "click";
		
		$("hr:first").before('<div id="toggle-image-fittoscreen" style="text-align:right"><a class="unimportant" href="javascript:void(0)">-</a></div>');
		$("div#toggle-image-fittoscreen a").text(_('Fit expanded Images')+' (' + (fitToScreen ? _('enabled') : _('disabled')) + ')');
	}
	
	$(selector).on(event, function() {
		if (!fitToScreen){
			if (event === "click")
				$("div#toggle-image-fittoscreen a").text(_('Fit expanded Images')+' ('+_('enabled')+')');
			fitToScreen = true;
			inline_expand_post.call(document);
			localStorage.fittoscreen = true;
		} else {
			if (event === "click")
				$("div#toggle-image-fittoscreen a").text(_('Fit expanded Images')+' ('+_('disabled')+')');
			fitToScreen = false;
			inline_expand_post.call(document);
			delete localStorage.fittoscreen;
		}
	});	
});
