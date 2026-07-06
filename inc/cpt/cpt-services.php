<?php
/**
 * CPT: drumstudy_service — Services
 *
 * Meta fields: price, service_image (ID), cta_label, cta_url
 * Admin label: "Used in Services section on homepage and Services archive"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_service post type.
 */
function drumstudy_register_cpt_service(): void {
	$labels = [
		'name'               => __( 'Services', 'drumstudy' ),
		'singular_name'      => __( 'Service', 'drumstudy' ),
		'menu_name'          => __( 'Services', 'drumstudy' ),
		'all_items'          => __( 'All Services', 'drumstudy' ),
		'add_new'            => __( 'Add Service', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Service', 'drumstudy' ),
		'edit_item'          => __( 'Edit Service', 'drumstudy' ),
		'view_item'          => __( 'View Service', 'drumstudy' ),
		'search_items'       => __( 'Search Services', 'drumstudy' ),
		'not_found'          => __( 'No services found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No services found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_service',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-hammer',
			'rewrite'             => [ 'slug' => 'services', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_service' );

/**
 * Add custom columns to the Services list table.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_service_columns( array $columns ): array {
	$new = [];
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['service_price'] = __( 'Price', 'drumstudy' );
		}
	}
	return $new;
}
add_filter( 'manage_drumstudy_service_posts_columns', 'drumstudy_service_columns' );

/**
 * Render custom column content for Services.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_service_column_content( string $column, int $post_id ): void {
	if ( 'service_price' === $column ) {
		$price = get_post_meta( $post_id, 'price', true );
		echo $price ? esc_html( $price ) : '&mdash;';
	}
}
add_action( 'manage_drumstudy_service_posts_custom_column', 'drumstudy_service_column_content', 10, 2 );
