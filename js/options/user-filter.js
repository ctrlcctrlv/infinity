//>onclick
function updateFilter(){
	localStorage["filter"][board_name] = $("#filter_ta").val();
	location.reload();
}

Options.add_tab("filter", "times", "Filter", "<textarea id='filter_ta' style='font-size: 12px; position: absolute; top: 35px; bottom: 35px; width: calc(100% - 12px); margin: 0px; padding: 0px; border: 1px solid black; left: 5px; right: 5px;'>"+localStorage["filter"][board_name].replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;")+"</textarea>"
		 +'<button onclick="updateFilter()" style="position: absolute; height: 25px; bottom: 5px; width: calc(100% - 10px); left: 5px; right: 5px;">Update filters</button>');
