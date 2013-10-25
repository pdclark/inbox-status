<?php echo $before_title ?>
<?php echo $title ?>
<?php echo $after_title ?>

<ul>
	<li class="unread-count">
		<span><?php echo $inbox->unread_count ?></span>
		<?php _e( 'unread emails', 'inbox-status' ) ?>
	</li>
	<li class="total-count">
		<span><?php echo $inbox->total_count ?></span>
		<?php _e( 'total emails', 'inbox-status' ) ?>
	</li>
</ul>
