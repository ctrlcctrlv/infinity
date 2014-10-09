$(document).ready(function(){

window.boards = new Array();
function handle_boards(data) {
	$.each(data, function(k, v) {
		if (v.uri != 'meta' && v.uri != 'b' && v.uri != 'int') {
			boards.push('<a href="/'+v.uri+'">'+v.uri+'</a>');
		}
	})

	if (boards[0]) {
		$('.favorite-boards').before('<span class="sub" data-description="3"> [ '+boards.slice(0,15).join(" / ")+' ] </span>');
	}
	
}

$.getJSON("/boards.json", handle_boards)


});
