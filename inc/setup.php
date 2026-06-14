<?php
/**
 * Theme Setup
 *
 * Theme support declarations, image sizes, menu registration, text domain,
 * comments disabling, author/date archive redirects, plugin install notices,
 * and CSS variable output.
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core theme setup hooked to after_setup_theme.
 */
function tts_theme_setup(): void {
	load_theme_textdomain( 'tts-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'wp-block-styles' );

	// Disable block-based widgets.
	remove_theme_support( 'widgets-block-editor' );

	register_nav_menus(
		[
			'primary'      => __( 'Primary Navigation', 'tts-theme' ),
			'footer'       => __( 'Footer Navigation', 'tts-theme' ),
			'footer-legal' => __( 'Footer Legal Links', 'tts-theme' ),
		]
	);
}
add_action( 'after_setup_theme', 'tts_theme_setup' );

/**
 * Register custom image sizes.
 */
function tts_register_image_sizes(): void {
	add_image_size( 'tts-hero',    1920, 800,  true );
	add_image_size( 'tts-feature', 1280, 720,  true );
	add_image_size( 'tts-card',    600,  400,  true );
	add_image_size( 'tts-thumb',   300,  300,  true );
	add_image_size( 'tts-logo',    400,  200,  false );
	add_image_size( 'tts-og',      1200, 630,  true );
}
add_action( 'after_setup_theme', 'tts_register_image_sizes' );

/**
 * Add custom image sizes to the media uploader dropdown.
 *
 * @param array<string, string> $sizes Existing size labels.
 * @return array<string, string>
 */
function tts_add_image_size_names( array $sizes ): array {
	return array_merge(
		$sizes,
		[
			'tts-hero'    => __( 'TTS Hero', 'tts-theme' ),
			'tts-feature' => __( 'TTS Feature', 'tts-theme' ),
			'tts-card'    => __( 'TTS Card', 'tts-theme' ),
			'tts-thumb'   => __( 'TTS Thumbnail', 'tts-theme' ),
			'tts-logo'    => __( 'TTS Logo', 'tts-theme' ),
			'tts-og'      => __( 'TTS OG Image', 'tts-theme' ),
		]
	);
}
add_filter( 'image_size_names_choose', 'tts_add_image_size_names' );

// ── Comments — disabled everywhere, permanently ──────────────────────────────

add_action(
	'init',
	function (): void {
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}

		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}
);

add_filter( 'comments_open',  '__return_false', 20, 2 );
add_filter( 'pings_open',     '__return_false', 20, 2 );
add_filter( 'comments_array', '__return_empty_array', 10, 2 );

add_action(
	'admin_menu',
	function (): void {
		remove_menu_page( 'edit-comments.php' );
	}
);

// ── Disabled archives ────────────────────────────────────────────────────────

add_filter( 'author_rewrite_rules', '__return_empty_array' );

add_action(
	'template_redirect',
	function (): void {
		if ( is_author() || is_date() ) {
			wp_safe_redirect( home_url( '/' ), 301 );
			exit;
		}
	}
);

// ── Reduce motion — data attribute on <html> ─────────────────────────────────

/**
 * Add data-reduce-motion attribute to <html> when admin option is enabled.
 *
 * @param string $output Existing language_attributes string.
 * @return string
 */
function tts_html_attrs( string $output ): string {
	if ( tts_get_option( 'tts_reduce_motion' ) ) {
		$output .= ' data-reduce-motion="true"';
	}
	return $output;
}
add_filter( 'language_attributes', 'tts_html_attrs' );

// ── Brand colors → CSS custom properties ────────────────────────────────────

/**
 * Output brand color and font CSS variables into <head> (priority 1).
 * These feed the @theme tokens in main.css at runtime.
 */
function tts_output_css_vars(): void {
	$primary   = tts_get_option( 'tts_color_primary' )   ?: '#1a1a2e';
	$secondary = tts_get_option( 'tts_color_secondary' ) ?: '#ffffff';
	$accent    = tts_get_option( 'tts_color_accent' )    ?: '#4ade80';

	printf(
		'<style>:root { --tts-color-primary: %s; --tts-color-secondary: %s; --tts-color-accent: %s; }</style>' . "\n",
		esc_attr( $primary ),
		esc_attr( $secondary ),
		esc_attr( $accent )
	);
}
add_action( 'wp_head', 'tts_output_css_vars', 1 );

/**
 * Output active font pairing CSS variables into <head>.
 */
function tts_output_font_vars(): void {
	$pairing  = tts_get_option( 'tts_font_pairing' ) ?: 'editorial';
	$headings = ( 'expressive' === $pairing )
		? "'Zalando Sans SemiExpanded', ui-sans-serif, system-ui, sans-serif"
		: "'DM Serif Display', ui-serif, Georgia, serif";
	$body     = ( 'expressive' === $pairing )
		? "'Figtree', ui-sans-serif, system-ui, sans-serif"
		: "'Manrope', ui-sans-serif, system-ui, sans-serif";

	printf(
		'<style>:root { --tts-font-heading: %s; --tts-font-body: %s; }</style>' . "\n",
		esc_attr( $headings ),
		esc_attr( $body )
	);
}
add_action( 'wp_head', 'tts_output_font_vars', 1 );

// ── Custom scripts from Admin Options ───────────────────────────────────────

/**
 * Output custom header scripts from Admin Options Tab 05.
 * wp_kses_post is used since these are admin-entered embed codes.
 */
function tts_output_header_scripts(): void {
	$scripts = tts_get_option( 'tts_scripts_header' );
	if ( $scripts ) {
		echo wp_kses_post( $scripts ) . "\n";
	}
}
add_action( 'wp_head', 'tts_output_header_scripts', 99 );

/**
 * Output custom footer scripts from Admin Options Tab 05.
 */
function tts_output_footer_scripts(): void {
	$scripts = tts_get_option( 'tts_scripts_footer' );
	if ( $scripts ) {
		echo wp_kses_post( $scripts ) . "\n";
	}
}
add_action( 'wp_footer', 'tts_output_footer_scripts', 99 );

// ── Search — exclude tts_gallery from results ────────────────────────────────

add_action(
	'pre_get_posts',
	function ( WP_Query $query ): void {
		if ( ! $query->is_main_query() || ! $query->is_search() ) {
			return;
		}
		$public_types = array_keys( get_post_types( [ 'public' => true ] ) );
		$query->set( 'post_type', array_diff( $public_types, [ 'tts_gallery', 'attachment' ] ) );
	}
);

// ── Sitemap — exclude thin-content CPTs ─────────────────────────────────────

add_filter(
	'wp_sitemaps_post_types',
	function ( array $post_types ): array {
		unset( $post_types['tts_gallery'], $post_types['tts_testimonial'], $post_types['tts_faq'] );
		return $post_types;
	}
);

add_filter(
	'wp_sitemaps_taxonomies',
	function ( array $taxonomies ): array {
		unset( $taxonomies['post_tag'], $taxonomies['post_format'] );
		return $taxonomies;
	}
);

add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, string $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);

// ── Admin menu cleanup + Content grouping ────────────────────────────────────

/**
 * Register top-level "Content" menu and remove unneeded WP defaults.
 * All CPTs use show_in_menu => 'tts-content' to nest under here.
 */
function tts_setup_admin_menu(): void {
	add_menu_page(
		__( 'Content', 'tts-theme' ),
		__( 'Content', 'tts-theme' ),
		'edit_posts',
		'tts-content',
		'tts_content_menu_redirect',
		'dashicons-layout',
		5
	);
	// Remove the auto-generated "Content" sub-item pointing back to itself.
	remove_submenu_page( 'tts-content', 'tts-content' );
	remove_menu_page( 'tools.php' );
}
add_action( 'admin_menu', 'tts_setup_admin_menu' );

/**
 * Redirect the top-level Content menu click to the Services list.
 */
function tts_content_menu_redirect(): void {
	wp_safe_redirect( admin_url( 'edit.php?post_type=tts_service' ) );
	exit;
}

/**
 * Rename native Posts to "Updates" throughout the admin.
 *
 * @param array<string, mixed> $args Post type args.
 * @return array<string, mixed>
 */
function tts_relabel_posts( array $args ): array {
	if ( isset( $args['labels'] ) ) {
		$args['labels']->name          = __( 'Updates', 'tts-theme' );
		$args['labels']->singular_name = __( 'Update', 'tts-theme' );
		$args['labels']->menu_name     = __( 'Updates', 'tts-theme' );
		$args['labels']->all_items     = __( 'All Updates', 'tts-theme' );
		$args['labels']->add_new       = __( 'Add Update', 'tts-theme' );
		$args['labels']->add_new_item  = __( 'Add New Update', 'tts-theme' );
		$args['labels']->edit_item     = __( 'Edit Update', 'tts-theme' );
	}
	return $args;
}
add_filter( 'register_post_type_args', 'tts_relabel_posts_filter', 10, 2 );

/**
 * Filter callback to relabel only the 'post' post type.
 *
 * @param array<string, mixed> $args      Post type args.
 * @param string               $post_type Post type slug.
 * @return array<string, mixed>
 */
function tts_relabel_posts_filter( array $args, string $post_type ): array {
	if ( 'post' !== $post_type ) {
		return $args;
	}
	return tts_relabel_posts( $args );
}

// ── Plugin install notices ───────────────────────────────────────────────────

/**
 * Show admin notices for required and recommended plugins.
 */
function tts_plugin_install_notice(): void {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	$required = [
		[ 'name' => 'Classic Editor', 'slug' => 'classic-editor', 'file' => 'classic-editor/classic-editor.php' ],
	];

	$recommended = [
		[ 'name' => 'Query Monitor',         'slug' => 'query-monitor',         'file' => 'query-monitor/query-monitor.php' ],
		[ 'name' => 'Accessibility Checker', 'slug' => 'accessibility-checker', 'file' => 'accessibility-checker/accessibility-checker.php' ],
		[ 'name' => 'Theme Check',           'slug' => 'theme-check',           'file' => 'theme-check/theme-check.php' ],
		[ 'name' => 'Broken Link Checker',   'slug' => 'broken-link-checker',   'file' => 'broken-link-checker/broken-link-checker.php' ],
	];

	$missing_required    = array_filter( $required,    fn( $p ) => ! is_plugin_active( $p['file'] ) && ! file_exists( WP_PLUGIN_DIR . '/' . $p['slug'] ) );
	$missing_recommended = array_filter( $recommended, fn( $p ) => ! is_plugin_active( $p['file'] ) && ! file_exists( WP_PLUGIN_DIR . '/' . $p['slug'] ) );

	if ( $missing_required ) {
		$links = array_map(
			fn( $p ) => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=' . rawurlencode( $p['name'] ) . '&tab=search&type=term' ) ) . '">' . esc_html( $p['name'] ) . '</a>',
			$missing_required
		);
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'TTS Theme requires:', 'tts-theme' ) . '</strong> ' . implode( ', ', $links ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( $missing_recommended ) {
		$links = array_map(
			fn( $p ) => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=' . rawurlencode( $p['name'] ) . '&tab=search&type=term' ) ) . '">' . esc_html( $p['name'] ) . '</a>',
			$missing_recommended
		);
		echo '<div class="notice notice-info is-dismissible"><p><strong>' . esc_html__( 'TTS Theme recommends:', 'tts-theme' ) . '</strong> ' . implode( ', ', $links ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'admin_notices', 'tts_plugin_install_notice' );

// ── Image alt text warnings ──────────────────────────────────────────────────

/**
 * Warn editors when an image field on the current post is missing alt text.
 * Each meta file defines $tts_image_meta_keys for its post type.
 */
add_action(
	'admin_notices',
	function (): void {
		$screen = get_current_screen();
		if ( ! $screen || ! in_array( $screen->base, [ 'post', 'post-new' ], true ) ) {
			return;
		}

		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return;
		}

		/**
		 * Filter the image meta keys to check for alt text on this post.
		 * Each meta file should add to this via the tts_image_meta_keys filter.
		 *
		 * @param array<string> $keys     Image meta key names.
		 * @param int           $post_id  Current post ID.
		 */
		$image_keys = apply_filters( 'tts_image_meta_keys', [], $post_id );

		foreach ( $image_keys as $key ) {
			$img_id = absint( get_post_meta( $post_id, $key, true ) );
			if ( $img_id && ! get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) {
				printf(
					'<div class="notice notice-warning"><p>%s</p></div>',
					sprintf(
						/* translators: %s: image title */
						esc_html__( 'Image "%s" is missing alt text. Please add it in the Media Library.', 'tts-theme' ),
						esc_html( get_the_title( $img_id ) )
					)
				);
			}
		}
	}
);
