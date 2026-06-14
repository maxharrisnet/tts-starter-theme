<?php
/**
 * Custom REST API Endpoints
 *
 * Used by n8n for intake-to-launch content population.
 * Authentication via WordPress Application Passwords.
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Guard: allow disabling REST endpoints via constant
if ( defined( 'TTS_REST_API_ENABLED' ) && ! TTS_REST_API_ENABLED ) {
	return;
}

// ── Route registration ────────────────────────────────────────────────────────

add_action(
	'rest_api_init',
	function (): void {
		// Intake: push Admin Options from form data
		register_rest_route(
			'tts/v1',
			'/intake',
			[
				'methods'             => 'POST',
				'callback'            => 'tts_rest_handle_intake',
				'permission_callback' => 'tts_rest_can_manage',
				'args'                => [
					'site_profile'  => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_key' ],
					'business_name' => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'tagline'       => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'phone'         => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'email'         => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_email' ],
					'address_1'     => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'address_2'     => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'city'          => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'state'         => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'postal'        => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'country'       => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_text_field' ],
					'hours'         => [ 'type' => 'string',  'sanitize_callback' => 'sanitize_textarea_field' ],
					'cta_primary_label'   => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
					'cta_primary_url'     => [ 'type' => 'string', 'sanitize_callback' => 'tts_rest_sanitize_url' ],
					'cta_secondary_label' => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
					'cta_secondary_url'   => [ 'type' => 'string', 'sanitize_callback' => 'tts_rest_sanitize_url' ],
				],
			]
		);

		// Services: create tts_service CPT entries
		register_rest_route(
			'tts/v1',
			'/services',
			[
				'methods'             => 'POST',
				'callback'            => 'tts_rest_create_service',
				'permission_callback' => 'tts_rest_can_edit',
				'args'                => [
					'title'       => [ 'type' => 'string', 'required' => true,  'sanitize_callback' => 'sanitize_text_field' ],
					'description' => [ 'type' => 'string', 'sanitize_callback' => 'wp_kses_post' ],
					'price'       => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
					'cta_label'   => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
					'cta_url'     => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
				],
			]
		);

		// Team: create tts_team CPT entries
		register_rest_route(
			'tts/v1',
			'/team',
			[
				'methods'             => 'POST',
				'callback'            => 'tts_rest_create_team',
				'permission_callback' => 'tts_rest_can_edit',
				'args'                => [
					'title' => [ 'type' => 'string', 'required' => true,  'sanitize_callback' => 'sanitize_text_field' ],
					'bio'   => [ 'type' => 'string', 'sanitize_callback' => 'wp_kses_post' ],
					'role'  => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
					'email' => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_email' ],
					'phone' => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
				],
			]
		);

		// Testimonials: create tts_testimonial CPT entries
		register_rest_route(
			'tts/v1',
			'/testimonials',
			[
				'methods'             => 'POST',
				'callback'            => 'tts_rest_create_testimonial',
				'permission_callback' => 'tts_rest_can_edit',
				'args'                => [
					'quote'       => [ 'type' => 'string', 'required' => true,  'sanitize_callback' => 'sanitize_textarea_field' ],
					'author_name' => [ 'type' => 'string', 'required' => true,  'sanitize_callback' => 'sanitize_text_field' ],
					'author_role' => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
					'rating'      => [ 'type' => 'integer', 'minimum' => 1, 'maximum' => 5 ],
					'source'      => [ 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field' ],
				],
			]
		);
	}
);

// ── Permission callback ───────────────────────────────────────────────────────

/**
 * Permission callback for CPT-creation endpoints: requires edit_posts.
 *
 * @return bool|WP_Error
 */
function tts_rest_can_edit() {
	if ( ! is_user_logged_in() ) {
		return new WP_Error( 'rest_not_logged_in', __( 'Authentication required.', 'tts-theme' ), [ 'status' => 401 ] );
	}
	if ( ! current_user_can( 'edit_posts' ) ) {
		return new WP_Error( 'rest_forbidden', __( 'Insufficient permissions.', 'tts-theme' ), [ 'status' => 403 ] );
	}
	return true;
}

/**
 * Permission callback for the intake endpoint: requires manage_options.
 * Intake writes site-wide Admin Options — same capability as the Settings API page.
 *
 * @return bool|WP_Error
 */
function tts_rest_can_manage() {
	if ( ! is_user_logged_in() ) {
		return new WP_Error( 'rest_not_logged_in', __( 'Authentication required.', 'tts-theme' ), [ 'status' => 401 ] );
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return new WP_Error( 'rest_forbidden', __( 'Administrator required.', 'tts-theme' ), [ 'status' => 403 ] );
	}
	return true;
}

/**
 * Sanitize a URL field for storage: only http, https, mailto, and tel schemes allowed.
 * Rejects javascript:, data:, protocol-relative (//) and any unknown scheme.
 *
 * @param string $url Raw URL value from REST request.
 * @return string Sanitized URL or empty string if invalid.
 */
function tts_rest_sanitize_url( string $url ): string {
	$url = trim( $url );
	if ( ! $url ) {
		return '';
	}

	$allowed_schemes = [ 'http', 'https', 'mailto', 'tel' ];
	$parsed          = wp_parse_url( $url );
	$scheme          = isset( $parsed['scheme'] ) ? strtolower( $parsed['scheme'] ) : '';

	// Reject protocol-relative URLs.
	if ( str_starts_with( $url, '//' ) ) {
		return '';
	}

	// Has a scheme: must be in allowlist.
	if ( $scheme ) {
		if ( ! in_array( $scheme, $allowed_schemes, true ) ) {
			return '';
		}
		// http/https must also have a host.
		if ( in_array( $scheme, [ 'http', 'https' ], true ) && empty( $parsed['host'] ) ) {
			return '';
		}
		return esc_url_raw( $url, $allowed_schemes );
	}

	// No scheme — treat as relative path or anchor: must start with / or #.
	if ( str_starts_with( $url, '#' ) || str_starts_with( $url, '/' ) ) {
		return sanitize_text_field( $url );
	}

	return '';
}

// ── Callbacks ─────────────────────────────────────────────────────────────────

/**
 * Handle /tts/v1/intake — push business data to Admin Options.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response
 */
function tts_rest_handle_intake( WP_REST_Request $request ): WP_REST_Response {
	$map = [
		'site_profile'        => 'tts_site_profile',
		'business_name'       => 'tts_business_name',
		'tagline'             => 'tts_tagline',
		'phone'               => 'tts_phone',
		'email'               => 'tts_email',
		'address_1'           => 'tts_address_1',
		'address_2'           => 'tts_address_2',
		'city'                => 'tts_city',
		'state'               => 'tts_state',
		'postal'              => 'tts_postal',
		'country'             => 'tts_country',
		'hours'               => 'tts_hours',
		'cta_primary_label'   => 'tts_cta_primary_label',
		'cta_primary_url'     => 'tts_cta_primary_url',
		'cta_secondary_label' => 'tts_cta_secondary_label',
		'cta_secondary_url'   => 'tts_cta_secondary_url',
	];

	$updated = [];
	foreach ( $map as $param => $option_key ) {
		$value = $request->get_param( $param );
		if ( null !== $value ) {
			update_option( $option_key, $value );
			$updated[] = $option_key;
		}
	}

	return new WP_REST_Response(
		[
			'success' => true,
			'updated' => $updated,
		],
		200
	);
}

/**
 * Handle /tts/v1/services — create a Service CPT post.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response|WP_Error
 */
function tts_rest_create_service( WP_REST_Request $request ) {
	$post_id = wp_insert_post( [
		'post_type'    => 'tts_service',
		'post_title'   => $request->get_param( 'title' ),
		'post_content' => $request->get_param( 'description' ) ?? '',
		'post_status'  => 'publish',
	], true );

	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'rest_insert_failed', $post_id->get_error_message(), [ 'status' => 500 ] );
	}

	$meta_map = [ 'price', 'cta_label', 'cta_url' ];
	foreach ( $meta_map as $key ) {
		$value = $request->get_param( $key );
		if ( null !== $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	return new WP_REST_Response(
		[
			'success' => true,
			'post_id' => $post_id,
			'link'    => get_permalink( $post_id ),
		],
		201
	);
}

/**
 * Handle /tts/v1/team — create a Team Member CPT post.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response|WP_Error
 */
function tts_rest_create_team( WP_REST_Request $request ) {
	$post_id = wp_insert_post( [
		'post_type'    => 'tts_team',
		'post_title'   => $request->get_param( 'title' ),
		'post_content' => $request->get_param( 'bio' ) ?? '',
		'post_status'  => 'publish',
	], true );

	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'rest_insert_failed', $post_id->get_error_message(), [ 'status' => 500 ] );
	}

	foreach ( [ 'role', 'email', 'phone' ] as $key ) {
		$value = $request->get_param( $key );
		if ( null !== $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

	return new WP_REST_Response(
		[
			'success' => true,
			'post_id' => $post_id,
		],
		201
	);
}

/**
 * Handle /tts/v1/testimonials — create a Testimonial CPT post.
 *
 * @param WP_REST_Request $request Request object.
 * @return WP_REST_Response|WP_Error
 */
function tts_rest_create_testimonial( WP_REST_Request $request ) {
	$author_name = $request->get_param( 'author_name' );

	$post_id = wp_insert_post( [
		'post_type'   => 'tts_testimonial',
		'post_title'  => $author_name,
		'post_status' => 'publish',
	], true );

	if ( is_wp_error( $post_id ) ) {
		return new WP_Error( 'rest_insert_failed', $post_id->get_error_message(), [ 'status' => 500 ] );
	}

	$meta_map = [ 'quote', 'author_name', 'author_role', 'rating', 'source' ];
	foreach ( $meta_map as $key ) {
		$value = $request->get_param( $key );
		if ( null !== $value ) {
			update_post_meta( $post_id, $key, 'rating' === $key ? absint( $value ) : $value );
		}
	}

	return new WP_REST_Response(
		[
			'success' => true,
			'post_id' => $post_id,
		],
		201
	);
}

// ── Restrict unauthenticated REST access ─────────────────────────────────────
// Only apply when REST is not needed publicly (no public-facing API consumers).
// Comment this out if you have public REST consumers (e.g. headless frontend).

add_filter(
	'rest_authentication_errors',
	function ( $result ) {
		if ( ! empty( $result ) ) {
			return $result;
		}
		// Allow access to public read endpoints (wp/v2/posts, etc.)
		// Only restrict custom tts/v1 write endpoints — handled per-route above
		return $result;
	}
);
