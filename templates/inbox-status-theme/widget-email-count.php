<?php echo $before_widget ?>

<?php echo $before_title ?>
<?php echo $title ?>
<?php echo $after_title ?>

<ul>
	<li class="is-unread-li">
		<span class="is-unread-count"><?php echo $inbox->unread_count ?></span>
		<?php _e( 'unread emails', 'inbox-status' ) ?>
	</li>
	<li class="is-total-li">
		<span class="is-total-count"><?php echo $inbox->total_count ?></span>
		<?php _e( 'total emails', 'inbox-status' ) ?>
	</li>
</ul>

<?php echo $after_widget ?>