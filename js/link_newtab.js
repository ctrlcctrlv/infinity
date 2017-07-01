$(function() {

  $(".boardlist").find(".sub").find("a").each(function() {
      var link_target = ["Boards", "FAQ", "Random", "New board", "Public ban list", "Search", "Manage board", "Advertise on 8chan!", "Twitter", "Claim a board", "File a bug report"];

      if(link_target.includes($(this).find("i").attr('title'))){
        $(this).attr('target','_blank');
      }
  });


});
