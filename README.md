OpenIB
========================================================

About
------------
OpenIB is a fork of Infinity which is a fork of vichan. OpenIB will be a security fork focused on user security. Infinity offered us board creation ontop of vichan. Now OpenIB will be refactoring Infinity and making the imageboard ecosystem safer for users. A running instance is at [8ch.net](https://8ch.net/) 

As of now, most things (other than installation) that apply to upstream vichan also apply to OpenIB. See their readme for a detailed FAQ: https://github.com/vichan-devel/vichan/blob/master/README.md

If you are not interested in letting your users make their own boards, install vichan instead of infinity.

**Much like Arch Linux, OpenIB should be considered ``rolling release''. Unlike upstream vichan, we have no install.php. Database schema and templates are changed often and it is on you to read the Git log before updating!**

Installation
------------
Basic requirements:
A computer running a Unix or Unix-like OS (OpenIB has been specifically tested with and is known to work under FreeBSD 10.3), Apache, MySQL, and PHP
* Make sure Apache has read/write access to the directory OpenIB resides in.
* `install.php` is not maintained. Don't use it.
* As of February 22, 2015, you need the [DirectIO module (dio.so)](http://php.net/manual/en/ref.dio.php). This is for compatibility with NFS. 

Step 1. Create OpenIB's database from the included install.sql file. Enter mysql and create an empty database named 'openib'. Then cd into the openib base directory and run:
```
mysql -uroot -p openib < install.sql
echo '+ <a href="https://github.com/OpenIB/OpenIB">OpenIB</a> '`git rev-parse HEAD|head -c 10` > .installed
```

Step 2. /inc/secrets.php does not exist by default, but OpenIB needs it in order to function. To fix this, cd into /inc/ and run:
```
sudo cp secrets.example.php secrets.php
```

Now open secrets.php and edit the $config['db'] settings to point to the 'openib' MySQL database you created in Step 1. 'user' and 'password' refer to your MySQL login credentials.  It should look something like this when you're finished:

```
	$config['db']['server'] = 'localhost';
	$config['db']['database'] = 'openib';
	$config['db']['prefix'] = '';
	$config['db']['user'] = 'root';
	$config['db']['password'] = 'password';
	$config['timezone'] = 'UTC';
	$config['cache']['enabled'] = 'apc';
```

Step 3.(Optional) By default, OpenIB will ignore any changes you make to the template files until you log into mod.php, go to Rebuild, and select Flush Cache. You may find this inconvenient. To make OpenIB automatically accept your changes to the template files, set $config['twig_cache'].

Step 4. OpenIB can function in a *very* barebones fashion after the first two steps, but you should probably install these additional packages if you want to seriously run it and/or contribute to it. Make sure to run the below as root:

```
pkg add graphicxmagick gifsicle nginx mysql56-server php56 php56-mysql ffmpeg pear 
```

Page Generation
------------
A lot of the static pages (claim.html, boards.html, index.html) need to be regenerated every so often. You can do this with a crontab.

```cron
*/10 * * * * cd /srv/http; /usr/bin/php /srv/http/boards.php
*/5 * * * * cd /srv/http; /usr/bin/php /srv/http/claim.php
*/20 * * * * cd /srv/http; /usr/bin/php -r 'include "inc/functions.php"; rebuildThemes("bans");'
*/5 * * * * cd /srv/http; /usr/bin/php /srv/http/index.php
```

Also, main.js is empty by default. Run tools/rebuild.php to create it every time you update one of the JS files.

Have fun!
