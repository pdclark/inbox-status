( function( $, api, plugin ){

	api.addInboxStatusLink = function( processMethod ) {
		var url = $('#' + plugin.slug + '-url').val(),
			label = $('#' + plugin.slug + '-name').val();

		processMethod = processMethod || api.addMenuItemToBottom;

		if ( '' === url || 'http://' == url || plugin.url_default == url ) {
			url = '#';
		}

		// Show the ajax spinner
		$('#' + plugin.slug + '-div .spinner').show();
		this.addInboxStatusToMenu( url, label, processMethod, function() {
			// Remove the ajax spinner
			$('#' + plugin.slug + '-div .spinner').hide();
			// Set custom link form back to defaults
			populate_template();
			$('#' + plugin.slug + '-url').val( plugin.url_default );
		});
	};

	api.addInboxStatusToMenu = function(url, label, processMethod, callback) {
		processMethod = processMethod || api.addMenuItemToBottom;
		callback = callback || function(){};

		api.addItemToMenu({
			'-1': {
				'menu-item-type': 'custom',
				'menu-item-url': url,
				'menu-item-title': label,
				'menu-item-classes': plugin.slug
			}
		}, processMethod, callback);
	};

	/**
	 * Fill name field with value of template selector
	 */
	var populate_template = function() {
		var value = $('.' + plugin.slug + '-template:checked').val();

		$('#' + plugin.slug + '-name').val( value ).blur();
	}

	var init = function(){
		$('#submit-' + plugin.slug + '-div').click( function(){
			api.addInboxStatusLink( api.addMenuItemToBottom );
		});

		$('.' + plugin.slug + '-template').on( 'click change', populate_template );
		$('.' + plugin.slug + '-template:checked').change();

		// Testing only
		if ( 'http://pdclark.com/wp-admin/nav-menus.php' == window.location ) {
			setTimeout( function(){
				$('#add-' + plugin.slug + ' h3').click();
			}, 1000 );
		}
	};

	init();

})( jQuery, wpNavMenu, InboxStatusAdmin );