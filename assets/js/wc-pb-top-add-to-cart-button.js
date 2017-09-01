;( function ( $, window, document, undefined ) {

	var PB_Integration = function( $top, bundle ) {

		this.$qty       = false;
		this.qty_synced = false;

		var self = this;

		// Init.
		this.integrate = function() {

			var $bundle_button       = $top.find( '.bundle_button' ),
				$bundle_error        = $top.find( '.bundle_error' ),
				$bundle_price        = $top.find( '.bundle_price' ),
				$bundle_availability = $top.find( '.bundle_availability' );

			if ( $bundle_button.length > 0 ) {
				$bundle_button.append( bundle.$bundle_button.html() );
				bundle.$bundle_button.push( $bundle_button.get( 0 ) );
				self.$qty = $top.find( 'input.qty' );
			}

			if ( $bundle_error.length > 0 ) {
				$bundle_error.append( bundle.$bundle_error.html() );
				bundle.$bundle_error.push( $bundle_error.get( 0 ) );
				bundle.$bundle_error_content.push( $bundle_error.find( 'ul.msg' ).get( 0 ) );
			}

			if ( $bundle_price.length > 0 ) {
				$bundle_price.append( bundle.$bundle_price.html() );
				bundle.$bundle_price.push( $bundle_price.get( 0 ) );
			}

			if ( $bundle_availability.length > 0 ) {
				$bundle_availability.append( bundle.$bundle_availability.html() );
				bundle.$bundle_availability.push( $bundle_availability.get( 0 ) );
			}
		};

		this.add_hooks = function() {

			// Relay button click to actual form button.
			$top.on( 'submit', function() {
				bundle.$bundle_button.find( '.bundle_add_to_cart_button' ).trigger( 'click' );
				return false;
			} );

			// Keep bundle quantities in sync: Top quantity changed.
			if ( self.$qty ) {
				self.$qty.on( 'input change', function() {

					if ( ! self.qty_synced ) {
						self.qty_synced = true;
						bundle.$bundle_quantity.val( self.$qty.val() ).change();
						self.qty_synced = false;
					}
				} );
			}

			// Keep bundle quantities in sync: Bottom quantity changed.
			bundle.$bundle_quantity.on( 'input change', function() {
				self.$qty.val( bundle.$bundle_quantity.val() ).change();
			} );
		};

		// Lights on.
		this.integrate();
		this.add_hooks();
	};

	// Hook into Bundles.
	$( '.bundle_form_top' ).each( function() {
		var $top = $( this ),
			$bundle_data = $( this ).closest( '.product' ).find( '.bundle_form .bundle_data' );

		if ( $bundle_data.length > 0 ) {
			$bundle_data.on( 'woocommerce-product-bundle-initializing', function( event, bundle ) {
				new PB_Integration( $top, bundle );
			} );
		}
	} );

} )( jQuery, window, document );
