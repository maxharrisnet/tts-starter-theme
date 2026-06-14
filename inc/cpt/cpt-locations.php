<?php
/**
 * CPT: tts_location — Locations (Directory profile)
 *
 * Meta fields: address_1 (required), address_2, city, state, postal,
 *              location_phone, location_email, location_hours, map_embed,
 *              location_image (ID), manager_name
 * Native: title (location name)
 * Admin label: "Displayed in Directory/Locations section"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_location post type.
 */
function tts_register_cpt_location(): void {
	$labels = [
		'name'               => __( 'Locations', 'tts-theme' ),
		'singular_name'      => __( 'Location', 'tts-theme' ),
		'menu_name'          => __( 'Locations', 'tts-theme' ),
		'all_items'          => __( 'All Locations', 'tts-theme' ),
		'add_new'            => __( 'Add Location', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Location', 'tts-theme' ),
		'edit_item'          => __( 'Edit Location', 'tts-theme' ),
		'view_item'          => __( 'View Location', 'tts-theme' ),
		'not_found'          => __( 'No locations found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No locations found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_location',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'thumbnail' ],
			'menu_icon'           => 'dashicons-location-alt',
			'rewrite'             => [ 'slug' => 'locations', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'tts_register_cpt_location' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'tts_location' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( $post_id && ! get_post_meta( $post_id, 'address_1', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Address Line 1', 'tts-theme' ) . '</strong> ' . esc_html__( 'is recommended before publishing this location.', 'tts-theme' ) . '</p></div>';
		}
	}
);

/**
 * Custom columns for Locations list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_location_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'location_city'  => __( 'City', 'tts-theme' ),
			'location_phone' => __( 'Phone', 'tts-theme' ),
			'date'           => __( 'Date', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_location_posts_columns', 'tts_location_columns' );

/**
 * Render custom column content for Locations.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_location_column_content( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'location_city':
			$city  = get_post_meta( $post_id, 'city', true );
			$state = get_post_meta( $post_id, 'state', true );
			if ( $city && $state ) {
				echo esc_html( $city . ', ' . $state );
			} elseif ( $city ) {
				echo esc_html( $city );
			} else {
				echo '&mdash;';
			}
			break;
		case 'location_phone':
			$phone = get_post_meta( $post_id, 'location_phone', true );
			echo $phone ? esc_html( $phone ) : '&mdash;';
			break;
	}
}
add_action( 'manage_tts_location_posts_custom_column', 'tts_location_column_content', 10, 2 );
