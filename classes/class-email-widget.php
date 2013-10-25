<?php

/**
 * @author Ben Lobaugh <http://ben.lobaugh.net>
 */
class IS_Email_Widget extends WP_Widget {

	public function __construct() {
		parent::WP_Widget( false, $name = __( 'Email Count', 'blob_email_count' ) );
	}

	public function widget( $args, $instance ) {
		$defaults = array(
			'server' => '',
			'user' => '',
			'title' => '',
			'password' => '',
		);
		
		$instance = wp_parse_args( $instance, $defaults );

		$inbox = IS_Inbox_Status::get_instance();

		$e = new IS_IMAP();
		$e->create_connection(
			$inbox->get_option( 'imap_server' ),
			$inbox->get_option( 'username' ),
			$inbox->get_option( 'password' )
		);

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
			'password' => '',
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
		<?php 
	}

} // end class