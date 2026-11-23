/**
 * Customizer: keep the hidden value of a category checklist in sync.
 */
( function () {
	'use strict';

	document.addEventListener( 'change', function ( event ) {
		var target = event.target;

		if ( ! target || ! target.classList || ! target.classList.contains( 'ze-category-checklist__box' ) ) {
			return;
		}

		var wrap = target.closest( '.ze-category-checklist' );
		var hidden = wrap ? wrap.querySelector( 'input[type="hidden"]' ) : null;

		if ( ! hidden ) {
			return;
		}

		var ids = [];
		wrap.querySelectorAll( '.ze-category-checklist__box:checked' ).forEach( function ( box ) {
			ids.push( box.value );
		} );

		hidden.value = ids.join( ',' );
		hidden.dispatchEvent( new Event( 'change', { bubbles: true } ) );
	} );
}() );
