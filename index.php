<?php
include "inc/functions.php";
$boards = sizeof(listBoards());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>∞chan</title>
<style>
body {
	font: 100%/1.4 Verdana, Arial, Helvetica, sans-serif;
	background: #EEF2FF url('https://8chan.co/img/fade-blue.png') repeat-x 50% 0%;
    background-size: 100% 100%;
    background-repeat: repeat-x;
}
ul, ol, dl { 
	padding: 0;
	margin: 0;
}
h1, h2, h3, h4, h5, h6, p {	
	padding-right: 15px;
	padding-left: 15px; 
}
a img { 
	border: none;
}
a:link {
	color: #42413C;
	text-decoration: underline;
}
a:visited {
	color: #6E6C64;
	text-decoration: underline;
}
a:hover, a:active, a:focus { 
	text-decoration: none;
}
.container {
	width: 960px;
	margin: 0 auto;
}
.head {
    margin: 0px;
    border: 1px solid gray;
    line-height: 150%;
	text-align: center;
	border: 1px solid #000;
    background-color: #D6DAF0;
	padding:5px;
	height: 145px;
}
.headLogo {
	align: left;
}
.sideLeft {
	float: left;
	width: 200px;
	padding-bottom: 10px;
	text-align: left;
    border: 1px solid #000;
    background-color: #D6DAF0;
    list-style-position: inside;
    clear: both;
	padding: 20px;
}
.content {
    margin: 0px;
    line-height: 150%;
	text-align: center;
	padding: 10px;
}
.content ul, .content ol { 
	padding: 0 15px 15px 40px; 
}
ul.nav {
	list-style: none; 
	border-top: 1px solid #666; 
	margin-bottom: 15px; 
}
ul.nav li {
	border-bottom: 1px solid #666; 
}
ul.nav a, ul.nav a:visited { 
	padding: 5px 5px 5px 15px;
	display: block; 
	width: 160px;  
	text-decoration: none;
}
ul.nav a:hover, ul.nav a:active, ul.nav a:focus { 
	color: #FFF;
}
.footer {
	padding: 10px 0;
	position: relative;
	clear: both; 
	font-size: 10px;
	margin-top: 20px;
	text-align:center;
}
.fltrt {  
	float: right;
	margin-left: 8px;
}
.fltlft {
	float: left;
	margin-right: 8px;
}
.clearfloat {
	clear:both;
	height:0;
	font-size: 1px;
	line-height: 0px;
}
.why {
	text-align: left;
    border: 1px solid #000;
    background-color: #D6DAF0;
    list-style-position: inside;
    border-radius: 20px;
    clear: both;
	padding: 15px;
}
h420 {
    color: #AF0A0F;
    font-size: 11pt;
	font-weight:bold;
    margin: 0px;
    padding: 0px;
}
a.button {
	padding: 20px;
	border: 2px solid black;
	font-size: 2.75em;
	color: #AF0A0F;
	text-align: center;
	display: inline-block;
	background: #D6DAF0;
	margin-right: 5px;
	margin-top: 5px;
	text-decoration: none;
}
a.button:hover {
	color: white;
}
.head1 {
    margin: 0px;
    border: 1px solid gray;
    line-height: 150%;
	text-align: center;
	border: 1px solid #000;
    background-color: #D6DAF0;
	padding:5px;
}
.login {
	text-align: center;
	font-size: 10px;
}
</style>
</head>
<body>
<div class="container">
  <div class="header">
<div class="head">
<img src="https://8chan.co/static/logo_33.svg" alt="logo" name="logo" width="220" height="145" id="headLogo2" display:block;" align="left"  /></a><em><br /><br /><br />
Welcome to ∞chan, the infinitely expanding imageboard.</em><br />
<strong>Featured boards:</strong>

<span class="sub" data-description="2">[ <a href="/v/index.html" title="Vidya Games">v</a> / <a href="/a/index.html" title="Anime, Manga and all things related">a</a> / <a href="/tg/index.html" title="Traditional Games">tg</a> / <a href="/fit/index.html" title="Fitness, Health, and Feels">fit</a> / <a href="/pol/index.html" title=" Politically Incorrect">pol</a> / <a href="/tech/index.html" title="Technology">tech</a> / <a href="/mu/index.html" title="Music">mu</a> / <a href="/co/index.html" title="Comics &amp; Cartoons">co</a> / <a href="/sp/index.html" title="Sports">sp</a> / <a href="/boards/index.html" title="8chan Boards">boards</a> ]</span>
</div>
  </div>
  <div class="sideLeft">
    <ul class="nav">
		<li><em><a href="https://qchat.rizon.net/?channels=#8chan" target="_blank" />IRC:#8chan @ irc.rizon.net</a></li>
		<li><a href="/faq.html">Frequently Asked Questions</a></li>
		<li><a href="/random.php">View random board</a></li>
		<li><a href="/mod.php" >Manage your board</a></li>
    </ul>
    
  	<h420>Why host your imageboard on 8ch?</h420>
    <br />
    <li>100% free</li>
    <li>Customizable board styles and banners</li
    ><li>User-definable word filters</li>
    <li>Optional forced anonymity</li>
    <li>Optional country flags</li>
    <li>YouTube embedding</li>
    <li>Run by competent, experienced global staff</li>
    <li>No technical knowledge needed</li>
    <li>HTTPS by default</li>
    <li>Simple global rules — the rest is up to you</li>
    <li><s>Featured on Al Jazeera America</s> ;_;</li>
    <li><em>Not owned by moot</em></li>
<hr/>
<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/infinitechan" data-widget-id="512750449955328002">Tweets by @infinitechan</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
</div>
<div class="content"><br />
<br /><p>On ∞chan, you can create your own imageboard for free with no experience or programming knowledge needed. As long as the admin logs in once per week and it gets one new post every 72 hours, the board will be hosted forever.</p><br />
	<a href="create.php" class="button">Create your board</a><br /><br />
	<a href="boards.html" class="button">Browse existing boards</a>
<!--- START RIZON PASTE / NEWS SCRIPT CODE HERE ---><br />
<br />
<iframe src="https://qchat2.rizon.net/?channels=8chan&uio=OT0xMTE05" width="647" height="400"></iframe>
<!---END PASTE CODE--->
<br />
<br />
<br />
</div>
 
<div class="footer">
  <hr />
    - <a href="http://tinyboard.org"/>Tinyboard</a> + <a href="https://int.vichan.net/devel/"/>vichan</a> 4.9.91 -<br />
<a href="http://tinyboard.org/"/>Tinyboard</a> Copyright © 2010-2014 Tinyboard Development Group <br />
<a href="https://int.vichan.net/devel/"/>vichan</a> Copyright © 2012-2014 vichan-devel<br />
<hr width="50%" />
All trademarks, copyrights, comments, and images on this page are owned by and are the responsibility of their respective parties.<br />
Proprietary Tinyboard changes & 8chan.co trademark and logo © 2013-2014 <a href="https://blog.8chan.co/"/>Fredrick Brennan</a><br />
To make a DMCA request or report illegal content, please email <a href="mailto:admin@8chan.co">admin@8chan.co</a> or use the "Global Report" functionality on every page.<br />
</div>
</div>
</body>
</html>
