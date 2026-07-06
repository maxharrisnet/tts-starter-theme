<?php
/**
 * CPT: drumstudy_press — Press Items (Creative profile)
 *
 * Meta fields: article_url, publish_date, outlet_logo (ID), pull_quote
 * Native: title (outlet name), content (headline)
 * Admin label: "Displayed in Press/As Seen In section"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_press post type.
 */
function drumstudy_register_cpt_press(): void {
	$labels = [
		'name'               => __( 'Press Items', 'drumstudy' ),
		'singular_name'      => __( 'Press Item', 'drumstudy' ),
		'menu_name'          => __( 'Press Items', 'drumstudy' ),
		'all_items'          => __( 'All Press Items', 'drumstudy' ),
		'add_new'            => __( 'Add Press Item', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Press Item', 'drumstudy' ),
		'edit_item'          => __( 'Edit Press Item', 'drumstudy' ),
		'view_item'          => __( 'View Press Item', 'drumstudy' ),
		'not_found'          => __( 'No press items found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No press items found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_press',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor' ],
			'menu_icon'           => 'dashicons-megaphone',
			'rewrite'             => [ 'slug' => 'press', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_press' );

/**
 * Custom columns for Press Items list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_press_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'press_publish_date' => __( 'Published', 'drumstudy' ),
			'date'               => __( 'Date Added', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_press_posts_columns', 'drumstudy_press_columns' );

/**
 * Render custom column content for Press Items.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_press_column_content( string $column, int $post_id ): void {
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
add_action( 'manage_drumstudy_press_posts_custom_column', 'drumstudy_press_column_content', 10, 2 );
