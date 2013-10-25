<?php

/**
 * Controller for the plugin.
 * 
 * @author Paul Clark <http://brainstormmedia.com>
 */
class IS_Inbox_Status {

	/**
	 * @var Storm_Inbox_Status Instance of the class.
	 */
	private static $instance = false;

	/**
	 * @var array Options from wp_options key inbox-status
	 */
	protected $options;

	/**
	 * @var IS_Admin Admin class
	 */
	protected $admin;

	/**
	 * Don't access directly. Use $this->get_imap() instead.
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

		// Todo: Move option_key from IS_Admin to this class and remove duplicate string
		$this->options = get_option( 'inbox-status' );

		add_action( 'wp_ajax_unread-gmail-count', array( $this, 'wp_ajax_unread_gmail_count' ) );
		add_action( 'wp_ajax_nopriv_unread-gmail-count', array( $this, 'wp_ajax_unread_gmail_count' ) );
		
		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );

		// Widgets
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

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
			'admin_file' => INBOX_STATUS_DIR . "/templates/admin/$file.php",
			'theme_file' => get_stylesheet_directory() . "/inbox-status-theme/$file.php",
			'plugin_file' => INBOX_STATUS_DIR . "/templates/inbox-status-theme/$file.php",
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
		// wp_enqueue_script( 'gmail-unread-count', plugins_url( 'unread-count.js', __FILE__ ), array( 'jquery' ), INBOX_STATUS_VERSION, true );
	}

	public function wp_ajax_unread_gmail_count() {
		exit( $this->get_unread_count() );
	}

	/**
	 * @return bool Whether username and password have been filled out in settings.
	 */
	public function have_credentials() {
		// Todo: Add notice linking to settings; requesting setup.

		if ( false === $this->get_option('username') || false === $this->get_option('password') ) {
			return false;
		}

		return true;
	}

	/**
	 * @return int $unread Number of unread emails.
	 */
	public function get_unread_count() {
		if ( !$this->have_credentials() ) { return false; }

		$transient_key = 'inbox-status-unread-count';
		$transient_timeout = 60 * 15; // 15 minutes

		// Check cache
		if ( false !== get_transient( $transient_key ) ) {
			return get_transient( $transient_key );
		}

		$imap = $this->get_imap();
		$unread = $imap->getNumberOfUnSeenMessages();

		// Set cache
		set_transient( $transient_key, $unread, $transient_timeout );

		return $unread;
	}

	/**
	 * @return int $total Total number of emails, read or unread.
	 */
	public function get_total_count() {
		if ( !$this->have_credentials() ) { return false; }

		$transient_key = 'inbox-status-total-count';
		$transient_timeout = 60 * 15; // 15 minutes

		// Check cache
		if ( false !== get_transient( $transient_key ) ) {
			return get_transient( $transient_key );
		}

		$imap = $this->get_imap();
		$total = $imap->getNumberOfMessages();

		// Set cache
		set_transient( $transient_key, $total, $transient_timeout );

		return $total;
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
			993,  // Port
			true // TLS
		);

		$this->imap->login( $this->get_option( 'username' ), $this->get_option( 'password' ) );

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

}