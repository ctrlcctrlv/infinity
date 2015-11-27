infinity
========================================================

## This software is DEPRECATED and UNMAINTAINED. Come January 1, 2016, no security fixes will be merged in. You are encouraged to [use the rewrite](https://github.com/infinity-next/infinity-next) or [downgrade to vichan](https://github.com/vichan-devel/vichan).

About
------------
infinity is a fork of vichan, with the difference that infinity is geared towards allowing users to create their own boards. A running instance is at [8ch.net](https://8ch.net/) (new! a user of the software wrote to me that they created a Polish version: [8ch.pl](http://8ch.pl/))

Most things (other than installation) that apply to upstream vichan also apply to infinity. See their readme for a detailed FAQ: https://github.com/vichan-devel/vichan/blob/master/README.md

If you are not interested in letting your users make their own boards, install vichan instead of infinity.

**Much like Arch Linux, infinity should be considered ``rolling release''. Unlike upstream vichan, we have no install.php. Database schema and templates are changed often and it is on you to read the Git log before updating!**

Installation
------------
Basic requirements:
A computer running a Unix or Unix-like OS(infinity has been specifically tested with and is known to work under Ubuntu 14.x), Apache, MySQL, and PHP
* Make sure Apache has read/write access to the directory infinity resides in.
* `install.php` is not maintained. Don't use it.
* As of February 22, 2015, you need the [DirectIO module (dio.so)](http://php.net/manual/en/ref.dio.php). This is for compatibility with NFS. 

Step 1. Create infinity's database from the included install.sql file. Enter mysql and create an empty database named 'infinity'. Then cd into the infinity base directory and run:
```
mysql -uroot -p infinity < install.sql
echo '+ <a href="https://github.com/ctrlcctrlv/infinity">infinity</a> '`git rev-parse HEAD|head -c 10` > .installed
```

Step 2. /inc/secrets.php does not exist by default, but infinity needs it in order to function. To fix this, cd into /inc/ and run:
```
sudo cp secrets.example.php secrets.php
```

Now open secrets.php and edit the $config['db'] settings to point to the 'infinity' MySQL database you created in Step 1. 'user' and 'password' refer to your MySQL login credentials.  It should look something like this when you're finished:

```
	$config['db']['server'] = 'localhost';
	$config['db']['database'] = 'infinity';
	$config['db']['prefix'] = '';
	$config['db']['user'] = 'root';
	$config['db']['password'] = 'password';
	$config['timezone'] = 'UTC';
	$config['cache']['enabled'] = 'apc';
```

Step 3.(Optional) By default, infinity will ignore any changes you make to the template files until you log into mod.php, go to Rebuild, and select Flush Cache. You may find this inconvenient. To make infinity automatically accept your changes to the template files, set $config['twig_cache'].

Step 4. Infinity can function in a *very* barebones fashion after the first two steps, but you should probably install these additional packages if you want to seriously run it and/or contribute to it. ffmpeg may fail to install under certain versions of Ubuntu. If it does, remove it from this script and install it via an alternate method. Make sure to run the below as root:

```
apt-get install graphicsmagick gifsicle php5-fpm mysql-client php5-mysql php5-cli php-pear php5-apcu php5-dev; add-apt-repository ppa:jon-severinsson/ffmpeg; add-apt-repository ppa:nginx/stable; apt-get update; apt-get install nginx ffmpeg; pear install Net_DNS2; pecl install "channel://pecl.php.net/dio-0.0.7"
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
