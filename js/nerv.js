$(document).ready(function(){    

	/*icon*/
	$("head").append('<link rel="shortcut icon" href="https://8ch.net/static/favicon.ico">');

	/*ads*/
	var ads_top = ' <div class="top_ads"><div class="thumbnail" style="text-align:center"><div id="8ch-top-ads" class="ad-banner" style="text-align:center"></div></div><link rel="stylesheet" href="https://softserve.8ch.net/static/css/8ch.css"><script async src="https://softserve.8ch.net/static/js/board.js"></script></div>';
	$(ads_top).insertAfter(".subtitle");

	$("html").addClass("desktop-style");

	var sort_by_html = '<span>Sort by: </span> \
        <select id="sort_by" style="display: inline-block">\
                <option selected value="bump:desc">Bump order</option>\
                <option value="time:desc">Creation</option>\
                <option value="reply:desc">Reply count</option>\
                <option value="random:desc">Random</option>\
                <option value="speed:desc">Speed</option>\
                <option value="mass:desc">Mass</option>\
                <option value="energy:desc">Energy</option>\
        </select>';

	var image_size_html = '<span>Image size: </span> \
	    <select id="image_size" style="display: inline-block\">\
	            <option value="vsmall">Very small</option>\
	            <option selected value="small">Small</option>\
	            <option value="medium">Medium</option>\
	            <option value="large">Large</option>\
	    </select>';

	var show_all = '<span id="navsettings"> <a href="javascript:void(0)" style="text-decoration:none; cursor:pointer;" action="clicked" id="show-all-hide-thread">[Show all] </a> </span>';

	$(".GRIDS").before(sort_by_html + " " + image_size_html + " " + show_all);



	//catalog_js
	if (localStorage.catalog !== undefined) {
		var catalog = JSON.parse(localStorage.catalog);
	} else {
		var catalog = {};
		localStorage.catalog = JSON.stringify(catalog);
	}

	$("#sort_by").change(function(){
		var value = this.value;
	 	$('.GRIDS').mixItUp('sort', (value == "random" ? value : "bumplocked:asc " + value));	
		//$('.GRIDS').mixItUp('sort', (value == "random" ? value : value));
		catalog.sort_by = value;
		localStorage.catalog = JSON.stringify(catalog);
	});

	$("#image_size").change(function(){
		var value = this.value, old;
		$(".grid-li").removeClass("grid-size-vsmall");
		$(".grid-li").removeClass("grid-size-small");
		$(".grid-li").removeClass("grid-size-medium");
		$(".grid-li").removeClass("grid-size-large");
		$(".grid-li").addClass("grid-size-"+value);
		catalog.image_size = value;
		localStorage.catalog = JSON.stringify(catalog);
	});

	$('.GRIDS').mixItUp({
		animation: {
		enable: false
		}
	});

	if (catalog.sort_by !== undefined) {
		$('#sort_by').val(catalog.sort_by).trigger('change');
	}
	if (catalog.image_size !== undefined) {
		$('#image_size').val(catalog.image_size).trigger('change');
	}

	$('div.thread').on('click', function(e) {
		if ($(this).css('overflow-y') === 'hidden') {
			$(this).css('overflow-y', 'auto');
			$(this).css('width', '100%');
		} else {
			$(this).css('overflow-y', 'hidden');
			$(this).css('width', 'auto');
		}
	});



    /* SEARCH */
    //  'true' = enable shortcuts
    var useKeybinds = true;

    //  trigger the search 400ms after last keystroke
    var delay = 400;
    var timeoutHandle;

    //search and hide none matching threads
    function filter(search_term) {
      $('.thread').each(function () {
        var subject = $(this).children('.threadtitle').text().toLowerCase();
        var comment = $(this).clone().children().remove(':lt(2)').end().text().trim().toLowerCase();
        search_term = search_term.toLowerCase();

        if (subject.indexOf(search_term) == -1 && comment.indexOf(search_term) == -1) {
          $(this).parents('div[class="GRIDS"]>.mix').css('display', 'none');
        } else {
          $(this).parents('div[class="GRIDS"]>.mix').css('display', 'inline-block');
        }
      });
    }

    function searchToggle() {
      var button = $('#catalog_search_button');

      if (!button.data('expanded')) {
        button.data('expanded', '1');
        button.text('Close');
        $('.catalog_search').append(' <input id="search_field" style="border: inset 1px;">');
        $('#search_field').focus();
      } else {
        button.removeData('expanded');
        button.text('Search');
        $('.catalog_search').children().last().remove();
        $('div[class="GRIDS"]>.mix').each(function () { $(this).css('display', 'inline-block'); });
      }
    }


    $('#show-all-hide-thread').after('<span class="catalog_search">[<a href="javascript:void(0)" id="catalog_search_button" style="text-decoration:none; cursor:pointer;"></a>]</span>');
    $('#catalog_search_button').text('Search');

    $('#catalog_search_button').on('click', searchToggle);
    $('.catalog_search').on('keyup', 'input#search_field', function (e) {
      window.clearTimeout(timeoutHandle);
      timeoutHandle = window.setTimeout(filter, 400, e.target.value);
    });

    if (useKeybinds) {
      //  's'
      $('body').on('keydown', function (e) {
        if (e.which === 83 && e.target.tagName === 'BODY' && !(e.ctrlKey || e.altKey || e.shiftKey)) {
          e.preventDefault();
          if ($('#search_field').length !== 0) {
            $('#search_field').focus();
          } else {
            searchToggle();
          }
        }
      });
      //  'esc'
      $('.catalog_search').on('keydown', 'input#search_field', function (e) {
        if (e.which === 27 && !(e.ctrlKey || e.altKey || e.shiftKey)) {
          window.clearTimeout(timeoutHandle);
          searchToggle();
        }
      });
    }

    /*END SEARCH*/

    /*UPDATE - catalog-updater_js*/
    var countdown_interval_catalog;
    var catalog_interval_mindelay = 60000;
    var catalog_interval_delay = catalog_interval_mindelay;
    var catalog_current_time = catalog_interval_delay;

    var decrement_timer_catalog = function() {
            catalog_current_time = catalog_current_time - 1000;
            $('#update_catalog_secs').text(catalog_current_time/1000);

            if (catalog_current_time <= 0) {
                    updateCatalogContent()
                    catalog_current_time = catalog_interval_delay + 1000;
            }
    }

    var catalog_auto_update = function(delay) {
            clearInterval(countdown_interval_catalog);

            catalog_current_time = delay;
            countdown_interval_catalog = setInterval(decrement_timer_catalog, 1000);
            $('#update_catalog_secs').text(catalog_current_time/1000);
    }

    var catalog_stop_auto_update = function() {
            clearInterval(countdown_interval_catalog);
    }

    // Add an update catalog link
    $('span.catalog_search').after("&nbsp;<span id='updater_catalog_panel'><a href='#' style='text-decoration:none; cursor:pointer;' id='update_catalog'>[Update]</a><label id='auto_update_catalog_status'><input type='checkbox' id='auto_update_catalog_cb'></label> Auto (<span id='update_catalog_secs'></span>)</span>");

    // Set the updater checkbox according to user setting
    if (localStorage.auto_catalog_update === 'true') {
            $('#auto_update_catalog_cb').prop('checked', true);
            catalog_auto_update(catalog_interval_mindelay);
    }

    $('#auto_update_catalog_status>input').on('click', function() {
            if ($('#auto_update_catalog_status>input').is(':checked')) {
                    localStorage.auto_catalog_update = 'true';
                    catalog_auto_update(catalog_interval_mindelay);
            } else {
                    localStorage.auto_catalog_update = 'false';
                    catalog_stop_auto_update();
                    $('#update_catalog_secs').text("");
            }
    });



    $('#update_catalog').on('click', function() {
            updateCatalogContent();

            if($("#auto_update_catalog_cb").is(':checked')){
                    catalog_auto_update(catalog_interval_mindelay);
            }

    });

    function updateCatalogContent(){
                var body = $(this).parents('.GRIDS');
                var url = window.location.href;
                $('#update_catalog_secs').text("Updating...");
                $.ajax({
                        url: url,
                        context: document.body,
                        success: function(data) {
                                //var content = $(data).find('.GRIDS').html();
				var content = $($.parseHTML(data)).filter(".GRIDS").html();
                                //Update catalog content
                                $(".GRIDS").html(content);
                                //Sort catalog by Bump Order, Creation Date, Reply Count, Random and sort by image sizes
                                var v_sort_by = $("#sort_by").val();
                                var v_images_size = $("#image_size").val();
				$('.GRIDS').mixItUp('sort', (v_sort_by == "random" ? v_sort_by :  "bumplocked:asc " + v_sort_by));
                                //$('.GRIDS').mixItUp('sort', (v_sort_by == "random" ? v_sort_by : v_sort_by))
                                //Change image size
                                $(".grid-li").removeClass("grid-size-vsmall");
                                $(".grid-li").removeClass("grid-size-small");
                                $(".grid-li").removeClass("grid-size-medium");
                                $(".grid-li").removeClass("grid-size-large");
                                $(".grid-li").addClass("grid-size-"+v_images_size);

                                //initImageHover();
                                $('#update_catalog_secs').text("");
				$(document).trigger('filterThreads');
				globalReportButton();
                        }
                });
    }
    /*END UPDATER*/

    /*Top Bar*/
    $.ajax({
      url: "//8ch.net/js/topmenu.json",
      cache: false,
      type: 'GET',
      success: function( res ) {
      		$(".subtitle").before(res.boardlist);

    		/*Modify topbar boards link*/
    		$('.sub[data-description="1"] a').each(function() {
       			var url = $(this).attr('href');
       			$(this).attr('href', "//8ch.net" + url);
    		});


		var links_top = '';
		$('.sub[data-description="0"] a').each(function() {
		        var url = $(this).attr('href');
		        var content = $(this).html();
		        if(url!="/mod.php"){
		                links_top = links_top + "<a href='" + url + "'>" + content + "</a> / ";

		        }

		});

		links_top = links_top.substring(0, links_top.length - 2);
		$('.sub[data-description="0"]').html("");
		$('.sub[data-description="0"]').html(links_top);

	/*Customize Option Menu*/
        optionsMenu();

	/* THEMES */
	optionsThemes();

      },
      error: function( res ) {
        console.log('cant load top bar');
      }

    });


    /*Image Hover*/

    var image_hover_overboard = "&nbsp;&nbsp;[<label class='image-hover' id='catalogImageHover'><input type='checkbox' /> Image hover </label>]";
    $('#image_size').after(image_hover_overboard);

    $('.image-hover').on('change', function(){
        var setting = $(this).attr('id');

        localStorage[setting] = $(this).children('input').is(':checked');
    });

    if (!localStorage.imageHover || !localStorage.catalogImageHover || !localStorage.imageHoverFollowCursor) {
        localStorage.imageHover = 'false';
        localStorage.catalogImageHover = 'false';
        localStorage.imageHoverFollowCursor = 'false';
    }


    if (getSetting('catalogImageHover')) $('#catalogImageHover>input').prop('checked', 'checked');

    function getFileExtension(filename) { //Pashe, WTFPL
        if (filename.match(/\.([a-z0-9]+)(&loop.*)?$/i) !== null) {
            return filename.match(/\.([a-z0-9]+)(&loop.*)?$/i)[1];
        } else if (filename.match(/https?:\/\/(www\.)?youtube.com/)) {
            return 'Youtube';
        } else {
            return "unknown: " + filename;
        }
    }

    function isImage(fileExtension) { //Pashe, WTFPL
        return ($.inArray(fileExtension, ["jpg", "jpeg", "gif", "png"]) !== -1);
    }

    function isVideo(fileExtension) { //Pashe, WTFPL
        return ($.inArray(fileExtension, ["webm", "mp4"]) !== -1);
    }

    function getSetting(key) {
        return (localStorage[key] == 'true');
    }

    function initImageHover() { //Pashe, influenced by tux, et al, WTFPL
        // if (!getSetting("catalogImageHover")) {return;}
        
        var selectors = [];
        
        if (getSetting("imageHover")) {selectors.push("img.post-image", "canvas.post-image");}
        // if (getSetting("catalogImageHover")) {
            selectors.push(".thread-image");
            $("div.thread").css("position", "inherit");
        // }

        

        function bindEvents(el) {
            $(el).find(selectors.join(", ")).each(function () {
                if ($(this).parent().data("expanded")) {return;}
                
                var $this = $(this);
                
                $this.on("mousemove", imageHoverStart);
                $this.on("mouseout",  imageHoverEnd);
                $this.on("click",     imageHoverEnd);
            });
        }

        bindEvents(document.body);
        $(document).on('new_post', function(e, post) {
            bindEvents(post);
        });
    }

    function imageHoverStart(e) { //Pashe, anonish, WTFPL
        if (!getSetting("catalogImageHover") || !$('#catalogImageHover>input').is(':checked')) {return;}
        var hoverImage = $("#chx_hoverImage");
        
        if (hoverImage.length) {
            if (getSetting("imageHoverFollowCursor")) {
                var scrollTop = $(window).scrollTop();
                var imgY = e.pageY;
                var imgTop = imgY;
                var windowWidth = $(window).width();
                var imgWidth = hoverImage.width() + e.pageX;
                
                if (imgY < scrollTop + 15) {
                    imgTop = scrollTop;
                } else if (imgY > scrollTop + $(window).height() - hoverImage.height() - 15) {
                    imgTop = scrollTop + $(window).height() - hoverImage.height() - 15;
                }
                
                if (imgWidth > windowWidth) {
                    hoverImage.css({
                        'left': (e.pageX + (windowWidth - imgWidth)),
                        'top' : imgTop,
                    });
                } else {
                    hoverImage.css({
                        'left': e.pageX,
                        'top' : imgTop,
                    });
                }
                
                hoverImage.appendTo($("body"));
            }
            
            return;
        }
        
        var $this = $(this);
        
        var fullUrl;
        var static_img_src_hover = false;

        fullUrl = $this.attr("data-fullimage");
        if (!isImage(getFileExtension(fullUrl))) {fullUrl = $this.attr("src");}


        if(!static_img_src_hover){
            if (isVideo(getFileExtension(fullUrl))) {return;}
        
            hoverImage = $('<img id="chx_hoverImage" src="'+fullUrl+'" />');

            hoverImage.css({
                "position"      : "fixed",
                "top"           : 0,
                "right"         : 0,
                "z-index"       : 101,
                "pointer-events": "none",
                "max-width"     : "100%",
                "max-height"    : "100%",
            });

            hoverImage.appendTo($("body"));

        }
    }

    function imageHoverEnd() { //Pashe, WTFPL
        $("#chx_hoverImage").remove();
    }


    initImageHover();

    function globalReportButton(){
    	/*[G] button*/
    	$('.global-report').each(function() {
       		var id = $(this).parent().parent().parent().attr('data-id');
        	var board = $(this).parent().parent().parent().attr('data-board');
        	var link_href = "https://8ch.net/report.php?board="+board+"&post=delete_"+id+"&global";
        	var onclick = "window.open(this.href,'globalreportWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width=600,height=575');return false;";
        	$(this).find('.global-report-thread').attr('href', link_href);
        	$(this).find('.global-report-thread').attr('onclick', onclick);
    	});
    }
    globalReportButton();

    function timestamp() {
       return Math.floor((new Date()).getTime() / 1000);
    }

    function optionsMenu(){
        var options_button, options_handler, options_background, options_div, options_close, options_tablist, options_tabs, options_current_tab;

        var Options = {};
        window.Options = Options;

        var first_tab = function() {
          for (var i in options_tabs) {
            return i;
          }
          return false;
        };

        Options.show = function() {

          if (!options_current_tab) {
            Options.select_tab(first_tab(), true);
          }
          options_handler.fadeIn();
        };
        Options.hide = function() {
          options_handler.fadeOut();
        };

        options_tabs = {};

        Options.add_tab = function(id, icon, name, content) {
          var tab = {};

          if (typeof content == "string") {
            content = $("<div>"+content+"</div>");
          }

          tab.id = id;
          tab.name = name;
          tab.icon = $("<div class='options_tab_icon'><i class='fa fa-"+icon+"'></i><div>"+name+"</div></div>");
          tab.content = $("<div class='options_tab'></div>").css("display", "none");

          tab.content.appendTo(options_div);

          tab.icon.on("click", function() {
            Options.select_tab(id);
          }).appendTo(options_tablist);

          $("<h2>"+name+"</h2>").appendTo(tab.content);

          if (content) {
            content.appendTo(tab.content);
          }
          
          options_tabs[id] = tab;

          return tab;
        };

        Options.get_tab = function(id) {
          return options_tabs[id];
        };

        Options.extend_tab = function(id, content) {
          if (typeof content == "string") {
            content = $("<div>"+content+"</div>");
          }

          content.appendTo(options_tabs[id].content);

          return options_tabs[id];
        };

        Options.select_tab = function(id, quick) {
          if (options_current_tab) {
            if (options_current_tab.id == id) {
              return false;
            }
            options_current_tab.content.fadeOut();
            options_current_tab.icon.removeClass("active");
          }
          var tab = options_tabs[id];
          options_current_tab = tab;
          options_current_tab.icon.addClass("active");
          tab.content[quick? "show" : "fadeIn"]();

          return tab;
        };

        options_handler = $("<div id='options_handler'></div>").css("display", "none");
        options_background = $("<div id='options_background'></div>").on("click", Options.hide).appendTo(options_handler);
        options_div = $("<div id='options_div'></div>").appendTo(options_handler);
        options_close = $("<a id='options_close' href='javascript:void(0)'><i class='fa fa-times'></i></div>")
          .on("click", Options.hide).appendTo(options_div);
        options_tablist = $("<div id='options_tablist'></div>").appendTo(options_div);


        options_button = $("<a href='javascript:void(0)' id='options_temp' title='Options'>[Options]</a>");

        if ($(".boardlist.compact-boardlist").length) {
            options_button.addClass("cb-item cb-fa").html("<i class='fa fa-gear'></i>");
        }

        if ($(".boardlist:first").length) {
            options_button.css('float', 'right').appendTo($(".boardlist:first"));
        }
        else {
            var optsdiv = $('<div style="text-align: right"></div>');
            options_button.appendTo(optsdiv);
            optsdiv.prependTo($(document.body));
        }

        options_button.on("click", Options.show);

        options_handler.appendTo($(document.body));

        function initList(list, boardId, threadId) {
            if (typeof list.postFilter[boardId] == 'undefined') {
                list.postFilter[boardId] = {};
                list.nextPurge[boardId] = {};
            }
            if (typeof list.postFilter[boardId][threadId] == 'undefined') {
                list.postFilter[boardId][threadId] = [];
            }
            list.nextPurge[boardId][threadId] = {timestamp: timestamp(), interval: 86400};  // 86400 seconds == 1 day
        }


        function nameSpanToString(el) {
            var s = ''; 

            $.each($(el).contents(), function(k,v) {
                if (v.nodeName === 'IMG')
                    s=s+$(v).attr('alt')
                
                if (v.nodeName === '#text')
                    s=s+v.nodeValue
            });
            return s.trim();
        }

        var blacklist = {
            add: {
                post: function (boardId, threadId, postId, hideReplies) {
                    var list = getList();
                    var filter = list.postFilter;

                    initList(list, boardId, threadId);

                    for (var i in filter[boardId][threadId]) {
                        if (filter[boardId][threadId][i].post == postId) return;
                    }
                    filter[boardId][threadId].push({
                        post: postId,
                        hideReplies: hideReplies
                    });
                    setList(list);
                },
                uid: function (boardId, threadId, uniqueId, hideReplies) {
                    var list = getList();
                    var filter = list.postFilter;

                    initList(list, boardId, threadId);

                    for (var i in filter[boardId][threadId]) {
                        if (filter[boardId][threadId][i].uid == uniqueId) return;
                    }
                    filter[boardId][threadId].push({
                        uid: uniqueId,
                        hideReplies: hideReplies
                    });
                    setList(list);
                }
            },
            remove: {
                post: function (boardId, threadId, postId) {
                    var list = getList();
                    var filter = list.postFilter;

                    // thread already pruned
                    if (typeof filter[boardId] == 'undefined' || typeof filter[boardId][threadId] == 'undefined')
                        return;

                    for (var i=0; i<filter[boardId][threadId].length; i++) {
                        if (filter[boardId][threadId][i].post == postId) {
                            filter[boardId][threadId].splice(i, 1);
                            break;
                        }
                    }

                    if ($.isEmptyObject(filter[boardId][threadId])) {
                        delete filter[boardId][threadId];
                        delete list.nextPurge[boardId][threadId];

                        if ($.isEmptyObject(filter[boardId])) {
                            delete filter[boardId];
                            delete list.nextPurge[boardId];
                        }
                    }
                    setList(list);
                },
                uid: function (boardId, threadId, uniqueId) {
                    var list = getList();
                    var filter = list.postFilter;

                    // thread already pruned
                    if (typeof filter[boardId] == 'undefined' || typeof filter[boardId][threadId] == 'undefined')
                        return;

                    for (var i=0; i<filter[boardId][threadId].length; i++) {
                        if (filter[boardId][threadId][i].uid == uniqueId) {
                            filter[boardId][threadId].splice(i, 1);
                            break;
                        }
                    }

                    if ($.isEmptyObject(filter[boardId][threadId])) {
                        delete filter[boardId][threadId];
                        delete list.nextPurge[boardId][threadId];

                        if ($.isEmptyObject(filter[boardId])) {
                            delete filter[boardId];
                            delete list.nextPurge[boardId];
                        }
                    }
                    setList(list);
                }
            }
        };


        init();



        /************************************ Post menu *************************************/
        var List = function (menuId, text) {
          this.id = menuId;
          this.text = text;
          this.items = [];

          this.add_item = function (itemId, text, title) {
            this.items.push(new Item(itemId, text, title));
          };
          this.list_items = function () {
            var array = [];
            var i, length, obj, $ele;

            if ($.isEmptyObject(this.items))
              return;

            length = this.items.length;
            for (i = 0; i < length; i++) {
              obj = this.items[i];

              $ele = $('<li>', {id: obj.id}).text(obj.text);
              if ('title' in obj) $ele.attr('title', obj.title);

              if (obj instanceof Item) {
                $ele.addClass('post-item');
              } else {
                $ele.addClass('post-submenu');

                $ele.prepend(obj.list_items());
                $ele.append($('<span>', {class: 'post-menu-arrow'}).text('Â»'));
              }

              array.push($ele);
            }

            return $('<ul>').append(array);
          };
          this.add_submenu = function (menuId, text) {
            var ele = new List(menuId, text);
            this.items.push(ele);
            return ele;
          };
          this.get_submenu = function (menuId) {
            for (var i = 0; i < this.items.length; i++) {
              if ((this.items[i] instanceof Item) || this.items[i].id != menuId) continue;
              return this.items[i];
            }
          };
        };

        var Item = function (itemId, text, title) {
          this.id = itemId;
          this.text = text;

          // optional
          if (typeof title != 'undefined') this.title = title;
        };

        function buildMenu(e) {
          var pos = $(e.target).offset();
          var i, length;

          var $menu = $('<div class="post-menu"></div>').append(mainMenu.list_items());

          //  execute registered click handlers
          length = onclick_callbacks.length;
          for (i = 0; i < length; i++) {
            onclick_callbacks[i](e, $menu);
          }

          //  set menu position and append to page
           $menu.css({top: pos.top, left: pos.left + 20});
           $('body').append($menu);
        }

        function addButton(post) {
          var $ele = $(post);
          $ele.find('input.delete').after(
            $('<a>', {href: '#', class: 'post-btn', title: 'Post menu'}).text('â–¶')
          );
        }


        /* * * * * * * * * *
            Public methods
         * * * * * * * * * */
        var Menu = {};
        var mainMenu = new List();
        var onclick_callbacks = [];

        Menu.onclick = function (fnc) {
          onclick_callbacks.push(fnc);
        };

        Menu.add_item = function (itemId, text, title) {
          mainMenu.add_item(itemId, text, title);
        };

        Menu.add_submenu = function (menuId, text) {
          return mainMenu.add_submenu(menuId, text);
        };

        Menu.get_submenu = function (id) {
          return mainMenu.get_submenu(id);
        };

        window.Menu = Menu;

        /* * * * * * * *
            Initialize
         * * * * * * * */

        /* Styling */
        var $ele, cssStyle, cssString;

        $ele = $('<div>').addClass('post reply').hide().appendTo('body');
        cssStyle = $ele.css(['border-top-color']);
        cssStyle.hoverBg = $('body').css('background-color');
        $ele.remove();

        cssString =
          '\n/*** Generated by post-menu ***/\n' +
          '.post-menu {position: absolute; font-size: 12px; line-height: 1.3em;}\n' +
          '.post-menu ul {\n' +
          '    background-color: '+ cssStyle['border-top-color'] +'; border: 1px solid #666;\n' +
          '    list-style: none; padding: 0; margin: 0; white-space: nowrap;\n}\n' +
          '.post-menu .post-submenu{white-space: normal; width: 90px;}' +
          '.post-menu .post-submenu>ul{white-space: nowrap; width: auto;}' +
          '.post-menu li {cursor: pointer; position: relative; padding: 4px 4px; vertical-align: middle;}\n' +
          '.post-menu li:hover {background-color: '+ cssStyle.hoverBg +';}\n' +
          '.post-menu ul ul {display: none; position: absolute;}\n' +
          '.post-menu li:hover>ul {display: block; left: 100%; margin-top: -3px;}\n' +
          '.post-menu-arrow {float: right; margin-left: 10px;}\n' +
          '.post-menu.hidden, .post-menu .hidden {display: none;}\n' +
          '.post-btn {transition: transform 0.1s; width: 15px; text-align: center; font-size: 10pt; opacity: 0.8; text-decoration: none; margin: -6px 0px 0px -5px !important; display: inline-block;}\n' +
          '.post-btn:hover {opacity: 1;}\n' +
          '.post-btn-open {transform: rotate(90deg);}\n';

        if (!$('style.generated-css').length) $('<style class="generated-css">').appendTo('head');
        $('style.generated-css').html($('style.generated-css').html() + cssString);

        /*  Add buttons
         */
        $('.reply:not(.hidden), .thread>.op').each(function () {
          addButton(this);
         });

         /*  event handlers
          */
        $('form[name=postcontrols]').on('click', '.post-btn', function (e) {
          e.preventDefault();
          var post = e.target.parentElement.parentElement;
          $('.post-menu').remove();

          if ($(e.target).hasClass('post-btn-open')) {
            $('.post-btn-open').removeClass('post-btn-open');
          } else {
            //  close previous button
            $('.post-btn-open').removeClass('post-btn-open');
            $(post).find('.post-btn').addClass('post-btn-open');

            buildMenu(e);
          }
        });

        $(document).on('click', function (e){
          if ($(e.target).hasClass('post-btn') || $(e.target).hasClass('post-submenu'))
            return;

          $('.post-menu').remove();
          $('.post-btn-open').removeClass('post-btn-open');
        });

        // on new posts
        $(document).on('new_post', function (e, post) {
          addButton(post);
        });

        $(document).trigger('menu_ready');

    }// end optionsMenu



    // stores blacklist into storage and reruns the filter
    function setList(blacklist) {
        localStorage.postFilter = JSON.stringify(blacklist);
        $(document).trigger('filter_page');
    }


    function addFilter(type, value, useRegex) {
        var list = getList();
        var filter = list.generalFilter;
        var obj = {
            type: type,
            value: value,
            regex: useRegex
        };

        for (var i=0; i<filter.length; i++) {
            if (filter[i].type == type && filter[i].value == value && filter[i].regex == useRegex)
                return;
        }

        filter.push(obj);
        setList(list);
        drawFilterList();
    }

    function removeFilter(type, value, useRegex) {
        var list = getList();
        var filter = list.generalFilter;

        for (var i=0; i<filter.length; i++) {
            if (filter[i].type == type && filter[i].value == value && filter[i].regex == useRegex) {
                filter.splice(i, 1);
                break;
            }
        }

        setList(list);
        drawFilterList();
    }


    /* 
     *  clear out pruned threads
     */
    function purge() {
        var list = getList();
        var board, thread, boardId, threadId;
        var deferred;
        var requestArray = [];

        var successHandler = function (boardId, threadId) {
            return function () {
                // thread still alive, keep it in the list and increase the time between checks.
                var list = getList();
                var thread = list.nextPurge[boardId][threadId];

                thread.timestamp = timestamp();
                thread.interval = Math.floor(thread.interval * 1.5);
                setList(list);
            };
        };
        var errorHandler = function (boardId, threadId) {
            return function (xhr) {
                if (xhr.status == 404) {
                    var list = getList();

                    delete list.nextPurge[boardId][threadId];
                    delete list.postFilter[boardId][threadId];
                    if ($.isEmptyObject(list.nextPurge[boardId])) delete list.nextPurge[boardId];
                    if ($.isEmptyObject(list.postFilter[boardId])) delete list.postFilter[boardId];
                    setList(list);
                }
            };
        };


        if ((timestamp() - list.lastPurge) < 86400)  // less than 1 day
            return;
        
        for (boardId in list.nextPurge) {
            board = list.nextPurge[boardId];
            for (threadId in board) {
                thread = board[threadId];
                if (timestamp() > (thread.timestamp + thread.interval)) {
                    // check if thread is pruned
                    deferred = $.ajax({
                        cache: false,
                        url: '/'+ boardId +'/res/'+ threadId +'.json',
                        success: successHandler(boardId, threadId),
                        error: errorHandler(boardId, threadId)
                    });
                    requestArray.push(deferred);
                }
            }
        }

        // when all requests complete
        $.when.apply($, requestArray).always(function () {
            var list = getList();
            list.lastPurge = timestamp();
            setList(list);
        });
    }

    /*  (re)runs the filter on the entire page
     */
    function filterPage(pageData) {
        var list = getList();
        var postFilter = list.postFilter[pageData.boardId];
        var $collection = $('.mix');
        if ($.isEmptyObject(postFilter)){
            return;
        }
        // for each thread that has filtering rules
        // check if filter contains thread OP and remove the thread from catalog
        $.each(postFilter, function (key, thread) {
            var threadId = key;
            $.each(thread, function () {
                if (this.post == threadId) {
                    $collection.filter('[data-id='+ threadId +']').remove();
                }
            });
        });

    }


    function filterOverboard(opData) {
        var list = getList();
        var subject, comment;
        var i, length, array, rule, pattern;  // temp variables
        var board_name = opData.data('board');
        var op_id = opData.data('id');

        var hasSub = (opData.find(".threadtitle").length > 0);
        if (hasSub)
            subject = opData.find(".threadtitle").text();

        array = opData.find('.body-line').contents().filter(function () {if ($(this).text() !== '') return true;}).toArray();
        array = $.map(array, function (ele) {
            return $(ele).text().trim();
        });
        comment = array.join(' ');

        for (i = 0, length = list.generalFilter.length; i < length; i++) {
            rule = list.generalFilter[i];
            if (rule.regex) {
                pattern = new RegExp(rule.value);
                switch (rule.type) {
                    case 'sub':
                        if (hasSub && pattern.test(subject)) {
                            opData.remove();
                            // $post.data('hiddenBySubject', true);
                            // hide(post);
                        }
                        break;
                    case 'com':
                        if (pattern.test(comment)) {
                            opData.remove();
                        }
                        break;
                    case 'board':
                        if (pattern.test(board_name)) {
                            opData.remove();
                        }
                        break;
                }
            } else {
                switch (rule.type) {
                    case 'sub':
                        pattern = new RegExp('\\b'+ rule.value+ '\\b');
                        if (hasSub && pattern.test(subject)) {
                            opData.remove();
                            // $post.data('hiddenBySubject', true);
                            // hide(post);
                        }
                        break;
                    case 'com':
                        pattern = new RegExp('\\b'+ rule.value+ '\\b');
                        if (pattern.test(comment)) {
                            opData.remove();
                        }
                        break;
                    case 'board':
                        pattern = new RegExp('\\b'+ rule.value+ '\\b');
                        if (pattern.test(board_name)) {
                            opData.remove();
                        }
                        break;
                }
            }
        }

    }


    // returns blacklist object from storage
    function getList() {
        return JSON.parse(localStorage.postFilter);
    }


    function drawFilterList() {
        var list = getList().generalFilter;
        var $ele = $('#filter-list');
        var $row, i, length, obj, val;

        var typeName = {
            sub: 'subject',
            com: 'comment',
            board: 'board'
        };

        $ele.empty();

        $ele.append('<tr id="header"><th>Type</th><th>Content</th><th>Remove</th></tr>');
        for (i = 0, length = list.length; i < length; i++) {
            obj = list[i];

            // display formatting
            val = (obj.regex) ? '/'+ obj.value +'/' : obj.value;

            $row = $('<tr>');
            $row.append(
                '<td>'+ typeName[obj.type] +'</td>',
                '<td>'+ val +'</td>',
                $('<td>').append(
                    $('<a>').html('X')
                        .addClass('del-btn')
                        .attr('href', '#')
                        .data('type', obj.type)
                        .data('val', obj.value)
                        .data('useRegex', obj.regex)
                )
            );
            $ele.append($row);
        }
    }

    function initOptionsPanel() {
        if (window.Options && !Options.get_tab('filter')) {
            Options.add_tab('filter', 'list', 'Filters');
            Options.extend_tab('filter',
                '<div id="filter-control">' +
                    '<select>' +
                        '<option value="sub">Subject</option>' +
                        '<option value="com">Comment</option>' +
                        '<option value="board">Board</option>' +
                    '</select>' +
                    '<input type="text">' +
                    '<input type="checkbox">' +
                    'regex ' +
                    '<button id="set-filter">Add</button>' +
                    '<button id="clear">Clear all filters</button>' +
                    '<div id="confirm" class="hidden">' +
                        'This will clear all filtering rules including hidden posts. <a id="confirm-y" href="#">yes</a> | <a id="confirm-n" href="#">no</a>' +
                    '</div>' +
                '</div>' +
                '<div id="filter-container"><table id="filter-list"></table></div>'
            );
            drawFilterList();

            // control buttons
            $('#filter-control').on('click', '#set-filter', function () {
                var type = $('#filter-control select option:selected').val();
                var value = $('#filter-control input[type=text]').val();
                var useRegex = $('#filter-control input[type=checkbox]').prop('checked');

                //clear the input form
                $('#filter-control input[type=text]').val('');

                addFilter(type, value, useRegex);
                drawFilterList();
            });
            $('#filter-control').on('click', '#clear', function () {
                $('#filter-control #clear').addClass('hidden');
                $('#filter-control #confirm').removeClass('hidden');
            });
            $('#filter-control').on('click', '#confirm-y', function (e) {
                e.preventDefault();

                $('#filter-control #clear').removeClass('hidden');
                $('#filter-control #confirm').addClass('hidden');
                setList({
                    generalFilter: [],
                    postFilter: {},
                    nextPurge: {},
                    lastPurge: timestamp()
                });
                drawFilterList();
            });
            $('#filter-control').on('click', '#confirm-n', function (e) {
                e.preventDefault();

                $('#filter-control #clear').removeClass('hidden');
                $('#filter-control #confirm').addClass('hidden');
            });


            // remove button
            $('#filter-list').on('click', '.del-btn', function (e) {
                e.preventDefault();

                var $ele = $(e.target);
                var type = $ele.data('type');
                var val = $ele.data('val');
                var useRegex = $ele.data('useRegex');

                removeFilter(type, val, useRegex);
            });
        }
    }

    function initStyle() {
        var $ele, cssStyle, cssString;

        $ele = $('<div>').addClass('post reply').hide().appendTo('body');
        cssStyle = $ele.css(['background-color', 'border-color']);
        cssStyle.hoverBg = $('body').css('background-color');
        $ele.remove();

        cssString = '\n/*** Generated by post-filter ***/\n' +
            '#filter-control input[type=text] {width: 130px;}' +
            '#filter-control input[type=checkbox] {vertical-align: middle;}' +
            '#filter-control #clear {float: right;}\n' +
            '#filter-container {margin-top: 20px; border: 1px solid; height: 270px; overflow: auto;}\n' +
            '#filter-list {width: 100%; border-collapse: collapse;}\n' +
            '#filter-list th {text-align: center; height: 20px; font-size: 14px; border-bottom: 1px solid;}\n' +
            '#filter-list th:nth-child(1) {text-align: center; width: 70px;}\n' +
            '#filter-list th:nth-child(2) {text-align: left;}\n' +
            '#filter-list th:nth-child(3) {text-align: center; width: 58px;}\n' +
            '#filter-list tr:not(#header) {height: 22px;}\n' +
            '#filter-list tr:nth-child(even) {background-color:rgba(255, 255, 255, 0.5);}\n' +
            '#filter-list td:nth-child(1) {text-align: center; width: 70px;}\n' +
            '#filter-list td:nth-child(3) {text-align: center; width: 58px;}\n' +
            '#confirm {text-align: right; margin-bottom: -18px; padding-top: 2px; font-size: 14px; color: #FF0000;}';

        if (!$('style.generated-css').length) $('<style class="generated-css">').appendTo('head');
        $('style.generated-css').html($('style.generated-css').html() + cssString);
    }


    /**************** start here *****************/
    if (typeof localStorage.postFilter === 'undefined') {
        localStorage.postFilter = JSON.stringify({
            generalFilter: [],
            postFilter: {},
            nextPurge: {},
            lastPurge: timestamp()
        });
    }

    /*Get all boards in overboard page and filter it*/
    $(document).on("filterThreads", function() {
        $(".mix").each(function() {
            var board_name = $(this).data('board');
            /*Filter hide threads on overboard page*/
            var pageData = {
                boardId: board_name,  // get the id from the global variable
                localList: [],  // all the blacklisted post IDs or UIDs that apply to the current page
                noReplyList: [],  // any posts that replies to the contents of this list shall be hidden
                hasUID: (document.getElementsByClassName('poster_id').length > 0),
                forcedAnon: ($('th:contains(Name)').length === 0)  // tests by looking for the Name label on the reply form
            };

            // initStyle();
            // initOptionsPanel();

            filterOverboard($(this));


            filterPage(pageData);
            $(document).on('filter_page', function () {
                filterPage(pageData);
            });

        });
    });
    $(document).trigger('filterThreads');

    // shift+click on catalog to hide thread
    $(document).on('click', '.mix', function(e) {
        if (e.shiftKey) {
            var threadId = $(this).parent().parent().parent().parent().data('id').toString();
            var board_name = $(this).parent().parent().parent().parent().data('board');
            var postId = threadId;

             var pageData = {
                boardId: board_name,  // get the id from the global variable
                localList: [],  // all the blacklisted post IDs or UIDs that apply to the current page
                noReplyList: [],  // any posts that replies to the contents of this list shall be hidden
                hasUID: (document.getElementsByClassName('poster_id').length > 0),
                forcedAnon: ($('th:contains(Name)').length === 0)  // tests by looking for the Name label on the reply form
            };


            $(document).on('filter_page', function () {
                filterPage(pageData);
            });
           
            blacklist.add.post(pageData.boardId, threadId, postId, false);
        }
    });

    $(document).on('click', '#catalog-hide-thread', function(e) {
            var threadId = $(this).parent().parent().parent().parent().data('id').toString();
            var board_name = $(this).parent().parent().parent().parent().data('board');
            var postId = threadId;
            var pageData = {
                boardId: board_name,  // get the id from the global variable
                localList: [],  // all the blacklisted post IDs or UIDs that apply to the current page
                noReplyList: [],  // any posts that replies to the contents of this list shall be hidden
                hasUID: (document.getElementsByClassName('poster_id').length > 0),
                forcedAnon: ($('th:contains(Name)').length === 0)  // tests by looking for the Name label on the reply form
            };

            $(document).on('filter_page', function () {
                filterPage(pageData);
            });

            /*new process*/
            function initList(list, boardId, threadId) {
                if (typeof list.postFilter[boardId] == 'undefined') {
                    list.postFilter[boardId] = {};
                    list.nextPurge[boardId] = {};
                }
                if (typeof list.postFilter[boardId][threadId] == 'undefined') {
                    list.postFilter[boardId][threadId] = [];
                }
                list.nextPurge[boardId][threadId] = {timestamp: timestamp(), interval: 86400};  // 86400 seconds == 1 day
            }


            var blacklist = {
                add: {
                    post: function (boardId, threadId, postId, hideReplies) {
                        var list = getList();
                        var filter = list.postFilter;

                        initList(list, boardId, threadId);

                        for (var i in filter[boardId][threadId]) {
                            if (filter[boardId][threadId][i].post == postId) return;
                        }
                        filter[boardId][threadId].push({
                            post: postId,
                            hideReplies: hideReplies
                        });
                        setList(list);
                    },
                    uid: function (boardId, threadId, uniqueId, hideReplies) {
                        var list = getList();
                        var filter = list.postFilter;

                        initList(list, boardId, threadId);

                        for (var i in filter[boardId][threadId]) {
                            if (filter[boardId][threadId][i].uid == uniqueId) return;
                        }
                        filter[boardId][threadId].push({
                            uid: uniqueId,
                            hideReplies: hideReplies
                        });
                        setList(list);
                    }
                },
                remove: {
                    post: function (boardId, threadId, postId) {
                        var list = getList();
                        var filter = list.postFilter;

                        // thread already pruned
                        if (typeof filter[boardId] == 'undefined' || typeof filter[boardId][threadId] == 'undefined')
                            return;

                        for (var i=0; i<filter[boardId][threadId].length; i++) {
                            if (filter[boardId][threadId][i].post == postId) {
                                filter[boardId][threadId].splice(i, 1);
                                break;
                            }
                        }

                        if ($.isEmptyObject(filter[boardId][threadId])) {
                            delete filter[boardId][threadId];
                            delete list.nextPurge[boardId][threadId];

                            if ($.isEmptyObject(filter[boardId])) {
                                delete filter[boardId];
                                delete list.nextPurge[boardId];
                            }
                        }
                        setList(list);
                    },
                    uid: function (boardId, threadId, uniqueId) {
                        var list = getList();
                        var filter = list.postFilter;

                        // thread already pruned
                        if (typeof filter[boardId] == 'undefined' || typeof filter[boardId][threadId] == 'undefined')
                            return;

                        for (var i=0; i<filter[boardId][threadId].length; i++) {
                            if (filter[boardId][threadId][i].uid == uniqueId) {
                                filter[boardId][threadId].splice(i, 1);
                                break;
                            }
                        }

                        if ($.isEmptyObject(filter[boardId][threadId])) {
                            delete filter[boardId][threadId];
                            delete list.nextPurge[boardId][threadId];

                            if ($.isEmptyObject(filter[boardId])) {
                                delete filter[boardId];
                                delete list.nextPurge[boardId];
                            }
                        }
                        setList(list);
                    }
                }
            };
            /*end new process*/

            blacklist.add.post(pageData.boardId, threadId, postId, false);
    });

    //Show all hide threads
    $(document).on('click', '#show-all-hide-thread', function(e) {
            $("#show-all-hide-thread").html("Processing...");
            localStorage.postFilter = JSON.stringify({
                    generalFilter: [],
                    postFilter: {},
                    nextPurge: {},
                    lastPurge: timestamp()
            });


            var body = $(this).parents('.GRIDS');
            var url = window.location.href;
            $.ajax({
                    url: url,
                    context: document.body,
                    success: function(data) {
                            //var content = $(data).find('.GRIDS').html();
                            var content = $($.parseHTML(data)).filter(".GRIDS").html();

                            $(".GRIDS").html(content);
                            $("#show-all-hide-thread").html(" [Show All] ");
                    }
            });

    });


    function init() {
        initStyle();
        initOptionsPanel();

        // clear out the old threads
        purge();
    }


    function optionsThemes(){
        /*********************** Themes ***************************/
        var configRoot="https://8ch.net/_css";
        var board_name="overboard"; 
        var tab = Options.add_tab("user-css", "css3", "Theme");
        var styles = {'Yotsuba B' : '/themes/','Yotsuba' : '/themes/yotsuba.css','Tomorrow' : '/themes/tomorrow.css','Dark' : '/themes/dark.css','Photon' : '/themes/photon.css','Redchanit' : '/themes/redchanit.css','2channel' : '/themes/2channel.css','Cyberpunk' : '/themes/cyberpunk.css','Amber' : '/themes/amber.css','Custom' : '/themes/board/fresh.css'};

        var textarea = $("<textarea></textarea>").css({
            "height"     : "80%",
            "width"      : "100%",
            "font-size"  : "9pt",
            "font-family": "monospace",
            }).appendTo(tab.content);
            var submit = $("<input type='button' value='Save custom CSS'>").css({
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
                k === "Custom" ? k2 = "Board-specific CSS" : k2 = k;
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

            var allboards = $("<label><input type='checkbox' id='css-all-boards'> Style should affect all boards, not just current board (/"+board_name+"/)</label>").appendTo(tab.content).find('input');

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
              textarea.text("/* Enter here your own CSS rules... */\n" +
                            "/* If you want to make a redistributable style, be sure to\nhave a Yotsuba B theme selected. */\n" +
                            "/* These will be applied on top of whatever theme you choose below. */\n" +
                            "/* You can include CSS files from remote servers, for example: */\n" +
                            '/* @import "http://example.com/style.css"; */');
            }
            textarea.text(localStorage.user_css);
            apply_css();
        };

        main();

    }


    //Show top boards
    window.boards = new Array();
    var show_top_boards;

    show_top_boards = "<span class='show_top_boards_settings'><label id='show_top_boards'><input type='checkbox' /> Show top boards</label></span>"
    $(show_top_boards).insertAfter("#navsettings");

    if (typeof localStorage.show_top_boards === 'undefined') {
        localStorage.show_top_boards = 'false';
        var show_top = JSON.parse(localStorage.show_top_boards);
    }

    var show_top = JSON.parse(localStorage.show_top_boards);
    if (show_top) $('#show_top_boards>input').attr('checked', 'checked');


    $('#show_top_boards>input').on('change', function() {
        var show_top = ($(this).is(':checked'));
        localStorage.show_top_boards = JSON.stringify(show_top);
        if(show_top){
            updateTopBoards(show_top);
        }else{
            $('.sub[data-description="4"]').remove();
        }
    });


    function handle_boards(data) {
      $.each(data, function(k, v) {
        boards.push('<a href="/'+v.uri+(window.active_page === 'catalog' ? '/catalog.html' : '/index.html')+'" title="'+v.title+'">'+v.uri+'</a>');
      })

      if (boards[0]) {
        $('.sub[data-description="1"]').after('<span class="sub" data-description="4"> [ '+boards.slice(0,25).join(" / ")+' ] </span>');
      }
    }

    function updateTopBoards(show_top){
        if (!(typeof show_top !== "undefined" && !show_top)) {
          $.getJSON("https://8ch.net/boards-top20.json", handle_boards)
        }
    }
    updateTopBoards(show_top);

});
