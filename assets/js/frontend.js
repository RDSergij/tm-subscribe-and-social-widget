jQuery( document ).ready( function() {
	jQuery( '.tm-subscribe-and-share-widget .form form ').submit( function( e ){
		var _this = jQuery( this );
		var emailInput = _this.find( 'input[type=email]' );
		var _messages = _this.parent().find( '.message' );
		var data = _this.serialize();
		_messages.removeClass( 'success failed' );
		jQuery.post( window.TMSubscribeAndShareWidgetParam.ajaxurl, data, function( response ) {
			var _messageItem = _messages.find( '.' + response.status );
			_messageItem.show( 'slow' ).delay( 3000 ).fadeOut( function() {
				emailInput.val('');
			});
		 } )
		.fail( function( response ) {
			var _messageItem = _messages.find( '.failed' );
			_messageItem.show( 'slow' ).delay( 3000 ).fadeOut();
		} );

		e.preventDefault();
	});
});
