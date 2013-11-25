<?php
/**
 * Base class for plugin admin functions.
 */
class IS_Admin {

	/**
	 * @var array All sections
	 */
	var $sections;

	/**
	 * @var array All settings
	 */
	var $settings;

	/**
	 * @var array Notices & errors to display to user.
	 */
	public $notices = array();
	
	function __construct() {

		$this->sections_init(); 
		$this->settings_init(); 
		
		add_action( 'admin_init', array( $this, 'request_setup' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		add_action( 'load-settings_page_inbox-status', array( $this, 'load_settings_page_inbox_status' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}

	public function request_setup() {
		if ( isset( $_GET['page'] ) && 'inbox-status' == $_GET['page'] ) {
			return false;
		}

		$inbox = IS_Inbox_Status::get_instance();

		if ( !$inbox->have_credentials() ) {
			$this->notices[] = sprintf(
				__( 'Please %senter your IMAP credentials%s to use Inbox Status.', 'inbox-status' ),
				'<a href="' . admin_url( 'options-general.php?page=inbox-status' ) . '" >',
				'</a>'
			);
		}
	}

	/**
	 * Populate $this->sections with all section arguements
	 * 
	 * @return void
	 */
	public function sections_init() {
		$this->sections = array(
			'server' => __( 'Email Provider', 'inbox-status' ),
			'login' => __( 'Login Credentials', 'inbox-status' ),
		);
	}

	/**
	 * Populate $this->settings with all settings arguements
	 * 
	 * @return void
	 */
	public function settings_init() {
		$this->settings = array(

			'provider' => array(
				'title'       => __( 'Email provider', 'inbox-status' ),
				'description' => sprintf( 
						__( 'Select your email host to auto-fill server information. %sAdvanced settings%s.', 'inbox-status' ),
						'<br/><a href="#" class="toggle-advanced">',
						'</a>'
				),
				'default'     => '',
				'type'        => 'select',
				'section'     => 'server',
				'choices'     => array(
					'Gmail' => json_encode( array(
						'imap_server' => 'imap.gmail.com',
						'port' => 993, 'tls' => 1,
					) ),
					'Outlook' => json_encode( array(
						'imap_server' => 'imap-mail.outlook.com',
						'port' => 993, 'tls' => 1,
					) ),
					'Yahoo' => json_encode( array(
						'imap_server' => 'imap.mail.yahoo.com',
						'port' => 993, 'tls' => 1,
					) ),
					'iCloud' => json_encode( array(
						'imap_server' => 'imap.mail.me.com',
						'port' => 993, 'tls' => 1,
					) ),
					'AOL' => json_encode( array(
						'imap_server' => 'imap.aol.com',
						'port' => 993, 'tls' => 1,
					) ),
					'Other' => json_encode( array(
						'imap_server' => '',
						'port' => 993, 'tls' => 1,
					) ),
				),
			),

			'imap_server' => array(
				'title'       => __( 'IMAP Server', 'inbox-status' ),
				'description' => __( 'For example, <code>imap.gmail.com</code>', 'inbox-status' ),
				'default'     => '',
				'type'        => 'input',
				'section'     => 'server',
				'class'       => 'advanced',
			),

			'port' => array(
				'title'       => __( 'Port', 'inbox-status' ),
				'description' => __( 'This should almost always be <code>993</code>', 'inbox-status' ),
				'default'     => '993',
				'type'        => 'input',
				'section'     => 'server',
				'class'       => 'advanced',
			),

			'tls' => array(
				'title'       => __( 'Require SSL/TLS', 'inbox-status' ),
				'description' => __( 'Secures your connection. This should almost always be enabled.', 'inbox-status' ),
				'default'     => true,
				'type'        => 'radio',
				'section'     => 'server',
				'class'       => 'advanced',
				'choices'     => array(
					'Require SSL/TLS (secure)' => 1,
					'Don\'t require (insecure)' => 0,
				),
			),

			'username' => array(
				'title'       => __( 'Username', 'inbox-status' ),
				'description' => __( 'For example, <code>awesomesauce</code> or <code>awesomesauce@gmail.com</code>', 'inbox-status' ),
				'default'     => '',
				'type'        => 'input',
				'section'     => 'login',
			),

			'password' => array(
				'title'       => __( 'Password', 'inbox-status' ),
				'description' => __( 'Password for your e-mail account.<br/> If using Gmail 2-step verification, use an <a href="https://support.google.com/accounts/answer/185833" target="_blank">application specific password</a>.', 'inbox-status' ),
				'default'     => '',
				'type'        => 'password',
				'section'     => 'login',
			),
		);
	}

	public function admin_enqueue_scripts( $page ) {

		if ( 'settings_page_inbox-status' == $page ) {

			wp_enqueue_script( 'is-admin-options', plugins_url( 'js/admin-options.js', IS_PLUGIN_FILE ), array( 'jquery' ), IS_PLUGIN_VERSION, true );

		}

	}

	public function admin_menu() {
		
		add_options_page(
			IS_PLUGIN_NAME,                 // Page title
			IS_PLUGIN_NAME,                 // Menu title
			'manage_options',               // Capability
			'inbox-status',                 // Menu slug
			array( $this, 'admin_options' ) // Page display callback
		);

	}

	/**
	 * Run scripts on the admin settings page
	 * Test & refresh the imap connection
	 * 
	 * @param  string $page Current page slug
	 * @return void
	 */
	function load_settings_page_inbox_status() {
		$inbox = IS_Inbox_Status::get_instance();
		$inbox->update_inbox();
	}

	/**
	 * Output the options page view.
	 * 
	 * @return null Outputs views/licenses.php and exits.
	 */
	function admin_options() {
		$inbox = IS_Inbox_Status::get_instance();
		$shortcodes = $inbox->shortcodes->shortcodes;

		IS_Inbox_Status::get_template( 'admin-options', array( 'shortcodes' => $shortcodes ) );
	}

	/**
	* Register settings
	*/
	public function register_settings() {
		
		register_setting( 'inbox-status', IS_Inbox_Status::OPTION_KEY, array ( $this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			add_settings_section(
				$slug,
				$title,
				null, // Section display callback
				'inbox-status'
			);
		}
		
		foreach ( $this->settings as $id => $setting ) {
			$setting['id'] = $id;
			$this->create_setting( $setting );
		}
		
	}

	/**
	 * Create settings field
	 *
	 * @since 1.0
	 */
	public function create_setting( $args = array() ) {
		
		$defaults = array(
			'id'          => 'default_field',
			'title'       => __( 'Default Field', 'inbox-status' ),
			'description' => __( 'Default description.', 'inbox-status' ),
			'default'     => '',
			'type'        => 'text',
			'section'     => 'general',
			'choices'     => array(),
			'class'       => ''
		);
			
		extract( wp_parse_args( $args, $defaults ) );
		
		$field_args = array(
			'type'        => $type,
			'id'          => $id,
			'description' => $description,
			'default'     => $default,
			'choices'     => $choices,
			'label_for'   => $id,
			'class'       => $class
		);
		
		add_settings_field(
			$id,
			$title,
			array( $this, 'display_setting' ),
			IS_Inbox_Status::OPTION_KEY,
			$section,
			$field_args
		);
	}

	/**
	 * Load view for setting, passing arguments
	 */
	public function display_setting( $args = array() ) {
		
		$options = get_option( IS_Inbox_Status::OPTION_KEY );
		
		if ( !isset( $options[$id] ) ) {
			$options[$id] = $default;
		}

		$id = $args['id'];
		$args['option_value'] = $options[ $id ];
		$args['option_name'] = IS_Inbox_Status::OPTION_KEY . '[' . $id . ']';

		$template = 'setting-' . $args['type'];

		IS_Inbox_Status::get_template( $template, $args );
		
	}

	/**
	* Validate settings
	*/
	public function validate_settings( $input ) {

		// Provider
		$provider = $input['provider'];

		// IMAP server
		if ( false === strpos( $input['imap_server'], 'http') ) {
			$input['imap_server'] = 'http://' . $input['imap_server'];
		}
		$imap_server = parse_url( $input['imap_server'], PHP_URL_HOST );
		if ( false === $imap_server ) {
			$this->notices[] = __( 'Please enter a valid address for the IMAP server.', 'inbox_status' );
		}

		// Port
		$port = ( !empty( $input['port'] ) ) ? (int) $input['port'] : 993;

		// TLS
		$tls = (int) $input['tls'];

		$username = sanitize_text_field( $input['username'] );

		$password = $input['password'];

		return compact( 'provider', 'imap_server', 'port', 'tls', 'username', 'password' );
	}

	/**
	 * Output all notices that have been added to the $this->notices array
	 */
	public function admin_notices() {
		foreach( $this->notices as $key => $message ) {
			echo "<div class='updated fade' id='inbox-status-$key'><p>$message</p></div>";
		}
	}

}