<?php
/**
 * Asset Enqueuing
 *
 * Loads compiled Vite assets in production (via manifest.json) and
 * the Vite dev server in development (DRUMSTUDY_DEV constant).
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end styles and scripts.
 */
function drumstudy_enqueue_assets(): void {
	$manifest_path = DRUMSTUDY_THEME_DIR . '/assets/dist/.vite/manifest.json';

	if ( defined( 'DRUMSTUDY_DEV' ) && DRUMSTUDY_DEV ) {
		// Development: load directly from Vite dev server.
		wp_enqueue_style(
			'tts-main',
			'http://localhost:5173/assets/src/main.css', // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			[],
			null
		);
		wp_enqueue_script(
			'tts-app',
			'http://localhost:5173/assets/src/app.js', // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			[],
			null,
			true
		);
	} elseif ( file_exists( $manifest_path ) ) {
		// Production: load from compiled Vite manifest.
		$manifest = json_decode( file_get_contents( $manifest_path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( ! is_array( $manifest ) ) {
			return;
		}

		$css_file = $manifest['assets/src/main.css']['file'] ?? 'main.css';
		$js_file  = $manifest['assets/src/app.js']['file']  ?? 'app.js';

		wp_enqueue_style(
			'tts-main',
			DRUMSTUDY_THEME_URI . '/assets/dist/' . $css_file,
			[],
			DRUMSTUDY_THEME_VERSION
		);
		wp_enqueue_script(
			'tts-app',
			DRUMSTUDY_THEME_URI . '/assets/dist/' . $js_file,
			[],
			DRUMSTUDY_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'drumstudy_enqueue_assets' );

/**
 * Enqueue admin styles and scripts.
 * Scoped to post edit screens and the TTS options page.
 *
 * @param string $hook Current admin page hook suffix.
 */
function drumstudy_enqueue_admin_assets( string $hook ): void {
	$allowed_hooks = [ 'post.php', 'post-new.php', 'settings_page_drumstudy-options' ];
	if ( ! in_array( $hook, $allowed_hooks, true ) ) {
		return;
	}

	$manifest_path = DRUMSTUDY_THEME_DIR . '/assets/dist/.vite/manifest.json';

	// Media uploader support for image and file fields. Enqueued before our
	// own script (and declared as its dependency below) so wp.media is
	// guaranteed to exist by the time admin.js runs — without this, WordPress
	// has no reason to print admin.js after the media scripts, and on some
	// admin screens it printed first, meaning admin.js's own
	// `if ( ! wp.media ) return;` guard fired and silently skipped attaching
	// the upload-button click handler for the rest of the page load.
	wp_enqueue_media();

	if ( defined( 'DRUMSTUDY_DEV' ) && DRUMSTUDY_DEV ) {
		wp_enqueue_style(
			'tts-admin',
			'http://localhost:5173/assets/src/admin.css', // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			[],
			null
		);
		wp_enqueue_script(
			'tts-admin',
			'http://localhost:5173/assets/src/admin.js', // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			[ 'media-editor' ],
			null,
			true
		);
	} elseif ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( file_get_contents( $manifest_path ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( ! is_array( $manifest ) ) {
			return;
		}

		$css_file = $manifest['assets/src/admin.css']['file'] ?? 'admin.css';
		$js_file  = $manifest['assets/src/admin.js']['file']  ?? 'admin.js';

		if ( isset( $manifest['assets/src/admin.css'] ) ) {
			wp_enqueue_style(
				'tts-admin',
				DRUMSTUDY_THEME_URI . '/assets/dist/' . $css_file,
				[],
				DRUMSTUDY_THEME_VERSION
			);
		}

		wp_enqueue_script(
			'tts-admin',
			DRUMSTUDY_THEME_URI . '/assets/dist/' . $js_file,
			[ 'media-editor' ],
			DRUMSTUDY_THEME_VERSION,
			true
		);
	}
}
add_action( 'admin_enqueue_scripts', 'drumstudy_enqueue_admin_assets' );
