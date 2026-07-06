<?php
/**
 * Drum Study Theme Helper Functions
 *
 * These are the foundational helpers used across every template and inc file.
 * Build and load this file before everything else.
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Retrieve a theme option. Always use this — never call get_option() directly.
 *
 * @param string $key     Option key (drumstudy_ prefix expected).
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function drumstudy_get_option( string $key, mixed $default = '' ): mixed {
	return get_option( $key, $default );
}

/**
 * Return the active site profile slug.
 *
 * @return string One of: booking|local|creative|venture|sales|events|directory|community
 */
function drumstudy_get_profile(): string {
	$profile = drumstudy_get_option( 'drumstudy_site_profile', 'local' );
	$valid   = [ 'booking', 'local', 'creative', 'venture', 'sales', 'events', 'directory', 'community' ];
	return in_array( $profile, $valid, true ) ? $profile : 'local';
}

/**
 * Return true if the active profile matches the given slug.
 *
 * @param string $profile Profile slug to test.
 * @return bool
 */
function drumstudy_is_profile( string $profile ): bool {
	return drumstudy_get_profile() === $profile;
}

/**
 * Return true if a theme option is set and non-empty.
 *
 * @param string $key Option key.
 * @return bool
 */
function drumstudy_has_option( string $key ): bool {
	$value = drumstudy_get_option( $key );
	return ! empty( $value );
}

/**
 * Output the standard two-button CTA pattern.
 * Falls back to Admin Options values when labels/URLs are empty.
 *
 * @param string $primary_label   Primary button label. Falls back to drumstudy_cta_primary_label option.
 * @param string $primary_url     Primary button URL. Falls back to drumstudy_cta_primary_url option.
 * @param string $secondary_label Secondary button label.
 * @param string $secondary_url   Secondary button URL.
 * @return void
 */
function drumstudy_render_cta(
	string $primary_label   = '',
	string $primary_url     = '',
	string $secondary_label = '',
	string $secondary_url   = ''
): void {
	$p_label = $primary_label   ?: drumstudy_get_option( 'drumstudy_cta_primary_label' );
	$p_url   = $primary_url     ?: drumstudy_get_option( 'drumstudy_cta_primary_url' );
	$s_label = $secondary_label ?: drumstudy_get_option( 'drumstudy_cta_secondary_label' );
	$s_url   = $secondary_url   ?: drumstudy_get_option( 'drumstudy_cta_secondary_url' );

	if ( ! $p_label && ! $s_label ) {
		return;
	}
	?>
	<div class="tts-cta-pair flex flex-col sm:flex-row gap-4">
		<?php if ( $p_label && $p_url ) : ?>
			<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $p_url ) ); ?>"
			   class="tts-btn tts-btn--primary">
				<?php echo esc_html( $p_label ); ?>
			</a>
		<?php endif; ?>
		<?php if ( $s_label && $s_url ) : ?>
			<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $s_url ) ); ?>"
			   class="tts-btn tts-btn--secondary">
				<?php echo esc_html( $s_label ); ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Retrieve a post meta image field and return wp_get_attachment_image() output.
 * Always stores/retrieves attachment IDs — never URLs.
 *
 * @param string $meta_key Meta key holding the attachment ID.
 * @param int    $post_id  Post ID. Defaults to current post.
 * @param string $size     Image size name.
 * @return string HTML img tag or empty string.
 */
function drumstudy_get_image( string $meta_key, int $post_id = 0, string $size = 'large' ): string {
	$pid    = $post_id ?: get_the_ID();
	$img_id = absint( get_post_meta( $pid, $meta_key, true ) );
	if ( ! $img_id ) {
		return '';
	}
	$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $img_id );
	return wp_get_attachment_image( $img_id, $size, false, [ 'alt' => $alt ] );
}

/**
 * Retrieve an Admin Options image field and return wp_get_attachment_image() output.
 *
 * @param string $key  Option key holding the attachment ID.
 * @param string $size Image size name.
 * @return string HTML img tag or empty string.
 */
function drumstudy_get_image_option( string $key, string $size = 'large' ): string {
	$img_id = absint( drumstudy_get_option( $key ) );
	if ( ! $img_id ) {
		return '';
	}
	$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: drumstudy_get_option( 'drumstudy_business_name', 'Logo' );
	return wp_get_attachment_image( $img_id, $size, false, [ 'alt' => $alt ] );
}

/**
 * Include a section template part from template-parts/sections/.
 *
 * @param string $slug Section slug (without path or .php).
 * @return void
 */
function drumstudy_render_section( string $slug ): void {
	get_template_part( 'template-parts/sections/' . sanitize_key( $slug ) );
}

/**
 * Include a card template part from template-parts/cards/.
 *
 * @param string $slug Card slug (without path or .php).
 * @return void
 */
function drumstudy_render_card( string $slug ): void {
	get_template_part( 'template-parts/cards/' . sanitize_key( $slug ) );
}

/**
 * Process a URL value for safe output in href attributes.
 *
 * Handles three cases:
 *  - External/absolute URLs (http/https) → esc_url()
 *  - Relative paths (/about, ../contact) → esc_attr()
 *  - Anchor links (#section-id)           → esc_attr()
 *
 * Pass a meta key + post ID to retrieve from meta, or pass a raw URL as $raw_url.
 *
 * @param string $meta_key Option meta key to retrieve URL from. Pass '' to use $raw_url.
 * @param int    $post_id  Post ID. Defaults to current post.
 * @param string $raw_url  Direct URL value. Used when $meta_key is empty.
 * @return string Sanitized URL safe for output in href.
 */
function drumstudy_the_url( string $meta_key, int $post_id = 0, string $raw_url = '' ): string {
	if ( $raw_url ) {
		$url = $raw_url;
	} elseif ( $meta_key ) {
		$pid = $post_id ?: get_the_ID();
		$url = (string) get_post_meta( $pid, $meta_key, true );
	} else {
		return '';
	}

	$url = trim( $url );
	if ( ! $url ) {
		return '';
	}

	// Block protocol-relative URLs — ambiguous and exploitable.
	if ( str_starts_with( $url, '//' ) ) {
		return '';
	}

	// External / absolute URLs: only http and https allowed.
	if ( str_starts_with( $url, 'http://' ) || str_starts_with( $url, 'https://' ) ) {
		return esc_url( $url );
	}

	// mailto: and tel: — safe non-HTTP absolute schemes.
	if ( str_starts_with( $url, 'mailto:' ) || str_starts_with( $url, 'tel:' ) ) {
		return esc_url( $url, [ 'mailto', 'tel' ] );
	}

	// Reject any other scheme (javascript:, data:, vbscript:, etc.).
	if ( preg_match( '/^[a-z][a-z0-9+\-.]*:/i', $url ) ) {
		return '';
	}

	// Safe relative paths: must start with / or # only.
	if ( str_starts_with( $url, '#' ) || str_starts_with( $url, '/' ) ) {
		return esc_attr( $url );
	}

	return '';
}

/**
 * Return a clearly marked placeholder string for unfilled custom fields.
 * Never use real-sounding default copy — always use this.
 *
 * @param string $field_label Human-readable field label.
 * @return string e.g. '[PLACEHOLDER: Hero Headline]'
 */
function drumstudy_placeholder( string $field_label ): string {
	return '[PLACEHOLDER: ' . $field_label . ']';
}

/**
 * Return true if a post meta field exists and is non-empty.
 *
 * @param string $meta_key Meta key to check.
 * @param int    $post_id  Post ID. Defaults to current post.
 * @return bool
 */
function drumstudy_has_meta( string $meta_key, int $post_id = 0 ): bool {
	$pid   = $post_id ?: get_the_ID();
	$value = get_post_meta( $pid, $meta_key, true );
	return ! empty( $value );
}

/**
 * Return an array of [platform => url] for all non-empty social options.
 *
 * @return array<string, string>
 */
function drumstudy_social_links(): array {
	$platforms = [
		'facebook'   => drumstudy_get_option( 'drumstudy_social_facebook' ),
		'instagram'  => drumstudy_get_option( 'drumstudy_social_instagram' ),
		'x'          => drumstudy_get_option( 'drumstudy_social_x' ),
		'linkedin'   => drumstudy_get_option( 'drumstudy_social_linkedin' ),
		'youtube'    => drumstudy_get_option( 'drumstudy_social_youtube' ),
		'tiktok'     => drumstudy_get_option( 'drumstudy_social_tiktok' ),
		'spotify'    => drumstudy_get_option( 'drumstudy_social_spotify' ),
		'soundcloud' => drumstudy_get_option( 'drumstudy_social_soundcloud' ),
	];

	return array_filter( $platforms );
}

/**
 * Return true if maintenance mode is currently active.
 *
 * @return bool
 */
function drumstudy_maintenance_active(): bool {
	return (bool) drumstudy_get_option( 'drumstudy_maintenance_active' );
}

/**
 * Query upcoming or past events.
 *
 * @param string $view  'upcoming' or 'past'.
 * @param int    $limit Number of posts. Use -1 for all (sets no_found_rows false).
 * @return WP_Query
 */
function drumstudy_get_events( string $view = 'upcoming', int $limit = 6 ): WP_Query {
	$today   = gmdate( 'Y-m-d' );
	$compare = ( 'past' === $view ) ? '<' : '>=';
	$order   = ( 'past' === $view ) ? 'DESC' : 'ASC';

	return new WP_Query(
		[
			'post_type'      => 'drumstudy_event',
			'posts_per_page' => $limit,
			'orderby'        => 'meta_value',
			'meta_key'       => 'event_date',
			'order'          => $order,
			'no_found_rows'  => ( -1 !== $limit ),
			'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				[
					'key'     => 'event_date',
					'value'   => $today,
					'compare' => $compare,
					'type'    => 'DATE',
				],
			],
		]
	);
}

/**
 * Process and render a flexible embed/shortcode/oEmbed value.
 *
 * @param string $content Raw content from meta field.
 * @return string Processed HTML.
 */
function drumstudy_render_embed( string $content ): string {
	$content = do_shortcode( $content );

	// If content looks like a bare URL, try oEmbed.
	$trimmed = trim( $content );
	if ( filter_var( $trimmed, FILTER_VALIDATE_URL ) ) {
		$oembed = wp_oembed_get( $trimmed );
		if ( $oembed ) {
			return $oembed;
		}
	}

	return wp_kses_post( $content );
}
