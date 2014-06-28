(function( window, $, api ) {
	'use strict';

	$( window ).on( 'load', function() {
		var logoHeight = api.instance( 'genlogo-settings[genlogo_height]' ),
			logoWidth = api.instance( 'genlogo-settings[genlogo_width]' ),
			logoUrl = api.instance( 'genlogo-settings[genlogo_logo]' );

		logoUrl.bind( 'change', function( url ) {
			var image;

			if ( '' === url ) {
				logoHeight.set( 0 );
				logoWidth.set( 0 );
			} else {
				image = new Image();
				image.onload = function() {
					logoHeight.set( this.height );
					logoWidth.set( this.width );
				};
				image.src = url;
			}
		});
	});
})( window, jQuery, wp.customize );
