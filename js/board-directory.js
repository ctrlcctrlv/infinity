// ============================================================
// Purpose                      : Board directory handling
// Contributors                 : 8n-tech
// ============================================================

;( function( window, $, undefined ) {
	var boardlist = {
		options : {
			$boardlist : false,
			
			// Selectors for finding and binding elements.
			selector   : {
				'boardlist'     : "#boardlist",
				
				'board-head'    : ".board-list-head",
				'board-body'    : ".board-list-tbody",
				'board-loading' : ".board-list-loading",
				'board-omitted' : ".board-list-omitted",
				
				'search'        : "#search-form",
				'search-lang'   : "#search-lang-input",
				'search-sfw'    : "#search-sfw-input",
				'search-tag'    : "#search-tag-input",
				'search-title'  : "#search-title-input",
				'search-submit' : "#search-submit",
				
				'tag-list'      : ".tag-list",
				'tag-link'      : ".tag-link",
				
				'footer-page'   : ".board-page-num",
				'footer-count'  : ".board-page-count",
				'footer-total'  : ".board-page-total",
				'footer-more'   : "#board-list-more"
			},
			
			// HTML Templates for dynamic construction
			template   : {
				// Board row item
				'board-row'          : "<tr></tr>",
				
				// Individual cell definitions
				'board-cell-meta'    : "<td class=\"board-meta\"></td>",
				'board-cell-uri'     : "<td class=\"board-uri\"></td>",
				'board-cell-title'   : "<td class=\"board-title\"></td>",
				'board-cell-pph'     : "<td class=\"board-pph\"></td>",
				'board-cell-posts_total' : "<td class=\"board-max\"></td>",
				'board-cell-active'  : "<td class=\"board-unique\"></td>",
				'board-cell-tags'    : "<td class=\"board-tags\"></td>",
				
				// Content wrapper
				// Used to help constrain contents to their <td>.
				'board-content-wrap' : "<p class=\"board-cell\"></p>",
				
				// Individual items or parts of a single table cell.
				'board-datum-lang'   : "<span class=\"board-lang\"></span>",
				'board-datum-uri'    : "<a class=\"board-link\"></a>",
				'board-datum-sfw'    : "<i class=\"fa fa-briefcase board-sfw\" title=\"SFW\"></i>",
				'board-datum-nsfw'   : "<i class=\"fa fa-briefcase board-nsfw\" title=\"NSFW\"></i>",
				'board-datum-tags'   : "<a class=\"tag-link\" href=\"#\"></a>",
				'board-datum-pph'    : "<p class=\"board-cell board-pph-desc\" title=\"%1 made in the last hour, %2 on average\"></p>",
				
				
				// Tag list.
				'tag-list'           : "<ul class=\"tag-list\"></ul>",
				'tag-item'           : "<li class=\"tag-item\"></li>",
				'tag-link'           : "<a class=\"tag-link\" href=\"#\"></a>"
			}
		},
		
		lastSearch : {},
		
		bind : {
			form : function() {
				var selectors = boardlist.options.selector;
				
				var $search       = $( selectors['search'] ),
				    $searchLang   = $( selectors['search-lang'] ),
				    $searchSfw    = $( selectors['search-sfw'] ),
				    $searchTag    = $( selectors['search-tag'] ),
				    $searchTitle  = $( selectors['search-title'] ),
				    $searchSubmit = $( selectors['search-submit'] );
				
				var searchForms   = {
						'boardlist'    : boardlist.$boardlist,
						'search'       : $search,
						'searchLang'   : $searchLang,
						'searchSfw'    : $searchSfw,
						'searchTag'    : $searchTag,
						'searchTitle'  : $searchTitle,
						'searchSubmit' : $searchSubmit
					};
				
				if ($search.length > 0) {
					// Bind form events.
					boardlist.$boardlist
						// Load more
						.on( 'click', selectors['board-omitted'], searchForms, boardlist.events.loadMore )
						// Tag click
						.on( 'click', selectors['tag-link'], searchForms, boardlist.events.tagClick )
						// Form Submission
						.on( 'submit', selectors['search'], searchForms, boardlist.events.searchSubmit )
						// Submit click
						.on( 'click', selectors['search-submit'], searchForms, boardlist.events.searchSubmit );
					
					$(window)
						.on( 'hashchange', searchForms, boardlist.events.hashChange );
					
					$searchSubmit.prop( 'disabled', false );
				}
			}
		},
		
		build  : {
			boardlist : function(data) {
				boardlist.build.boards(data['boards'], data['order']);
				boardlist.build.lastSearch(data['search']);
				boardlist.build.footer(data);
				boardlist.build.tags(data['tagWeight']);
				
			},
			
			boards : function(boards, order) {
				// Find our head, columns, and body.
				var $head = $( boardlist.options.selector['board-head'], boardlist.$boardlist ),
				    $cols = $("[data-column]", $head ),
				    $body = $( boardlist.options.selector['board-body'], boardlist.$boardlist );
				
				$.each( order, function( index, uri ) {
					var row  = boards[uri];
					    $row = $( boardlist.options.template['board-row'] );
					
					$cols.each( function( index, col ) {
						boardlist.build.board( row, col ).appendTo( $row );
					} );
					
					$row.appendTo( $body );
				} );
				
			},
			board : function(row, col) {
				var $col   = $(col),
				    column = $col.attr('data-column'),
				    value  = row[column]
				    $cell  = $( boardlist.options.template['board-cell-' + column] ),
				    $wrap  = $( boardlist.options.template['board-content-wrap'] );
				
				if (typeof boardlist.build.boardcell[column] === "undefined") {
					if (value instanceof Array) {
						if (typeof boardlist.options.template['board-datum-' + column] !== "undefined") {
							$.each( value, function( index, singleValue ) {
								$( boardlist.options.template['board-datum-' + column] )
									.text( singleValue )
									.appendTo( $wrap );
							} );
						}
						else {
							$wrap.text( value.join(" ") );
						}
					}
					else {
						$wrap.text( value );
					}
				}
				else {
					var $content = boardlist.build.boardcell[column]( row, value );
					
					if ($content instanceof jQuery) {
						if ($content.is("." + $wrap[0].class)) {
							// Our new content has the same classes as the wrapper.
							// Replace the old wrapper.
							$wrap = $content;
						}
						else {
							// We use .append() instead of .appendTo() as we do elsewhere
							// because $content can be multiple elements.
							$wrap.append( $content );
						}
					}
					else if (typeof $content === "string") {
						$wrap.html( $content );
					}
					else {
						console.log("Special cell constructor returned a " + (typeof $content) + " that board-directory.js cannot interpret.");
					}
				}
				
				$wrap.appendTo( $cell );
				return $cell;
			},
			boardcell : {
				'meta' : function(row, value) {
					return $( boardlist.options.template['board-datum-lang'] ).text( row['locale'] );
				},
				'uri'  : function(row, value) {
					var $link = $( boardlist.options.template['board-datum-uri'] ),
						$sfw  = $( boardlist.options.template['board-datum-' + (row['sfw'] == 1 ? "sfw" : "nsfw")] );
					
					$link
						.attr( 'href', "/"+row['uri']+"/" )
						.text( "/"+row['uri']+"/" );
					
					// I decided against NSFW icons because it clutters the index.
					// Blue briefcase = SFW. No briefcase = NSFW. Seems better.
					if (row['sfw'] == 1) {
						return $link[0].outerHTML + $sfw[0].outerHTML;
					}
					else {
						return $link[0].outerHTML;
					}
				},
				'pph' : function(row, value) {
					return $( boardlist.options.template['board-datum-pph'] )
						.attr( 'title', function(index, value) {
							return value.replace("%1", row['pph']).replace("%2", row['pph_average']);
						} )
						.text( row['pph'] );
				},
			},
			
			lastSearch : function(search) {
				return boardlist.lastSearch =  { 
					'lang'  : search.lang === false ? "" : search.lang,
					'page'  : search.page,
					'tags'  : search.tags === false ? "" : search.tags.join(" "),
					'time'  : search.time,
					'title' : search.title === false ? "" : search.title,
					'sfw'   : search.nsfw ? 0 : 1
				};
			},
			
			footer : function(data) {
				var selector = boardlist.options.selector,
				    $page    = $( selector['footer-page'], boardlist.$boardlist ),
				    $count   = $( selector['footer-count'], boardlist.$boardlist ),
				    $total   = $( selector['footer-total'], boardlist.$boardlist ),
				    $more    = $( selector['footer-more'], boardlist.$boardlist ),
				    $omitted = $( selector['board-omitted'], boardlist.$boardlist );
				
				var boards   = Object.keys(data['boards']).length,
				    omitted  = data['omitted'] - data['search']['page'];
				
				if (omitted < 0) {
					omitted = 0;
				}
				
				var total    = boards + omitted + data['search']['page'];
				
				//$page.text( data['search']['page'] );
				$count.text( data['search']['page'] + boards );
				$total.text( total );
				$more.toggleClass( "board-list-hasmore", omitted != 0 );
				$omitted.toggle( boards + omitted > 0 );
			},
			
			tags : function(tags) {
				var selector = boardlist.options.selector,
				    template = boardlist.options.template,
				    $list    = $( selector['tag-list'], boardlist.$boardlist );
				
				if ($list.length) {
					
					$.each( tags, function(tag, weight) {
						var $item = $( template['tag-item'] ),
						    $link = $( template['tag-link'] );
						
						$link
							.css( 'font-size', weight+"%" )
							.text( tag )
							.appendTo( $item );
						
						$item.appendTo( $list );
					} );
				}
			}
		},
		
		events : {
			loadMore : function(event) {
				var parameters = $.extend( {}, boardlist.lastSearch );
				
				parameters.page = $( boardlist.options.selector['board-body'], boardlist.$boardlist ).children().length;
				
				boardlist.submit( parameters );
			},
			
			hashChange : function(event) {
				if (window.location.hash != "") {
					// Turns "#porn,tits" into "porn tits" for easier search results.
					var tags = window.location.hash.substr(1, window.location.hash.length).split(","),
					    hash = tags.join(" ");
				}
				else {
					var tags = [],
					    hash = ""
				}
				
				$( boardlist.options.selector['search-tag'], boardlist.$boardlist ).val( hash );
				$( boardlist.options.selector['tag-list'], boardlist.$boardlist ).html("");
				$( boardlist.options.selector['board-body'], boardlist.$boardlist ).html("");
				
				boardlist.submit( { 'tags' : tags } );
				
				return true;
			},
			
			searchSubmit : function(event) {
				event.preventDefault();
				
				$( boardlist.options.selector['tag-list'], boardlist.$boardlist ).html("");
				$( boardlist.options.selector['board-body'], boardlist.$boardlist ).html("");
				
				boardlist.submit( { 
					'lang'  : event.data.searchLang.val(),
					'tags'  : event.data.searchTag.val(),
					'title' : event.data.searchTitle.val(),
					'sfw'   : event.data.searchSfw.prop('checked') ? 1 : 0
				} );
				
				return false;
			},
			
			tagClick : function(event) {
				event.preventDefault();
				
				var $this  = $(this),
					$input = $( boardlist.options.selector['search-tag'] );
				
				$input
					.val( ( $input.val() + " " + $this.text() ).replace(/\s+/g, " ").trim() )
					.trigger( 'change' )
					.focus();
				
				return false;
			}
		},
		
		submit : function( parameters ) {
			var $boardlist    = boardlist.$boardlist,
			    $boardload    = $( boardlist.options.selector['board-loading'], $boardlist ),
			    $searchSubmit = $( boardlist.options.selector['search-submit'], $boardlist ),
			    $footerMore   = $( boardlist.options.selector['board-omitted'], $boardlist );
			
			$searchSubmit.prop( 'disabled', true );
			$boardload.show();
			$footerMore.hide();
			
			return $.get(
				"/board-search.php",
				parameters,
				function(data) {
					$searchSubmit.prop( 'disabled', false );
					$boardload.hide();
					
					boardlist.build.boardlist( $.parseJSON(data) );
				}
			);
		},
		
		init : function( target ) {
			if (typeof target !== "string") {
				target = boardlist.options.selector.boardlist;
			}
			
			// Parse ?GET parameters into lastSearch object.
			if (window.location.search != "" && window.location.search.length > 0) {
				// ?a=1&b=2 -> a=1&b=2 -> { a : 1, b : 2 }
				window.location.search.substr(1).split("&").forEach( function(item) {
					boardlist.lastSearch[item.split("=")[0]] = item.split("=")[1];
				} );
			}
			
			var $boardlist = $(target);
			
			if ($boardlist.length > 0 ) {
				$( boardlist.options.selector['board-loading'], $boardlist ).hide();
				
				boardlist.$boardlist = $boardlist;
				boardlist.bind.form();
				
				if (window.location.hash != "") {
					$(window).trigger( 'hashchange' );
				}
			}
		}
	};
	
	// Tie to the vichan object.
	if (typeof window.vichan === "undefined") {
		window.vichan = {};
	}
	window.vichan.boardlist = boardlist;
	
	// Initialize the boardlist when the document is ready.
	$( document ).on( 'ready', window.vichan.boardlist.init );
	// Run it now if we're already ready.
	if  (document.readyState === 'complete') {
		window.vichan.boardlist.init();
	}
} )( window, jQuery );