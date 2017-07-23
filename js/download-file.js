/*
 * download-file.js
 * https://github.com/savetheinternet/Tinyboard/blob/master/js/download-original.js
 *
 * Makes image filenames clickable, allowing users to download and save files as their original filename.
 * Only works in newer browsers. http://caniuse.com/#feat=download
 *
 * Released under the MIT license
 * Copyright (c) 2012-2013 Michael Save <savetheinternet@tinyboard.org>
 * Copyright (c) 2013-2014 Marcin Ã…Âabanowski <marcin@6irc.net>
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/download-file.js';
 *
 */
onready(function(){
				var isFirefox = typeof InstallTrigger !== 'undefined';
        var do_original_filename = function() {
                var filename, truncated;
                if ($(this).attr('title')) {
                        filename = $(this).attr('title');
                } else {
                        filename = $(this).text();
                }

								if(isFirefox){
                		$(this).replaceWith(
                        $('<a></a>')
																.attr('style', 'cursor: pointer')
                                .attr('class', 'download_file_8ch')
                                .append($(this).contents())
                                .attr('path_href', $(this).parent().parent().find('a').attr('href')+"/"+filename)
                                .attr('title', filename)
                        );
								}else{
                		$(this).replaceWith(
                        $('<a></a>')
                                .attr('download', filename)
                                .append($(this).contents())
                                .attr('href', $(this).parent().parent().find('a').attr('href')+"/"+filename)
                                .attr('title', filename)
                        );
								}
		
        };

        $('.postfilename').each(do_original_filename);

        $(document).on('new_post', function(e, post) {
                $(post).find('.postfilename').each(do_original_filename);
        });

});



/*Download Hash and Unix filename*/
onready(function(){
        var get_files, do_hash_unix_filename, unixtime_value, file_ctr, unix_file_ctr_str, unix_filename_download, download_unixtime, filename_get_extention;
				var isFirefox = typeof InstallTrigger !== 'undefined';
				var paleMoon          = !!navigator.userAgent.match(/[\d\.]+.*PaleMoon\/*/);

        do_hash_unix_filename = function() {

          if($(this).find("time").attr("unixtime") !== 'undefined'){
            unixtime_value = $(this).find("time").attr("unixtime");

            if($(this).hasClass('op')){
             get_files = $(this).prev();
            }
            else{
             get_files = $(this);
            }

           file_ctr = 0;
           var files_filenames = function() {

              filenamedownload = $(this).find(".fileinfo").find('a').first().text().split(".");
              filename_get_extention  = filenamedownload[filenamedownload.length - 1];

              unix_file_ctr_str = (file_ctr!=0 ? '-' + file_ctr  : '');

              var unix_filename_download = unixtime_value + unix_file_ctr_str + "." + filename_get_extention;
              var hash_filename_download = $(this).find("a").first().attr('title');

              file_ctr = file_ctr + 1;

              if($(this).find('a').attr('href') && !$(this).find('.unimportant').find('a').hasClass('hash_unix')){
                var new_href = $(this).find('a').attr('href').replace('//8ch.net', '//media.8ch.net');
								if(isFirefox && !paleMoon){
                	var download_hash = " <a class='hash_unix download_file_8ch' title='"+hash_filename_download+"' path_href='"+ new_href +"' style='cursor: pointer'>(h)</a>";
                	var download_unixtime = "<a class='hash_unix download_file_8ch' title='"+unix_filename_download+"' path_href='"+ new_href +"/"+unix_filename_download+"' style='cursor: pointer'>(u)</a>";
								}else{
									var download_hash = " <a download='"+hash_filename_download+"' class='hash_unix' title='"+hash_filename_download+"' href='"+ new_href +"'>(h)</a>";
									var download_unixtime = "<a download='"+unix_filename_download+"' class='hash_unix' title='"+unix_filename_download+"' href='"+ new_href +"/"+unix_filename_download+"'>(u)</a>";
								}


                $(this).find('.unimportant').append( download_hash + " " + download_unixtime );
              }
           };

           get_files.find('.file').each(files_filenames);
          }

        };

        $('.has-file').each(do_hash_unix_filename);

        $(document).on('new_post', function(e, post) {
                $(post).parent().find('.has-file').last().each(do_hash_unix_filename);

                $(".download_file_8ch").on("click", function() {
                  var fileContents = $(this).attr('path_href');
                  var fileName = $(this).attr('title')
                  var myHeaders = new Headers();

                  var myInit = { method: 'GET',
                             headers: myHeaders,
                             mode: 'cors',
                             cache: 'default' };

                  var myRequest = new Request(fileContents, myInit);

                  fetch(myRequest).then(function(response) {
                    return response.blob();
                  }).then(function(myBlob) {
                    var objectURL = URL.createObjectURL(myBlob);
                    var a = document.createElement('a');
                    a.href = objectURL;
                    a.download = fileName;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                  });

                });


        });
});

$(function() {

	$(".download_file_8ch").on("click", function() {
		var fileContents = $(this).attr('path_href');
		var fileName = $(this).attr('title')
		var myHeaders = new Headers();
		myHeaders.append("Content-Disposition", "attachment");
	
		var myInit = { method: 'GET',
               headers: myHeaders,
               mode: 'cors',
               cache: 'default' };

		var myRequest = new Request(fileContents, myInit);

		fetch(myRequest).then(function(response) {
  		return response.blob();
		}).then(function(myBlob) {
  		var objectURL = URL.createObjectURL(myBlob);
			var a = document.createElement('a');
			a.href = objectURL;
			a.download = fileName;
			document.body.appendChild(a);
			a.click();
			document.body.removeChild(a);
		});

	});

});



// ==UserScript==
// @name          Save As Filename Fix
// @namespace     http://www.grauw.nl/projects/pc/greasemonkey/
// @description   Fixes 'Save as original filename' function
// @include     https*//8ch.net/*
// @include     http*//8ch.net/*
// ==/UserScript==
$(function() {
  $("a[download]").each(function() { var url = $(this).attr('href'); $(this).attr('href', url.replace('media.', '').replace('media2.','')); });

  $("a[download]").each(function() {
          var url = $(this).attr('href');
          var filenamedownload = $(this).text();
          $(this).attr('href', url.replace('//8ch.net', '//media.8ch.net'));
  });


	/*Filename New Process*/
  $(".download_file_8ch").each(function() { var url = $(this).attr('path_href'); $(this).attr('path_href', url.replace('media.', '').replace('media2.','')); });

  $(".download_file_8ch").each(function() {
          var url = $(this).attr('path_href');
          var filenamedownload = $(this).text();
					$(this).attr('path_href', url.replace('//8ch.net', '//media.8ch.net'));
  });


});
