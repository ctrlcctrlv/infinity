
This fork adds a new board option to the version of infinity as of Jan. 28th 2015:

The requirement to solve a captcha in order to create a thread, but not in order to post a reply.

Here's a quick rundown of where code edits have been made:

ALL CODE EDITS FOR NEW THREAD CAPTCHA ARE MARKED WITH THE COMMENT "New thread captcha"
ONE ADITIONAL EDIT TO SETTINGS.HTML MARKED WITH:
"Added explanation to clarify purpose of "Enable CAPTCHA" now that "Enable CAPTCHA for new thread creation only" also exists".

The $config setting for new thread captcha is $config[new_thread_capt]

templates/mod/settings.html
Option for new captcha thread creation has been added to the settings template.
"Enable CAPTCHA" also clarified with better description.

inc/8chan-mod-pages.php
Three lines of code added to 8chan-mod-pages.php

inc/config.php
Line added, makes new thread captcha default to off.

/templates/post_form.html
Several lines added. Makes the verification box show up in the new thread form
when $config[new_thread_capt] is enabled. 

/post.php
Added two new conditions to if statement.
