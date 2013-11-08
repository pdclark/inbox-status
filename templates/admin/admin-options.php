<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( IS_PLUGIN_NAME, 'inbox-status' ); ?></h2>

	<form name="is_options" method="post" action="options.php">

		<?php settings_fields( 'inbox-status' ); ?>
		<?php do_settings_sections( 'inbox-status' ); ?>
		<?php submit_button( 'Save' ); ?>
	
	</form>

	<h3>Usage</h3>

	<ul class="usage">
		<li><a href="<?php echo admin_url( 'widgets.php' ); ?>">Add the widget</a>.</li>
		<li><a href="<?php echo admin_url( 'nav-menus.php' ); ?>">Add a menu item</a>.</li>
		<li>Use <strong>shortcodes</strong> in your post content.</li>
	</ul>

	<h3>Shortcodes</h3>

	<p>For use in the WordPress post editor.</p>

	<table class="shortcodes">
		<thead>
			<tr>
				<th>Example</th>
				<th>Output</th>
			</tr>
		</thead>
		<tbody>
			
		<?php foreach ( $shortcodes as $key => $example ) : ?>

			<tr>
				<td><?php echo $example ?></td>
				<td><?php echo do_shortcode( $example ); ?></td>
			</tr>

		<?php endforeach; ?>

		</tbody>
	</table>

	<h3>Actions</h3>

	<p>For use in themes.</p>

	<table class="shortcodes">
		<thead>
			<tr>
				<th>Example</th>
				<th>Output</th>
			</tr>
		</thead>
		<tbody>
		
		<?php foreach ( $shortcodes as $type => $example ) : ?>
			
			<tr>
				<td><code>&lt;?php do_action( 'inbox_status_count', '<?php echo $type ?>' ); ?&gt;</code></td>
				<td><?php do_action( 'inbox_status_count', $type ); ?></td>
			</tr>
		

		<?php endforeach; ?>

		</tbody>
	</table>


</div>

<style>

.wrap > h3 { margin-top: 40px; }

.usage { list-style-type: disc; margin-left:20px; }

.shortcodes th { text-align: left; }
.shortcodes th, .shortcodes td { padding: 0 16px 16px 0; }

</style>