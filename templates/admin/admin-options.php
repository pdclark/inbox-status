<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( IS_PLUGIN_NAME, IS_PLUGIN_SLUG ); ?></h2>

	<form name="is_options" method="post" action="options.php">

		<?php settings_fields( IS_PLUGIN_SLUG ); ?>
		<?php do_settings_sections( IS_PLUGIN_SLUG ); ?>
		<?php submit_button( 'Save' ); ?>
	
	</form>

</div>