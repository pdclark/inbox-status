<?php

/**
 * Controller for the plugin.
 * 
 * @author Paul Clark <http://brainstormmedia.com>
 */
class IS_Inbox_Status {

	/**
	 * @var IS_Inbox_Status Instance of this class.
	 */
	private static $instance = false;

	/**
	 * @var string Key for plugin options in wp_options table
	 */
	const OPTION_KEY = 'inbox-status';

	/**
	 * @var int How often should inbox data be updated, in seconds.
	 */
	protected $update_interval;

	/**
	 * @var array Options from wp_options
	 */
	protected $options;

	/**
	 * @var IS_Admin Admin object
	 */
	protected $admin;

	/**
	 * @var IS_Shortcodes Shortcodes object
	 */
	protected $shortcodes;

	/**
	 * @var IS_Nav_Menu Navigation Menus object
	 */
	protected $nav_menus;

	/**
	 * Don't access directly within this class.
	 * Use $this->get_imap() instead.
	 * 
	 * @var Net_IMAP Pear IMAP library. Does not require PHP IMAP extension.
	 */
	protected $imap;
	
	/**
	 * Don't use this. Use ::get_instance() instead.
	 */
	public function __construct() {
		if ( !self::$instance ) {
			$message = '<code>' . __CLASS__ . '</code> is a singleton.<br/> Please get an instantiate it with <code>' . __CLASS__ . '::get_instance();</code>';
			wp_die( $message );
		}       
	}

	/**
	 * If a variable is accessed from outside the class,
	 * return a value from method get_$var()
	 * 
	 * For example, $inbox->unread_count returns $inbox->get_unread_count()
	 * 
	 * @return pretty-much-anything
	 */
	public function __get( $var ) {
		$method = 'get_' . $var;

		if ( method_exists( $this, $method ) ) {
			return $this->$method();
		}else {
			return $this->$var;
		}
	}
	
	public static function get_instance() {
		if ( !is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = true;
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}
	
	/**
	 * Initial setup. Called by get_instance.
	 */
	protected function init() {

		$this->options = get_option( self::OPTION_KEY );

		// Filter allows inbox stats to be updated more or less frequently.
		// Default is 15 minutes
		$this->update_interval = apply_filters( 'inbox_status_update_interval', 60*15 );

		add_action( 'wp_ajax_inbox-status-update-inbox', array( $this, 'wp_ajax_update_inbox' ) );
		add_action( 'wp_ajax_nopriv_inbox-status-update-inbox', array( $this, 'wp_ajax_update_inbox' ) );
		
		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );

		// Widgets
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		// Custom Actions (for use in themes)
		add_action( 'inbox_status_unread_count', array( $this, 'unread_count' ) );
		add_action( 'inbox_status_total_count', array( $this, 'total_count' ) );

		// Shortcodes
		$this->shortcodes = new IS_Shortcodes();

		// Navigation menu items
		$this->nav_menus = new IS_Nav_Menus();

		// Admin
		if ( is_admin() ) { $this->get_admin(); }

	}

	public function get_option( $key ) {
		if ( isset( $this->options[ $key ] ) ) {
			return $this->options[ $key ];
		}else {
			return false;
		}
	}

	/**
	 * Load HTML template from templates directory.
	 * Contents of $args are turned into variables for use in the template.
	 * 
	 * For example, $args = array( 'foo' => 'bar' );
	 *   becomes variable $foo with value 'bar'
	 */
	public static function get_template( $file, $args = array() ) {
		extract( $args );

		$locations = array(
			'admin_file' => dirname( IS_PLUGIN_FILE ) . "/templates/admin/$file.php",
			'theme_file' => get_stylesheet_directory() . "/inbox-status-theme/$file.php",
			'plugin_file' => dirname( IS_PLUGIN_FILE ) . "/templates/inbox-status-theme/$file.php",
		);

		foreach ( $locations as $file ) {
			if ( file_exists( $file ) ) {
				include $file;
				return;
			}
		}
	}

	public function widgets_init() {
		register_widget( 'IS_Email_Widget' );
	}

	public function wp_print_scripts() {
		wp_enqueue_script( 'is-update-inbox', plugins_url( 'js/update-inbox.js', IS_PLUGIN_FILE ), array( 'jquery' ), IS_PLUGIN_VERSION, true );

		// Pass PHP variables to update script
		wp_localize_script( 'is-update-inbox', 'is_inbox_status', array(
			'last_updated' => $this->get_option( 'last-updated' ),
			'current_time' => time(), // Use PHP time to avoid timezone mismatch
			'update_interval' => $this->update_interval,
			'ajax_url' => admin_url( 'admin-ajax.php?action=inbox-status-update-inbox' ),
		) );
	}

	/**
	 * Respond to AJAX request to update the inbox stats.
	 * Using AJAX avoids slowing down or crashing the site during imap connections.
	 */
	public function wp_ajax_update_inbox() {
		
		$success = $this->update_inbox();

		$response = array(
			'success' => (bool) $success,
			'unread_count' => $this->get_option('unread-count'),
			'total_count' => $this->get_option('total-count'),
			'notices' => $this->admin->notices,
		);

		echo json_encode( $response );

		exit;
	}

	/**
	 * @return bool Whether username and password have been filled out in settings.
	 */
	public function have_credentials() {
		if ( false === $this->get_option('username') || false === $this->get_option('password') ) {
			return false;
		}

		return true;
	}

	/**
	 * Update cache in options with latest inbox data.
	 * 
	 * @return bool Whether update succeeded or not.
	 */
	public function update_inbox() {
		if ( !$this->have_credentials() ) {
			return false;
		}

		// Notices of failure set in get_imap >> pear_error_to_nice_error
		$imap = $this->get_imap();

		if ( false === $imap ) {
			$this->options['unread-count'] = 0;
			$this->options['total-count']  = 0;
			$this->options['last-updated'] = time();
			return false;
		}

		$this->options['unread-count'] = $imap->getNumberOfUnSeenMessages();
		$this->options['total-count']  = $imap->getNumberOfMessages();
		$this->options['last-updated'] = time();

		$this->notice( __('Connection successful!', 'inbox-status' ) );

		// Update cache.
		return update_option( self::OPTION_KEY, $this->options );
	}

	public function unread_count() {
		echo $this->get_unread_count();
	}

	/**
	 * @return int $unread Number of unread emails.
	 */
	public function get_unread_count() {
		// Check cache
		if ( false !== $this->get_option( 'unread-count' ) ) {
			return $this->get_option( 'unread-count' );
		}

		// Nothing cached. Probably a first run, so query and cache.
		$this->update_inbox();

		return $this->get_option( 'unread-count' );
	}

	public function total_count() {
		echo $this->get_total_count();
	}

	/**
	 * @return int $total Total number of emails, read or unread.
	 */
	public function get_total_count() {
		// Check cache
		if ( false !== $this->get_option( 'total-count' ) ) {
			return $this->get_option( 'total-count' );
		}

		// Nothing cached. Probably a first run, so query and cache.
		$this->update_inbox();

		return $this->get_option( 'total-count' );
	}

	/**
	 * @return Net_IMAP Conneted and authenticated IMAP object.
	 */
	public function get_imap() {
		if ( !$this->have_credentials() ) { return false; }

		if ( is_a( $this->imap, 'Net_IMAP' ) ) {
			return $this->imap;
		}

		$this->imap = new Net_IMAP(
			$this->get_option( 'imap_server' ),
			$this->get_option( 'port' ),
			$this->get_option( 'tls' )
		);

		$login = $this->imap->login( $this->get_option( 'username' ), $this->get_option( 'password' ) );

		if ( is_a( $login, 'PEAR_Error' ) ) {
			$this->pear_error_to_nice_error( $login );
			return false;
		}

		return $this->imap;
	}

	/**
	 * @return IS_Admin Object for admin interface.
	 */
	public function get_admin() {
		if ( is_a( $this->admin, 'IS_Admin') ) {
			return $this->admin;
		}

		require_once dirname ( __FILE__ ) . '/class-admin.php';
		$this->admin = new IS_Admin();

		return $this->admin;
	}

	public function notice( $message ) {
		$admin = $this->get_admin();
		$admin->notices[] = $message;
	}

	/**
	 * Convert cryptic PEAR IMAP errors to notices that might not scare people.
	 * Add messages to notices
	 * 
	 * @param  PEAR_Error $login Error object
	 * @return void
	 */
	public function pear_error_to_nice_error( $login ) {
		switch ( $login->message ) {
			case 'NO, [AUTHENTICATIONFAILED] Invalid credentials (Failure)': // Gmail
			case 'NO, Invalid username or password.': // Outlook
			case 'NO, [AUTHORIZATIONFAILED] Incorrect username or password. (#MBR1212)': // Yahoo
			case 'NO, [AUTHENTICATIONFAILED] (#AUTH012) Incorrect username or password.': // Yahoo
			case 'NO, [AUTHENTICATIONFAILED] Authentication failed': // iCloud
			case 'NO, Invalid login or password': // AOL
				$this->notice( __( 'Authentication failed. Please check your username and password.', 'inbox-status' ) );
				break;
			case 'not connected! (CMD:LOGIN)':
				$this->notice( __( 'Could not connect to IMAP server. Please verify the server address and port are correct.', 'inbox-status' ) );
				break;
			default:
				$this->notice( $login->message );
				break;
		}
	}

}