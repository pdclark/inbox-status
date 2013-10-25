<?php $i = 0; ?>
<?php foreach ( $choices as $value => $label ) : ?>

	<input class="radio <?php echo $class ?>" type="radio" name="<?php echo $this->option_key . "[$id]" ?>" id="<?php echo $id . $i ?>" value="<?php esc_attr_e( $value ) ?>" <?php checked( $options[$id], $value, false ) ?> >
	<label for="<?php echo $id . $i ?>"><?php echo $label ?></label>

	<?php if ( $i < count( $options ) - 1 ) : $i++ ?>
		<br />
	<?php endif; ?>

<?php endforeach; ?>

<?php if ( $description ): ?>
	<br /><span class="description"><?php echo $description ?></span>
<?php endif; ?>