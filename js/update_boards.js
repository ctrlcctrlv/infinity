$(document).ready(function(){

window.boards = new Array();
function handle_boards(data) {
	$.each(data, function(k, v) {
		if (v.uri != 'meta' && v.uri != 'b') {
			boards.push('<a href="/'+v.uri+(window.active_page === 'catalog' ? '/catalog.html' : '')+'">'+v.uri+'</a>');
		}
	})

	if (boards[0]) {
		$('.sub[data-description="2"]').after('<span class="sub" data-description="3"> [ '+boards.slice(0,15).join(" / ")+' ] </span>');
	}
	
}

$.getJSON("/boards-top20.json", handle_boards)


});
