<?php
/*
Plugin Name: Inbox Status 
Plugin URI: https://github.com/brainstormmedia/inbox-status
Description: Easy widget and functions to show email counts in your theme (like unread email).
Version: 1.0
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
define( 'IS_PLUGIN_VERSION', '1.0' );

/**
 * @var string Absolute path to this file.
 */
define( 'IS_PLUGIN_FILE', __FILE__ );

/**
 * @var string Absolute path to the root plugin directory
 */
define( 'IS_PLUGIN_DIR', dirname( __FILE__ ) );

/**
 * Used for localization text-domain, which must match wp.org slug.
 * Used for wp-admin settings page slug.
 * 
 * @var string Slug of the plugin on wordpress.org.
 */
define( 'IS_PLUGIN_SLUG', 'inbox-status' );

/**
 * Used for error messages.
 * Used for settings page title.
 * 
 * @var string Nice name of the plugin.
 */
define( 'IS_PLUGIN_NAME', __( 'Inbox Status', IS_PLUGIN_SLUG ) );

// Add PEAR directory to include path
set_include_path( IS_PLUGIN_DIR . '/classes/PEAR' . PATH_SEPARATOR . get_include_path() );

/**
 * Verify that we're running WordPress 3.2 (which enforces PHP 5.2.4).
 */
if ( version_compare( $GLOBALS['wp_version'], '3.2', '>=' ) ) :

	// Load plugin classes and instantiate the plugin.
	if ( !class_exists( 'Net_IMAP' ) ) {
		require_once IS_PLUGIN_DIR . '/classes/PEAR/Net/IMAP.php';
	}
	require_once IS_PLUGIN_DIR . '/classes/class-email-widget.php';
	require_once IS_PLUGIN_DIR . '/classes/class-shortcodes.php';
	require_once IS_PLUGIN_DIR . '/classes/class-nav-menus.php';
	require_once IS_PLUGIN_DIR . '/classes/class-inbox-status.php';

	add_action( 'plugins_loaded', 'IS_Inbox_Status::get_instance' );

endif;