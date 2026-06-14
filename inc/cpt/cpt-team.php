<?php
/**
 * CPT: tts_team — Team Members
 *
 * Meta fields: role (required), team_image (ID), email, phone, linkedin, twitter
 * Native: title (name), content (bio)
 * Admin label: "Displayed in Team section and About page"
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the tts_team post type.
 */
function tts_register_cpt_team(): void {
	$labels = [
		'name'               => __( 'Team Members', 'tts-theme' ),
		'singular_name'      => __( 'Team Member', 'tts-theme' ),
		'menu_name'          => __( 'Team Members', 'tts-theme' ),
		'all_items'          => __( 'All Team Members', 'tts-theme' ),
		'add_new'            => __( 'Add Team Member', 'tts-theme' ),
		'add_new_item'       => __( 'Add New Team Member', 'tts-theme' ),
		'edit_item'          => __( 'Edit Team Member', 'tts-theme' ),
		'view_item'          => __( 'View Team Member', 'tts-theme' ),
		'not_found'          => __( 'No team members found.', 'tts-theme' ),
		'not_found_in_trash' => __( 'No team members found in Trash.', 'tts-theme' ),
	];

	register_post_type(
		'tts_team',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'show_in_menu'        => 'tts-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-groups',
			'rewrite'             => [ 'slug' => 'team', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'tts_register_cpt_team' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'tts_team' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( $post_id && ! get_post_meta( $post_id, 'role', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Role', 'tts-theme' ) . '</strong> ' . esc_html__( 'is recommended before publishing this team member.', 'tts-theme' ) . '</p></div>';
		}
	}
);

/**
 * Custom columns for Team Members list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function tts_team_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'team_role' => __( 'Role', 'tts-theme' ),
			'date'      => __( 'Date', 'tts-theme' ),
		]
	);
}
add_filter( 'manage_tts_team_posts_columns', 'tts_team_columns' );

/**
 * Render custom column content for Team Members.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function tts_team_column_content( string $column, int $post_id ): void {
	if ( 'team_role' === $column ) {
		$role = get_post_meta( $post_id, 'role', true );
		echo $role ? esc_html( $role ) : '&mdash;';
	}
}
add_action( 'manage_tts_team_posts_custom_column', 'tts_team_column_content', 10, 2 );
