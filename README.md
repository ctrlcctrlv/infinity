8chan - The infinitely expanding imageboard.
========================================================

About
------------
8chan is a fork of vichan, with the difference that 8chan is geared towards allowing users to create their own boards.

Most things (other than installation) that apply to upstream vichan also apply to 8chan. See their readme for a detailed FAQ: https://github.com/vichan-devel/vichan/blob/master/README.md

If you are not interested in letting your users make their own boards, install vichan instead of 8chan.

Because I cannot be bothered to maintain `install.php`, the install process is as such:

```
mysql -uroot 8chan < install.sql
echo '8chan' > .installed
```

Here's my install script as of 11/14/2014 for the 8chan servers which run Ubuntu 14.04:

```
apt-get install graphicsmagick gifsicle php5-fpm mysql-client php5-mysql php5-cli php-pear php5-apcu; add-apt-repository ppa:jon-severinsson/ffmpeg; add-apt-repository ppa:nginx/stable; apt-get update; apt-get install nginx ffmpeg; pear install Net_DNS2
```

Have fun!
