( function( $ ){

	var inboxAdminOptions = {

		"providerChange": function(){
			var json = $.parseJSON( $(this).val() );
			console.log( json );

			$('#imap_server').val( json.imap_server );
			$('#port').val( json.port );
			$('input:radio[name="inbox-status[tls]"]')
				.attr('checked', false)
				.filter('[value="' + json.tls + '"]').attr('checked', true);

			if ( 'Other' == $(this).find('option:selected').text() ) {
				this.showAdvanced();
			}

		},

		"showAdvanced": function(){
			$('table.form-table .advanced').parents('tr').show();
			return false;
		},

		"hideAdvanced": function(){
			$('table.form-table .advanced').parents('tr').hide();
		},

		"init": function(){

			this.hideAdvanced();
			$('a.toggle-advanced').click( this.showAdvanced );

			$('#provider').change( this.providerChange ).change();

		}

	}; // end inboxAdminOptions

	inboxAdminOptions.init();

})( jQuery );