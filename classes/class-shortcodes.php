<?php

class IS_Shortcodes {
	/**
	 * @var IS_Inbox_Status Reference to Inbox Status controller.
	 */
	var $inbox;

	public function __construct() {
		$this->inbox = IS_Inbox_Status::get_instance();

		add_shortcode( 'inbox-unread',  array( $this, 'inbox_unread' ) );
		add_shortcode( 'inbox_unread',  array( $this, 'inbox_unread' ) );

		add_shortcode( 'inbox-total',  array( $this, 'inbox_total' ) );
		add_shortcode( 'inbox_total',  array( $this, 'inbox_total' ) );
	}

	public function inbox_unread() {
		return '<span class="is-unread-count">' . $this->inbox->get_unread_count() . '</span>';
	}

	public function inbox_total() {
		return '<span class="is-total-count">' .$this->inbox->get_total_count() . '</span>';
	}
	
}