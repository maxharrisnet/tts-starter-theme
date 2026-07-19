<?php
/**
 * Theme Setup
 *
 * Theme support declarations, image sizes, menu registration, text domain,
 * comments disabling, author/date archive redirects, plugin install notices,
 * and CSS variable output.
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core theme setup hooked to after_setup_theme.
 */
function drumstudy_theme_setup(): void {
	load_theme_textdomain( 'drumstudy', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'wp-block-styles' );

	// Disable block-based widgets.
	remove_theme_support( 'widgets-block-editor' );

	register_nav_menus(
		[
			'primary'      => __( 'Primary Navigation', 'drumstudy' ),
			'footer'       => __( 'Footer Navigation', 'drumstudy' ),
			'footer-legal' => __( 'Footer Legal Links', 'drumstudy' ),
		]
	);
}
add_action( 'after_setup_theme', 'drumstudy_theme_setup' );

/**
 * Register custom image sizes.
 */
function drumstudy_register_image_sizes(): void {
	add_image_size( 'tts-hero',    1920, 800,  true );
	add_image_size( 'tts-feature', 1280, 720,  true );
	add_image_size( 'tts-card',    600,  400,  true );
	add_image_size( 'tts-thumb',   300,  300,  true );
	add_image_size( 'tts-logo',    400,  200,  false );
	add_image_size( 'tts-og',      1200, 630,  true );
}
add_action( 'after_setup_theme', 'drumstudy_register_image_sizes' );

/**
 * Add custom image sizes to the media uploader dropdown.
 *
 * @param array<string, string> $sizes Existing size labels.
 * @return array<string, string>
 */
function drumstudy_add_image_size_names( array $sizes ): array {
	return array_merge(
		$sizes,
		[
			'tts-hero'    => __( 'TTS Hero', 'drumstudy' ),
			'tts-feature' => __( 'TTS Feature', 'drumstudy' ),
			'tts-card'    => __( 'TTS Card', 'drumstudy' ),
			'tts-thumb'   => __( 'TTS Thumbnail', 'drumstudy' ),
			'tts-logo'    => __( 'TTS Logo', 'drumstudy' ),
			'tts-og'      => __( 'TTS OG Image', 'drumstudy' ),
		]
	);
}
add_filter( 'image_size_names_choose', 'drumstudy_add_image_size_names' );

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
function drumstudy_html_attrs( string $output ): string {
	if ( drumstudy_get_option( 'drumstudy_reduce_motion' ) ) {
		$output .= ' data-reduce-motion="true"';
	}
	return $output;
}
add_filter( 'language_attributes', 'drumstudy_html_attrs' );

// ── Brand colors → CSS custom properties ────────────────────────────────────

/**
 * Output brand color and font CSS variables into <head> (priority 1).
 * These feed the @theme tokens in main.css at runtime.
 */
function drumstudy_output_css_vars(): void {
	// drumstudy_sanitize_hex_color() (inc/options/options-sanitize.php) returns
	// '' for anything that isn't a valid #rrggbb hex code.
	$primary   = drumstudy_sanitize_hex_color( drumstudy_get_option( 'drumstudy_color_primary' ) )   ?: '#F4F2EC';
	$secondary = drumstudy_sanitize_hex_color( drumstudy_get_option( 'drumstudy_color_secondary' ) ) ?: '#121214';
	$accent    = drumstudy_sanitize_hex_color( drumstudy_get_option( 'drumstudy_color_accent' ) )    ?: '#4E73AB';

	// Note: these are hex color codes only (validated above) — never run through
	// esc_attr()/esc_html() here, since HTML-entity escaping is the wrong
	// transform for <style> text content (it's raw CSS, not an HTML attribute).
	printf(
		'<style>:root { --tts-color-primary: %s; --tts-color-secondary: %s; --tts-color-accent: %s; }</style>' . "\n",
		$primary,
		$secondary,
		$accent
	);
}
add_action( 'wp_head', 'drumstudy_output_css_vars', 1 );

/**
 * Output active font pairing CSS variables into <head>.
 */
function drumstudy_output_font_vars(): void {
	// Both pairings are sans-serif by design — no serif in any code path.
	$pairing  = drumstudy_get_option( 'drumstudy_font_pairing' ) ?: 'expressive';
	$headings = ( 'expressive' === $pairing )
		? "'Archivo', ui-sans-serif, system-ui, sans-serif"
		: "'Manrope', ui-sans-serif, system-ui, sans-serif";
	$body     = "'Manrope', ui-sans-serif, system-ui, sans-serif";

	// $headings/$body are always one of the two fixed literals above (never
	// user input), and must NOT be run through esc_attr()/esc_html() — that
	// HTML-entity-encodes the quotes (' becomes &#039;), which browsers do not
	// decode inside <style> text content, silently breaking the CSS.
	printf(
		'<style>:root { --tts-font-heading: %s; --tts-font-body: %s; }</style>' . "\n",
		$headings,
		$body
	);
}
add_action( 'wp_head', 'drumstudy_output_font_vars', 1 );

// ── Favicons — theme logo fallback ───────────────────────────────────────────

/**
 * Output favicon links from the theme-bundled logo icons.
 * Skipped when a Site Icon is set in the Customizer — WordPress outputs its
 * own icon links in that case, and duplicating them confuses browsers.
 */
function drumstudy_output_favicons(): void {
	if ( has_site_icon() ) {
		return;
	}

	$img = get_template_directory_uri() . '/assets/img';

	printf( '<link rel="icon" type="image/png" sizes="32x32" href="%s">' . "\n", esc_url( $img . '/favicon-32.png' ) );
	printf( '<link rel="icon" type="image/png" sizes="192x192" href="%s">' . "\n", esc_url( $img . '/favicon-192.png' ) );
	printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( $img . '/apple-touch-icon.png' ) );
}
add_action( 'wp_head', 'drumstudy_output_favicons', 2 );

// ── Custom scripts from Admin Options ───────────────────────────────────────

/**
 * Output custom header scripts from Admin Options Tab 05.
 * wp_kses_post is used since these are admin-entered embed codes.
 */
function drumstudy_output_header_scripts(): void {
	$scripts = drumstudy_get_option( 'drumstudy_scripts_header' );
	if ( $scripts ) {
		echo wp_kses_post( $scripts ) . "\n";
	}
}
add_action( 'wp_head', 'drumstudy_output_header_scripts', 99 );

/**
 * Output custom footer scripts from Admin Options Tab 05.
 */
function drumstudy_output_footer_scripts(): void {
	$scripts = drumstudy_get_option( 'drumstudy_scripts_footer' );
	if ( $scripts ) {
		echo wp_kses_post( $scripts ) . "\n";
	}
}
add_action( 'wp_footer', 'drumstudy_output_footer_scripts', 99 );

// ── Search — exclude drumstudy_gallery from results ────────────────────────────────

add_action(
	'pre_get_posts',
	function ( WP_Query $query ): void {
		if ( ! $query->is_main_query() || ! $query->is_search() ) {
			return;
		}
		$public_types = array_keys( get_post_types( [ 'public' => true ] ) );
		$query->set( 'post_type', array_diff( $public_types, [ 'drumstudy_gallery', 'attachment' ] ) );
	}
);

// ── Sitemap — exclude thin-content CPTs ─────────────────────────────────────

add_filter(
	'wp_sitemaps_post_types',
	function ( array $post_types ): array {
		unset( $post_types['drumstudy_gallery'], $post_types['drumstudy_testim'], $post_types['drumstudy_faq'] );
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
 * All CPTs use show_in_menu => 'drumstudy-content' to nest under here.
 */
function drumstudy_setup_admin_menu(): void {
	add_menu_page(
		__( 'Content', 'drumstudy' ),
		__( 'Content', 'drumstudy' ),
		'edit_posts',
		'drumstudy-content',
		'drumstudy_content_menu_redirect',
		'dashicons-layout',
		5
	);
	// Remove the auto-generated "Content" sub-item pointing back to itself.
	remove_submenu_page( 'drumstudy-content', 'drumstudy-content' );
	remove_menu_page( 'tools.php' );
}
add_action( 'admin_menu', 'drumstudy_setup_admin_menu' );

/**
 * Redirect the top-level Content menu click to the Services list.
 */
function drumstudy_content_menu_redirect(): void {
	wp_safe_redirect( admin_url( 'edit.php?post_type=drumstudy_service' ) );
	exit;
}

/**
 * Rename native Posts to "Updates" throughout the admin.
 *
 * @param array<string, mixed> $args Post type args.
 * @return array<string, mixed>
 */
function drumstudy_relabel_posts( array $args ): array {
	if ( isset( $args['labels'] ) ) {
		$args['labels']['name']          = __( 'Updates', 'drumstudy' );
		$args['labels']['singular_name'] = __( 'Update', 'drumstudy' );
		$args['labels']['menu_name']     = __( 'Updates', 'drumstudy' );
		$args['labels']['all_items']     = __( 'All Updates', 'drumstudy' );
		$args['labels']['add_new']       = __( 'Add Update', 'drumstudy' );
		$args['labels']['add_new_item']  = __( 'Add New Update', 'drumstudy' );
		$args['labels']['edit_item']     = __( 'Edit Update', 'drumstudy' );
	} else {
		$args['labels'] = [
			'name'          => __( 'Updates', 'drumstudy' ),
			'singular_name' => __( 'Update', 'drumstudy' ),
			'menu_name'     => __( 'Updates', 'drumstudy' ),
			'all_items'     => __( 'All Updates', 'drumstudy' ),
			'add_new'       => __( 'Add Update', 'drumstudy' ),
			'add_new_item'  => __( 'Add New Update', 'drumstudy' ),
			'edit_item'     => __( 'Edit Update', 'drumstudy' ),
		];
	}
	return $args;
}
add_filter( 'register_post_type_args', 'drumstudy_relabel_posts_filter', 10, 2 );

/**
 * Filter callback to relabel only the 'post' post type.
 *
 * @param array<string, mixed> $args      Post type args.
 * @param string               $post_type Post type slug.
 * @return array<string, mixed>
 */
function drumstudy_relabel_posts_filter( array $args, string $post_type ): array {
	if ( 'post' !== $post_type ) {
		return $args;
	}
	return drumstudy_relabel_posts( $args );
}

// ── Plugin install notices ───────────────────────────────────────────────────

/**
 * Show admin notices for required and recommended plugins.
 */
function drumstudy_plugin_install_notice(): void {
	if ( ! current_user_can( 'install_plugins' ) ) {
		return;
	}

	$required = [
		[ 'name' => 'Classic Editor', 'slug' => 'classic-editor', 'file' => 'classic-editor/classic-editor.php' ],
	];

	$recommended = [
		[ 'name' => 'Query Monitor',         'slug' => 'query-monitor',         'file' => 'query-monitor/query-monitor.php' ],
		[ 'name' => 'Accessibility Checker', 'slug' => 'accessibility-checker', 'file' => 'accessibility-checker/accessibility-checker.php' ],
		[ 'name' => 'Broken Link Checker',   'slug' => 'broken-link-checker',   'file' => 'broken-link-checker/broken-link-checker.php' ],
	];

	$missing_required    = array_filter( $required,    fn( $p ) => ! is_plugin_active( $p['file'] ) && ! file_exists( WP_PLUGIN_DIR . '/' . $p['slug'] ) );
	$missing_recommended = array_filter( $recommended, fn( $p ) => ! is_plugin_active( $p['file'] ) && ! file_exists( WP_PLUGIN_DIR . '/' . $p['slug'] ) );

	if ( $missing_required ) {
		$links = array_map(
			fn( $p ) => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=' . rawurlencode( $p['name'] ) . '&tab=search&type=term' ) ) . '">' . esc_html( $p['name'] ) . '</a>',
			$missing_required
		);
		echo '<div class="notice notice-error"><p><strong>' . esc_html__( 'Drum Study Theme requires:', 'drumstudy' ) . '</strong> ' . implode( ', ', $links ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( $missing_recommended ) {
		$links = array_map(
			fn( $p ) => '<a href="' . esc_url( admin_url( 'plugin-install.php?s=' . rawurlencode( $p['name'] ) . '&tab=search&type=term' ) ) . '">' . esc_html( $p['name'] ) . '</a>',
			$missing_recommended
		);
		echo '<div class="notice notice-info is-dismissible"><p><strong>' . esc_html__( 'Drum Study Theme recommends:', 'drumstudy' ) . '</strong> ' . implode( ', ', $links ) . '</p></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'admin_notices', 'drumstudy_plugin_install_notice' );

// ── Image alt text warnings ──────────────────────────────────────────────────

/**
 * Warn editors when an image field on the current post is missing alt text.
 * Each meta file defines $drumstudy_image_meta_keys for its post type.
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
		 * Each meta file should add to this via the drumstudy_image_meta_keys filter.
		 *
		 * @param array<string> $keys     Image meta key names.
		 * @param int           $post_id  Current post ID.
		 */
		$image_keys = apply_filters( 'drumstudy_image_meta_keys', [], $post_id );

		foreach ( $image_keys as $key ) {
			$img_id = absint( get_post_meta( $post_id, $key, true ) );
			if ( $img_id && ! get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ) {
				printf(
					'<div class="notice notice-warning"><p>%s</p></div>',
					sprintf(
						/* translators: %s: image title */
						esc_html__( 'Image "%s" is missing alt text. Please add it in the Media Library.', 'drumstudy' ),
						esc_html( get_the_title( $img_id ) )
					)
				);
			}
		}
	}
);
