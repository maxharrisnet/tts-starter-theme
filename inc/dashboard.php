<?php
/**
 * Custom Admin Dashboard
 *
 * Removes unnecessary WP defaults.
 * Adds: Site Overview, Content Status, Setup Checklist.
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Setup ─────────────────────────────────────────────────────────────────────

/**
 * Register custom dashboard widgets and remove unused defaults.
 */
function tts_setup_dashboard(): void {
	remove_meta_box( 'dashboard_quick_press',   'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary',        'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary',      'dashboard', 'side' );
	remove_meta_box( 'dashboard_site_health',    'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity',       'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now',      'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_drafts',  'dashboard', 'side' );

	wp_add_dashboard_widget(
		'tts_welcome',
		__( 'Site Overview', 'tts-theme' ),
		'tts_dashboard_welcome'
	);
	wp_add_dashboard_widget(
		'tts_status',
		__( 'Content Status', 'tts-theme' ),
		'tts_dashboard_status'
	);
	wp_add_dashboard_widget(
		'tts_checklist',
		__( 'Setup Checklist', 'tts-theme' ),
		'tts_dashboard_checklist'
	);
}
add_action( 'wp_dashboard_setup', 'tts_setup_dashboard' );

// ── Widget: Site Overview ─────────────────────────────────────────────────────

/**
 * Render the Site Overview widget.
 */
function tts_dashboard_welcome(): void {
	$profile       = tts_get_profile();
	$business_name = tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' );
	$options_url   = admin_url( 'options-general.php?page=tts-options' );

	$profile_labels = [
		'booking'   => __( 'Booking', 'tts-theme' ),
		'local'     => __( 'Local Business', 'tts-theme' ),
		'creative'  => __( 'Creative', 'tts-theme' ),
		'venture'   => __( 'Venture', 'tts-theme' ),
		'sales'     => __( 'Sales', 'tts-theme' ),
		'events'    => __( 'Events', 'tts-theme' ),
		'directory' => __( 'Directory', 'tts-theme' ),
		'community' => __( 'Community', 'tts-theme' ),
	];
	$profile_label = $profile_labels[ $profile ] ?? ucfirst( $profile );
	?>
	<p>
		<strong><?php echo esc_html( $business_name ); ?></strong>
		&mdash; <?php echo esc_html( $profile_label ); ?> <?php esc_html_e( 'profile', 'tts-theme' ); ?>
	</p>
	<p style="margin-top:0.75em;">
		<a href="<?php echo esc_url( $options_url ); ?>" class="button button-primary">
			<?php esc_html_e( 'Site Settings', 'tts-theme' ); ?>
		</a>
		&nbsp;
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener" class="button">
			<?php esc_html_e( 'View Site', 'tts-theme' ); ?>
		</a>
	</p>
	<hr style="margin:1em 0;">
	<p style="font-size:0.875rem; color:#666;">
		<?php
		printf(
			/* translators: 1: link open, 2: link close */
			esc_html__( 'Content is managed via %1$sContent menu%2$s. Layout is controlled by the developer.', 'tts-theme' ),
			'<a href="' . esc_url( admin_url( 'edit.php?post_type=tts_service' ) ) . '">',
			'</a>'
		);
		?>
	</p>
	<?php
}

// ── Widget: Content Status ────────────────────────────────────────────────────

/**
 * Render the Content Status widget — counts per CPT.
 */
function tts_dashboard_status(): void {
	$cpts = [
		'tts_service'     => __( 'Services', 'tts-theme' ),
		'tts_testimonial' => __( 'Testimonials', 'tts-theme' ),
		'tts_team'        => __( 'Team Members', 'tts-theme' ),
		'tts_gallery'     => __( 'Gallery Items', 'tts-theme' ),
		'tts_faq'         => __( 'FAQs', 'tts-theme' ),
		'tts_event'       => __( 'Events', 'tts-theme' ),
		'tts_location'    => __( 'Locations', 'tts-theme' ),
		'tts_press'       => __( 'Press Items', 'tts-theme' ),
		'tts_demo'        => __( 'Demo / Video', 'tts-theme' ),
		'post'            => __( 'Updates', 'tts-theme' ),
	];

	echo '<table style="width:100%; border-collapse:collapse;">';
	foreach ( $cpts as $post_type => $label ) {
		$counts = wp_count_posts( $post_type );
		$count  = isset( $counts->publish ) ? (int) $counts->publish : 0;
		$color  = $count > 0 ? '#2271b1' : '#bbb';
		$url    = admin_url( 'edit.php?post_type=' . $post_type );
		printf(
			'<tr><td style="padding:4px 0; width:60%%"><a href="%s">%s</a></td><td style="color:%s; font-weight:600">%d</td></tr>',
			esc_url( $url ),
			esc_html( $label ),
			esc_attr( $color ),
			$count
		);
	}
	echo '</table>';
}

// ── Widget: Setup Checklist ───────────────────────────────────────────────────

/**
 * Render the Setup Checklist widget.
 */
function tts_dashboard_checklist(): void {
	$options_url = admin_url( 'options-general.php?page=tts-options' );
	$menus_url   = admin_url( 'nav-menus.php' );

	$items = [
		[
			'done'  => tts_has_option( 'tts_site_profile' ),
			'label' => __( 'Site profile selected', 'tts-theme' ),
			'link'  => $options_url,
		],
		[
			'done'  => tts_has_option( 'tts_logo' ),
			'label' => __( 'Logo uploaded', 'tts-theme' ),
			'link'  => $options_url,
		],
		[
			'done'  => tts_has_option( 'tts_business_name' ),
			'label' => __( 'Business name set', 'tts-theme' ),
			'link'  => $options_url,
		],
		[
			'done'  => tts_has_option( 'tts_color_primary' ),
			'label' => __( 'Brand colors set', 'tts-theme' ),
			'link'  => $options_url,
		],
		[
			'done'  => tts_has_option( 'tts_cta_primary_label' ),
			'label' => __( 'Primary CTA configured', 'tts-theme' ),
			'link'  => $options_url,
		],
		[
			'done'  => has_nav_menu( 'primary' ),
			'label' => __( 'Primary menu assigned', 'tts-theme' ),
			'link'  => $menus_url,
		],
		[
			'done'  => has_nav_menu( 'footer' ),
			'label' => __( 'Footer menu assigned', 'tts-theme' ),
			'link'  => $menus_url,
		],
		[
			'done'  => (int) wp_count_posts( 'tts_service' )->publish > 0,
			'label' => __( 'At least one Service added', 'tts-theme' ),
			'link'  => admin_url( 'edit.php?post_type=tts_service' ),
		],
		[
			'done'  => ! tts_maintenance_active(),
			'label' => __( 'Maintenance mode OFF', 'tts-theme' ),
			'link'  => $options_url,
		],
	];

	$done_count  = count( array_filter( $items, fn( $item ) => $item['done'] ) );
	$total_count = count( $items );

	printf(
		'<p style="margin-bottom:0.75em; font-size:0.875rem; color:#666;">%d / %d %s</p>',
		$done_count,
		$total_count,
		esc_html__( 'items complete', 'tts-theme' )
	);

	echo '<ul style="margin:0; padding:0; list-style:none;">';
	foreach ( $items as $item ) {
		$icon  = $item['done'] ? '✅' : '⬜';
		$style = $item['done'] ? 'color:#888; text-decoration:line-through;' : '';
		printf(
			'<li style="padding:3px 0;">%s <a href="%s" style="%s">%s</a></li>',
			$icon,
			esc_url( $item['link'] ),
			esc_attr( $style ),
			esc_html( $item['label'] )
		);
	}
	echo '</ul>';
}
