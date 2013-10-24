jQuery( document ).ready( function( $ ){

	// Needs revision to work in subdirectory installs
	$.get( '/wp-admin/admin-ajax.php?action=unread-gmail-count', unread_gmail_count );

	function unread_gmail_count( count ) {
		if ( count.length === 0 ) {
			return;
		}

		var emails = ( unread == 1 ) ? 'email' : 'emails';

		var content = '<i style="margin: 3px 3px 0 0" class="icon icon-envelope"></i> ' +
									count + ' unread ' + emails;

		var unread = $( '<li>' ).addClass('menu-item social-icon unread-count').append( content );

		unread.css( 'margin-top', 13 );
		unread.css( 'margin-right', 20 );

		$( '#menu-social' ).append( unread );

	}

} );