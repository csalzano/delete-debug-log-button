jQuery( document ).ready(
	function(){
		jQuery( '#wp-admin-bar-delete_debug_log > a' ).on(
			'click',
			function(){
				jQuery( '.spinner.spinner-delete-debug-log' ).addClass( 'is-active' ).css( { "height": ".8em", "width": ".8em", "background-size": ".8em", "margin-left": ".3em", "float": "none" } );
				var data = {
					action: 'delete_debug_log',
					_ajax_nonce: ddlb.nonce,
				};
				jQuery.post(
					ddlb.ajax_url,
					data,
					function( response ) {
						// hide spinner
						jQuery( '.spinner.spinner-delete-debug-log' ).removeClass( 'is-active' ).css( "width", "0" );
					}
				);
			}
		);
	}
);
