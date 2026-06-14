<?php
/**
 * CPT: tts_event — Events
 *
 * Meta fields: event_date (required), event_time, end_date, end_time,
 *              location_name, location_address, ticket_url, ticket_price,
 *              event_image (ID), organizer, embed_block
 * Native: title, content (description)
 * Admin label: "Displayed in Events section and Events archive"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_event post type.
 */
function tts_register_cpt_event(): void {
	$labels = [
		'name'               => __( 'Events', 'tts-theme' ),
		'singular_name'      => __( 'Event', 'tts-theme' ),
		'menu_name'          => __( 'Events', 'tts-theme' ),
		'all_items'          => __( 'All Events', 'tts-theme' ),
		'add_new'            => __( 'Add Event', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Event', 'tts-theme' ),
		'edit_item'          => __( 'Edit Event', 'tts-theme' ),
		'view_item'          => __( 'View Event', 'tts-theme' ),
		'not_found'          => __( 'No events found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No events found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_event',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => true,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-calendar-alt',
			'rewrite'             => [ 'slug' => 'events', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => false,
		]
	);
}
add_action( 'init', 'tts_register_cpt_event' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'tts_event' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( $post_id && ! get_post_meta( $post_id, 'event_date', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Event Date', 'tts-theme' ) . '</strong> ' . esc_html__( 'is recommended before publishing this event.', 'tts-theme' ) . '</p></div>';
		}
	}
);

/**
 * Add tts-past-event class to past event rows in the admin list.
 *
 * @param string[] $classes  Post CSS classes.
 * @param string   $class    Additional class string.
 * @param int      $post_id  Post ID.
 * @return string[]
 */
function tts_event_post_class( array $classes, string $class, int $post_id ): array {
	if ( get_post_type( $post_id ) !== 'tts_event' ) {
		return $classes;
	}
	$event_date = get_post_meta( $post_id, 'event_date', true );
	if ( $event_date && $event_date < gmdate( 'Y-m-d' ) ) {
		$classes[] = 'tts-past-event';
	}
	return $classes;
}
add_filter( 'post_class', 'tts_event_post_class', 10, 3 );

/**
 * Custom columns for Events list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_event_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'event_date'   => __( 'Event Date', 'tts-theme' ),
			'event_status' => __( 'Status', 'tts-theme' ),
			'date'         => __( 'Date Added', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_event_posts_columns', 'tts_event_columns' );

/**
 * Make Event Date column sortable.
 *
 * @param array<string, string> $columns Sortable columns.
 * @return array<string, string>
 */
function tts_event_sortable_columns( array $columns ): array {
	$columns['event_date'] = 'event_date';
	return $columns;
}
add_filter( 'manage_edit-tts_event_sortable_columns', 'tts_event_sortable_columns' );

/**
 * Render custom column content for Events.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_event_column_content( string $column, int $post_id ): void {
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
					echo '<span style="color:#999;">' . esc_html__( 'Past', 'tts-theme' ) . '</span>';
				} else {
					echo '<span style="color:#2ea44f;">' . esc_html__( 'Upcoming', 'tts-theme' ) . '</span>';
				}
			} else {
				echo '&mdash;';
			}
			break;
	}
}
add_action( 'manage_tts_event_posts_custom_column', 'tts_event_column_content', 10, 2 );
