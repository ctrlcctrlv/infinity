infinity
========================================================

About
------------
infinity is a fork of vichan, with the difference that infinity is geared towards allowing users to create their own boards. A running instance is at [8ch.net](https://8ch.net/)

Most things (other than installation) that apply to upstream vichan also apply to infinity. See their readme for a detailed FAQ: https://github.com/vichan-devel/vichan/blob/master/README.md

If you are not interested in letting your users make their own boards, install vichan instead of infinity.

Because I cannot be bothered to maintain `install.php`, the install process is as such:

```
mysql -uroot infinity < install.sql
echo 'infinity' > .installed
```

Here's my install script as of 11/14/2014 for the 8chan servers which run Ubuntu 14.04:

```
apt-get install graphicsmagick gifsicle php5-fpm mysql-client php5-mysql php5-cli php-pear php5-apcu; add-apt-repository ppa:jon-severinsson/ffmpeg; add-apt-repository ppa:nginx/stable; apt-get update; apt-get install nginx ffmpeg; pear install Net_DNS2
```

Have fun!
