<select class="select <?php echo $class ?>" name="<?php echo $this->option_key . "[$id]" ?>">
	
	<?php foreach( $choices as $value => $label ) : ?>
		<option value="<?php esc_attr_e( $value ) ?>" <?php selected( $options[$id], $value, false ) ?> >
			<?php echo $label ?>
		</option>
	<?php endforeach; ?>

</select>

<?php if ( $description ): ?>
	<br /><span class="description"><?php echo $description ?></span>
<?php endif; ?>