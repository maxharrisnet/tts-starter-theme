/**
 * TTS Theme — Front-end JavaScript
 *
 * Handles: reduce motion, banner dismiss, FAQ accordion, mobile nav toggle.
 */

// Apply reduce motion immediately — check both admin toggle and OS preference.
( function () {
	if (
		document.documentElement.dataset.reduceMotion === 'true' ||
		window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
	) {
		document.documentElement.dataset.reduceMotion = 'true';
	}
} )();

// Banner dismiss — persists in sessionStorage for the tab session.
( function () {
	const banner = document.querySelector( '.tts-banner' );
	if ( ! banner ) return;

	const storageKey = 'tts-banner-dismissed';

	if ( sessionStorage.getItem( storageKey ) ) {
		banner.remove();
		return;
	}

	const closeBtn = banner.querySelector( '.tts-banner__close' );
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', () => {
			banner.remove();
			sessionStorage.setItem( storageKey, '1' );
		} );
	}
} )();

// FAQ accordion — toggles aria-expanded and hidden per WCAG pattern.
( function () {
	document.querySelectorAll( '.tts-faq__trigger' ).forEach( ( btn ) => {
		btn.addEventListener( 'click', () => {
			const expanded = btn.getAttribute( 'aria-expanded' ) === 'true';
			const panelId  = btn.getAttribute( 'aria-controls' );
			const panel    = panelId ? document.getElementById( panelId ) : null;

			btn.setAttribute( 'aria-expanded', String( ! expanded ) );
			if ( panel ) {
				panel.hidden = expanded;
			}
		} );
	} );
} )();

// Mobile nav toggle.
( function () {
	const toggle = document.querySelector( '.tts-nav-toggle' );
	const nav    = document.querySelector( '.tts-nav-primary' );
	if ( ! toggle || ! nav ) return;

	toggle.addEventListener( 'click', () => {
		const expanded = toggle.getAttribute( 'aria-expanded' ) === 'true';
		toggle.setAttribute( 'aria-expanded', String( ! expanded ) );
		nav.hidden = expanded;
	} );
} )();
