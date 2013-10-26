<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( $options_page_name, IS_PLUGIN_SLUG ); ?></h2>

	<form name="is_options" method="post" action="options.php">

		<?php settings_fields( $options_page_slug ); ?>
		<?php do_settings_sections( $options_page_slug ); ?>
		<?php submit_button( 'Save' ); ?>
	
	</form>

</div>