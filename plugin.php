<?php
/*
Plugin Name: Inbox Status 
Plugin URI: https://github.com/brainstormmedia/inbox-status
Description: Easy widget and functions to show email counts in your theme (like unread email).
Version: 1.1.3
Author: Brainstorm Media
Author URI: http://brainstormmedia.com
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/**
 * Used for cache-busting on JavaScript and CSS files.
 * 
 * @var string Version number of the plugin.
 */
define( 'IS_PLUGIN_VERSION', '1.1.3' );

/**
 * @var string Absolute path to this file.
 */
define( 'IS_PLUGIN_FILE', __FILE__ );

/**
 * Used for error messages.
 * Used for settings page title.
 * 
 * @var string Nice name of the plugin.
 */
define( 'IS_PLUGIN_NAME', __( 'Inbox Status', 'inbox-status' ) );

// Add PEAR directory to include path
set_include_path( dirname( IS_PLUGIN_FILE ) . '/classes/PEAR' . PATH_SEPARATOR . get_include_path() );

/**
 * Verify that we're running WordPress 3.2 (which enforces PHP 5.2.4).
 */
if ( version_compare( $GLOBALS['wp_version'], '3.2', '>=' ) ) :

	// Load plugin classes and instantiate the plugin.
	if ( !class_exists( 'Net_IMAP' ) ) {
		require_once dirname( IS_PLUGIN_FILE ) . '/classes/PEAR/Net/IMAP.php';
	}
	require_once dirname( IS_PLUGIN_FILE ) . '/classes/class-imap.php';
	require_once dirname( IS_PLUGIN_FILE ) . '/classes/class-email-widget.php';
	require_once dirname( IS_PLUGIN_FILE ) . '/classes/class-shortcodes.php';
	require_once dirname( IS_PLUGIN_FILE ) . '/classes/class-nav-menus.php';
	require_once dirname( IS_PLUGIN_FILE ) . '/classes/class-inbox-status.php';

	add_action( 'plugins_loaded', 'IS_Inbox_Status::get_instance' );

endif;