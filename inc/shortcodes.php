<?php
/**
 * Theme Shortcodes
 *
 * [drumstudy_cta]        — CTA button (primary or secondary style)
 * [drumstudy_button]     — Alias for drumstudy_cta
 * [drumstudy_pdf_download] — PDF download link
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── [drumstudy_cta] / [drumstudy_button] ─────────────────────────────────────────────────

/**
 * Render a CTA button.
 *
 * Usage: [drumstudy_cta label="Get Started" url="/contact" style="primary"]
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string HTML output.
 */
function drumstudy_shortcode_cta( $atts ): string {
	$atts = shortcode_atts(
		[
			'label' => '',
			'url'   => '',
			'style' => 'primary',
		],
		$atts,
		'drumstudy_cta'
	);

	$label = sanitize_text_field( $atts['label'] );
	$url   = drumstudy_the_url( '', 0, sanitize_text_field( $atts['url'] ) );
	$style = in_array( $atts['style'], [ 'primary', 'secondary' ], true ) ? $atts['style'] : 'primary';

	if ( ! $label || ! $url ) {
		return '';
	}

	return sprintf(
		'<a href="%s" class="tts-btn tts-btn--%s">%s</a>',
		esc_attr( $url ),
		esc_attr( $style ),
		esc_html( $label )
	);
}
add_shortcode( 'drumstudy_cta',    'drumstudy_shortcode_cta' );
add_shortcode( 'drumstudy_button', 'drumstudy_shortcode_cta' );

// ── [drumstudy_pdf_download] ───────────────────────────────────────────────────────

/**
 * Render a PDF download link.
 *
 * Usage: [drumstudy_pdf_download id="42" label="Download Press Kit"]
 *
 * @param array<string, string>|string $atts Shortcode attributes.
 * @return string HTML output.
 */
function drumstudy_shortcode_pdf_download( $atts ): string {
	$atts = shortcode_atts(
		[
			'id'    => '',
			'label' => __( 'Download PDF', 'drumstudy' ),
		],
		$atts,
		'drumstudy_pdf_download'
	);

	$id = absint( $atts['id'] );
	if ( ! $id ) {
		return '';
	}

	// Validate MIME type
	if ( 'application/pdf' !== get_post_mime_type( $id ) ) {
		return '';
	}

	$url   = wp_get_attachment_url( $id );
	$label = sanitize_text_field( $atts['label'] );

	if ( ! $url ) {
		return '';
	}

	return sprintf(
		'<a href="%s" class="tts-btn tts-btn--secondary tts-pdf-download" download>%s</a>',
		esc_url( $url ),
		esc_html( $label )
	);
}
add_shortcode( 'drumstudy_pdf_download', 'drumstudy_shortcode_pdf_download' );
