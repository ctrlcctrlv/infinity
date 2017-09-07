$('.fileboard td').find("a[target='_blank']").each(function() {
    var url = $(this).attr('href');
    $(this).attr('href', url.replace("media.8ch.net/"+board_name+"/src/","media.8ch.net/file_store/"));
});
