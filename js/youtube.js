/*
* youtube.js
* https://github.com/ctrlcctrlv/8chan/blob/master/js/youtube.js
*
* Released under the MIT license
* Copyright (c) 2014 Undido
* 
*
* Optional: 
*	If you use settings.js(https://github.com/savetheinternet/Tinyboard/blob/master/js/settings.js)
*	you can change the video player box size with these settings
*
*	tb_settings['youtube_embed'] = {
*	player_width:"420px",//embed player width
*	player_height:"315px"//embed player height
*	};
*
*/
$(document).ready(function () {
	if (window.Options && window.Options.get_tab("general")){
        var settings = new script_settings('youtube_embed');
			//expression for finding a youtube url
        var youtubeExptext = /(?:youtube.com\/watch\?[^>]*v=|youtu.be\/)([\w_-]{11})(?:(?:#|\?|&amp;)a?t=([ms\d]+)|[^"])*.*?/i;
        var PlayerWidth = (typeof settings.get('player_width') == "undefined") ? "420px" : settings.get('player_width');
        var PlayerHeight = (typeof settings.get('player_height') == "undefined") ? "315px" : settings.get('player_height');
		var autoPlay = (typeof settings.get('autoplay') == "undefined") ? "1" : ((settings.get('autoplay') == true) ? "1":"0" );


		
		var findYoutubeLink = function(string){
			if (typeof string == "undefined")
				return false;
		
            var match = string.match(youtubeExptext);
            if (match && match[1].length == 11) {
                return match[1];
            } else {
                return false;
            }
		};

        var YouTubeBox = function () {
            //video url
            var yt_url = $(this).attr("href");
            //video id
            var yt_id = findYoutubeLink(yt_url);

            var $button = $("<a/>", {
                        "rel": "nofollow",
                        "target": "_BLANK",
                        "text": "Embed"
                    });

            var $youtubeEmbButtonContainer = $('<span/>', {
					"class": 'embedbutton',
					"data-opened": "false",
					"data-youtubeid": yt_id,
					"css": {
								"cursor": "pointer"
							}
				});
			$youtubeEmbButtonContainer.append(" [");
            $youtubeEmbButtonContainer.append($button);
			$youtubeEmbButtonContainer.append("]");
            $youtubeEmbButtonContainer.click(clickEmbedButton);

            $(this).after($youtubeEmbButtonContainer);
        };

        var clickEmbedButton = function () {
            var yt_id = $(this).attr("data-youtubeid");
            var yt_ind = $(this).index();

            if ($(this).attr("data-opened") == "false") {
                $(this).after('<span data-ytid="' + yt_id + '" data-ytind="' + yt_ind + '" class="youtube-box"></br><iframe style="display:inline-block;width:' + PlayerWidth + ';height:' + PlayerHeight + ';border:none;" class="youtube-frame" src="//www.youtube.com/embed/' + yt_id + '?origin=' + document.location.host + '&autoplay="' + autoPlay + '></iframe></span>');
                $(this).attr("data-opened", "true");
                $(this).children('a').text("Close");
            } else {

                var a = $.find("[data-ytid='" + yt_id + "']" + "[data-ytind='" + yt_ind + "']");
                $(a).remove();
                $(this).attr("data-opened", "false");
                $(this).children('a').text("Embed");
            }

        };

        var YouTubeInit = function () {
            var text = $(this).html();
            var isYoutubeLink = findYoutubeLink(text);

            if (isYoutubeLink != false) {
                $(this).each(YouTubeBox);
            }
        };
		
        var disableYouTubeEmbed = function () {
            $('.embedbutton').each(function () {
				$(this).remove();
			});
            $('.youtube-box').each(function () {
				$(this).remove();
			});
        };

		var emb_vid = localStorage['embvid'] ? true : false;
		var selector, event;
		
		
		
		Options.extend_tab('general', '<div><label id="toggle-emb-vid"><input type="checkbox"> Embed youtube links</label></div>');
		
		selector = "#toggle-emb-vid>input";
		event = "click";
		
		$(selector).prop("checked", !emb_vid);


        $(selector).on(event,function () {
				if (!$(this).prop("checked")){
					emb_vid = true;
					disableYouTubeEmbed();
					localStorage['embvid'] = true;
				} else {
					emb_vid = false;
					delete localStorage['embvid'];
					$(".body a").each(YouTubeInit);
				}
            });
			
        if (!emb_vid){
           $(".body a").each(YouTubeInit);
		}
        $(document).bind('new_post', function (e, post) {
                if (!emb_vid)
                    $(post).find('.body a').each(YouTubeInit);
            });
	}
});