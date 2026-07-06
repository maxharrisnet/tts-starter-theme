<?php
/**
 * CPT: drumstudy_demo — Demo / Video
 *
 * Meta fields: video_url, thumbnail_override (ID), duration, cta_label,
 *              cta_url, video_category (Demo/Testimonial/Tutorial/Reel/Performance)
 * Native: title, content (description)
 * Admin label: "Displayed in Video/Demo section"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_demo post type.
 */
function drumstudy_register_cpt_demo(): void {
	$labels = [
		'name'               => __( 'Demo / Video', 'drumstudy' ),
		'singular_name'      => __( 'Demo', 'drumstudy' ),
		'menu_name'          => __( 'Demo / Video', 'drumstudy' ),
		'all_items'          => __( 'All Demos', 'drumstudy' ),
		'add_new'            => __( 'Add Demo', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Demo', 'drumstudy' ),
		'edit_item'          => __( 'Edit Demo', 'drumstudy' ),
		'view_item'          => __( 'View Demo', 'drumstudy' ),
		'not_found'          => __( 'No demos found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No demos found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_demo',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-video-alt3',
			'rewrite'             => [ 'slug' => 'demos', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_demo' );

/**
 * Custom columns for Demo list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_demo_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'demo_category' => __( 'Category', 'drumstudy' ),
			'demo_duration' => __( 'Duration', 'drumstudy' ),
			'date'          => __( 'Date', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_demo_posts_columns', 'drumstudy_demo_columns' );

/**
 * Render custom column content for Demo.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_demo_column_content( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'demo_category':
			$cat = get_post_meta( $post_id, 'video_category', true );
			echo $cat ? esc_html( $cat ) : '&mdash;';
			break;
		case 'demo_duration':
			$dur = get_post_meta( $post_id, 'duration', true );
			echo $dur ? esc_html( $dur ) : '&mdash;';
			break;
	}
}
add_action( 'manage_drumstudy_demo_posts_custom_column', 'drumstudy_demo_column_content', 10, 2 );
