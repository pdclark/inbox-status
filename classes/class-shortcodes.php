<?php

class IS_Shortcodes {
	/**
	 * @var IS_Inbox_Status Reference to Inbox Status controller.
	 */
	var $inbox;

	public function __construct() {
		$this->inbox = IS_Inbox_Status::get_instance();

		add_shortcode( 'unread-emails',  array( $this, 'unread_emails' ) );
		add_shortcode( 'unread-e-mails', array( $this, 'unread_emails' ) );
		add_shortcode( 'unread_emails',  array( $this, 'unread_emails' ) );

		add_shortcode( 'total-emails',  array( $this, 'total_emails' ) );
		add_shortcode( 'total-e-mails', array( $this, 'total_emails' ) );
		add_shortcode( 'total_emails',  array( $this, 'total_emails' ) );
	}

	public function unread_emails() {
		return '<span class="is-unread-count">' . $this->inbox->get_unread_count() . '</span>';
	}

	public function total_emails() {
		return '<span class="is-total-count">' .$this->inbox->get_total_count() . '</span>';
	}
	
}