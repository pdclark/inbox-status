<?php 
	$link_text = sprintf( __( '%s unread emails', IS_PLUGIN_SLUG ), '[unread-emails]' );
?>

<div class="<?php echo IS_PLUGIN_SLUG ?>-div" id="<?php echo IS_PLUGIN_SLUG ?>-div">
	<input type="hidden" value="custom" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" />

	<p id="menu-item-template-wrap">
		<label class="howto" for="<?php echo IS_PLUGIN_SLUG ?>-template">
			<span><?php _e( 'Template', IS_PLUGIN_SLUG ); ?></span>

			<select
				id="<?php echo IS_PLUGIN_SLUG ?>-template"
				name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-template]"
				style="float:right;width:180px;"
			>
				<option value="[inbox-unread] <?php _e('unread emails', IS_PLUGIN_SLUG ) ?>" selected><?php _e('Unread emails', IS_PLUGIN_SLUG ) ?></option>
				<option value="[inbox-total] <?php _e('total emails', IS_PLUGIN_SLUG ) ?>"><?php _e('Total emails', IS_PLUGIN_SLUG ) ?></option>
			</select>
		</label>

		<br style="clear:both;" />
	</p>

	<p id="menu-item-name-wrap">
		<label class="howto" for="<?php echo IS_PLUGIN_SLUG ?>-name">
			<span><?php _e( 'Text' ); ?></span>
			<input 
				id="<?php echo IS_PLUGIN_SLUG ?>-name"
				name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]"
				type="text" class="regular-text menu-item-textbox"
				value="<?php echo $link_text ?>"
			/>
		</label>
	</p>

	<p id="menu-item-url-wrap">
		<label class="howto" for="<?php echo IS_PLUGIN_SLUG ?>-url">
			<span><?php _e('URL'); ?></span>
			<input
				id="<?php echo IS_PLUGIN_SLUG ?>-url"
				name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-url]"
				type="text"
				class="code menu-item-textbox input-with-default-title"
				title="<?php esc_attr_e('Optional'); ?>"
			/>
		</label>
	</p>

	<p class="button-controls">
		<span class="add-to-menu">
			<input
				type="submit"<?php wp_nav_menu_disabled_check( $nav_menu_selected_id ); ?>
				class="button-secondary submit-add-to-menu right"
				value="<?php esc_attr_e('Add to Menu'); ?>"
				name="add-<?php echo IS_PLUGIN_SLUG ?>"
				id="submit-<?php echo IS_PLUGIN_SLUG ?>-div"
			/>
			<span class="spinner"></span>
		</span>
	</p>

</div>