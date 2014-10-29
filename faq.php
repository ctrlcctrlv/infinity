<?php

include "inc/functions.php";

$body = <<<EOT
<div class="ban">
<h2>What is ∞chan?</h2>
<p>∞chan allows anyone who wants to to become the owner of their own board. They are given free reign to institute whatever rules they wish on their board, as long as they do not affect the global rules.</p>

<p>The idea of ∞chan is to minimize the trust needed in individual moderators to prevent "do it for free" moderators from accepting bribes (sexual or otherwise) to corrupt the whole site.</p>

<p>The largest board owners are promoted to Global Volunteers. This is devised by an algorithm, but volunteers are only promoted with my express approval.</p>

<p>All new global volunteers are sent a message congratulating them on becoming global volunteers and explaining the position. Global volunteers simply delete CP, excessive spam and other illegal content that comes on the server and ban the posting users and their IP ranges.</p>

<p>There is a large penalty for abusing their powers to ban users for other reasons. That penalty is that I will not only remove their global volunteer position, but also commandeer their board. I will then find another suitable owner for it among the board's users and give it to them.</p>

<p>Thus, the only people who can delete content from 8chan.co are those who have a stake in the site itself because they own the boards. Thus, they are not really doing it for free - they are simply protecting their boards by protecting the site as a whole. It is in their interest to keep the site free of illegal content so that their boards stay up. If they abuse their power, the board that they worked hard to create is stripped from them.</p>

<p>This means that there's only one person that needs to be trusted: me. If I could have found a way to remove myself from the trust model, I would have, but that is impossible given someone has to run the server.</p>


<h2>Are there any global rules regarding content?</h2>
<p>Only one:</p>
<ul>
<li>Do not post, request, or link to any content illegal in the United States of America. Do not create boards with the sole purpose of posting or spreading such content.</li>
</ul>
<p>Other than that, you are free to institute whatever rules you want on your board.</p>
<h2>How do I add more volunteers?</h2>
<p>You may do this in your board settings, click on "Edit board volunteers".
<h2>How do I manage my board?</h2>
<p>Go to <a href="/mod.php">the volunteer panel</a> and click on the board link for your board.</p>
<h2>How do I contact the admin?</h2>
<p>The admin can be reached at <tt>admin at 8chan dot co</tt>.</p>

<h2>Help! My board has been deleted!</h2>
<p>Were you inactive for longer than one week? Were there no posts on the board for 72 hours?</p>

<p>If either of those is true, the board was deleted automatically. You are free to recreate it. I cannot restore it, so don't bother emailing me about it.</p>

<h2>How do I post as a volunteer on my board?</h2>
<p>Make sure you are using the volunteer interface to view your board. The URL of your browser should be <a href="https://8chan.co/mod.php?/yourboard"><tt>https://8chan.co/mod.php?/yourboard</tt></a>.</p>

<p>If you are the owner of the board, put "## Board Owner" in the name field. If someone else is the owner and you are just assisting them, put "## Board Volunteer" in the name field. Write your post and click "Reply". It will appear with your capcode.</p>
<h2>Help! The owner of X board is doing something I don't like!</h2>
<p>If they aren't doing anything illegal, I can't help you. I don't dictate how board owners should manage their boards.</p>
<p>If they are doing something illegal, email me.</p>

<h2>Can you give me X board?</h2>
<p>If the owner of the board is inactive or the board is broken due to bad CSS, sure. Send me an email.</p>

<h2>Can you add some new feature?</h2>
<p>Open a <a href="https://github.com/ctrlcctrlv/8chan/issues">Github issue</a>. Better yet, write it yourself and open a pull request.

<h2>How do I format my text?</h2>
<ul>
<li>**spoiler** or [spoiler]spoiler[/spoiler] -&gt; spoiler</li>
<li>''italics'' -&gt; <em>italics</em></li>
<li>'''bold''' -&gt; <strong>bold</strong></li>
<li>__underline__ -&gt; <u>underline</u></li>
<li>==heading== -&gt; <span class='heading'>heading</span> (must be on own line)</li>
<li>~~strikethrough~~ -&gt; <s>strikethrough</s></li>
<li>[code] tags if enabled by board owner</li>
<li>[tex] tags if enabled by board owner</li>
</ul>

<h2>How are featured boards chosen?</h2>
<p>Top fifteen boards excluding /meta/, /b/ and /int/.</p>

<h2>Who owns /meta/, /b/, and /int/?</h2>
<p>No one, so they are <em>de facto</em> property of the administration.</p>

<h2>Where's the mobile app?</h2>
<p>There is no official mobile app, however there is an unofficial Android app at <a href="https://github.com/wingy/Exodus/releases">wingy/Exodus</a>.</p>

<p>I don't provide support for this app, ask the developer of it if you have a problem with it.</p>

<h2>Where's the archive?</h2>
<p>There isn't one yet and there will never be an official archive.</p>

<h2>How do I donate?</h2>
<p>Donations can be sent to 1NpQaXqmCBji6gfX8UgaQEmEstvVY7U32C (Bitcoin) or LUPgSCJt3iGeJXUETVhmnbQ89Riaq1yjZm (Litecoin). PayPal is also accepted @ fredrick.brennan1@gmail.com .</p>
<p>You may also donate monthly via Patreon at <a href="http://www.patreon.com/user?u=162165">http://www.patreon.com/user?u=162165</a>.

<h2>Are you really a cripple?</h2>
<p>Yes.</p>

</div>

EOT;

echo Element("page.html", array("config" => $config, "body" => $body, "title" => "FAQ"));
