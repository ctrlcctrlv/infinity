if(active_page == 'thread' || active_page == 'index' || active_page == "ukko"){
	$(document).ready(function(){
		function check_filter(post){
			if(localStorage["filter"][board_name].trim().length == 0) return;
			
			localStorage["filter"][board_name].split("\n").forEach(function(e, i, a){
				var body = $(post).find(".body");
				
				console.log(new RegExp(e).test($(body).text()));
				
				if(new RegExp(e).test($(body).text())){
					var filteredPost = $(body).text();
					$(body).html("<i>Filtered.</i>");
					
					$(body).hover(function(){
						$(this).text(filteredPost);
					}, function(){
						$(this).html("<i>Filtered.</i>");
					});
				}
			});
		}
		
		$(".post").each(function(){
			check_filter($(this));
		});
		
		$(document).on('new_post', function(e, post){
			check_filter(post);
		});
	});
}