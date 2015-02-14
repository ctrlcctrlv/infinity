/*
 * options/user-css.js - allow user enter custom css entries
 *
 * Copyright (c) 2014 Marcin Łabanowski <marcin@6irc.net>
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/options.js';
 *   $config['additional_javascript'][] = 'js/options/user-css.js';
 */

+function(){

var tab = Options.add_tab("user-css", "css3", _("User CSS"));

var textarea = $("<textarea></textarea>").css({
  "height"     : "85%",
  "width"      : "100%",
  "font-size"  : "9pt",
  "font-family": "monospace",
}).appendTo(tab.content);
var submit = $("<input type='button' value='"+_("Update custom CSS")+"'>").css({
  "width": "100%",
}).click(function() {
  localStorage.user_css = textarea.val();
  apply_css();
}).appendTo(tab.content);

var apply_css = function() {
  $('.user-css').remove();
  $('link[rel="stylesheet"]')
    .last()
    .after($("<style></style>")
      .addClass("user-css")
      .text(localStorage.user_css)
    );
};

var update_textarea = function() {
  if (!localStorage.user_css) {
    textarea.text("/* "+_("You can include CSS files from remote servers, for example:")+" */\n" +
                  '/* @import "http://example.com/style.css"; */');
  }
  else {
    textarea.text(localStorage.user_css);
    apply_css();
  }
};

update_textarea();


}();
