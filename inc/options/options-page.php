<?php
/**
 * Admin Options — Page Registration & Settings API
 *
 * Registers the TTS Site Settings page under Settings → Site Settings.
 * All options use individual register_setting() calls so they can be
 * retrieved via drumstudy_get_option( 'drumstudy_key' ) / get_option( 'drumstudy_key' ).
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the options page.
 */
function drumstudy_add_options_page(): void {
	add_options_page(
		__( 'TTS Site Settings', 'drumstudy' ),
		__( 'Site Settings', 'drumstudy' ),
		'manage_options',
		'drumstudy-options',
		'drumstudy_render_options_page'
	);
}
add_action( 'admin_menu', 'drumstudy_add_options_page' );

/**
 * Register all theme options with WordPress Settings API.
 * Group: drumstudy_options — all share this group for form action.
 */
function drumstudy_register_settings(): void {

	// ── Tab 01 — Identity ────────────────────────────────────────────────────
	register_setting( 'drumstudy_options', 'drumstudy_site_profile',    [ 'sanitize_callback' => 'drumstudy_sanitize_profile' ] );
	register_setting( 'drumstudy_options', 'drumstudy_logo',            [ 'sanitize_callback' => 'drumstudy_sanitize_attachment_id' ] );
	register_setting( 'drumstudy_options', 'drumstudy_logo_alt',        [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_color_primary',   [ 'sanitize_callback' => 'drumstudy_sanitize_hex_color' ] );
	register_setting( 'drumstudy_options', 'drumstudy_color_secondary', [ 'sanitize_callback' => 'drumstudy_sanitize_hex_color' ] );
	register_setting( 'drumstudy_options', 'drumstudy_color_accent',    [ 'sanitize_callback' => 'drumstudy_sanitize_hex_color' ] );
	register_setting( 'drumstudy_options', 'drumstudy_font_pairing',    [ 'sanitize_callback' => 'drumstudy_sanitize_font_pairing' ] );
	register_setting( 'drumstudy_options', 'drumstudy_header_layout',   [ 'sanitize_callback' => 'drumstudy_sanitize_layout' ] );
	register_setting( 'drumstudy_options', 'drumstudy_footer_layout',   [ 'sanitize_callback' => 'drumstudy_sanitize_layout' ] );
	register_setting( 'drumstudy_options', 'drumstudy_reduce_motion',   [ 'sanitize_callback' => 'drumstudy_sanitize_checkbox' ] );

	// ── Tab 02 — Business ────────────────────────────────────────────────────
	register_setting( 'drumstudy_options', 'drumstudy_business_name', [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_tagline',       [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_phone',         [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_email',         [ 'sanitize_callback' => 'drumstudy_sanitize_email_field' ] );
	register_setting( 'drumstudy_options', 'drumstudy_address_1',     [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_address_2',     [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_city',          [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_state',         [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_postal',        [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_country',       [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_map_embed',     [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_hours',         [ 'sanitize_callback' => 'drumstudy_sanitize_textarea' ] );
	register_setting( 'drumstudy_options', 'drumstudy_lat',           [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_lng',           [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );

	// ── Tab 03 — Social ──────────────────────────────────────────────────────
	register_setting( 'drumstudy_options', 'drumstudy_social_facebook',   [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_instagram',  [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_x',          [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_linkedin',   [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_youtube',    [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_tiktok',     [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_spotify',    [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_social_soundcloud', [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );

	// ── Tab 04 — CTAs & Banner ───────────────────────────────────────────────
	register_setting( 'drumstudy_options', 'drumstudy_cta_primary_label',   [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_cta_primary_url',     [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_cta_secondary_label', [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_cta_secondary_url',   [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_cta_header_label',    [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_cta_header_url',      [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_banner_active',       [ 'sanitize_callback' => 'drumstudy_sanitize_checkbox' ] );
	register_setting( 'drumstudy_options', 'drumstudy_banner_text',         [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_banner_cta_label',    [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_banner_cta_url',      [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );

	// ── Tab 05 — Integrations ────────────────────────────────────────────────
	register_setting( 'drumstudy_options', 'drumstudy_embed_booking',    [ 'sanitize_callback' => 'drumstudy_sanitize_embed_code' ] );
	register_setting( 'drumstudy_options', 'drumstudy_embed_donation',   [ 'sanitize_callback' => 'drumstudy_sanitize_embed_code' ] );
	register_setting( 'drumstudy_options', 'drumstudy_ga_id',            [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_gtm_id',           [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_pixel_id',         [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_scripts_header',   [ 'sanitize_callback' => 'drumstudy_sanitize_embed_code' ] );
	register_setting( 'drumstudy_options', 'drumstudy_scripts_footer',   [ 'sanitize_callback' => 'drumstudy_sanitize_embed_code' ] );

	// ── Tab 06 — Profile Settings ────────────────────────────────────────────
	register_setting( 'drumstudy_options', 'drumstudy_maintenance_active',  [ 'sanitize_callback' => 'drumstudy_sanitize_checkbox' ] );
	register_setting( 'drumstudy_options', 'drumstudy_maintenance_message', [ 'sanitize_callback' => 'drumstudy_sanitize_textarea' ] );

	// Archive headers.
	$archive_cpts = [ 'events', 'team', 'services', 'locations', 'gallery', 'demo', 'press' ];
	foreach ( $archive_cpts as $cpt ) {
		register_setting( 'drumstudy_options', 'drumstudy_archive_header_' . $cpt, [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	}

	// Profile-specific.
	register_setting( 'drumstudy_options', 'drumstudy_ticket_platform',  [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_location_count',   [ 'sanitize_callback' => 'absint' ] );
	register_setting( 'drumstudy_options', 'drumstudy_donation_goal',    [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
	register_setting( 'drumstudy_options', 'drumstudy_waitlist_url',     [ 'sanitize_callback' => 'drumstudy_sanitize_url' ] );
	register_setting( 'drumstudy_options', 'drumstudy_press_kit_pdf',    [ 'sanitize_callback' => 'drumstudy_sanitize_attachment_id' ] );
	register_setting( 'drumstudy_options', 'drumstudy_booking_platform', [ 'sanitize_callback' => 'drumstudy_sanitize_text' ] );
}
add_action( 'admin_init', 'drumstudy_register_settings' );

/**
 * Render the options page shell. Content is in options-fields.php.
 */
function drumstudy_render_options_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	require_once get_template_directory() . '/inc/options/options-fields.php';
	drumstudy_render_options_fields();
}
