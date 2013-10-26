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
			$('#' + plugin.slug + '-name').val('').blur();
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

	$('#submit-' + plugin.slug + '-div').click( function(){
		api.addInboxStatusLink( api.addMenuItemToBottom );
	});

	// Testing only
	setTimeout( function(){
		$('#add-' + plugin.slug + ' h3').click();
	}, 1000 );

})( jQuery, wpNavMenu, InboxStatusAdmin );