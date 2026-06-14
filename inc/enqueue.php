<?php
/**
 * Asset Enqueuing
 *
 * Loads compiled Vite assets in production (via manifest.json) and
 * the Vite dev server in development (TTS_DEV constant).
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue front-end styles and scripts.
 */
function tts_enqueue_assets(): void {
	$manifest_path = TTS_THEME_DIR . '/assets/dist/manifest.json';

	if ( defined( 'TTS_DEV' ) && TTS_DEV ) {
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
			TTS_THEME_URI . '/assets/dist/' . $css_file,
			[],
			TTS_THEME_VERSION
		);
		wp_enqueue_script(
			'tts-app',
			TTS_THEME_URI . '/assets/dist/' . $js_file,
			[],
			TTS_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'tts_enqueue_assets' );

/**
 * Enqueue admin styles and scripts.
 * Scoped to post edit screens and the TTS options page.
 *
 * @param string $hook Current admin page hook suffix.
 */
function tts_enqueue_admin_assets( string $hook ): void {
	$allowed_hooks = [ 'post.php', 'post-new.php', 'settings_page_tts-options' ];
	if ( ! in_array( $hook, $allowed_hooks, true ) ) {
		return;
	}

	$manifest_path = TTS_THEME_DIR . '/assets/dist/manifest.json';

	if ( defined( 'TTS_DEV' ) && TTS_DEV ) {
		wp_enqueue_style(
			'tts-admin',
			'http://localhost:5173/assets/src/admin.css', // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			[],
			null
		);
		wp_enqueue_script(
			'tts-admin',
			'http://localhost:5173/assets/src/admin.js', // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			[],
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
				TTS_THEME_URI . '/assets/dist/' . $css_file,
				[],
				TTS_THEME_VERSION
			);
		}

		wp_enqueue_script(
			'tts-admin',
			TTS_THEME_URI . '/assets/dist/' . $js_file,
			[],
			TTS_THEME_VERSION,
			true
		);
	}

	// Media uploader support for image and file fields.
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'tts_enqueue_admin_assets' );
