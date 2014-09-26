/*
* youtube.js

    Copyright (C) 2014  undido

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
	
	PURE JAVASCRIPT YOUTUBE EMBBEDING
	
	
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
$(document)
    .ready(function () {
        var settings = new script_settings('youtube_embed'),
            youtubeExptext = /(?:youtube.com\/watch\?[^>]*v=|youtu.be\/)([\w_-]{11})(?:(?:#|\?|&amp;)a?t=([ms\d]+)|[^"])*.*?/i,
            PlayerWidth = (typeof settings.get('player_width') == "undefined") ? "420px" : settings.get('player_width'),
            PlayerHeight = (typeof settings.get('player_height') == "undefined") ? "315px" : settings.get('player_height');
			autoPlay = (typeof settings.get('autoplay') == "undefined") ? "1" : ((settings.get('autoplay') == true) ? "1":"0" );



        String.prototype.youTubeparsetext = function () {
            var match = this.match(youtubeExptext);
            if (match && match[1].length == 11) {
                return match[1];
            } else {
                return String('false');
            }
        };


        var YouTubeBox = function () {
            //video url
            var yt_url = this.href;
            //video id
            yt_id = yt_url.youTubeparsetext();

            var $button = $("<div />")
                .append($($("<a/>", {
                        "rel": "nofollow",
                        "target": "_BLANK",
                        "text": "Embed"
                    }))
                    .clone())
                .html();

            $youtubeBox = $('<span/>', {
                "class": 'embedbutton',
                "data-opened": "false",
                "data-youtubeid": yt_id,
                "css": {
                    "cursor": "pointer"
                }


            });

            $youtubeBox.html(" [" + $button + "]");

            $youtubeBox.click(clickEmbed);

            $(this)
                .after($youtubeBox);
        };




        var clickEmbed = function () {
            yt_id2 = $(this)
                .attr("data-youtubeid");
            yt_ind = $(this)
                .index();


            if ($(this)
                .attr("data-opened") == "false") {
                $(this)
                    .after('<span data-ytid="' + yt_id2 + '" data-ytind="' + yt_ind + '" class="youtube-box"></br><iframe style="display:inline-block;width:' + PlayerWidth + ';height:' + PlayerHeight + ';border:none;" class="youtube-frame" src="//www.youtube.com/embed/' + yt_id2 + '?origin=' + document.location.host + '&autoplay="' + autoPlay + '></iframe></span>');
                $(this)
                    .attr("data-opened", "true");
                $(this)
                    .children('a')
                    .text("Close");
            } else {

                a = $.find("[data-ytid='" + yt_id2 + "']" + "[data-ytind='" + yt_ind + "']");
                $(a)
                    .remove();
                $(this)
                    .attr("data-opened", "false");
                $(this)
                    .children('a')
                    .text("Embed");
            }

        };



        var YouTubeInit = function () {
            var text = $(this)
                .html();
            var isYoutubeLink = text.youTubeparsetext();

            if (isYoutubeLink != 'false') {
                $(this)
                    .each(YouTubeBox);

            }
        };




        var disableYouTubeBox = function () {
            $('.embedbutton')
                .each(function () {
                    $(this)
                        .remove();
                });

            $('.youtube-box')
                .each(function () {
                    $(this)
                        .remove();
                });



        };




        emb_vid = localStorage['embvid'] ? true : false;

		
		$('.options_tab:first').append('<div><label id="emb-vid"><input type="checkbox" id="emb-toggle" class="menuOption" data-option="embvid"> Embed youtube links</label></div>');

		$('#emb-toggle').prop("checked", emb_vid ? false:true);


        $('#emb-toggle')
            .click(function () {
			
				if (!$(this).prop("checked")){
					emb_vid = true;
					disableYouTubeBox();
					localStorage['embvid'] = true;
				} else {
					emb_vid = false;
					delete localStorage['embvid'];
					$(".body a").each(YouTubeInit);
				}
            });
			
	
        if (!emb_vid){
           $(".body a")
                .each(YouTubeInit);
		}


        $(document)
            .bind('new_post', function (e, post) {
                if (!emb_vid)
                    $(post)
                        .find('.body a')
                        .each(YouTubeInit);
            });


    });