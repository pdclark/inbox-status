<?php

class IS_Shortcodes {

	public $shortcodes = array(
		// Generic
		'inbox-unread'     => '<code>[inbox-unread]</code> unread emails',
		'inbox-total'      => '<code>[inbox-total]</code> total emails',

		// Gmail
		'gmail-important'  => '<code>[gmail-important]</code> important emails',
		'gmail-starred'    => '<code>[gmail-starred]</code> starred emails',
		'gmail-primary'    => '<code>[gmail-primary]</code> primary emails',
		'gmail-social'     => '<code>[gmail-social]</code> social emails',
		'gmail-promotions' => '<code>[gmail-promotions]</code> promotional emails',
		'gmail-updates'    => '<code>[gmail-updates]</code> update emails',
		'gmail-forums'     => '<code>[gmail-forums]</code> forum emails',
	);

	public function __construct() {
		/**
		 * Tie each shortcode key with the corresponding method in this class.
		 * Allow shortcodes to use hyphens or underscores.
		 */
		foreach( $this->shortcodes as $id => $meta ) {
			$alternate = str_replace( '-', '_', $id );

			add_shortcode( $id, array( $this, 'do_shortcode' ) );
			add_shortcode( $alternate, array( $this, 'do_shortcode' ) );
		}
	}

	public function get_valid_keys() {
		return array_keys( $this->shortcodes );
	}

	public function do_shortcode( $attr, $null, $tag ) {
		$inbox = IS_Inbox_Status::get_instance();

		$tag = str_replace( '_', '-', $tag );

		$output = "<span class='is-$tag'>" .
		            $inbox->get_count( $tag ) . 
		          '</span>';
		
		return $output;
	}
	
}