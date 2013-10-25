<?php

/**
 * @author Ben Lobaugh <http://ben.lobaugh.net>
 */
class IS_Email_Widget extends WP_Widget {

	var $defaults = array(
		'title' => '',
	);

	public function __construct() {
		parent::WP_Widget( false, $name = __( 'Email Count', 'inbox-status' ) );
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

		$inbox = IS_Inbox_Status::get_instance();

		echo $args['before_widget'];
		
		echo $args['before_title'];
		echo $instance['title'];
		echo $args['after_title'];

		echo '<p>' . $inbox->unread_count . ' ' . __( 'unread emails', 'inbox-status' ) . '<br/>';
		echo $inbox->total_count . ' ' . __( 'total emails', 'inbox-status' ) . '</p>';

		echo $args['after_widget'];
			
	}

	public function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->defaults );

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

}