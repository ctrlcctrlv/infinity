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
				
				'search'        : "#search-form",
				'search-lang'   : "#search-lang-input",
				'search-sfw'    : "#search-sfw-input",
				'search-tag'    : "#search-tag-input",
				'search-title'  : "#search-title-input",
				'search-submit' : "#search-submit",
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
				'board-cell-max'     : "<td class=\"board-max\"></td>",
				'board-cell-unique'  : "<td class=\"board-unique\"></td>",
				'board-cell-tags'    : "<td class=\"board-tags\"></td>",
				
				// Content wrapper
				// Used to help constrain contents to their <td>.
				'board-content-wrap' : "<div class=\"board-cell\"></div>",
				
				
				// Tagging
				'board-datum-tags' : "<a class=\"tag-link\" href=\"#\"></a>"
			}
		},
		
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
					$search.on( 'submit', searchForms, boardlist.events.searchSubmit );
					$searchSubmit.on( 'click', searchForms, boardlist.events.searchSubmit );
				}
			}
		},
		
		build  : {
			boardlist : function(data) {
				boardlist.build.boards(data['boards']);
				boardlist.build.tags(data['tags']);
				
			},
			
			boards : function(data) {
				// Find our head, columns, and body.
				var $head = $( boardlist.options.selector['board-head'], boardlist.$boardlist ),
				    $cols = $("[data-column]", $head ),
				    $body = $( boardlist.options.selector['board-body'], boardlist.$boardlist );
				
				$.each( data, function( index, row ) {
					var $row = $( boardlist.options.template['board-row'] );
					
					$cols.each( function( index, col ) {
						var $col   = $(col),
						    column = $col.attr('data-column'),
						    value  = row[column]
						    $cell  = $( boardlist.options.template['board-cell-' + column] ),
						    $wrap  = $( boardlist.options.template['board-content-wrap'] );
						
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
						
						$wrap.appendTo( $cell );
						$cell.appendTo( $row );
					} );
						
						if( index >= 100 ) return false;
					
					$row.appendTo( $body );
				} );
				
			},
			
			tags : function(data) {
				
			}
		},
		
		events : {
			searchSubmit : function(event) {
				event.preventDefault();
				
				var $boardlist = event.data.boardlist,
				    $boardbody = $( boardlist.options.selector['board-body'], $boardlist ),
				    $boardload = $( boardlist.options.selector['board-loading'], $boardlist );
				
				$boardbody.html("");
				$boardload.show();
				
				$.get(
					"/board-search.php",
					{
						'lang'  : event.data.searchLang.val(),
						'tags'  : event.data.searchTag.val(),
						//'time'  : event.data.searchTag.val(),
						'title' : event.data.searchTitle.val(),
						'sfw'   : event.data.searchSfw.prop('checked') ? 1 : 0
					},
					function(data) {
						$boardload.hide();
						boardlist.build.boardlist( $.parseJSON(data) );
					}
				);
				
				return false;
			}
		},
		
		init : function( target ) {
			if (typeof target !== "string") {
				target = boardlist.options.selector.boardlist;
			}
			
			var $target = $(target);
			
			if ($target.length > 0 ) {
				boardlist.$boardlist = $target;
				boardlist.bind.form();
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