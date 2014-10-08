<?php

include "inc/functions.php";

$body = <<<EOT
<div class="ban">
<h2>Are there any global rules regarding content?</h2>
<p>Only one:</p>
<ul>
<li>Do not post, request, or link to any content illegal in the United States of America. Do not create boards with the sole purpose of posting or spreading such content.</li>
</ul>
<p>Other than that, you are free to stipulate whatever rules you want on your board.</p>
<h2>How do I add more volunteers?</h2>
<p>Give them your password. If you don't trust them enough for that, you probably shouldn't be making them a volunteer.</p>
<h2>How do I manage my board?</h2>
<p>Go to <a href="/mod.php">the volunteer panel</a> and click on the board link for your board.</p>
<h2>How do I contact the admin?</h2>
<p>The admin can be reached at <tt>admin at 8chan dot co</tt>.</p>

<h2>Help! My board has been deleted!</h2>
<p>Were you inactive for longer than two weeks? Were there no posts on the board for two weeks?</p>

<p>If either of those is true, the board was deleted automatically. You are free to recreate it. I cannot restore it, so don't bother emailing me about it.</p>

<h2>How do I post as a volunteer on my board?</h2>
<p>Make sure you are using the volunteer interface to view your board. The URL of your browser should be <a href="https://8chan.co/mod.php?/yourboard"><tt>https://8chan.co/mod.php?/yourboard</tt></a>. Then, put "## Board Volunteer" in the name field. Write your post and click "Reply". It will appear with your volunteer capcode.</p>

<h2>How do I donate?</h2>
<p>Donations can be sent to 1NpQaXqmCBji6gfX8UgaQEmEstvVY7U32C (Bitcoin) or LUPgSCJt3iGeJXUETVhmnbQ89Riaq1yjZm (Litecoin). PayPal is also accepted @ fredrick.brennan1@gmail.com .</p>
</div>

EOT;

echo Element("page.html", array("config" => $config, "body" => $body, "title" => "FAQ"));
