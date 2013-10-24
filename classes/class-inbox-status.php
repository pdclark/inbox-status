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
	 * @var string Gmail username
	 */
	private $username;

	/**
	 * @var string Gmail password
	 */
	private $password;

	/**
	 * @var string URL to Gmail atom feed
	 */
	private $atom_feed_url = 'https://mail.google.com/mail/feed/atom';
	
	/**
	 * Don't use this. Use ::get_instance() instead.
	 */
	public function __construct() {
		if ( !self::$instance ) {
			$message = '<code>' . __CLASS__ . '</code> is a singleton.<br/> Please get an instantiate it with <code>' . __CLASS__ . '::get_instance();</code>';
			wp_die( $message );
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

		$this->username = apply_filters( 'unread_gmail_username', '' );
		$this->password = apply_filters( 'unread_gmail_password', '' );

		add_action( 'wp_ajax_unread-gmail-count', array( $this, 'wp_ajax_unread_gmail_count' ) );
		add_action( 'wp_ajax_nopriv_unread-gmail-count', array( $this, 'wp_ajax_unread_gmail_count' ) );
		
		add_filter( 'http_request_args', array( $this, 'http_request_args' ), 10, 2 );

		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );

		// Widgets
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		
	}

	public function widgets_init() {
		register_widget( 'IS_Email_Widget' );
	}

	public function wp_print_scripts() {
		wp_enqueue_script( 'gmail-unread-count', plugins_url( 'unread-count.js', __FILE__ ), array( 'jquery' ), INBOX_STATUS_VERSION, true );
	}

	public function wp_ajax_unread_gmail_count() {
		exit( $this->get_unread_count() );
	}

	/**
	 * Fetch Gmail atom feed and return unread count
	 * 
	 * @return int $unread Unread email count
	 */
	public function get_unread_count() {
		if ( empty( $this->username ) || empty( $this->password ) ) {
			return false;
		}

		$transient_key = 'gmail-unread-count';
		$transient_timeout = 60 * 15; // 15 minutes

		// Check cache
		if ( false !== get_transient( $transient_key ) ) {
			return get_transient( $transient_key );
		}

		$response = wp_remote_get( $this->atom_feed_url );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$xml = simplexml_load_string( $response['body'] );

		$unread = (int) $xml->fullcount;

		// Set cache
		set_transient( $transient_key, $unread, $transient_timeout );

		return $unread;
	}

	/**
	 * Add username and password to Gmail request.
	 */
	public function http_request_args( $request, $url ) {
		if ( $this->atom_feed_url == $url ) {
			$request['headers'] = wp_parse_args( array(
				'Authorization' => 'Basic ' . base64_encode( $this->username . ':' . $this->password )
			), $request );
		}

		return $request;
	}
}