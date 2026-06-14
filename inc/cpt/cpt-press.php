<?php
/**
 * CPT: tts_press — Press Items (Creative profile)
 *
 * Meta fields: article_url, publish_date, outlet_logo (ID), pull_quote
 * Native: title (outlet name), content (headline)
 * Admin label: "Displayed in Press/As Seen In section"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_press post type.
 */
function tts_register_cpt_press(): void {
	$labels = [
		'name'               => __( 'Press Items', 'tts-theme' ),
		'singular_name'      => __( 'Press Item', 'tts-theme' ),
		'menu_name'          => __( 'Press Items', 'tts-theme' ),
		'all_items'          => __( 'All Press Items', 'tts-theme' ),
		'add_new'            => __( 'Add Press Item', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Press Item', 'tts-theme' ),
		'edit_item'          => __( 'Edit Press Item', 'tts-theme' ),
		'view_item'          => __( 'View Press Item', 'tts-theme' ),
		'not_found'          => __( 'No press items found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No press items found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_press',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor' ],
			'menu_icon'           => 'dashicons-megaphone',
			'rewrite'             => [ 'slug' => 'press', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'tts_register_cpt_press' );

/**
 * Custom columns for Press Items list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_press_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'press_publish_date' => __( 'Published', 'tts-theme' ),
			'date'               => __( 'Date Added', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_press_posts_columns', 'tts_press_columns' );

/**
 * Render custom column content for Press Items.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_press_column_content( string $column, int $post_id ): void {
	if ( 'press_publish_date' === $column ) {
		$date = get_post_meta( $post_id, 'publish_date', true );
		if ( $date ) {
			$formatted = wp_date( get_option( 'date_format' ), strtotime( $date ) );
			echo esc_html( $formatted ?: $date );
		} else {
			echo '&mdash;';
		}
	}
}
add_action( 'manage_tts_press_posts_custom_column', 'tts_press_column_content', 10, 2 );
