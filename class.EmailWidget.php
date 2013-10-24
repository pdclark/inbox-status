<?php

class BlobEmailWidget extends WP_Widget {

	public function __construct() {
	    parent::WP_Widget( false, $name = __( 'Email Count', 'blob_email_count' ) );
	}

        public function widget( $args, $instance ) {
	    $defaults = array(
		'server' => '',
		'user' => '',
		'title' => '',
		'pass' => '',
	    );
	    $instance = wp_parse_args( $instance, $defaults );

	    $e = BlobImap();
	    $e->create_connection( $instance['server'], $instance['user'], $instance['pass'] );


	    echo $args['before_widget'];
	    
	    echo $args['before_title'];
	    echo $instance['title'];
	    echo $args['after_title'];

	    echo "<p>";
	    echo $e->count_unread() . '/' . $e->count_all();
	    echo "<br/>Unread/All";
	    echo "</p>";

	    echo $args['after_widget'];
	    
	}

	public function form( $instance ) {
	    $defaults = array(
		'server' => '',
		'user' => '',
		'title' => '',
		'pass' => '',
	    );

	    $instance = wp_parse_args( $instance, $defaults );

	    ?>
	    <p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" 
		    id="<?php echo $this->get_field_id( 'title' ); ?>" 
		    name="<?php echo $this->get_field_name( 'title' ); ?>"
		    type="text"
		    value="<?php echo esc_attr( $instance['title'] ); ?>"
		/>
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'server' ); ?>"><?php _e( 'Server:' );?></label>
		<input class="widefat"
			name="<?php echo $this->get_field_name( 'server' ); ?>"
			type="text"
			value="<?php echo esc_attr( $instance['server'] ); ?>"
		/>
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'user' ); ?>"><?php _e( 'User:' );?></label>
		<input class="widefat"
			name="<?php echo $this->get_field_name( 'user' ); ?>"
			type="text"
			value="<?php echo esc_attr( $instance['user'] ); ?>"
		/>
	    </p>
	    <p>
		<label for="<?php echo $this->get_field_id( 'pass' ); ?>"><?php _e( 'Pass:' );?></label>
		<input class="widefat"
			name="<?php echo $this->get_field_name( 'pass' ); ?>"
			type="text"
			value="<?php echo esc_attr( $instance['pass'] ); ?>"
		/>
	    </p>
	    <?php 
	}

} // end class

add_action( 'widgets_init', function(){
         register_widget( 'BlobEmailWidget' );
});
