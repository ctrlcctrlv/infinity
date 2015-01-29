infinity
========================================================

About
------------
infinity is a fork of vichan, with the difference that infinity is geared towards allowing users to create their own boards. A running instance is at [8ch.net](https://8ch.net/)

Most things (other than installation) that apply to upstream vichan also apply to infinity. See their readme for a detailed FAQ: https://github.com/vichan-devel/vichan/blob/master/README.md

If you are not interested in letting your users make their own boards, install vichan instead of infinity.

Installation
------------
Basic requirements:
A computer running a Unix or Unix-like OS(infinity has been specifically tested with and is known to work under Ubuntu 14.x), Apache, MySQL, and PHP
* Make sure Apache has read/write access to the directory infinity resides in.
* `install.php` is not maintained. Don't use it.

Step 1. Create infinity's database from the included install.sql file. Enter mysql and create an empty database named 'infinity'. Then, cd into the infinity base directory and run:
```
mysql -uroot -p infinity < install.sql
echo 'infinity' > .installed
```

Step 2. /inc/secrets.php does not exist by default, but infinity needs it in order to function. To remedy this, cd into /inc/ and run:
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

Step 3.(Optional) By default, infinity will ignore any changes you make to the template files until you log into mod.php, go to Rebuild, and select Flush Cache. You may find this inconvenient. To make infinity automatically accept your changes to template files, open /inc/template.php and add:

```
'auto_reload' => true
```

To the array of settings passed to Twig_Environment().

Step 4. Infinity can function in a very barebones fashion after the first two steps, but you should probably install these additional packages if you want to seriously run it and/or contribute to it. ffmpeg may fail to install under certain versions of Ubuntu. If it does, remove it from the script below and install it via an alternate method. Make sure to run the below as root:

```
apt-get install graphicsmagick gifsicle php5-fpm mysql-client php5-mysql php5-cli php-pear php5-apcu; add-apt-repository ppa:jon-severinsson/ffmpeg; add-apt-repository ppa:nginx/stable; apt-get update; apt-get install nginx ffmpeg; pear install Net_DNS2
```

Step 5. The current captcha provider listed inc/config.php is dead. You may want to work around this. 

Have fun!
