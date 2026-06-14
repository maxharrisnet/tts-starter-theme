<?php
/**
 * CPT: tts_demo — Demo / Video
 *
 * Meta fields: video_url, thumbnail_override (ID), duration, cta_label,
 *              cta_url, video_category (Demo/Testimonial/Tutorial/Reel/Performance)
 * Native: title, content (description)
 * Admin label: "Displayed in Video/Demo section"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_demo post type.
 */
function tts_register_cpt_demo(): void {
	$labels = [
		'name'               => __( 'Demo / Video', 'tts-theme' ),
		'singular_name'      => __( 'Demo', 'tts-theme' ),
		'menu_name'          => __( 'Demo / Video', 'tts-theme' ),
		'all_items'          => __( 'All Demos', 'tts-theme' ),
		'add_new'            => __( 'Add Demo', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Demo', 'tts-theme' ),
		'edit_item'          => __( 'Edit Demo', 'tts-theme' ),
		'view_item'          => __( 'View Demo', 'tts-theme' ),
		'not_found'          => __( 'No demos found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No demos found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_demo',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-video-alt3',
			'rewrite'             => [ 'slug' => 'demos', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'tts_register_cpt_demo' );

/**
 * Custom columns for Demo list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_demo_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'demo_category' => __( 'Category', 'tts-theme' ),
			'demo_duration' => __( 'Duration', 'tts-theme' ),
			'date'          => __( 'Date', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_demo_posts_columns', 'tts_demo_columns' );

/**
 * Render custom column content for Demo.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_demo_column_content( string $column, int $post_id ): void {
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
add_action( 'manage_tts_demo_posts_custom_column', 'tts_demo_column_content', 10, 2 );
