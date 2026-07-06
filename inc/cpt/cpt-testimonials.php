<?php
/**
 * CPT: drumstudy_testim — Testimonials
 *
 * Meta fields: quote (required), author_name (required), author_role,
 *              author_image (ID), rating (1–5), source
 * Admin label: "Displayed in Testimonials section"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_testim post type. Slug intentionally shortened from drumstudy_testimonial to stay within WP's 20-char post_type limit.
 */
function drumstudy_register_cpt_testimonial(): void {
	$labels = [
		'name'               => __( 'Testimonials', 'drumstudy' ),
		'singular_name'      => __( 'Testimonial', 'drumstudy' ),
		'menu_name'          => __( 'Testimonials', 'drumstudy' ),
		'all_items'          => __( 'All Testimonials', 'drumstudy' ),
		'add_new'            => __( 'Add Testimonial', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Testimonial', 'drumstudy' ),
		'edit_item'          => __( 'Edit Testimonial', 'drumstudy' ),
		'not_found'          => __( 'No testimonials found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_testim',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title' ],
			'menu_icon'           => 'dashicons-format-quote',
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_testimonial' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'drumstudy_testim' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return;
		}
		if ( ! get_post_meta( $post_id, 'quote', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Quote', 'drumstudy' ) . '</strong> ' . esc_html__( 'is required before publishing this testimonial.', 'drumstudy' ) . '</p></div>';
		}
		if ( ! get_post_meta( $post_id, 'author_name', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Author Name', 'drumstudy' ) . '</strong> ' . esc_html__( 'is required before publishing this testimonial.', 'drumstudy' ) . '</p></div>';
		}
	}
);

/**
 * Custom columns for Testimonials list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_testimonial_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'author_name' => __( 'Author', 'drumstudy' ),
			'rating'      => __( 'Rating', 'drumstudy' ),
			'date'        => __( 'Date', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_testim_posts_columns', 'drumstudy_testimonial_columns' );

/**
 * Render custom column content for Testimonials.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_testimonial_column_content( string $column, int $post_id ): void {
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
add_action( 'manage_drumstudy_testim_posts_custom_column', 'drumstudy_testimonial_column_content', 10, 2 );
