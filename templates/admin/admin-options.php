<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( IS_PLUGIN_NAME, IS_PLUGIN_SLUG ); ?></h2>

	<form name="is_options" method="post" action="options.php">

		<?php settings_fields( IS_PLUGIN_SLUG ); ?>
		<?php do_settings_sections( IS_PLUGIN_SLUG ); ?>
		<?php submit_button( 'Save' ); ?>
	
	</form>

	<h3>Usage</h3>

	<ul class="usage">
		<li><a href="<?php echo admin_url( 'widgets.php' ); ?>">Add the widget</a>.</li>
		<li><a href="<?php echo admin_url( 'nav-menus.php' ); ?>">Add a menu item</a>.</li>
		<li>Use <strong>shortcodes</strong> in your post content.</li>
	</ul>

	<h3>Shortcodes</h3>

	<table class="shortcodes">
		<thead>
			<tr>
				<th>Shortcode</th>
				<th>Example</th>
				<th>Output</th>
			</tr>
		</thead>
		<tbody>
			
			<tr>
				<td><code>[inbox-unread]</code></td>
				<td>I have <code>[inbox-unread]</code> unread emails.</td>
				<td>I have <code><?php echo do_shortcode( '[inbox-unread]' ); ?></code> unread emails.</td>
			</tr>

			<tr>
				<td><code>[inbox-total]</code></td>
				<td>I have <code>[inbox-total]</code> total emails.</td>
				<td>I have <code><?php echo do_shortcode( '[inbox-total]' ); ?></code> total emails.</td>
			</tr>

		</tbody>
	</table>

</div>

<style>

.wrap > h3 { margin-top: 40px; }

.usage { list-style-type: disc; margin-left:20px; }

.shortcodes th { text-align: left; }
.shortcodes th, .shortcodes td { padding: 0 16px 16px 0; }

</style>