/*Download Hash and Unix filename*/
onready(function(){
        var get_files, do_hash_unix_filename, unixtime_value, file_ctr, unix_file_ctr_str, unix_filename_download, download_unixtime, filename_get_extention;
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

              if($(this).find('a').attr('href') && !$(this).hasClass('hash_unix')){
                var new_href = $(this).find('a').attr('href').replace('//8ch.net', '//media.8ch.net');
                var download_hash = " <a download='"+hash_filename_download+"' class='hash_unix' title='"+hash_filename_download+"' href='"+ new_href +"'>(h)</a>";
                var download_unixtime = "<a download='"+unix_filename_download+"' class='hash_unix' title='"+unix_filename_download+"' href='"+ new_href +"/"+unix_filename_download+"'>(u)</a>";
                $(this).find('.unimportant').append( download_hash + " " + download_unixtime );
              }
           };

           get_files.find('.file').each(files_filenames);
          }

        };

        $('.has-file').each(do_hash_unix_filename);

        $(document).on('new_post', function(e, post) {
                $(post).parent().find('.has-file').last().each(do_hash_unix_filename);
        });
});
