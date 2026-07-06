<?php
/**
 * CPT: drumstudy_team — Team Members
 *
 * Meta fields: role (required), team_image (ID), email, phone, linkedin, twitter
 * Native: title (name), content (bio)
 * Admin label: "Displayed in Team section and About page"
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the drumstudy_team post type.
 */
function drumstudy_register_cpt_team(): void {
	$labels = [
		'name'               => __( 'Team Members', 'drumstudy' ),
		'singular_name'      => __( 'Team Member', 'drumstudy' ),
		'menu_name'          => __( 'Team Members', 'drumstudy' ),
		'all_items'          => __( 'All Team Members', 'drumstudy' ),
		'add_new'            => __( 'Add Team Member', 'drumstudy' ),
		'add_new_item'       => __( 'Add New Team Member', 'drumstudy' ),
		'edit_item'          => __( 'Edit Team Member', 'drumstudy' ),
		'view_item'          => __( 'View Team Member', 'drumstudy' ),
		'not_found'          => __( 'No team members found.', 'drumstudy' ),
		'not_found_in_trash' => __( 'No team members found in Trash.', 'drumstudy' ),
	];

	register_post_type(
		'drumstudy_team',
		[
			'labels'              => $labels,
			'public'              => true,
			'has_archive'         => false,
			'show_in_menu'        => 'drumstudy-content',
			'show_in_rest'        => true,
			'supports'            => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'           => 'dashicons-groups',
			'rewrite'             => [ 'slug' => 'team', 'with_front' => false ],
			'capability_type'     => 'post',
			'exclude_from_search' => true,
		]
	);
}
add_action( 'init', 'drumstudy_register_cpt_team' );

/**
 * Soft warning when required fields are missing on save.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || 'drumstudy_team' !== $screen->post_type ) {
			return;
		}
		$post_id = get_the_ID();
		if ( $post_id && ! get_post_meta( $post_id, 'role', true ) ) {
			echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Role', 'drumstudy' ) . '</strong> ' . esc_html__( 'is recommended before publishing this team member.', 'drumstudy' ) . '</p></div>';
		}
	}
);

/**
 * Custom columns for Team Members list.
 *
 * @param array<string, string> $columns Existing columns.
 * @return array<string, string>
 */
function drumstudy_team_columns( array $columns ): array {
	unset( $columns['date'] );
	return array_merge(
		$columns,
		[
			'team_role' => __( 'Role', 'drumstudy' ),
			'date'      => __( 'Date', 'drumstudy' ),
		]
	);
}
add_filter( 'manage_drumstudy_team_posts_columns', 'drumstudy_team_columns' );

/**
 * Render custom column content for Team Members.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post ID.
 */
function drumstudy_team_column_content( string $column, int $post_id ): void {
	if ( 'team_role' === $column ) {
		$role = get_post_meta( $post_id, 'role', true );
		echo $role ? esc_html( $role ) : '&mdash;';
	}
}
add_action( 'manage_drumstudy_team_posts_custom_column', 'drumstudy_team_column_content', 10, 2 );
