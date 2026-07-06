<?php
/**
 * CPT: drumstudy_faq — FAQs
 *
 * Meta fields: answer (textarea), display_order (number)
 * Native: title (question)
 * Admin label: "Displayed in FAQ section — ordered by Display Order field"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_faq post type.
 */
function drumstudy_register_cpt_faq(): void {
	$labels = [
		'name'               => __( 'FAQs', 'drumstudy' ),
		'singular_name'      => __( 'FAQ', 'drumstudy' ),
		'menu_name'          => __( 'FAQs', 'drumstudy' ),
		'all_items'          => __( 'All FAQs', 'drumstudy' ),
		'add_new'            => __( 'Add FAQ', 'drumstudy' ),
		'add_new_item'       => __( 'Add New FAQ', 'drumstudy' ),
		'edit_item'          => __( 'Edit FAQ', 'drumstudy' ),
		'not_found'          => __( 'No FAQs found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_faq',
		[
			'labels'              => $labels,
			'public'              => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'show_ui'             => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title' ],
			'menu_icon'           => 'dashicons-editor-help',
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_faq' );

/**
 * Custom columns for FAQs list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_faq_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'faq_order' => __( 'Display Order', 'drumstudy' ),
			'date'      => __( 'Date', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_faq_posts_columns', 'drumstudy_faq_columns' );

/**
 * Make Display Order column sortable.
 *
 * @param array<string, string> $columns Sortable columns.
 * @return array<string, string>
 */
function drumstudy_faq_sortable_columns( array $columns ): array {
	$columns['faq_order'] = 'faq_order';
	return $columns;
}
add_filter( 'manage_edit-drumstudy_faq_sortable_columns', 'drumstudy_faq_sortable_columns' );

/**
 * Render custom column content for FAQs.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_faq_column_content( string $column, int $post_id ): void {
	if ( 'faq_order' === $column ) {
		$order = get_post_meta( $post_id, 'display_order', true );
		echo '' !== $order ? esc_html( $order ) : '&mdash;';
	}
}
add_action( 'manage_drumstudy_faq_posts_custom_column', 'drumstudy_faq_column_content', 10, 2 );
