//Setting variables
var favorites = JSON.parse(localStorage.favorites);
var tab = Options.add_tab('fav-tab','star',_("Favorites"));
var i = 0;
var favList = [];
var minusList = [];
var helpMessage = "<span>Drag the boards to sort them.</span><br></br>";

//Creating functions
function generateList(){
	var favStor = [];
  for(i=1; i<favorites.length+1; i++){
  	favStor.push($("#sortable > div:nth-child("+i+")").html());
  }
	return JSON.stringify(favStor);
} //This will generate a list of boards based off of the list on the screen
function removeBoard(boardNumber){
	favorites.splice(boardNumber, 1);
	localStorage.favorites = JSON.stringify(favorites);
	$("#sortable > div:nth-child("+(boardNumber+1)+")").remove();
	$("#minusList > div:nth-child("+(favorites.length+1)+")").remove();
} //This removes a board from favorites, localStorage.favorites and the page
function addBoard(){
	$("#sortable").append("<div>"+($("#plusBox").val())+"</div>");
	$("#minusList").append("<div onclick=\"removeBoard("+favorites.length+")\" style=\"cursor: pointer; margin-left: 5px\">-</div>");
	favorites.push($("#plusBox").val());
	localStorage.favorites = JSON.stringify(favorites);
	$("#space").remove();
	$("#plusBox").remove(); //Refreshing the last 3 elements to move the box down
	$("#plus").remove();
	$("#submitFavorites").remove();
	$(space).appendTo(tab.content);
	$(plusBox).appendTo(tab.content);
	$("#plusBox").keydown(function( event ) {
 		if(event.keyCode == 13){
 			$("#plus").click();
 		}
	}); //Adding enter to submit
	document.getElementById("plusBox").value = ""; //Removing text from textbox
	$("#plusBox").focus(); //Moving cursor into text box again after refresh
	$(plus).appendTo(tab.content); //Adding the plus to the tab
	$(submit).appendTo(tab.content); //Adding button to the tab
} //This adds the text inside the textbox to favorites, localStorage.favorites and the page

//Creating content

//Creating list of boards
favList += "<div id=\"sortable\" style=\"cursor: pointer; float: left;display: inline-block\">";
for(i=0; i<favorites.length; i++){
    favList += "<div>"+favorites[i]+"</div>";
} 
favList += "</div>"; 

//Creating list of minus symbols to remove unwanted boards
minusList += "<div id=\"minusList\" style=\"color: #0000FF;display: inline-block\">";
for(i=0; i<favorites.length; i++){
    minusList += "<div onclick=\"removeBoard("+i+")\" style=\"cursor: pointer; margin-left: 5px\">-</div>";
} 
minusList += "</div>"; 

var space = $("<br id=\"space\"></br>");
var plusBox = $("<input id=\"plusBox\" type=\"text\">");
var plus = $("<div id=\"plus\" onclick=\"addBoard()\">+</div>").css({
	cursor: "pointer",
	color: "#0000FF"
}); //Creating plus symbol to add wanted boards
var submit = $("<input id=\"submitFavorites\" onclick=\"document.location.reload();\" type=\"button\" value=\""+_("Refresh")+"\">").css({
    height: 25, bottom: 5,
    width: "calc(100% - 10px)",
    left: 5, right: 5
});


//Adding content to the tab
$(tab.content).append(helpMessage); //Adding the help message to the tab
$(favList).appendTo(tab.content);  //Adding the list of favorite boards to the tab
$(minusList).appendTo(tab.content); //Adding the list of minus symbols to the tab
$(space).appendTo(tab.content);
$(plusBox).appendTo(tab.content);
$("#plusBox").keydown(function( event ) {
 if(event.keyCode == 13){
 	$("#plus").click();
 }
});
document.getElementById("plusBox").value = "";
$(plus).appendTo(tab.content); //Adding the plus to the tab
$(submit).appendTo(tab.content); //Adding button to the tab

$("#sortable").sortable(); //Making all objects with sortable id use the sortable jquery function
