$(document).ready(function(){

window.boards = new Array();

if (window.Options && Options.get_tab('general')) {
	Options.extend_tab("general", "<label id='show-top'><input type='checkbox' /> "+_('Show top boards')+"</label>");

	if (typeof localStorage.show_top === 'undefined') {
		localStorage.show_top = 'true';
		var show_top = JSON.parse(localStorage.show_top);
		$('#show-top>input').attr('checked', 'checked');
	} else {
		var show_top = JSON.parse(localStorage.show_top);
		if (show_top) $('#show-top>input').attr('checked', 'checked');
	}


	$('#show-top>input').on('change', function() {
		var show_top = ($(this).is(':checked'));

		localStorage.show_top = JSON.stringify(show_top);
	});
}

function handle_boards(data) {
	$.each(data, function(k, v) {
		boards.push('<a href="/'+v.uri+(window.active_page === 'catalog' ? '/catalog.html' : '/index.html')+'" title="'+v.title+'">'+v.uri+'</a>');
	})

	if (boards[0]) {
		$('.sub[data-description="3"]').after('<span class="sub" data-description="4"> [ '+boards.slice(0,25).join(" / ")+' ] </span>');
	}
}

if (!(window.location.pathname != '' && window.location.pathname != '/' && window.location.pathname != '/index.html' && typeof show_top !== "undefined" && !show_top)) {
	$.getJSON("/boards-top20.json", handle_boards)
}

});
