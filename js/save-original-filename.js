// ==UserScript==
// @name          Save As Filename Fix
// @namespace     http://www.grauw.nl/projects/pc/greasemonkey/
// @description   Fixes 'Save as original filename' function
// @include     https*//8ch.net/*
// @include     http*//8ch.net/*
// ==/UserScript==
$(function() {
	$("a[download]").each(function() { var url = $(this).attr('href'); $(this).attr('href', url.replace('media.', '').replace('media2.','')); });
	$("a[class=download_image]").each(function() { var url = $(this).attr('data_href'); $(this).attr('data_href', url.replace('media.', '').replace('media2.','')); });

	$("a[download]").each(function() {
        	var url = $(this).attr('href');
        	var filenamedownload = $(this).text();
        	$(this).attr('href', url.replace('//8ch.net', '//media.8ch.net'));
       		this.href += '/'+filenamedownload;
	});

	$('.download_image').on("click",function() {
		var id_download = $(this).attr('id');
		if(!$("#"+id_download+"_menu").hasClass('download_menu_open')){
					$(".download_menu_open:not(#"+id_download+"_menu)").remove();
          var url = $(this).attr('data_href');
          var unixtime_value = $(this).parent().parent().parent().parent().parent().find("time").attr("unixtime");
          var filenamedownload_data = $(this).text().split(".");
          var filename_get_extention  = filenamedownload_data[filenamedownload_data.length - 1];
          var filename_unixtime = unixtime_value;
          var filenamedownload = filename_unixtime +"."+filename_get_extention;
          $(this).attr('data_href', url.replace('//8ch.net', '//media.8ch.net'));
          var download_position = $(this).position();
          var download_position_left = download_position.left;
          var download_position = "left:"+download_position_left;
          var originalfilenamedownload = $(this).html();
          var download_unixtime = "<a download='"+filenamedownload+"' title='"+_('Save as Unix filename')+"' style='color:#2b2a2a' href='"+$(this).attr('data_href')+"/"+filenamedownload+"'>"+_('Save as Unix filename')+"</a>";
          var download_original = "<a download='"+originalfilenamedownload+"' title='"+_('Save as original filename')+"' style='color:#2b2a2a' href='"+$(this).attr('data_href')+"/"+originalfilenamedownload+"'>"+_('Save as original filename')+"</a>";
          var get_hash_data = $(this).attr('data_href').split("/");
          var get_hash_fn  = get_hash_data[get_hash_data.length - 1];
          var download_8chan_hash = "<a download='"+get_hash_fn+"' title='"+_('Save as 8chan hash')+"' style='color:#2b2a2a' href='"+$(this).attr('data_href')+"'>"+_('Save as 8chan hash')+"</a>";
          var download_menu = "<div id='"+$(this).attr('id')+"_menu' class='download_menu_open' style='display:block;"+download_position+"px; position: absolute;z-index:1'><ul style='background-color: rgb(183, 197, 217);border: 1px solid #666;list-style: none;padding: 0;margin: 0;white-space: nowrap;'><li style='cursor: pointer;position: relative;padding: 4px 4px;vertical-align: middle;display: list-item;text-align: -webkit-match-parent;'>"+download_original+"</li><li style='cursor: pointer;position: relative;padding: 4px 4px;vertical-align: middle;display: list-item;text-align: -webkit-match-parent;'>"+download_unixtime+"</li><li style='cursor: pointer;position: relative;padding: 4px 4px;vertical-align: middle;display: list-item;text-align: -webkit-match-parent;'>"+download_8chan_hash+"</li></ul></div>";
         	$(this).parent().append(download_menu);
		
		}else{
			$("#"+id_download+"_menu").remove();
		}

	});	

	$(document).on('click', function (e){
		if ($(e.target).hasClass('download_image'))
			return
		$('.download_menu_open').remove();
	});

});
