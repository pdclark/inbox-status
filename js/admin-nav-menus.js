( function( $, api, plugin ){

	api.addInboxStatusLink = function( processMethod ) {
		var url = $('#inbox-status-url').val(),
			label = $('#inbox-status-name').val();

		processMethod = processMethod || api.addMenuItemToBottom;

		if ( '' === url || 'http://' == url || plugin.url_default == url ) {
			url = '#';
		}

		// Show the ajax spinner
		$('#inbox-status-div .spinner').show();
		this.addInboxStatusToMenu( url, label, processMethod, function() {
			// Remove the ajax spinner
			$('#inbox-status-div .spinner').hide();
			// Set custom link form back to defaults
			populate_template();
			$('#inbox-status-url').val( plugin.url_default );
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
				'menu-item-classes': 'inbox-status'
			}
		}, processMethod, callback);
	};

	/**
	 * Fill name field with value of template selector
	 */
	var populate_template = function() {
		var value = $('.inbox-status-template').val();

		$('#inbox-status-name').val( value ).blur();
	};

	var init = function(){
		$('#submit-inbox-status-div').click( function(){
			api.addInboxStatusLink( api.addMenuItemToBottom );
		});

		$('.inbox-status-template').on( 'change', populate_template ).change();

		// Testing only
		if ( 'http://pdclark.com/wp-admin/nav-menus.php' == window.location ) {
			setTimeout( function(){
				$('#add-inbox-status h3').click();
			}, 1000 );
		}
	};

	init();

})( jQuery, wpNavMenu, InboxStatusAdmin );