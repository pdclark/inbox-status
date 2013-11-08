<?php 
	$link_text = sprintf( __( '%s unread emails', 'inbox-status' ), '[unread-emails]' );
?>

<div class="inbox-status-div" id="inbox-status-div">
	<input type="hidden" value="custom" name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-type]" />

	<p id="menu-item-template-wrap">
		<label class="howto">
			<span><?php _e( 'Template', 'inbox-status' ); ?></span>
		</label>

		<label style="float:right;width:180px;">
			<input type="radio" 
				class="inbox-status-template"
				name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-template]"
				value="[inbox-unread] <?php _e('unread emails', 'inbox-status' ) ?>"
				selected
			/>
			<?php _e('Unread emails', 'inbox-status' ) ?>
		</label>

		<label style="float:right;width:180px;clear:right;">
			<input type="radio" 
				class="inbox-status-template"
				name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-template]"
				value="[inbox-total] <?php _e('total emails', 'inbox-status' ) ?>"
				checked
			/>
			<?php _e('Total emails', 'inbox-status' ) ?>
		</label>

		<br style="clear:both;" />
	</p>

	<p id="menu-item-name-wrap">
		<label class="howto" for="inbox-status-name">
			<span><?php _e( 'Text' ); ?></span>
			<input 
				id="inbox-status-name"
				name="menu-item[<?php echo $_nav_menu_placeholder; ?>][menu-item-title]"
				type="text" class="regular-text menu-item-textbox"
				value="<?php echo $link_text ?>"
			/>
		</label>
	</p>

	<p id="menu-item-url-wrap">
		<label class="howto" for="inbox-status-url">
			<span><?php _e('URL'); ?></span>
			<input
				id="inbox-status-url"
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
				name="add-inbox-status"
				id="submit-inbox-status-div"
			/>
			<span class="spinner"></span>
		</span>
	</p>

</div>