$(document).ready(function(){

window.boards = new Array();

if (window.Options && Options.get_tab('general')) {
	Options.extend_tab("general", "<label id='show_top_boards'><input type='checkbox' /> "+_('Show top boards')+"</label>");

	if (typeof localStorage.show_top_boards === 'undefined') {
		localStorage.show_top_boards = 'false';
		var show_top = JSON.parse(localStorage.show_top_boards);
	}

	var show_top = JSON.parse(localStorage.show_top_boards);
	if (show_top) $('#show_top_boards>input').attr('checked', 'checked');


	$('#show_top_boards>input').on('change', function() {
		var show_top = ($(this).is(':checked'));

		localStorage.show_top_boards = JSON.stringify(show_top);
	});
}

function handle_boards(data) {
	$.each(data, function(k, v) {
		boards.push('<a href="/'+v.uri+(window.active_page === 'catalog' ? '/catalog.html' : '/index.html')+'" title="'+v.title+'">'+v.uri+'</a>');
	})

	if (boards[0]) {
		$('.sub[data-description="1"]').after('<span class="sub" data-description="4"> [ '+boards.slice(0,25).join(" / ")+' ] </span>');
	}
}

if (!(window.location.pathname != '' && window.location.pathname != '/' && window.location.pathname != '/index.html' && typeof show_top !== "undefined" && !show_top)) {
	$.getJSON("/boards-top20.json", handle_boards)
}

});
