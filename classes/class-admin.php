<?php
/**
 * Base class for plugin admin functions.
 */
class IS_Admin {

	var $options_page_name = 'Inbox Status';
	var $options_page_slug = 'inbox-status';  
	var $option_key = 'inbox-status'; 

	/**
	 * @var array All sections
	 */
	var $sections;

	/**
	 * @var array All settings
	 */
	var $settings;
	
	function __construct() {

		$this->sections_init(); 
		$this->settings_init(); 
		
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		
	}

	/**
	 * Populate $this->sections with all section arguements
	 * 
	 * @return void
	 */
	public function sections_init() {
		$this->sections = array(
			'login' => __( 'Login Info', 'inbox-status' ),
		);
	}

	/**
	 * Populate $this->settings with all settings arguements
	 * 
	 * @return void
	 */
	public function settings_init() {
		$this->settings = array(

			'imap_server' => array(
				'title'       => __( 'IMAP Server', 'inbox-status' ),
				'description' => __( 'For example, <code>imap.gmail.com</code>', 'inbox-status' ),
				'default'     => '',
				'type'        => 'input',
				'section'     => 'login',
			),

			'username' => array(
				'title'       => __( 'Username' ),
				'description' => __( 'For example, <code>awesomesauce</code> or <code>awesomesauce@gmail.com</code>', 'inbox-status' ),
				'default'     => '',
				'type'        => 'input',
				'section'     => 'login',
			),

			'password' => array(
				'title'       => __( 'Password' ),
				'description' => __( 'Password for you e-mail account.<br/> If using Gmail 2-step verification, use an <a href="https://support.google.com/accounts/answer/185833" target="_blank">application specific password</a>.', 'inbox-status' ),
				'default'     => '',
				'type'        => 'password',
				'section'     => 'login',
			),
		);
	}

	public function admin_menu() {
		
		add_options_page(
			$this->options_page_name,
			$this->options_page_name,
			'manage_options',
			$this->options_page_slug,
			array( $this, 'admin_options' )
		);

	}

	/**
	 * Output the options page view.
	 * 
	 * @return null Outputs views/licenses.php and exits.
	 */
	function admin_options() {
		$args = array(
			'options_page_name' => $this->options_page_name,
			'options_page_slug' => $this->options_page_slug,
		);

		IS_Inbox_Status::get_template( 'admin-options', $args );
	}

	/**
	* Register settings
	*/
	public function register_settings() {
		
		register_setting( $this->option_key, $this->option_key, array ( $this, 'validate_settings' ) );
		
		foreach ( $this->sections as $slug => $title ) {
			add_settings_section(
				$slug,
				$title,
				null, // Section display callback
				$this->option_key
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
			$this->option_key,
			$section,
			$field_args
		);
	}

	/**
	 * Load view for setting, passing arguments
	 */
	public function display_setting( $args = array() ) {
		
		$options = get_option( $this->option_key );
		
		if ( !isset( $options[$id] ) ) {
			$options[$id] = $default;
		}

		$id = $args['id'];
		$args['option_value'] = $options[ $id ];
		$args['option_name'] = $this->option_key . '[' . $id . ']';

		$template = 'setting-' . $args['type'];

		IS_Inbox_Status::get_template( $template, $args );
		
	}

	/**
	* Validate settings
	*/
	public function validate_settings( $input ) {
		if ( true ) {
			return $input;
		}

		return false;
	}

}