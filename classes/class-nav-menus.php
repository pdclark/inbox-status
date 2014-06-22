<?php

class IS_Nav_Menus {

	public function __construct() {

		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 5, 2 );

	}

	public function wp_print_scripts() {
		?>
		<style>
			.is-prevent-hover { position:relative; }
			.is-prevent-hover:before { content: ""; width: 100%; height: 100%; display:block; z-index: 999; position:absolute;}
		</style>
		<?php
	}

	public function admin_enqueue_scripts( $page ) {

		if ( 'nav-menus.php' == $page ) {

			wp_enqueue_script( 'is-admin-nav-menus', plugins_url( 'js/admin-nav-menus.js', IS_PLUGIN_FILE ), array( 'jquery' ), IS_PLUGIN_VERSION, true );

			wp_localize_script( 'is-admin-nav-menus', 'InboxStatusAdmin', array(
				'url_default' => __( 'Optional', 'inbox-status' ),
			) );

			add_meta_box( 'add-inbox-status', IS_PLUGIN_NAME, array( $this, 'meta_box' ), 'nav-menus', 'side', 'default' );

		}

	}

	/**
	 * Process shortcodes in inbox-status menu items
	 */
	public function wp_nav_menu_objects( $sorted_menu_items, $args ){

		foreach( $sorted_menu_items as &$item ) { 
			if ( !$this->is_targeted_menu_item( $item ) ) { continue; }

			$item->title = do_shortcode( $item->title );

			// Prevent hover state on empty links
			if ( '#' == $item->url ) {
				$item->classes[] = 'is-prevent-hover';
			}

		}

		return $sorted_menu_items;
		
	}

	public function is_targeted_menu_item( $item ) {
		if ( in_array( 'inbox-status', (array) $item->classes ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Displays a metabox for adding menu items.
	 *
	 * @param string $object Not used.
	 */
	function meta_box( $object ) {
		global $_nav_menu_placeholder, $nav_menu_selected_id;
		$inbox = IS_Inbox_Status::get_instance();

		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

		$shortcodes = $inbox->shortcodes->shortcodes;

		$template_args = compact( 'shortcodes', '_nav_menu_placeholder', 'nav_menu_selected_id' );
		IS_Inbox_Status::get_template( 'nav-menu-meta-box', $template_args );

	}

}