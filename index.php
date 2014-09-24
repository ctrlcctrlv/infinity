<?php
  include "inc/functions.php";
  $boards = sizeof(listBoards());
?>

<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>∞chan</title>
    <style type="text/css">
      /* Responsive helpers */

	.col {
	  float: left;
	}
	
	.col-12 { width: 100%; }
	.col-11 { width: 91.66666667%; }
	.col-10 { width: 83.33333333%; }
	.col-9 { width: 75%; }
	.col-8 { width: 66.66666667%; }
	.col-7 { width: 58.33333333%; }
	.col-6 { width: 50%; }
	.col-5 { width: 41.66666667%; }
	.col-4 { width: 33.33333333%; }
	.col-3 { width: 25%; }
	.col-2 { width: 16.66666667%; }
	.col-1 { width: 8.33333333%; }
	
	.left-push {
	  float: left;
	}
	
	.right-push {
	  float: right;
	}
	
	/* Main */
	
	* {
	  box-sizing: border-box;
	}
	
	body {
	  background: #EEF2FF;
	  font-family: 'Verdana', 'Arial', 'Helvetica', sans-serif;
	  line-height: 1.4;
	}
	
	#main {
	  margin: 0 auto;
	  max-width: 1110px;
	  width: 100%;
	}
	
	h420 {
	  color: #AF0A0F;
	  font-size: 11pt;
	  font-weight: bold;
	  margin: 0px;
	  padding: 0px;
	}
	
	ul, ol, dl {
	  padding: 0;
	  margin: 0;
	}
	
	a { color: #42413C; }
	a:hover { color: white; }
	a:visited { color: #6E6C64; }
	
	.clear {
	  clear: both;
	}
	
	.mobileDisplay {
	  display: none;
	  width: 100%;
	}
	
	/* Header */
	
	header {
	  background-color: #D6DAF0;
	  border: 1px solid #000;
	  display: inline-block;
	  line-height: 150%;
	  padding: 5px;
	  text-align: center;
	  width: 100%;
	}
	
	.header_text  {
	  margin-top: 55px;
	}
	
	/* Body */
	
	#content {
	  display: inline-block;
	  width: 100%;
	}
	
	.content_menu {
	  background-color: #D6DAF0;
	  border: 1px solid #000;
	  text-align: left;
	  list-style-position: inside;
	  padding: 15px 30px;
	  text-align: left;
	}
	
	.content_menu_head li {
	  list-style: none;
	  border-top: 1px solid #666;
	  margin-bottom: 15px;
	}
	
	.content_menu_head a {
	  padding: 5px 5px 5px 15px;
	  display: block;
	  width: 160px;
	  text-decoration: none;
	}
	
	.content_body {
	  padding: 20px;
	}
	
	iframe {
	  margin-top: 25px;
	}
	
	.button {
	  padding: 13px 18px;
	  border: 2px solid #000;
	  font-size: 2.75em;
	  color: #AF0A0F;
	  text-align: center;
	  display: inline-block;
	  background: #D6DAF0;
	  margin-right: 5px;
	  margin-top: 15px;
	  text-decoration: none;
	  font-style: normal;
	}
	
	/* Footer */
	
	.footer {
	  text-align: center;
	  padding: 10px 0;
	  font-size: 10px;
	  margin-top: 20px;
	  text-align: center;
	}
	
	/* Responsive */
	
	@media (max-width: 920px) {
	  #content {
	    margin-top: 10px;
	  }
	
	  .menuCol {
	    width: 100%;
	    float: none;
	  }
	
	  .bodyCol {
	    width: 100%;
	    float: none;
	    margin-top: 20px;
	  }
	
	  .header_logo {
	    float: none;
	    margin: auto;
	    width: 100%;
	  }
	
	  .header_text {
	    float: none;
	    margin: auto;
	    margin-top: 10px;
	    width: 100%;
	  }
	
	  .content_menu_head {
	    width: 45%;
	    float: left;
	  }
	
	  .content_menu_list {
	    width: 45%;
	    float: right;
	  }
	
	  .mobileDisplay {
	    display:inline-block;
	    text-align:center;
	  }
	
	  .mobileHide {
	    display:none;
	  }
	}
	
	@media (max-width: 570px) {
	  .content_menu_head {
	    width: 100%;
	    float: none;
	  }
	
	  .content_menu_list {
	    width: 100%;
	    float: none;
	  }
	
	  .button {
	    font-size: 1.2em;
	  }
	}
    </style>
  </head>

  <body>

    <div id="main">

      <header>

        <div class="col col-3 header_logo">
          <img src="https://8chan.co/static/logo_33.svg" alt="logo" name="logo" width="220" height="145" id="logo">
        </div>

        <div class="col col-9 header_text">
          <em>Welcome to ∞chan, the infinitely expanding imageboard.</em>
          <br/>
          <strong>Featured boards:</strong>
          <span class="sub" data-description="2">[ <a href="/v/index.html" title="Vidya Games">v</a> / <a href="/a/index.html" title="Anime, Manga and all things related">a</a> / <a href="/tg/index.html" title="Traditional Games">tg</a> / <a href="/fit/index.html" title="Fitness, Health, and Feels">fit</a> / <a href="/pol/index.html" title=" Politically Incorrect">pol</a> / <a href="/tech/index.html" title="Technology">tech</a> / <a href="/mu/index.html" title="Music">mu</a> / <a href="/co/index.html" title="Comics &amp; Cartoons">co</a> / <a href="/sp/index.html" title="Sports">sp</a> / <a href="/boards/index.html" title="8chan Boards">boards</a> ]</span>
        </div>
      </header>

      <div class="mobileDisplay">
        <a href="create.php" class="button">Create&nbsp;your&nbsp;board</a>
        <a href="boards.html" class="button">Browse&nbsp;existing&nbsp;boards</a>
      </div>

      <div id="content">

        <div class="col col-3 menuCol">

          <div class="content_menu">
            <ul class="content_menu_head">
              <li><em><a href="https://qchat.rizon.net/?channels=#8chan" target="_blank" />IRC:#8chan @ irc.rizon.net</a></li>
              <li><a href="/faq.html">Frequently Asked Questions</a></li>
              <li><a href="/random.php">View random board</a></li>
              <li><a href="/mod.php" >Manage your board</a></li>
            </ul>

            <div class="content_menu_list">
              <h420>Why host your imageboard on 8ch?</h420>
              <br/>
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
            </div>
            
            <div class="clear"></div>
            <hr/>

            <a class="twitter-timeline" data-dnt="true" href="https://twitter.com/infinitechan" data-widget-id="512750449955328002">Tweets by @infinitechan</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
          </div>

        </div>

        <div class="col col-9 bodyCol">
          <div class="content_body">
            <p>On ∞chan, you can create your own imageboard for free with no experience or programming knowledge needed. As long as the admin logs in once per week and it gets one new post every 72 hours, the board will be hosted forever.</p>
            <div class="mobileHide">
              <a href="create.php" class="button">Create&nbsp;your&nbsp;board</a>
              <a href="boards.html" class="button">Browse&nbsp;existing&nbsp;boards</a>
            </div>
            <br/>
            <!-- START RIZON PASTE / NEWS SCRIPT CODE HERE -->
            <iframe src="https://qchat2.rizon.net/?channels=8chan&uio=OT0xMTE05" width="100%" height="400"></iframe>
            <!--END PASTE CODE -->
          </div>
        </div>

      </div>


      <div class="col col-12 footer">
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
