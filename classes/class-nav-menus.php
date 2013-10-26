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

			wp_enqueue_script( 'is-nav-menus', plugins_url( 'js/nav-menus.js', IS_PLUGIN_FILE ), array( 'jquery' ), IS_VERSION, true );

			wp_localize_script( 'is-nav-menus', 'InboxStatusAdmin', array(
				'slug' => IS_PLUGIN_SLUG,
				'url_default' => __( 'Optional', IS_PLUGIN_SLUG ),
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

			$item = $this->maybe_add_email_icon( $item );

		}

		return $sorted_menu_items;
		
	}

	public function is_targeted_menu_item( $item ) {
		if ( in_array( IS_PLUGIN_SLUG, $item->classes ) ) {
			return true;
		}
		return false;
	}

	public function maybe_add_email_icon( $item ) {

		// Require filter to enable social icon integration
		// Users would have to know how to edit the CSS
		if ( apply_filters( 'inbox_status_add_social_icon', false ) ) {
			if ( !class_exists( 'MSI_Frontend' ) ) { return $item; }

			$msi = MSI_Frontend::get_instance();

			$item->title = $msi->get_icon( $msi->networks['mailto:'] ) . $item->title;
			$item->classes[] = $msi->li_class;
		}

		return $item;

	}

	/**
	 * Displays a metabox for adding menu items.
	 *
	 * @param string $object Not used.
	 */
	function meta_box( $object ) {
		global $_nav_menu_placeholder, $nav_menu_selected_id;

		$_nav_menu_placeholder = 0 > $_nav_menu_placeholder ? $_nav_menu_placeholder - 1 : -1;

		$template_args = compact( '_nav_menu_placeholder', 'nav_menu_selected_id' );
		IS_Inbox_Status::get_template( 'nav-menu-meta-box', $template_args );

	}

}