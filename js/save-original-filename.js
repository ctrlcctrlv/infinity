// ==UserScript==
// @name          Save As Filename Fix
// @namespace     http://www.grauw.nl/projects/pc/greasemonkey/
// @description   Fixes 'Save as original filename' function
// @include     https*//8ch.net/*
// @include     http*//8ch.net/*
// ==/UserScript==
$(function() {
  $("a[download]").each(function() { var url = $(this).attr('href'); $(this).attr('href', url.replace('media.', '').replace('media2.','')); });

  $("a[download]").each(function() {
          var url = $(this).attr('href');
          var filenamedownload = $(this).text();
          $(this).attr('href', url.replace('//8ch.net', '//media.8ch.net'));
  });

  $("a[download]").each(function() {
          var url = $(this).attr('href');
          url = url.replace('/file_store/', '/file_dl/');
          url = url.replace('/src/', '/file_dl/');
          url = url.replace(/\s/g, '_');
          $(this).attr('href', url);
  });

});
