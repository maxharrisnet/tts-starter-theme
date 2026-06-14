<?php
/**
 * CPT: tts_faq — FAQs
 *
 * Meta fields: answer (textarea), display_order (number)
 * Native: title (question)
 * Admin label: "Displayed in FAQ section — ordered by Display Order field"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_faq post type.
 */
function tts_register_cpt_faq(): void {
	$labels = [
		'name'               => __( 'FAQs', 'tts-theme' ),
		'singular_name'      => __( 'FAQ', 'tts-theme' ),
		'menu_name'          => __( 'FAQs', 'tts-theme' ),
		'all_items'          => __( 'All FAQs', 'tts-theme' ),
		'add_new'            => __( 'Add FAQ', 'tts-theme' ),
		'add_new_item'       => __( 'Add New FAQ', 'tts-theme' ),
		'edit_item'          => __( 'Edit FAQ', 'tts-theme' ),
		'not_found'          => __( 'No FAQs found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_faq',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title' ],
			'menu_icon'           => 'dashicons-editor-help',
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'tts_register_cpt_faq' );

/**
 * Custom columns for FAQs list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_faq_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'faq_order' => __( 'Display Order', 'tts-theme' ),
			'date'      => __( 'Date', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_faq_posts_columns', 'tts_faq_columns' );

/**
 * Make Display Order column sortable.
 *
 * @param array<string, string> $columns Sortable columns.
 * @return array<string, string>
 */
function tts_faq_sortable_columns( array $columns ): array {
	$columns['faq_order'] = 'faq_order';
	return $columns;
}
add_filter( 'manage_edit-tts_faq_sortable_columns', 'tts_faq_sortable_columns' );

/**
 * Render custom column content for FAQs.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_faq_column_content( string $column, int $post_id ): void {
	if ( 'faq_order' === $column ) {
		$order = get_post_meta( $post_id, 'display_order', true );
		echo '' !== $order ? esc_html( $order ) : '&mdash;';
	}
}
add_action( 'manage_tts_faq_posts_custom_column', 'tts_faq_column_content', 10, 2 );
