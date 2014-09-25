      /* 
    Copyright (C) 2014  undido

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. 
	
	expands images and webm on hover
	
	*/
	$(document).ready(function(){
var mouseisOnImage = false;
var mouseexitedImage = false;

var imageHover = (localStorage['imagehover']) ? true:false;


imageHover = !imageHover;


var imageEnter = function(){

 if (!imageHover)
	return;

	

mouseexitedImage = false;
mouseisOnImage = false;

isVideo = (($(this).prop("tagName") == "VIDEO") ? true:($(this).parent().attr("href").indexOf("player.php?v=") > -1) ? true:false);
maxWidth = document.body.offsetWidth-(document.body.offsetWidth * 0.25);
maxHeight = document.documentElement.clientHeight;
stylez = "z-index:1000;z-index: 1000;position: fixed;top: 0;right: 0;";
if (!isVideo){
fileInfo = $(this).parent().parent().children(".fileinfo").children(".unimportant").text();
isSpoiler = (fileInfo.indexOf("Spoiler") > -1) ? true:false;
imageD = ((isSpoiler) ? fileInfo.split(",")[2]:fileInfo.split(",")[1]);
imageWidth = parseInt(imageD.split("x")[0]);
imageHeight = parseInt(imageD.split("x")[1]);

widStyle = "max-width:" + maxWidth + "px;";
heiStyle = ((maxHeight < imageHeight) ? "height:"+maxHeight+"px;":"");
$imgH = $("<img/>", {"src":$(this).parent().attr("href"), "style":stylez + ((imageWidth > maxWidth) ? widStyle:"")+heiStyle, "id":"hover-image"});
} else {
videoWidth = parseInt($(this).parent().parent().find(".unimportant").text().split(",")[1].split("x")[0]);
videoHeight = parseInt($(this).parent().parent().find(".unimportant").text().split(",")[1].split("x")[1]);

widStyle = "width:" + ((maxWidth > videoWidth) ? videoWidth:maxWidth) + "px;" + "height:" + ((maxHeight < videoHeight) ? "100%": videoHeight+"px;");
$imgH = $("<iframe/>", {"src":$(this).parent().attr("href"), "style":stylez + widStyle, "id":"hover-image"});
}



$(document.body).append($imgH);
$("#hover-image").hover(function(){
mouseisOnImage = true;

}, function(){
mouseisOnImage = false;

if (mouseexitedImage){
$("#hover-image").remove();
}
});
};
imageLeave = function(){
setTimeout(function(){
mouseexitedImage = true;

if (!mouseisOnImage){
$("#hover-image").remove();
}
},50);
};
$("a .post-image").hover(imageEnter,imageLeave);

$mrCheckie = $('<div><label id=\"toggle-image-hover\"><input id="toggle-hover" type=\"checkbox\"> show image on hover</label></div>');





$(".options_tab").append($mrCheckie);
$("#toggle-hover").prop("checked", imageHover);
$("#toggle-hover").on("click", function(){
if ($(this).prop("checked")){
imageHover = true;
delete localStorage['imagehover'];
} else {
imageHover = false;
localStorage['imagehover'] = true;
}



});

$(".options_tab").append();



	$(document).on("new_post", function(e, post) {
		$(post).hover(imageEnter(),imageLeave());
	});
});