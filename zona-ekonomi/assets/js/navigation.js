/**
 * Zona Ekonomi: navigation.js
 * Mobile menu, search panel, and scrolling the active category into view.
 * Vanilla JS; the markup works without it.
 */
( function () {
	'use strict';

	var root = document.documentElement;
	var header = document.getElementById( 'masthead' );

	if ( ! header ) {
		return;
	}

	var menuButton = header.querySelector( '.menu-toggle' );
	var searchButton = header.querySelector( '.search-toggle' );
	var searchPanel = document.getElementById( 'search-panel' );
	var desktop = window.matchMedia( '(min-width: 900px)' );

	function setMenu( open ) {
		if ( ! menuButton ) {
			return;
		}
		header.classList.toggle( 'is-open', open );
		root.classList.toggle( 'ze-nav-open', open );
		menuButton.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	}

	function setSearch( open ) {
		if ( ! searchButton || ! searchPanel ) {
			return;
		}
		searchPanel.classList.toggle( 'is-open', open );
		searchButton.setAttribute( 'aria-expanded', open ? 'true' : 'false' );

		if ( open ) {
			var input = searchPanel.querySelector( 'input[type="search"]' );
			if ( input ) {
				input.focus();
			}
		}
	}

	if ( menuButton ) {
		menuButton.addEventListener( 'click', function () {
			var open = menuButton.getAttribute( 'aria-expanded' ) !== 'true';
			setMenu( open );
			if ( open ) {
				setSearch( false );
			}
		} );
	}

	if ( searchButton && searchPanel ) {
		searchButton.addEventListener( 'click', function () {
			var open = searchButton.getAttribute( 'aria-expanded' ) !== 'true';
			setSearch( open );
			if ( open ) {
				setMenu( false );
			}
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key !== 'Escape' ) {
			return;
		}
		if ( menuButton && menuButton.getAttribute( 'aria-expanded' ) === 'true' ) {
			setMenu( false );
			menuButton.focus();
		}
		if ( searchButton && searchButton.getAttribute( 'aria-expanded' ) === 'true' ) {
			setSearch( false );
			searchButton.focus();
		}
	} );

	function onViewportChange( event ) {
		if ( event.matches ) {
			setMenu( false );
		}
	}

	if ( desktop.addEventListener ) {
		desktop.addEventListener( 'change', onViewportChange );
	} else if ( desktop.addListener ) {
		desktop.addListener( onViewportChange );
	}

	// Bring the active category into view inside the horizontal scroller.
	var list = document.querySelector( '.catnav__list' );
	var active = list ? list.querySelector( '[aria-current="page"]' ) : null;

	if ( list && active && list.scrollWidth > list.clientWidth ) {
		list.scrollLeft = active.offsetLeft - ( list.clientWidth - active.offsetWidth ) / 2;
	}
}() );
