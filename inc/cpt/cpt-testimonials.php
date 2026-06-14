<?php
/**
 * CPT: tts_testimonial — Testimonials
 *
 * Meta fields: quote (required), author_name (required), author_role,
 *              author_image (ID), rating (1–5), source
 * Admin label: "Displayed in Testimonials section"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_testimonial post type.
 */
function tts_register_cpt_testimonial(): void {
	$labels = [
		'name'               => __( 'Testimonials', 'tts-theme' ),
		'singular_name'      => __( 'Testimonial', 'tts-theme' ),
		'menu_name'          => __( 'Testimonials', 'tts-theme' ),
		'all_items'          => __( 'All Testimonials', 'tts-theme' ),
		'add_new'            => __( 'Add Testimonial', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Testimonial', 'tts-theme' ),
		'edit_item'          => __( 'Edit Testimonial', 'tts-theme' ),
		'not_found'          => __( 'No testimonials found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_testimonial',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title' ],
			'menu_icon'           => 'dashicons-format-quote',
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'tts_register_cpt_testimonial' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'tts_testimonial' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return;
		}
		if ( ! get_post_meta( $post_id, 'quote', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Quote', 'tts-theme' ) . '</strong> ' . esc_html__( 'is required before publishing this testimonial.', 'tts-theme' ) . '</p></div>';
		}
		if ( ! get_post_meta( $post_id, 'author_name', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Author Name', 'tts-theme' ) . '</strong> ' . esc_html__( 'is required before publishing this testimonial.', 'tts-theme' ) . '</p></div>';
		}
	}
);

/**
 * Custom columns for Testimonials list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_testimonial_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'author_name' => __( 'Author', 'tts-theme' ),
			'rating'      => __( 'Rating', 'tts-theme' ),
			'date'        => __( 'Date', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_testimonial_posts_columns', 'tts_testimonial_columns' );

/**
 * Render custom column content for Testimonials.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_testimonial_column_content( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'author_name':
			$name = get_post_meta( $post_id, 'author_name', true );
			echo $name ? esc_html( $name ) : '&mdash;';
			break;
		case 'rating':
			$rating = absint( get_post_meta( $post_id, 'rating', true ) );
			echo $rating ? esc_html( $rating ) . '/5' : '&mdash;';
			break;
	}
}
add_action( 'manage_tts_testimonial_posts_custom_column', 'tts_testimonial_column_content', 10, 2 );
