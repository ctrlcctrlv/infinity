<?php
include "inc/functions.php";
$body = <<<EOT


<a id="top-faq">
<style>img{max-width:100%}</style>
<div class="ban">
<ol>
	<li> <a href="#what-is-8chan">What is 8chan?</a></li>
	<li> <a href="#what-is-an-imageboard">What is an imageboard?</a></li>
	<li> <a href="#how-is-8chan-run">How is 8chan run?</a></li>
	<li> <a href="#how-do-i-create-a-new-thread">How do I create a new thread?</a></li>  
	<li> <a href="#how-do-i-reply-to-a-thread">How do I reply to a thread?</a></li>  
	<li> <a href="#how-do-i-reply-to-another-poster">How do I reply to another poster?</a></li>  
	<li> <a href="#are-there-any-global-rules-regarding-content">Are there any global rules regarding content?</a></li>
	<li> <a href="#how-do-i-format-my-text">How do I format my text?</a></li>
	<li> <a href="#what-is-sage">What is sage?</a></li>
	<li> <a href="#what-is-a-tripcode">What is a tripcode?</a></li>
        <li> <a href="#what-is-alacrity">What is Alacrity?</a></li>
	<li> <a href="#how-screencap-works">How does [Screencap] work?</a></li>
	<li> <a href="#what-are-these-featured-boards-at-the-top-of-the-page">What are these featured boards at the top?</a></li>
	<li> <a href="#is-there-a-way-to-tell-if-im-banned">Is there a way to tell if I'm banned?</a></li>
	<li> <a href="#where-is-the-mobile-app">Where is the mobile app?</a></li>
	<li> <a href="#where-is-the-archive">Where is the archive?</a></li>
	<li> <a href="#what-is-the-best-way-to-contact-the-8chan-administration">What is the best way to contact the 8chan administration?</a></li>
	<li> <a href="#do-you-have-a-privacy-policy">Do you have a privacy policy?</a></li>
	<li> <a href="#which-boards-are-global-and-owned-by-the-8chan-administration">Which boards are global and owned by the 8chan administration?</a></li>
	<li> <a href="#could-you-add-a-new-feature">Could you add a new feature?</a></li>
	<li> <a href="#8chans-onion-service-is-giving-me-an-error-did-you-discontinue-tor-support">8chan's onion service is giving me an error. Did you discontinue Tor support?</a></li>
	<li> <a href="#i-am-getting-infinite-captchas-when-I-try-to-post-with-my-vpn-did-you-globally-ban-it-or-are-you-not-allowing-vpns-to-post">I am getting infinite captchas when I try to post with my VPN. Did you globally ban it or are you not allowing VPNs to post?</a></li>
	<li> <a href="#i-am-getting-x-error-when-trying-to-post-am-i-banned">I am getting X error when trying to post, am I banned?</a></li>
	<li> <a href="#can-i-donate-to-8chan">Can I donate to 8chan?</a></li>
	<li> <a href="#i-would-like-to-advertise-on-8ch.net-is-this-possible">I would like to advertise on 8ch.net, is this possible?</a></li>
	<li> <a href="#i-saw-an-ad-i-believe-violates-global-rules-how-can-i-report-it">I saw an ad that I believe violates global rules, how can I report it?</a></li>
	<li> <a href="#i-created-a-board-how-do-i-manage-it">I created a board, how do I manage it?</a></li>
	<li> <a href="#how-do-i-add-more-volunteers">How do I add more volunteers?</a></li>
	<li> <a href="#how-do-i-post-as-a-volunteer-on-my-board">How do I post as a volunteer on my board?</a></li>
	<li> <a href="#help-my-board-has-been-deleted">Help! My board has been deleted!</a></li>
	<li> <a href="#i-lost-ownership-of-my-board-what-happened">I lost ownership of my board, what happened?</a></li>
	<li> <a href="#help-the-owner-of-x-board-is-doing-something-i-dont-like-remove-him">Help! The owner of X board is doing something I don't like! Remove him!</a></li>
	<li> <a href="#are-there-any-publicly-available-statistics">Are there any publicly available statistics?</a></li>
	<li> <a href="#i-want-my-board-to-show-up-in-the-recommended-board-list">I want my board to show up in the recommended board list!</a></li>
	<li> <a href="#i-got-an-email-at-8chanco-email-address">I got an email from an @8chan.co email address. Is that you?</a></li>
	<li> <a href="#can-i-have-a-list-of-all-api-endpoints-for-getting-raw-data-from-8chan">Can I have a list of all API endpoints for getting raw data from 8chan?</a></li>
	<li> <a href="#i-would-like-to-contribute-a-translation-in-my-language">I would like to contribute a translation in my language.</a></li>
</ol>


<a id="what-is-8chan"></a><h2>What is 8chan?</h2>
<p>8ch.net is a website running <a href="https://github.com/OpenIB/OpenIB" target="_blank">OpenIB</a> as a result of the 2017 April Fools' Day hack. Previously, it ran <a target="_blank" href="https://github.com/ctrlcctrlv/infinity">infinity</a>, an open-source software that allows anyone to create and manage their own anonymous imageboard without any programming or webhosting experience for free. <a target="_blank" href="https://github.com/ctrlcctrlv/infinity">infinity</a> is based on <a target="_blank" href="https://engine.vichan.net/">vichan</a> (pronounced sixchan), which in turn stems from an older, abandoned project called <a target="_blank" href="https://tinyboard.org/">Tinyboard</a>. </p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>


<a id="what-is-an-imageboard"></a><h2>What is an imageboard?</h2>
<p>An imageboard lets you post text and images anonymously (without a name) about any topic - or no topic at all. Unlike forums, imageboards do not hold or store old content permanently and old threads are pruned as new ones are created.</p>

<p>The imageboard format has several advantages over traditional forums:</p>
<ul>
   <li>There is no registration process, which allows anyone to post what they like without having to jump through hoops;</li>
   <li>Users are not required to have names and, thus, feel no reason to build up an identity or reputation for themselves - posts are judged based on their content rather than who made them;</li>
   <li>Sharing images and multimedia content is as easy as saving and uploading it to the site.</li>
</ul>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="how-is-8chan-run"></a><h2>How is 8chan run?</h2>
<p>8chan is a service that hosts a large selection of imageboards to browse. The individual boards that make up 8chan are maintained by their respective owners, who are not affiliated with the 8chan administration.</p>

<p>The <a target="_blank" href="https://8ch.net/glovols.html">8chan global staff</a> maintains and improves the site and protects it from illegal content and spam. The 8chan administration does not enforce any rules other than the <a target="_blank" href="https://8ch.net/globalrule.html">8chan global rule</a>. Any complaints about the content, management, or moderation of a board should be addressed to the owner of said board, unless it violates the laws of the United States of America - because that is where the servers are located - or 8chan's <a target="_blank" href="https://8ch.net/dost.html">global policy</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="how-do-i-create-a-new-thread"></a><h2>How do I create a new thread?</h2>
<p>
Navigate to the board you would like to post on, fill out the post form, and click on <i>New Thread</i>. On most boards, you are required to upload an image, but, if you do not have one, you can also draw a picture by using the oekaki applet (<i>[Show post options & limits]</i> > <i>Show oekaki applet</i>). No board requires you to provide a name or email address.
</p>
<p><img src='static/how_to_make_a_thread.png'></p>

<p>By clicking on <i>[Show post options & limits]</i>, you can also choose a flag (if available on the board where you want to post), embed video links, choose not to bump, and spoiler images.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="how-do-i-reply-to-a-thread"></a><h2>How do I reply to a thread?</h2>

<p>On 8chan, threads are ordered from newest (top) to oldest (bottom), while replies are ordered from oldest (top) to newest (bottom).  
To reply to a thread, click on <i>[Reply]</i> on any thread on a board's index page or, from a board's catalog, click on any thread tile.</p>
<p><img src='static/how_to_reply.png'></p>

<p>Enter your reply in the <i>Comment</i> field. Once it is posted, your reply will be highlighted in the thread.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="how-do-i-reply-to-another-poster"></a><h2>How do I reply to another poster?</h2>
<p>Click on the number of the post you wish to reply to. A Quick Reply box will pop up automatically, prepopulated with the post number you are replying to. Enter your reply under the post number and click on <i>New Reply</i>. When someone replies to you, you will see <i>(You)</i> after the post number as a hint.</p>

<p><center><img src='static/replying_to_someone.png'></center></p>

<p>If you wish to delete a post you made or an image you posted, you can do so by clicking on the triangle on the left side of your post. Board owners may disable self-deletion, so it might not always be an option.</p>

<p>You can also report, hide, or filter other posts by clicking on the triangle.</p>

<p><center><img src='static/options.png'></center></p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>






<a id="are-there-any-global-rules-regarding-content"></a><h2>Are there any global rules regarding content?</h2>
<p>Only one:</p>

<p><b>Do not post, request, or link to any content that is illegal in the United States of America and do not create boards with the sole purpose of posting or spreading such content.</b></p>

<p>Other than that, you are free to make whatever rules you want on your board.</p>
<p>For more information on the United States obscenity laws and how they relate to 8chan boards, please <a target="_blank" href="https://8ch.net/obscenity.html">click here</a>. For more information about the Dost test, please <a target="_blank" href="https://8ch.net/dost.html">click here</a>.</p>
<p><b>In short, 8chan considers all nude images of children to be child pornography and they will be deleted and the posting address will be banned, if viable.</b> <a target="_blank" href="https://8ch.net/personhood.html">Just who is this 8chan person anyway?</a></p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>





<a id="how-do-i-format-my-text"></a><h2>How do I format my text?</h2>
<ul>
    <li>**spoiler** or [spoiler]spoiler[/spoiler] = <span class="spoiler">spoiler</span></li>
    <li>''italics'' = <em>italics</em></li>
    <li>'''bold''' = <strong>bold</strong></li>
    <li>__underline__ = <u>underline</u></li>
    <li>==RED TEXT== = <span class='heading'>RED TEXT</span> (must be on the same line)</li>
    <li>~~strikethrough~~ = <s>strikethrough</s></li>
    <li>[aa] tags for ASCII/JIS art (escape formatting)</li>
    <li>[code] tags, if enabled by board owner</li>
    <li>(((blue text))) to call <span class="detected">(((them)))</span> out</li>
    <li>< for <span class="rquote">faggotposting</span></li>
</ul>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="what-is-sage"></a><h2>What is <i>sage</i>?</h2>
<p>Posters may reply to threads without bumping them to the top of the index or the catalog by entering <i>sage</i> in the email field or checking the <i>Do not bump</i> box in the reply window (<i>Show post options & limits</i> > <i>Options</i> > <i>Do not bump</i>). </p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="what-is-a-tripcode"></a><h2>What is a tripcode?</h2>
<p>Most posts on 8chan are made anonymously, but this is not the only way to post. The <i>Name</i> field can be used to establish identity as follows: </p> 

<ol>
	<li>By simply entering a name in the <i>Name</i> field. This is not secure, since any other poster is able to use the same name;</li>
	<li>By entering one # character and a password in the <i>Name</i> field. Adding #example to the <i>Name</i> field would generate !KtW6XcghiY. This is reasonably secure - however, with increasing GPU speeds, these tripcodes may be cracked in a few days by a dedicated attacker;</li>
	<li>By entering two # characters and a password in the <i>Name</i> field. Adding ##example to the <i>Name</i> field would generate !!Dz.MSNRw9M. This is quite secure, but it relies on a secret salt on the server. This means the code will not work on websites other than 8chan;</li>
	<li>Board owners and volunteers may capcode by entering <i>## Board Owner</i> or <i>## Board Volunteer</i> in the <i>Name</i> field, depending on their respective positions.</li>
</ol>

<p>Please note: on 8chan, <b>board owners</b> may disable the <i>Name</i> field by clicking on <i>Settings</i> and checking the <i>Forced anonymous</i> box. Additionally, <b>users</b> are able to anonymize all posts on any 8chan board by clicking on <i>[Options]</i> (top-right corner) and checking the <i>Forced anonymity</i> box under <i>General</i>. This is because many users (including board owners) <b>do not like</b> people using tripcodes if there is no proper reason for it.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>


<a id="what-is-alacrity"></a><h2>What is Alacrity?</h2>
<p><a target="_blank" href="https://alacrity.sh/">Alacrity</a>, also known as the Alacrity daemon, is an <a target="_blank" href="https://github.com/Cipherwraith/alacrity">open-source</a> caching layer and write buffer workaround created by the 8chan Administrator to alleviate the resource-intensive hard-disk write bottleneck issues 8chan was suffering from in late 2015 and early 2016.</p>
<p>Alacrity is composed of four scalable servers (one master and three slaves). Each server contains multiple nodes and others may be added on demand (for instance, if there is a sudden influx of users). Whenever a node crashes, some threads on the website start displaying 404 despite not being deleted yet.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>

<a id="how-screencap-works"></a><h2>How does [Screencap] work?</h2>

<p>If you click on <i>[Screencap]</i> at the bottom of a thread, a screenshot of the whole thread is created for you to download. Alternatively, if you want to screencap only specific posts instead of the whole thread, you can select the posts you want to capture by clicking on the triangle on the left side of the posts and choosing <i>Select post</i>. Please watch the video demonstration below:</p>
<p><video src="/static/SCdemo8ch.webm" loop="" controls="" style="position: static; pointer-events: inherit; display: inline; max-width: 100%; max-height: 404px;">Your browser does not support HTML5 video.</video></p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="what-are-these-featured-boards-at-the-top-of-the-page"></a><h2>What are these featured boards at the top?</h2>
<p>There are three different lists that may be displayed at the top (from left to right):</p> 
<ol>
	<li>Trending Boards: <a href="https://8ch.net/dir" target="_blank">/dir/</a> (8chan's directory) plus eight boards periodically selected by an algorithm;</li>
	<li>Top Boards: these are the <a href="https://8ch.net/boards.html" target="_blank">top 25 boards</a> on 8chan according to the number of <a target="_blank" href="https://8ch.net/activeusers.html">active users</a>, that is, the number of /16 subnet ranges to post on any given board in the last 72 hours;</li>
	<li>Personal Favorite Boards: users may create a list of their favorite boards by clicking on the star icon <img src="static/star.png" width="15px"> next to a board's name.</li>
</ol>


<p><b>All lists are optional.</b> You may disable the <i>Trending Boards</i> and <i>Top Boards</i> lists by clicking on <i>[Options]</i> and unchecking either the <i>Show top boards</i> box or the <i>Show trending boards</i> box or both. By default, only the <i>Trending Boards</i> list will be displayed. </p> 
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="is-there-a-way-to-tell-if-im-banned"></a><h2>Is there a way to tell if I am banned?</h2>
<p>8chan has no ban check page at this moment, but we are working on one. To see if you are banned, you must try to post on a board - a ban page will be displayed if you are. Since boards are managed individually, being banned from one board does not necessarily mean you are banned from others. Only global volunteers are able to ban you from all boards and <b>only if you break the <a target="_blank" href="https://8ch.net/globalrule.html">8chan global rule</a></b>.</p>

<p><a target="_blank" href="https://8ch.net/bans.html">Here is a public list of all bans sitewide</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="where-is-the-mobile-app"></a><h2>Where is the mobile app?</h2>
<p><a href="https://github.com/everychan/everychan" target="_blank">Everychan</a> is 8chan's official mobile application and a fork of <a href="https://github.com/miku-nyan/Overchan-Android/releases" target="_blank">Overchan</a>. In addition, there is a third-party Android app for 8chan, which you can find at <a href="https://github.com/wingy/Exodus/releases" target="_blank">wingy/Exodus</a>. <a href="https://github.com/miku-nyan/Overchan-Android/releases" target="_blank">Overchan</a> also works on several imageboards, but currently does not display images on 8chan. 8chan does not provide support for either of the third-party applications. Please contact their respective developers if you have any issues with them.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="where-is-the-archive"></a><h2>Where is the archive?</h2>
<p>An official archive is currently in the works. Previously, 8chan used <a href="https://8archive.moe/" target="_blank">8archive</a>, an archival website supported by 8chan that is currently being worked on and could be up for public usage again in the future. <b>Board owners may opt in or out of the archival feature by clicking on <i>Settings</i> and checking / unchecking the <i>Archive my board on 8archive.moe</i> box.</b> Alternatively, users may create their own archives on an as-needed basis by using an archival website of their choice (<a href="https://archive.is" target="_blank">archive.is</a> is a popular service). <br>If you wish to create a third-party archival service, please only archive boards that have opted in to this feature.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>






<a id="what-is-the-best-way-to-contact-the-8chan-administration"></a><h2>What is the best way to contact the 8chan administration?</h2>
<p><b>Definitely not IRC.</b> You may reach the 8chan Administrator by sending an email to <a href="mailto:admin@8ch.net" target="_top">admin@8ch.net</a>.  
Alternatively, you can go to <a href="https://8ch.net/sudo/catalog.html" target="_blank">/sudo/</a> and create a thread to discuss technical issues, report bugs, request features, and <s>chimp out</s> speak with the 8chan Administrator.</p>
<p>For more information about who owns 8chan, please <a target="_blank" href="https://8ch.net/who.html">click here</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="do-you-have-a-privacy-policy"></a><h2>Do you have a privacy policy?</h2>
<p>Find it <a target="_blank" href="https://8ch.net/privacy.pdf">here</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="which-boards-are-global-and-owned-by-the-8chan-administration"></a><h2>Which boards are global and owned by the 8chan administration?</h2>
<p><a href="https://8ch.net/delete/catalog.html" target="_blank">/delete/</a> and <a href="https://8ch.net/newsplus/catalog.html" target="_blank">/newsplus/</a>, the 8chan counterpart of <a href="https://2ch.net/" target="_blank">2ch</a>'s <a href="https://daily.2ch.net/newsplus/" target="_blank">/newsplus/</a>. For more information about <a href="https://8ch.net/newsplus/catalog.html" target="_blank">/newsplus/</a>, please <a href="https://8ch.net/newsplus.html" target="_blank">click here</a>.
<br><a href="https://8ch.net/b/catalog.html" target="_blank">/b/</a>, <a href="https://8ch.net/n/catalog.html" target="_blank">/n/</a>, and <a href="https://8ch.net/operate/catalog.html" target="_blank">/operate/</a> used to be global boards, but this is not the case anymore. <a href="https://8ch.net/sudo/catalog.html" target="_blank">/sudo/</a> belongs to the 8chan Administrator, but is not a global board.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>


<a id="could-you-add-a-new-feature"></a><h2>Could you add a new feature?</h2>
<p>To discuss 8chan's technical issues and request new features (which may or may not be implemented), please go to <a target="_blank" href="https://8ch.net/sudo/catalog.html">/sudo/</a>.</p>
<p>Alternatively, <a target="_blank" href="https://github.com/ctrlcctrlv/infinity/issues">open a GitHub issue</a>. Better yet, write it yourself and open a <a target="_blank" href="https://github.com/ctrlcctrlv/infinity/pulls">pull request</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>


<a id="8chans-onion-service-is-giving-me-an-error-did-you-discontinue-tor-support"></a><h2>8chan's onion service is giving me an error. Did you discontinue Tor support?</h2>
<p>No. <b>If 8chan's <a target="_blank" href="http://oxwugzccvk3dk6tj.onion/">onion service</a> is not working, something is broken and needs to be fixed.</b> Please create a thread on <a target="_blank" href="https://8ch.net/sudo/catalog.html">/sudo/</a> if you find any issues with the onion service.</p>
<p>Please note: board owners may disable Tor posting in their respective boards if they want to.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="i-am-getting-infinite-captchas-when-I-try-to-post-with-my-vpn-did-you-globally-ban-it-or-are-you-not-allowing-vpns-to-post"></a><h2>I am getting infinite captchas when I try to post with my VPN. Did you globally ban it or are you not allowing VPNs to post?</h2>
<p><b>The use of VPNs is allowed on all boards.</b> Unlike Tor posting, <b>board owners cannot opt out of posting through VPNs</b>. However, board owners and volunteers might ban some VPNs unknowingly, since they cannot see full IPs, only IP hashes. If your VPN has been banned, a ban message will be displayed and you may appeal the ban.</p>

<p>If you receive infinite captchas without being able to post after switching to a VPN, restart your browser and try again. If it still does not work, explain your situation on <a target="_blank" href="https://8ch.net/sudo/catalog.html">/sudo/</a>.</p>  

<p>If you are getting infinite captchas without using a VPN, the error may be Cloudflare-related. Again, post about it on <a href="https://8ch.net/sudo/catalog.html" target="_blank">/sudo/</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="i-am-getting-x-error-when-trying-to-post-am-i-banned"></a><h2>I am getting X error when trying to post, am I banned?</h2>
<p>Likely not. If you are banned, you should see a ban page telling you that you are.</p>
<p>If you get an error, something went wrong. You can report the error you are getting on <a href="https://8ch.net/sudo/catalog.html" target="_blank">/sudo/</a> if you are able to post there. Otherwise, send an email to &lt;admin@8ch.net&gt;.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="can-i-donate-to-8chan"></a><h2>Can I donate to 8chan?</h2>
<p>8chan is currently <b>not</b> taking any donations. If you see any 8chan donation drives that are not mentioned here, they are a scam.  
If you want to financially support 8chan anyway, you can do so by <a target="_blank" href="https://softserve.8ch.net">advertising on the website</a>. Read the next question for more information.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="i-would-like-to-advertise-on-8ch.net-is-this-possible"></a><h2>I would like to advertise on 8ch.net. Is this possible?</h2>
<p>Yes, 8chan uses a softserve system that makes it easy and possible for anyone to place ads. Go to our <a target="_blank" href="https://softserve.8ch.net/">softserve page</a> and get the necessary vouchers to place your ads on 8chan. You can choose to have your ads displayed on a specific board for USD 5.00 a day or sitewide for USD 20.00 a day. Alternatively, please email <a href="mailto:ads@8ch.net">ads@8ch.net</a> if you have any questions or special requests or if you are interested in longer advertising campaigns with better rates.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="i-saw-an-ad-i-believe-violates-global-rules-how-can-i-report-it"></a><h2>I saw an ad that I believe violates global rules, how can I report it?</h2>
<p>You can report ads on the <a target="_blank" href="https://softserve.8ch.net/account/login/">softserve page</a> by sending an email to &lt;ads@8ch.net&gt;.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="i-created-a-board-how-do-i-manage-it"></a><h2>I created a board. How do I manage it?</h2>
<p>Click on the gear icon <img src="static/gear.png" width="25px"> in the top bar or the <a href="https://8ch.net/mod.php" target="_blank"><i>manage board</i></a> link on 8chan's <a href="https://8ch.net/index.html" target="_blank">front page</a> to reach the <a target="_blank" href="https://sys.8ch.net/mod.php?/">login or dashboard page</a>.<br><br>

There, you have access to the noticeboard (messages from the 8chan administration), your personal inbox, the report queue, the board-specific ban list, the ban appeals (if any) for the board owner and / or volunteers, a page where you can edit your profile, and the recent board-specific posts.
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="how-do-i-add-more-volunteers"></a><h2>How do I add more volunteers?</h2>
<p>If you are a board owner, go to your board settings and click on <i>Edit board volunteers</i>. Then, add a username and a password for the volunteer you wish to create.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>


<a id="how-do-i-post-as-a-volunteer-on-my-board"></a><h2>How do I post as a board owner or volunteer on my board?</h2>
<p>Make sure you are using the board owner or volunteer interface to view your board. The URL of your browser should be <b>https://8ch.net/mod.php?/<yourboard></b>.</p>
<p>If you are the owner of the board, enter <i>## Board Owner</i> in the <i>Name</i> field.</p> 
<p>If you are a volunteer on the board, enter <i>## Board Volunteer</i> in the <i>Name</i> field. Create your post and click on <i>New Reply</i>. It will appear with your capcode.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="help-my-board-has-been-deleted"></a><h2>Help! My board has been deleted!</h2>
<p>As of November 13th, 2014, board expiration no longer occurs automatically.</p>
<p>A board only gets deleted by the 8chan administration if the board is found to have been allowing and / or storing child pornography, per the <a target="_blank" href="https://8ch.net/globalrule.html">8chan global rule</a>.</p>  
<p>Alternatively, at the discretion of the 8chan administration, the board in question may be forced into becoming a textboard instead to remove all law-breaking images.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>


<a id="i-lost-ownership-of-my-board-what-happened"></a><h2>I lost ownership of my board. What happened?</h2>
<p>You may lose access to your board if you fail to log in for two weeks. Furthermore, you may lose access to your board if it is reassigned as a result of noncompliance with one of the conditions for board reassignment.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="help-the-owner-of-x-board-is-doing-something-i-dont-like-remove-him"></a><h2>Help! The owner of X board is doing something I don't like! Remove him!</h2>

<p><b>If the board owner is not doing anything illegal, the 8chan administration cannot and will not help you.</b> The 8chan administration does not dictate how board owners or volunteers should manage their boards.</p>

<p>However, a few conditions must be met:</p>

<ol>
	<li> The board owner must not allow illegal content to be posted on his or her board;</li> 
	<li> The board owner must not state in his or her board's rules that the 8chan global rule does not apply;</li>  
	<li> The board owner must not blanket-ban large swaths of IP ranges;</li>
	<li> The board owner must not make it impossible to post on his or her board, either by locking all or most threads and / or deleting all or most threads or posts;</li>  
	<li> The board owner must not implement CSS that makes it impossible to post;</li>
	<li> The board owner must not squat in a board for the purpose of redirecting users from 8chan to a different website. <b>Please note:</b> creating a bunker in case of primary-website malfunction / downtime is fine, provided all of the above are complied with.</li>
</ol>

<p>If the board owner fails to comply with <b>any</b> of the above, you may report the board to the 8chan Administrator on <a target="_blank" href="https://8ch.net/sudo/">/sudo/</a>. The board will then be eligible for reassignment, at the discretion of the 8chan administration.</p>

<p>For a list of boards that are available for claim, <a target="_blank" href="https://8ch.net/claim.html">click here</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="are-there-any-publicly-available-statistics"></a><h2>Are there any publicly-available statistics?</h2>
<p>Yes, take a look at <a target="_blank" href="https://stats.4ch.net/8chan/">https://stats.4ch.net/8chan/</a>.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="i-want-my-board-to-show-up-in-the-recommended-board-list"></a><h2>I want my board to show up in the Trending Boards bar!</h2>

<p>Qualifying boards are selected by an algorithm and change periodically. <a target="_blank" href="https://8ch.net/dir/">/dir/</a>, which is not a global board, is pinned because it acts as a board directory.</p>  

<p>If you <b>do not want your board to show up</b>, choose the option to have your board unlisted in the board owner settings.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>





<a id="i-got-an-email-at-8chanco-email-address"></a><h2>I got an email from an @8chan.co email address. Is that you?</h2>
<p>Probably not. Anyone can create an email account under the @8chan.co domain name at <a href="https://cock.li/auth/register" target="_blank">cock.li</a>. <a href="https://cock.li/auth/register" target="_blank">cock.li</a> is an unaffiliated third party that started to provide this service after an agreement with 8chan's creator and former Administrator Fredrick Brennan. The 8chan administration takes no responsibility for this service. That said, we have quite a few official 8chan email addresses. They are:</p>
<ul>
	<li><a href="mailto:admin@8ch.net">admin@8ch.net</a>;</li>
	<li><a href="mailto:dmca@8ch.net">dmca@8ch.net</a>;</li>
	<li><a href="mailto:ads@8ch.net">ads@8ch.net;</a></li>
	<li><a href="mailto:claim@8ch.net">claim@8ch.net</a>;</li>
	<li><a href="mailto:apply@8ch.net">apply@8ch.net</a>.</li>
</ul>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>




<a id="can-i-have-a-list-of-all-api-endpoints-for-getting-raw-data-from-8chan"></a><h2>Can I have a list of all API endpoints for getting raw data from 8chan?</h2>

<p>Taking <a href="https://8ch.net/b/catalog.html" target="_blank">/b/</a> as an example, they are as follows:</p>
<ul>
	<li>https://8ch.net/b/index.rss - an RSS-formatted index so that you can watch smaller boards and get updates when they get new posts using a feed reader like Thunderbird or Feedly;</li>
	<li>https://8ch.net/b/0.json - an index of all threads on page 0 of /b/;</li>
	<li>https://8ch.net/b/res/1.json - all replies of thread 1 on /b/;</li>
	<li>https://8ch.net/b/threads.json - a thread index of all 15 pages of /b/.</li>
</ul>

<p>There are also endpoints for getting information about 8chan's boards:</p>
<ul>
        <li>https://8ch.net/boards.json - boards on 8chan (warning, 1MB+);</li>
        <li>https://8ch.net/settings.php?board=b - board settings of /b/ (JSON format).</li>
</ul>
<p>Just read the data to get an idea of what is exposed and under what attribute names. It should be self-explanatory. Endpoints that are not listed here, like post.php, catalog.json or boards-top20.json are subject to change or removal at any time.</p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>



<a id="i-would-like-to-contribute-a-translation-in-my-language"></a><h2>I would like to contribute a translation in my language.</h2>
<p>Great! See <a target="_blank" href="https://8ch.net/translation.html">this page</a> for more information.</p>


<p><i>[This FAQ was updated on June 26, 2017. The previous version looked like <a target="_blank" href="https://archive.fo/1OE2C">this</a>.]</i></p>
<p align="right" style="font-size:0.7em"><a href="#top-faq">[Back to Index]</a></p>

</div>



EOT;

$body = Element("page.html", array("config" => $config, "body" => $body, "title" => "FAQ"));
file_write("faq.html", $body);
print $body;
