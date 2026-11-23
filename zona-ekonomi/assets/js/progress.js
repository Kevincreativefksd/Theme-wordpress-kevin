/**
 * Zona Ekonomi: progress.js
 * Reading progress bar. Loaded only on single articles when enabled.
 */
( function () {
	'use strict';

	var bar = document.querySelector( '.ze-progress span' );
	var content = document.querySelector( '.entry-content' );

	if ( ! bar || ! content ) {
		return;
	}

	var ticking = false;

	function update() {
		var rect = content.getBoundingClientRect();
		var total = rect.height - window.innerHeight;
		var ratio = total > 0 ? -rect.top / total : ( rect.top < 0 ? 1 : 0 );

		ratio = Math.max( 0, Math.min( 1, ratio ) );
		bar.style.transform = 'scaleX(' + ratio + ')';
		ticking = false;
	}

	function onScroll() {
		if ( ! ticking ) {
			ticking = true;
			window.requestAnimationFrame( update );
		}
	}

	window.addEventListener( 'scroll', onScroll, { passive: true } );
	window.addEventListener( 'resize', onScroll, { passive: true } );
	update();
}() );
