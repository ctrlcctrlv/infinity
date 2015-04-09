/*
 * options/user-css.js - allow user to enter custom css entries and (march 2015) replaces the old stylesheet system
 *
 * Copyright (c) 2014 Marcin Łabanowski <marcin@6irc.net>
 * Copyright (c) 2015 Fredrick Brennan <admin@8chan.co>
 *
 * Usage:
 *   $config['additional_javascript'][] = 'js/jquery.min.js';
 *   $config['additional_javascript'][] = 'js/options.js';
 *   $config['additional_javascript'][] = 'js/options/user-css.js';
 */

+function(){

var tab = Options.add_tab("user-css", "css3", _("Theme"));

var textarea = $("<textarea></textarea>").css({
  "height"     : "80%",
  "width"      : "100%",
  "font-size"  : "9pt",
  "font-family": "monospace",
}).appendTo(tab.content);
var submit = $("<input type='button' value='"+_("Save custom CSS")+"'>").css({
  "width": "100%",
}).click(function() {
  localStorage.user_css = textarea.val();
  apply_css();
}).appendTo(tab.content);

var main = function(){
  if (typeof styles === "undefined") return;

  var stylechooser = $("<select id='stylechooser'></select>").appendTo(tab.content);
  // Handle empty localStorage
  if (!localStorage.stylesheets_all_boards) localStorage.stylesheets_all_boards = "false";
  if (!localStorage.board_stylesheets) localStorage.board_stylesheets = '{}';

  var fix_choice = function(){
    var chosen = false;
    stylechooser.empty();
    $.each(styles, function(k, v) {
      var ps;
      k === "Custom" ? k2 = _("Board-specific CSS") : k2 = k;
      var option = $("<option value='"+k+"' "+(k === "Custom" ? "class='default'" : "")+">"+k2+"</option>").appendTo(stylechooser);
      if (localStorage.stylesheets_all_boards === "false") {
        var bs = JSON.parse(localStorage.board_stylesheets);
        if (bs[board_name]) ps = bs[board_name];
      }
      if ((k === localStorage.stylesheet && localStorage.stylesheets_all_boards === "true") || (localStorage.stylesheets_all_boards === "false" && (ps && k === ps))) { option.prop('selected', 'selected'); chosen = true }
    })
    if (!chosen) stylechooser.find('.default').prop('selected', 'selected');
  };

  fix_choice();

  var allboards = $("<label><input type='checkbox' id='css-all-boards'> "+_("Style should affect all boards, not just current board")+" (/"+board_name+"/)</label>").appendTo(tab.content).find('input');

  if (localStorage.stylesheets_all_boards === "true") allboards.prop('checked', 'checked');

  allboards.on('change', function(e) {
    if ($(this).is(':checked')) {
      localStorage.stylesheets_all_boards = "true";
    } else {
      localStorage.stylesheets_all_boards = "false";
    }
    fix_choice();
    apply_css();
  });

  stylechooser.on('change', function(e) {
    var ps = $(this).val();
    if (localStorage && localStorage.stylesheets_all_boards === "false") {
      var bs = JSON.parse(localStorage.board_stylesheets);
      if (!bs) bs = {};
      bs[board_name] = ps;
      localStorage.board_stylesheets = JSON.stringify(bs);
    } else { 
      localStorage.stylesheet = ps;
    }

    apply_css();
  });

  update_textarea();
}

var apply_css = function() {
  var to_apply;

  if (localStorage && localStorage.stylesheets_all_boards === "false") {
    if (localStorage && localStorage.board_stylesheets) {
      var bs = JSON.parse(localStorage.board_stylesheets);
      if (bs && bs[board_name]) {
        to_apply = styles[bs[board_name]];
      }
    } 
  } else if (localStorage && localStorage.stylesheet) {
    to_apply = styles[localStorage.stylesheet];
  }

  $('.user-css').remove();
  var ls = $('link[rel="stylesheet"]:not(#stylesheet)').last();

  ls.after($("<style></style>")
    .addClass("user-css")
    .text(localStorage.user_css)
  );
  
  if (to_apply) {
  $('.user-chosen-stylesheet,#stylesheet').remove();
  ls.after($("<link rel='stylesheet'/>")
    .attr("class", "user-chosen-stylesheet")
    .attr("id", "stylesheet")
    .attr("href", (configRoot !== '/' ? configRoot : '')+to_apply)
  );
  }
  return to_apply;
};

var update_textarea = function() {
  if (!localStorage.user_css) {
    textarea.text("/* "+_("Enter here your own CSS rules...")+" */\n" +
                  "/* "+_("If you want to make a redistributable style, be sure to\nhave a Yotsuba B theme selected.")+" */\n" +
                  "/* "+_("These will be applied on top of whatever theme you choose below.")+" */\n" +
                  "/* "+_("You can include CSS files from remote servers, for example:")+" */\n" +
                  '/* @import "http://example.com/style.css"; */');
  }
  textarea.text(localStorage.user_css);
  apply_css();
};

main();
}();
