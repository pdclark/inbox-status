<input class="regular-text <?php echo $class ?>" type="text" id="<?php echo $id ?>" name="<?php echo $this->option_key . "[$id]" ?>" placeholder="<?php echo $default ?>" value="<?php esc_attr_e( $options[$id] ) ?>" />

<?php if ( $description ): ?>
	<br /><span class="description"><?php echo $description ?></span>
<?php endif; ?>