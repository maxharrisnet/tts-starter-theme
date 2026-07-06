/**
 * Drum Study Theme — Admin JavaScript
 *
 * Handles: options page tab switching, media uploader, meta box visibility.
 */

// Admin Options page — tab switching.
( function () {
	const tabs   = document.querySelectorAll( '.tts-tab-btn' );
	const panels = document.querySelectorAll( '.tts-tab-panel' );
	if ( ! tabs.length ) return;

	function activateTab( targetTab ) {
		tabs.forEach( ( t ) => {
			t.setAttribute( 'aria-selected', 'false' );
			t.classList.remove( 'tts-tab-btn--active' );
		} );
		panels.forEach( ( p ) => {
			p.hidden = true;
		} );

		targetTab.setAttribute( 'aria-selected', 'true' );
		targetTab.classList.add( 'tts-tab-btn--active' );

		const panelId = targetTab.dataset.tab;
		const panel   = document.getElementById( 'tts-tab-' + panelId );
		if ( panel ) panel.hidden = false;
	}

	tabs.forEach( ( tab ) => {
		tab.addEventListener( 'click', () => activateTab( tab ) );
	} );

	// Activate first tab on load.
	activateTab( tabs[ 0 ] );
} )();

// Media uploader — handles both image and PDF file fields.
( function () {
	if ( typeof wp === 'undefined' || ! wp.media ) return;

	// Open frame on upload button click.
	document.addEventListener( 'click', ( e ) => {
		const btn = e.target.closest( '.tts-media-upload-btn' );
		if ( ! btn ) return;

		const fieldId   = btn.dataset.field;
		const previewId = btn.dataset.preview;
		const accept    = btn.dataset.accept || 'image';
		const field     = fieldId   ? document.getElementById( fieldId )   : null;
		const preview   = previewId ? document.getElementById( previewId ) : null;

		const frame = wp.media( {
			title:    btn.dataset.title  || 'Select Media',
			button:   { text: btn.dataset.button || 'Use this file' },
			multiple: false,
			library:  { type: accept === 'pdf' ? 'application/pdf' : 'image' },
		} );

		frame.on( 'select', () => {
			const attachment = frame.state().get( 'selection' ).first().toJSON();
			if ( field ) field.value = attachment.id;

			if ( preview ) {
				preview.replaceChildren();
				if ( attachment.type === 'image' ) {
					const img      = document.createElement( 'img' );
					img.src        = attachment.url;
					img.alt        = '';
					img.style.maxWidth = '150px';
					img.style.height   = 'auto';
					preview.appendChild( img );
				} else {
					preview.textContent = attachment.filename;
				}
			}
		} );

		frame.open();
	} );

	// Remove media on clear button click.
	document.addEventListener( 'click', ( e ) => {
		const btn = e.target.closest( '.tts-media-remove-btn' );
		if ( ! btn ) return;

		const fieldId   = btn.dataset.field;
		const previewId = btn.dataset.preview;
		const field     = fieldId   ? document.getElementById( fieldId )   : null;
		const preview   = previewId ? document.getElementById( previewId ) : null;

		if ( field )   field.value = '';
		if ( preview ) preview.replaceChildren();
	} );
} )();

// Meta box visibility — show/hide per selected page template.
( function () {
	const templateSelect = document.getElementById( 'page_template' );
	if ( ! templateSelect ) return;

	const metaBoxMap = {
		'templates/template-home.php':      [ 'drumstudy_home_meta' ],
		'templates/template-about.php':     [ 'drumstudy_about_meta' ],
		'templates/template-contact.php':   [ 'drumstudy_contact_meta' ],
		'templates/template-features.php':  [ 'drumstudy_features_meta' ],
		'templates/template-donate.php':    [ 'drumstudy_donate_meta' ],
		'templates/template-splash.php':    [ 'drumstudy_splash_meta' ],
		'templates/template-portfolio.php': [ 'drumstudy_portfolio_meta' ],
	};

	const allBoxIds = Object.values( metaBoxMap ).flat();

	function updateMetaBoxes( template ) {
		allBoxIds.forEach( ( id ) => {
			const el = document.getElementById( id );
			if ( el ) el.style.display = 'none';
		} );

		( metaBoxMap[ template ] || [] ).forEach( ( id ) => {
			const el = document.getElementById( id );
			if ( el ) el.style.display = '';
		} );
	}

	templateSelect.addEventListener( 'change', () => updateMetaBoxes( templateSelect.value ) );
	updateMetaBoxes( templateSelect.value );
} )();
