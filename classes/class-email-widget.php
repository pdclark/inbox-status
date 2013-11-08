<?php

/**
 * @author Ben Lobaugh <http://ben.lobaugh.net>
 * @author Paul Clark <http://brainstormmedia.com>
 */
class IS_Email_Widget extends WP_Widget {

	var $defaults = array(
		'title' => '',
	);

	public function __construct() {
		parent::WP_Widget( false, $name = __( 'Inbox Status – Email Count', 'inbox-status' ) );
	}

	/**
	 * Load widget HTML from WordPress Theme inbox-status-theme directory,
	 * or templates/inbox-status-theme/widget-email-count.php
	 */
	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$inbox = IS_Inbox_Status::get_instance();

		$template_args = array_merge( $args, $instance );
		$template_args['inbox'] = $inbox;

		IS_Inbox_Status::get_template( 'widget-email-count', $template_args );
			
	}

	/**
	 * Load form HTML from templates/admin/widget-email-count-form.php
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$template_args = array( 'widget' => $this, 'instance' => $instance, );
		IS_Inbox_Status::get_template( 'widget-email-count-form', $template_args );
	}

}