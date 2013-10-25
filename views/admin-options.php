<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( $this->options_page_name, 'inbox-status' ); ?></h2>

	<form name="is_options" method="post" action="options.php">

		<?php settings_fields( $this->options_page_slug ); ?>
		<?php do_settings_sections( $this->options_page_slug ); ?>
		<?php submit_button( 'Save' ); ?>
	
	</form>

</div>