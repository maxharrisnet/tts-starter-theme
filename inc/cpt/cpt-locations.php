<?php
/**
 * CPT: drumstudy_location — Locations (Directory profile)
 *
 * Meta fields: address_1 (required), address_2, city, state, postal,
 *              location_phone, location_email, location_hours, map_embed,
 *              location_image (ID), manager_name
 * Native: title (location name)
 * Admin label: "Displayed in Directory/Locations section"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_location post type.
 */
function drumstudy_register_cpt_location(): void {
	$labels = [
		'name'               => __( 'Locations', 'drumstudy' ),
		'singular_name'      => __( 'Location', 'drumstudy' ),
		'menu_name'          => __( 'Locations', 'drumstudy' ),
		'all_items'          => __( 'All Locations', 'drumstudy' ),
		'add_new'            => __( 'Add Location', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Location', 'drumstudy' ),
		'edit_item'          => __( 'Edit Location', 'drumstudy' ),
		'view_item'          => __( 'View Location', 'drumstudy' ),
		'not_found'          => __( 'No locations found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No locations found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_location',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'thumbnail' ],
			'menu_icon'           => 'dashicons-location-alt',
			'rewrite'             => [ 'slug' => 'locations', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_location' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'drumstudy_location' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( $post_id && ! get_post_meta( $post_id, 'address_1', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Address Line 1', 'drumstudy' ) . '</strong> ' . esc_html__( 'is recommended before publishing this location.', 'drumstudy' ) . '</p></div>';
		}
	}
);

/**
 * Custom columns for Locations list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_location_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'location_city'  => __( 'City', 'drumstudy' ),
			'location_phone' => __( 'Phone', 'drumstudy' ),
			'date'           => __( 'Date', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_location_posts_columns', 'drumstudy_location_columns' );

/**
 * Render custom column content for Locations.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_location_column_content( string $column, int $post_id ): void {
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
add_action( 'manage_drumstudy_location_posts_custom_column', 'drumstudy_location_column_content', 10, 2 );
