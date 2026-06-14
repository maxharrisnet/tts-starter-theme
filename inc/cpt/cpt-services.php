<?php
/**
 * CPT: tts_service — Services
 *
 * Meta fields: price, service_image (ID), cta_label, cta_url
 * Admin label: "Used in Services section on homepage and Services archive"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_service post type.
 */
function tts_register_cpt_service(): void {
	$labels = [
		'name'               => __( 'Services', 'tts-theme' ),
		'singular_name'      => __( 'Service', 'tts-theme' ),
		'menu_name'          => __( 'Services', 'tts-theme' ),
		'all_items'          => __( 'All Services', 'tts-theme' ),
		'add_new'            => __( 'Add Service', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Service', 'tts-theme' ),
		'edit_item'          => __( 'Edit Service', 'tts-theme' ),
		'view_item'          => __( 'View Service', 'tts-theme' ),
		'search_items'       => __( 'Search Services', 'tts-theme' ),
		'not_found'          => __( 'No services found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No services found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_service',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-hammer',
			'rewrite'             => [ 'slug' => 'services', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'tts_register_cpt_service' );

/**
 * Add custom columns to the Services list table.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_service_columns( array $columns ): array {
	$new = [];
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( 'title' === $key ) {
			$new['service_price'] = __( 'Price', 'tts-theme' );
		}
	}
	return $new;
}
add_filter( 'manage_tts_service_posts_columns', 'tts_service_columns' );

/**
 * Render custom column content for Services.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_service_column_content( string $column, int $post_id ): void {
	if ( 'service_price' === $column ) {
		$price = get_post_meta( $post_id, 'price', true );
		echo $price ? esc_html( $price ) : '&mdash;';
	}
}
add_action( 'manage_tts_service_posts_custom_column', 'tts_service_column_content', 10, 2 );
