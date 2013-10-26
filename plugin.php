<?php
/*
	Plugin Name: Inbox Status 
	Plugin URI: https://github.com/brainstormmedia/inbox-status
	Description: Easy widget and functions to show email counts in your theme (like unread email).
	Version: 0.1
	Author: Brainstorm Media
	Author URI: http://brainstormmedia.com
*/

/**
 * Used for cache-busting on JavaScript and CSS files.
 * 
 * @var string Version number of the plugin.
 */
define( 'IS_PLUGIN_VERSION', '0.1' );

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
 * Load plugin dependencies and instantiate the plugin.
 * Checks PHP version. Deactivates plugin and links to instructions if running PHP 4.
 */
function storm_inbox_status_init() {
	
	// PHP Version Check
	$php_is_outdated = version_compare( PHP_VERSION, '5.2', '<' );

	// Only exit and warn if on admin page
	$okay_to_exit = is_admin() && ( !defined('DOING_AJAX') || !DOING_AJAX );
	
	if ( $php_is_outdated ) {
		if ( $okay_to_exit ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
			deactivate_plugins( __FILE__ );
			wp_die( sprintf( __(
				'%s requires PHP 5.2 or higher, as does WordPress 3.2 and higher. The plugin has now disabled itself. For information on upgrading, %ssee this article%s.', IS_PLUGIN_SLUG ),
				IS_PLUGIN_NAME,
				'<a href="http://codex.wordpress.org/Switching_to_PHP5" target="_blank">',
				'</a>'
			) );
		} else {
			return;
		}
	}

	if ( !class_exists( 'Net_IMAP' ) ) {
		require_once IS_PLUGIN_DIR . '/classes/PEAR/Net/IMAP.php';
	}
	require_once IS_PLUGIN_DIR . '/classes/class-email-widget.php';
	require_once IS_PLUGIN_DIR . '/classes/class-shortcodes.php';
	require_once IS_PLUGIN_DIR . '/classes/class-inbox-status.php';

	IS_Inbox_Status::get_instance();

}

add_action( 'plugins_loaded', 'storm_inbox_status_init' );