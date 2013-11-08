<?php

class IS_Shortcodes {
	/**
	 * @var IS_Inbox_Status Reference to Inbox Status controller.
	 */
	protected $inbox;

	public $shortcodes = array(
		'inbox-unread' => array(
			'alternate' => 'inbox_unread',
			'method'    => 'inbox_unread',
			'example'   => '<code>[inbox-unread]</code> unread emails.',
		),
		'inbox-total' => array(
			'alternate' => 'inbox_total',
			'method'    => 'inbox_total',
			'example' => '<code>[inbox-total]</code> total emails.',
		),
		'gmail-important-unread' => array(
			'alternate' => 'gmail_important_unread',
			'method'    => 'gmail_important_unread',
			'example' => '<code>[gmail-important-unread]</code> important unread emails.',
		),
		'gmail-starred-unread' => array(
			'alternate' => 'gmail_starred_unread',
			'method'    => 'gmail_starred_unread',
			'example' => '<code>[gmail-starred-unread]</code> starred unread emails.',
		),
	);

	public function __construct() {
		$this->inbox = IS_Inbox_Status::get_instance();

		/**
		 * Tie each shortcode key with the corresponding method in this class.
		 * Allow shortcodes to use hyphens or underscores.
		 */
		foreach( $this->shortcodes as $id => $meta ) {
			add_shortcode( $id, array( $this, $meta['method'] ) );
			add_shortcode( $meta['alternate'], array( $this, $meta['method'] ) );
		}
	}

	public function get_valid_keys() {
		return array_keys( $this->shortcodes );
	}

	public function inbox_unread() {
		return '<span class="is-unread-count">' . $this->inbox->get_count( 'inbox-unread' ) . '</span>';
	}

	public function inbox_total() {
		return '<span class="is-total-count">' . $this->inbox->get_count( 'inbox-total' ) . '</span>';
	}

	public function gmail_important_unread() {
		return '<span class="is-gmail-important-count">' . $this->inbox->get_count( 'gmail-important-unread' ) . '</span>';
	}

	public function gmail_starred_unread() {
		return '<span class="is-gmail-starred-count">' . $this->inbox->get_count( 'gmail-starred-unread' ) . '</span>';
	}
	
}