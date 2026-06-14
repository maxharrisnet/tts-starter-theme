<?php
/**
 * Admin Options — Page Registration & Settings API
 *
 * Registers the TTS Site Settings page under Settings → Site Settings.
 * All options use individual register_setting() calls so they can be
 * retrieved via tts_get_option( 'tts_key' ) / get_option( 'tts_key' ).
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the options page.
 */
function tts_add_options_page(): void {
	add_options_page(
		__( 'TTS Site Settings', 'tts-theme' ),
		__( 'Site Settings', 'tts-theme' ),
		'manage_options',
		'tts-options',
		'tts_render_options_page'
	);
}
add_action( 'admin_menu', 'tts_add_options_page' );

/**
 * Register all theme options with WordPress Settings API.
 * Group: tts_options — all share this group for form action.
 */
function tts_register_settings(): void {

	// ── Tab 01 — Identity ────────────────────────────────────────────────────
	register_setting( 'tts_options', 'tts_site_profile',    [ 'sanitize_callback' => 'tts_sanitize_profile' ] );
	register_setting( 'tts_options', 'tts_logo',            [ 'sanitize_callback' => 'tts_sanitize_attachment_id' ] );
	register_setting( 'tts_options', 'tts_logo_alt',        [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_color_primary',   [ 'sanitize_callback' => 'tts_sanitize_hex_color' ] );
	register_setting( 'tts_options', 'tts_color_secondary', [ 'sanitize_callback' => 'tts_sanitize_hex_color' ] );
	register_setting( 'tts_options', 'tts_color_accent',    [ 'sanitize_callback' => 'tts_sanitize_hex_color' ] );
	register_setting( 'tts_options', 'tts_font_pairing',    [ 'sanitize_callback' => 'tts_sanitize_font_pairing' ] );
	register_setting( 'tts_options', 'tts_header_layout',   [ 'sanitize_callback' => 'tts_sanitize_layout' ] );
	register_setting( 'tts_options', 'tts_footer_layout',   [ 'sanitize_callback' => 'tts_sanitize_layout' ] );
	register_setting( 'tts_options', 'tts_reduce_motion',   [ 'sanitize_callback' => 'tts_sanitize_checkbox' ] );

	// ── Tab 02 — Business ────────────────────────────────────────────────────
	register_setting( 'tts_options', 'tts_business_name', [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_tagline',       [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_phone',         [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_email',         [ 'sanitize_callback' => 'tts_sanitize_email_field' ] );
	register_setting( 'tts_options', 'tts_address_1',     [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_address_2',     [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_city',          [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_state',         [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_postal',        [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_country',       [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_map_embed',     [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_hours',         [ 'sanitize_callback' => 'tts_sanitize_textarea' ] );
	register_setting( 'tts_options', 'tts_lat',           [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_lng',           [ 'sanitize_callback' => 'tts_sanitize_text' ] );

	// ── Tab 03 — Social ──────────────────────────────────────────────────────
	register_setting( 'tts_options', 'tts_social_facebook',   [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_instagram',  [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_x',          [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_linkedin',   [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_youtube',    [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_tiktok',     [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_spotify',    [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_social_soundcloud', [ 'sanitize_callback' => 'tts_sanitize_url' ] );

	// ── Tab 04 — CTAs & Banner ───────────────────────────────────────────────
	register_setting( 'tts_options', 'tts_cta_primary_label',   [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_cta_primary_url',     [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_cta_secondary_label', [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_cta_secondary_url',   [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_cta_header_label',    [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_cta_header_url',      [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_banner_active',       [ 'sanitize_callback' => 'tts_sanitize_checkbox' ] );
	register_setting( 'tts_options', 'tts_banner_text',         [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_banner_cta_label',    [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_banner_cta_url',      [ 'sanitize_callback' => 'tts_sanitize_text' ] );

	// ── Tab 05 — Integrations ────────────────────────────────────────────────
	register_setting( 'tts_options', 'tts_embed_booking',    [ 'sanitize_callback' => 'tts_sanitize_embed_code' ] );
	register_setting( 'tts_options', 'tts_embed_donation',   [ 'sanitize_callback' => 'tts_sanitize_embed_code' ] );
	register_setting( 'tts_options', 'tts_ga_id',            [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_gtm_id',           [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_pixel_id',         [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_scripts_header',   [ 'sanitize_callback' => 'tts_sanitize_embed_code' ] );
	register_setting( 'tts_options', 'tts_scripts_footer',   [ 'sanitize_callback' => 'tts_sanitize_embed_code' ] );

	// ── Tab 06 — Profile Settings ────────────────────────────────────────────
	register_setting( 'tts_options', 'tts_maintenance_active',  [ 'sanitize_callback' => 'tts_sanitize_checkbox' ] );
	register_setting( 'tts_options', 'tts_maintenance_message', [ 'sanitize_callback' => 'tts_sanitize_textarea' ] );

	// Archive headers.
	$archive_cpts = [ 'events', 'team', 'services', 'locations', 'gallery', 'demo', 'press' ];
	foreach ( $archive_cpts as $cpt ) {
		register_setting( 'tts_options', 'tts_archive_header_' . $cpt, [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	}

	// Profile-specific.
	register_setting( 'tts_options', 'tts_ticket_platform',  [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_location_count',   [ 'sanitize_callback' => 'absint' ] );
	register_setting( 'tts_options', 'tts_donation_goal',    [ 'sanitize_callback' => 'tts_sanitize_text' ] );
	register_setting( 'tts_options', 'tts_waitlist_url',     [ 'sanitize_callback' => 'tts_sanitize_url' ] );
	register_setting( 'tts_options', 'tts_press_kit_pdf',    [ 'sanitize_callback' => 'tts_sanitize_attachment_id' ] );
	register_setting( 'tts_options', 'tts_booking_platform', [ 'sanitize_callback' => 'tts_sanitize_text' ] );
}
add_action( 'admin_init', 'tts_register_settings' );

/**
 * Render the options page shell. Content is in options-fields.php.
 */
function tts_render_options_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	require_once get_template_directory() . '/inc/options/options-fields.php';
	tts_render_options_fields();
}
