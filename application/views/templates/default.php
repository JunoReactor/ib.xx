<!DOCTYPE html>
<html>
    <head>
        <title>Статистика пользователей</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!--<link rel="stylesheet" href="/css/reset.css">-->
	<link rel="stylesheet" href="/css/tables.css">
	<!--[if lte IE 9]><link rel="stylesheet" href="css/ie.css"><![endif]-->
	<link href="/css/redmond/jquery-ui-1.10.4.custom.css" rel="stylesheet">
	<script src="/js/jquery-1.10.2.js"></script>
	<script src="/js/jquery-ui-1.10.4.custom.js"></script>
        
        <script src="/js/bootstrap/bootstrap.min.js"></script>
        <link href="/css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="/css/bootstrap/bootstrap-theme.min.css" rel="stylesheet">
        
        <script src="/js/jquery.tablesorter.min.js"></script>
	<!--
        <link rel="stylesheet" href="/css/zoomer/easyzoom.css" />
        <script src="/js/zoomer/easyzoom.js"></script>
        -->
        <link rel="stylesheet" href="/css/tablesorter/style.css" />
        <link rel="stylesheet" href="/css/styles.css" />
        <script>
	(function( $ ) {
		$.widget( "custom.combobox", {
			_create: function() {
				this.wrapper = $( "<span>" )
					.addClass( "custom-combobox" )
					.insertAfter( this.element );

				this.element.hide();
				this._createAutocomplete();
				this._createShowAllButton();
			},

			_createAutocomplete: function() {
				var selected = this.element.children( ":selected" ),
					value = selected.val() ? selected.text() : "";

				this.input = $( "<input>" )
					.appendTo( this.wrapper )
					.val( value )
					.attr( "title", "" )
					.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: $.proxy( this, "_source" )
					})
					.tooltip({
						tooltipClass: "ui-state-highlight"
					});

				this._on( this.input, {
					autocompleteselect: function( event, ui ) {
						ui.item.option.selected = true;
                                                location = "?users/get/" + this.element.children( ":selected" ).val();
						this._trigger( "select", event, {
							item: ui.item.option
						});
					},

					autocompletechange: "_removeIfInvalid"
				});
			},

			_createShowAllButton: function() {
				var input = this.input,
					wasOpen = false;

				$( "<a>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All Items" )
					.tooltip()
					.appendTo( this.wrapper )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "custom-combobox-toggle ui-corner-right" )
					.mousedown(function() {
						wasOpen = input.autocomplete( "widget" ).is( ":visible" );
					})
					.click(function() {
						input.focus();

						// Close if already visible
						if ( wasOpen ) {
							return;
						}

						// Pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
					});
			},

			_source: function( request, response ) {
				var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
				response( this.element.children( "option" ).map(function() {
					var text = $( this ).text();
					if ( this.value && ( !request.term || matcher.test(text) ) )
						return {
							label: text,
							value: text,
							option: this
						};
				}) );
			},

			_removeIfInvalid: function( event, ui ) {

				// Selected an item, nothing to do
				if ( ui.item ) {
					return;
				}

				// Search for a match (case-insensitive)
				var value = this.input.val(),
					valueLowerCase = value.toLowerCase(),
					valid = false;
				this.element.children( "option" ).each(function() {
					if ( $( this ).text().toLowerCase() === valueLowerCase ) {
						this.selected = valid = true;
						return false;
					}
				});

				// Found a match, nothing to do
				if ( valid ) {
					return;
				}

				// Remove invalid value
				this.input
					.val( "" )
					.attr( "title", value + " didn't match any item" )
					.tooltip( "open" );
				this.element.val( "" );
				this._delay(function() {
					this.input.tooltip( "close" ).attr( "title", "" );
				}, 2500 );
				this.input.data( "ui-autocomplete" ).term = "";
			},

			_destroy: function() {
				this.wrapper.remove();
				this.element.show();
			}
		});
	})( jQuery );

	$(function() {
		$( "#combobox" ).combobox();
		$( "#toggle" ).click(function() {
                    $( "#combobox" ).toggle();
		});
                $("#tablesorter").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
		//$("#options").tablesorter({sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}}});
	});
	</script>
        <style>


/* Shrink wrap strategy 1 */
.easyzoom {
    float: left;
}
.easyzoom img {
    display: block;
}


/* Shrink wrap strategy 2 */
.easyzoom {
    display: inline-block;
}
.easyzoom img {
    vertical-align: bottom;
}



	.custom-combobox {
		position: relative;
		display: inline-block;
	}
	.custom-combobox-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* support: IE7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.custom-combobox-input {
		margin: 0;
		padding: 0.3em;
	}
	body{
		font: 62.5% "Trebuchet MS", sans-serif;
		margin: 50px;
	}
	.demoHeaders {
		margin-top: 2em;
	}
	#dialog-link {
		padding: .4em 1em .4em 20px;
		text-decoration: none;
		position: relative;
	}
	#dialog-link span.ui-icon {
		margin: 0 5px 0 0;
		position: absolute;
		left: .2em;
		top: 50%;
		margin-top: -8px;
	}
	#icons {
		margin: 0;
		padding: 0;
	}
	#icons li {
		margin: 2px;
		position: relative;
		padding: 4px 0;
		cursor: pointer;
		float: left;
		list-style: none;
	}
	#icons span.ui-icon {
		float: left;
		margin: 0 4px;
	}
	.fakewindowcontain .ui-widget-overlay {
		position: absolute;
	}    

        </style>
    </head>
    <body>
        <!--<a href="/">главная</a> / <a href="?users/">пользователи</a>--> 
        <a href="/"><img src="/img/vfp_logo.png" alt="ВФП" align="right"></a> 
         
        
        <!--/ <a href="?photo/loader/">загрузчик фотографий</a> / <a href="?photo/get/1">получить фото</a> -->
        <a href="http://old_spravka.vfp.ru">Старая справка</a>
        <?php 
        $inc = 'application/views/'.$content_view;
        if(file_exists($inc)){
           include_once $inc; 
        }
        ?>
        
        
        <!--<script>
            var $easyzoom = $('.easyzoom').easyZoom();
            var api = $easyzoom.data('easyZoom');
	</script>--> 
    </body>
</html>    