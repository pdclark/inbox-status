<?php
/*
  Plugin Name: Inbox Status 
  Plugin URI: https://github.com/brainstormmedia/inbox-status
  Description: Easy widget and functions to show email counts in your theme (like unread email).
  Version: 0.1
  Author: Brainstorm Media
  Author URI: http://brainstormmedia.com
*/

define( 'INBOX_STATUS_PLUGIN_FILE', __FILE__ );
define( 'INBOX_STATUS_VERSION', '0.1' );

add_action( 'plugins_loaded', 'storm_inbox_status_init' );

function storm_inbox_status_init() {
	
	// PHP Version Check
	$php_is_outdated = version_compare( PHP_VERSION, '5.2', '<' );

	// Only exit and warn if on admin page
	$okay_to_exit = is_admin() && ( !defined('DOING_AJAX') || !DOING_AJAX );
	
	if ( $php_is_outdated ) {
    if ( $okay_to_exit ) {
      require_once ABSPATH . '/wp-admin/includes/plugin.php';
      deactivate_plugins( __FILE__ );
      wp_die( sprintf( __( 'Inbox Status requires PHP 5.2 or higher, as does WordPress 3.2 and higher. The plugin has now disabled itself. For information on upgrading, %ssee this article%s.', 'menu-social-icons'), '<a href="http://codex.wordpress.org/Switching_to_PHP5" target="_blank">', '</a>') );
    } else {
      return;
    }
	}

	require_once dirname ( __FILE__ ) . '/classes/class-imap.php';
  require_once dirname ( __FILE__ ) . '/classes/class-email-widget.php';
	require_once dirname ( __FILE__ ) . '/classes/class-inbox-status.php';

  Storm_Inbox_Status::get_instance();

}
