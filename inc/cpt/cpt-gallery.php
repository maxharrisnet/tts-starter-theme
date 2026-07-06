<?php
/**
 * CPT: drumstudy_gallery — Gallery Items
 *
 * Meta fields: gallery_image (ID), caption, category, project_link, project_name
 * Admin label: "Displayed in Gallery section"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_gallery post type.
 */
function drumstudy_register_cpt_gallery(): void {
	$labels = [
		'name'               => __( 'Gallery', 'drumstudy' ),
		'singular_name'      => __( 'Gallery Item', 'drumstudy' ),
		'menu_name'          => __( 'Gallery', 'drumstudy' ),
		'all_items'          => __( 'All Gallery Items', 'drumstudy' ),
		'add_new'            => __( 'Add Gallery Item', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Gallery Item', 'drumstudy' ),
		'edit_item'          => __( 'Edit Gallery Item', 'drumstudy' ),
		'not_found'          => __( 'No gallery items found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No gallery items found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_gallery',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'thumbnail' ],
			'menu_icon'           => 'dashicons-format-gallery',
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_gallery' );

/**
 * Custom columns for Gallery list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_gallery_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'gallery_category' => __( 'Category', 'drumstudy' ),
			'date'             => __( 'Date', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_gallery_posts_columns', 'drumstudy_gallery_columns' );

/**
 * Render custom column content for Gallery.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_gallery_column_content( string $column, int $post_id ): void {
	if ( 'gallery_category' === $column ) {
		$cat = get_post_meta( $post_id, 'category', true );
		echo $cat ? esc_html( $cat ) : '&mdash;';
	}
}
add_action( 'manage_drumstudy_gallery_posts_custom_column', 'drumstudy_gallery_column_content', 10, 2 );
