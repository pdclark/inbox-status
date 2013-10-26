<?php echo $before_title ?>
<?php echo $title ?>
<?php echo $after_title ?>

<ul>
	<li class="unread-count">
		<span><?php echo $inbox->unread_count ?></span>
		<?php _e( 'unread emails', IS_PLUGIN_SLUG ) ?>
	</li>
	<li class="total-count">
		<span><?php echo $inbox->total_count ?></span>
		<?php _e( 'total emails', IS_PLUGIN_SLUG ) ?>
	</li>
</ul>
