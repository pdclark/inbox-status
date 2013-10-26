/**
 * Update inbox stats in the background if update interval has passed.
 * Using AJAX avoids slowing down or crashing the site during imap connections.
 */
( function( $, inbox ){

	/**
	 * Call IS_Inbox_Status::wp_ajax_update_inbox()
	 */
  var update_inbox = function() {
		$.get( inbox.ajax_url );
	};

	/**
	 * @return bool Is current time later than last updated time plus update interval?
	 */
	var update_interval_has_passed = function() {
		if ( '' === inbox.last_updated ) {
			// First plugin run. Status will update in PHP.
			return false;
		}

		return inbox.current_time > ( inbox.last_updated + inbox.update_interval );
	};

	var init = function() {
		if ( update_interval_has_passed() ) {
			update_inbox();
		}
	};

	init();

})( jQuery, is_inbox_status );