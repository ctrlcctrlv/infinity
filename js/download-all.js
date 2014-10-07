
if(active_page=='thread')

$(function (){

$('hr:first').before('<div id="download-all" style="text-align:right"><a class="unimportant" href="javascript:void(0)"></a></div>');
        $('div#download-all a')
                .text(_('Download All'))
                .click(function() {


	var zip = new JSZip();
	var files = document.getElementsByClassName("fileinfo");
	for(var i = 0; i < files.length; i++)
	{
		for(var j = 0; j < files.length; j++)
		{
			var x = files[i].getElementsByTagName('a')[j].src;
			zip.file(x);
		}		
	}


var content = zip.generate({type:"blob"});
var fns = document.URL.split('/');
var fn = fns[2] + '/' + fns[4].substring(0, fns[4].length-5);
saveAs(content, fn);

})})