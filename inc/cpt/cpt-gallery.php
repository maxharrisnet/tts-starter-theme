<?php
/**
 * CPT: tts_gallery — Gallery Items
 *
 * Meta fields: gallery_image (ID), caption, category, project_link, project_name
 * Admin label: "Displayed in Gallery section"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_gallery post type.
 */
function tts_register_cpt_gallery(): void {
	$labels = [
		'name'               => __( 'Gallery', 'tts-theme' ),
		'singular_name'      => __( 'Gallery Item', 'tts-theme' ),
		'menu_name'          => __( 'Gallery', 'tts-theme' ),
		'all_items'          => __( 'All Gallery Items', 'tts-theme' ),
		'add_new'            => __( 'Add Gallery Item', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Gallery Item', 'tts-theme' ),
		'edit_item'          => __( 'Edit Gallery Item', 'tts-theme' ),
		'not_found'          => __( 'No gallery items found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No gallery items found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_gallery',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'thumbnail' ],
			'menu_icon'           => 'dashicons-format-gallery',
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'tts_register_cpt_gallery' );

/**
 * Custom columns for Gallery list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_gallery_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'gallery_category' => __( 'Category', 'tts-theme' ),
			'date'             => __( 'Date', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_gallery_posts_columns', 'tts_gallery_columns' );

/**
 * Render custom column content for Gallery.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_gallery_column_content( string $column, int $post_id ): void {
	if ( 'gallery_category' === $column ) {
		$cat = get_post_meta( $post_id, 'category', true );
		echo $cat ? esc_html( $cat ) : '&mdash;';
	}
}
add_action( 'manage_tts_gallery_posts_custom_column', 'tts_gallery_column_content', 10, 2 );
