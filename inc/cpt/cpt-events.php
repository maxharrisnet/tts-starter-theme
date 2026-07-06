<?php
/**
 * CPT: drumstudy_event — Events
 *
 * Meta fields: event_date (required), event_time, end_date, end_time,
 *              location_name, location_address, ticket_url, ticket_price,
 *              event_image (ID), organizer, embed_block
 * Native: title, content (description)
 * Admin label: "Displayed in Events section and Events archive"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_event post type.
 */
function drumstudy_register_cpt_event(): void {
	$labels = [
		'name'               => __( 'Events', 'drumstudy' ),
		'singular_name'      => __( 'Event', 'drumstudy' ),
		'menu_name'          => __( 'Events', 'drumstudy' ),
		'all_items'          => __( 'All Events', 'drumstudy' ),
		'add_new'            => __( 'Add Event', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Event', 'drumstudy' ),
		'edit_item'          => __( 'Edit Event', 'drumstudy' ),
		'view_item'          => __( 'View Event', 'drumstudy' ),
		'not_found'          => __( 'No events found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_event',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-calendar-alt',
			'rewrite'             => [ 'slug' => 'events', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_event' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'drumstudy_event' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( $post_id && ! get_post_meta( $post_id, 'event_date', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Event Date', 'drumstudy' ) . '</strong> ' . esc_html__( 'is recommended before publishing this event.', 'drumstudy' ) . '</p></div>';
		}
	}
);

/**
 * Add tts-past-event class to past event rows in the admin list.
 *
 * @param string[] $classes  Post CSS classes.
 * @param array    $class    Additional classes passed to post_class() (normalized to an array by WP core).
 * @param int      $post_id  Post ID.
 * @return string[]
 */
function drumstudy_event_post_class( array $classes, array $class, int $post_id ): array {
	if ( get_post_type( $post_id ) !== 'drumstudy_event' ) {
		return $classes;
	}
	$event_date = get_post_meta( $post_id, 'event_date', true );
	if ( $event_date && $event_date < gmdate( 'Y-m-d' ) ) {
		$classes[] = 'tts-past-event';
	}
	return $classes;
}
add_filter( 'post_class', 'drumstudy_event_post_class', 10, 3 );

/**
 * Custom columns for Events list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_event_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'event_date'   => __( 'Event Date', 'drumstudy' ),
			'event_status' => __( 'Status', 'drumstudy' ),
			'date'         => __( 'Date Added', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_event_posts_columns', 'drumstudy_event_columns' );

/**
 * Make Event Date column sortable.
 *
 * @param array<string, string> $columns Sortable columns.
 * @return array<string, string>
 */
function drumstudy_event_sortable_columns( array $columns ): array {
	$columns['event_date'] = 'event_date';
	return $columns;
}
add_filter( 'manage_edit-drumstudy_event_sortable_columns', 'drumstudy_event_sortable_columns' );

/**
 * Render custom column content for Events.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_event_column_content( string $column, int $post_id ): void {
	$event_date = get_post_meta( $post_id, 'event_date', true );

	switch ( $column ) {
		case 'event_date':
			if ( $event_date ) {
				$formatted = wp_date( get_option( 'date_format' ), strtotime( $event_date ) );
				echo esc_html( $formatted ?: $event_date );
			} else {
				echo '&mdash;';
			}
			break;
		case 'event_status':
			if ( $event_date ) {
				if ( $event_date < gmdate( 'Y-m-d' ) ) {
					echo '<span style="color:#999;">' . esc_html__( 'Past', 'drumstudy' ) . '</span>';
				} else {
					echo '<span style="color:#2ea44f;">' . esc_html__( 'Upcoming', 'drumstudy' ) . '</span>';
				}
			} else {
				echo '&mdash;';
			}
			break;
	}
}
add_action( 'manage_drumstudy_event_posts_custom_column', 'drumstudy_event_column_content', 10, 2 );
