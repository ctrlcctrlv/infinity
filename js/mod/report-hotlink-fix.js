$(function() {
  $(".report-reason a").each(function() {
    var old_href = $(this).attr('href');
    $(this).attr('href', 'https://8ch.net' + old_href);
  });
});
