<textarea class="<?php echo $class ?>" id="<?php echo $id ?>" name="<?php echo $this->option_key . "[$id]" ?>" placeholder="<?php echo $default ?>" rows="5" cols="30"><?php wp_htmledit_pre( $options[$id] ) ?></textarea>

<?php if ( $description ): ?>
	<br /><span class="description"><?php echo $description ?></span>
<?php endif; ?>