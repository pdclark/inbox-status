<p>
	<label for="<?php echo $widget->get_field_id( 'title' ); ?>">
		<?php _e( 'Title:', IS_PLUGIN_SLUG ); ?>
	</label> 
	<input class="widefat" 
		id="<?php echo $widget->get_field_id( 'title' ); ?>" 
		name="<?php echo $widget->get_field_name( 'title' ); ?>"
		type="text"
		value="<?php echo esc_attr( $instance['title'] ); ?>"
	/>
</p>