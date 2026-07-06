<?php
/**
 * Admin Options — Sanitization Helpers
 *
 * These functions are assigned as sanitize_callback in register_setting().
 * Each maps to a field type used in the TTS options page.
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a plain text field.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_text( mixed $value ): string {
	return sanitize_text_field( wp_unslash( (string) $value ) );
}

/**
 * Sanitize a textarea field — allows basic HTML.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_textarea( mixed $value ): string {
	return wp_kses_post( wp_unslash( (string) $value ) );
}

/**
 * Sanitize a URL field.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_url( mixed $value ): string {
	return esc_url_raw( wp_unslash( (string) $value ) );
}

/**
 * Sanitize a hex color value (e.g. #1a2b3c).
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_hex_color( mixed $value ): string {
	$value = sanitize_text_field( wp_unslash( (string) $value ) );
	return preg_match( '/^#[0-9a-fA-F]{6}$/', $value ) ? $value : '';
}

/**
 * Sanitize a checkbox — returns '1' or ''.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_checkbox( mixed $value ): string {
	return ! empty( $value ) ? '1' : '';
}

/**
 * Sanitize an attachment ID (positive integer).
 *
 * @param mixed $value Raw input.
 * @return int
 */
function drumstudy_sanitize_attachment_id( mixed $value ): int {
	return absint( $value );
}

/**
 * Sanitize a site profile selection.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_profile( mixed $value ): string {
	$valid = [ 'booking', 'local', 'creative', 'venture', 'sales', 'events', 'directory', 'community' ];
	$value = sanitize_key( (string) $value );
	return in_array( $value, $valid, true ) ? $value : 'local';
}

/**
 * Sanitize a font pairing selection.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_font_pairing( mixed $value ): string {
	$valid = [ 'editorial', 'expressive' ];
	$value = sanitize_key( (string) $value );
	return in_array( $value, $valid, true ) ? $value : 'editorial';
}

/**
 * Sanitize a layout selection (standard / minimal).
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_layout( mixed $value ): string {
	$valid = [ 'standard', 'minimal' ];
	$value = sanitize_key( (string) $value );
	return in_array( $value, $valid, true ) ? $value : 'standard';
}

/**
 * Sanitize an email address.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_email_field( mixed $value ): string {
	return sanitize_email( wp_unslash( (string) $value ) );
}

/**
 * Sanitize embed/script content — allows iframes and script tags from trusted sources.
 * Uses a permissive kses that allows embed codes. Only admin-facing — never output raw.
 *
 * @param mixed $value Raw input.
 * @return string
 */
function drumstudy_sanitize_embed_code( mixed $value ): string {
	$allowed = array_merge(
		wp_kses_allowed_html( 'post' ),
		[
			'iframe' => [
				'src'             => true,
				'width'           => true,
				'height'          => true,
				'frameborder'     => true,
				'allowfullscreen' => true,
				'allow'           => true,
				'title'           => true,
				'style'           => true,
				'class'           => true,
				'id'              => true,
				'loading'         => true,
			],
			'script' => [
				'src'   => true,
				'async' => true,
				'defer' => true,
				'type'  => true,
				'id'    => true,
			],
		]
	);
	return wp_kses( wp_unslash( (string) $value ), $allowed );
}
