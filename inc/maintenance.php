<?php
/**
 * Maintenance Mode
 *
 * Toggled via Admin Options Tab 06 (tts_maintenance_active).
 * Non-admin visitors see a 503 static template.
 * Logged-in admins always see the live site.
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect non-admins to maintenance page when mode is active.
 */
function tts_maintenance_mode(): void {
	if ( ! tts_maintenance_active() ) {
		return;
	}
	if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( is_admin() ) {
		return;
	}

	status_header( 503 );
	header( 'Retry-After: 3600' );
	include get_template_directory() . '/templates/template-maintenance-static.php';
	exit;
}
add_action( 'template_redirect', 'tts_maintenance_mode' );

/**
 * Show an admin bar notice when maintenance mode is active.
 * Reminds admins the site is hidden from public view.
 */
add_action(
	'admin_notices',
	function (): void {
		if ( ! tts_maintenance_active() ) {
			return;
		}
		$options_url = admin_url( 'options-general.php?page=tts-options' );
		printf(
			'<div class="notice notice-warning"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
			esc_html__( 'Maintenance Mode is ON.', 'tts-theme' ),
			esc_html__( 'The site is hidden from public visitors.', 'tts-theme' ),
			esc_url( $options_url ),
			esc_html__( 'Turn it off in Site Settings → Tab 06.', 'tts-theme' )
		);
	}
);

/**
 * Show a persistent admin bar indicator when maintenance mode is active.
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar object.
 */
function tts_maintenance_admin_bar_node( WP_Admin_Bar $wp_admin_bar ): void {
	if ( ! tts_maintenance_active() ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$wp_admin_bar->add_node( [
		'id'    => 'tts-maintenance',
		'title' => '🔒 ' . __( 'Maintenance Mode ON', 'tts-theme' ),
		'href'  => admin_url( 'options-general.php?page=tts-options' ),
		'meta'  => [ 'class' => 'tts-maintenance-indicator' ],
	] );
}
add_action( 'admin_bar_menu', 'tts_maintenance_admin_bar_node', 100 );
