<?php
/**
 * Custom Admin Dashboard
 *
 * Removes unnecessary WP defaults.
 * Adds: Site Overview, Content Status, Setup Checklist.
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Setup ─────────────────────────────────────────────────────────────────────

/**
 * Register custom dashboard widgets and remove unused defaults.
 */
function drumstudy_setup_dashboard(): void {
	remove_meta_box( 'dashboard_quick_press',   'dashboard', 'side' );
	remove_meta_box( 'dashboard_primary',        'dashboard', 'side' );
	remove_meta_box( 'dashboard_secondary',      'dashboard', 'side' );
	remove_meta_box( 'dashboard_site_health',    'dashboard', 'normal' );
	remove_meta_box( 'dashboard_activity',       'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now',      'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_drafts',  'dashboard', 'side' );

	wp_add_dashboard_widget(
		'drumstudy_welcome',
		__( 'Site Overview', 'drumstudy' ),
		'drumstudy_dashboard_welcome'
	);
	wp_add_dashboard_widget(
		'drumstudy_status',
		__( 'Content Status', 'drumstudy' ),
		'drumstudy_dashboard_status'
	);
	wp_add_dashboard_widget(
		'drumstudy_checklist',
		__( 'Setup Checklist', 'drumstudy' ),
		'drumstudy_dashboard_checklist'
	);
}
add_action( 'wp_dashboard_setup', 'drumstudy_setup_dashboard' );

// ── Widget: Site Overview ─────────────────────────────────────────────────────

/**
 * Render the Site Overview widget.
 */
function drumstudy_dashboard_welcome(): void {
	$profile       = drumstudy_get_profile();
	$business_name = drumstudy_get_option( 'drumstudy_business_name' ) ?: get_bloginfo( 'name' );
	$options_url   = admin_url( 'options-general.php?page=drumstudy-options' );

	$profile_labels = [
		'booking'   => __( 'Booking', 'drumstudy' ),
		'local'     => __( 'Local Business', 'drumstudy' ),
		'creative'  => __( 'Creative', 'drumstudy' ),
		'venture'   => __( 'Venture', 'drumstudy' ),
		'sales'     => __( 'Sales', 'drumstudy' ),
		'events'    => __( 'Events', 'drumstudy' ),
		'directory' => __( 'Directory', 'drumstudy' ),
		'community' => __( 'Community', 'drumstudy' ),
	];
	$profile_label = $profile_labels[ $profile ] ?? ucfirst( $profile );
	?>
	<p>
		<strong><?php echo esc_html( $business_name ); ?></strong>
		&mdash; <?php echo esc_html( $profile_label ); ?> <?php esc_html_e( 'profile', 'drumstudy' ); ?>
	</p>
	<p style="margin-top:0.75em;">
		<a href="<?php echo esc_url( $options_url ); ?>" class="button button-primary">
			<?php esc_html_e( 'Site Settings', 'drumstudy' ); ?>
		</a>
		&nbsp;
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener" class="button">
			<?php esc_html_e( 'View Site', 'drumstudy' ); ?>
		</a>
	</p>
	<hr style="margin:1em 0;">
	<p style="font-size:0.875rem; color:#666;">
		<?php
		printf(
			/* translators: 1: link open, 2: link close */
			esc_html__( 'Content is managed via %1$sContent menu%2$s. Layout is controlled by the developer.', 'drumstudy' ),
			'<a href="' . esc_url( admin_url( 'edit.php?post_type=drumstudy_service' ) ) . '">',
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
function drumstudy_dashboard_status(): void {
	$cpts = [
		'drumstudy_service'     => __( 'Services', 'drumstudy' ),
		'drumstudy_testim'      => __( 'Testimonials', 'drumstudy' ),
		'drumstudy_team'        => __( 'Team Members', 'drumstudy' ),
		'drumstudy_gallery'     => __( 'Gallery Items', 'drumstudy' ),
		'drumstudy_faq'         => __( 'FAQs', 'drumstudy' ),
		'drumstudy_event'       => __( 'Events', 'drumstudy' ),
		'drumstudy_location'    => __( 'Locations', 'drumstudy' ),
		'drumstudy_press'       => __( 'Press Items', 'drumstudy' ),
		'drumstudy_demo'        => __( 'Demo / Video', 'drumstudy' ),
		'post'            => __( 'Updates', 'drumstudy' ),
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
function drumstudy_dashboard_checklist(): void {
	$options_url = admin_url( 'options-general.php?page=drumstudy-options' );
	$menus_url   = admin_url( 'nav-menus.php' );

	$items = [
		[
			'done'  => drumstudy_has_option( 'drumstudy_site_profile' ),
			'label' => __( 'Site profile selected', 'drumstudy' ),
			'link'  => $options_url,
		],
		[
			'done'  => drumstudy_has_option( 'drumstudy_logo' ),
			'label' => __( 'Logo uploaded', 'drumstudy' ),
			'link'  => $options_url,
		],
		[
			'done'  => drumstudy_has_option( 'drumstudy_business_name' ),
			'label' => __( 'Business name set', 'drumstudy' ),
			'link'  => $options_url,
		],
		[
			'done'  => drumstudy_has_option( 'drumstudy_color_primary' ),
			'label' => __( 'Brand colors set', 'drumstudy' ),
			'link'  => $options_url,
		],
		[
			'done'  => drumstudy_has_option( 'drumstudy_cta_primary_label' ),
			'label' => __( 'Primary CTA configured', 'drumstudy' ),
			'link'  => $options_url,
		],
		[
			'done'  => has_nav_menu( 'primary' ),
			'label' => __( 'Primary menu assigned', 'drumstudy' ),
			'link'  => $menus_url,
		],
		[
			'done'  => has_nav_menu( 'footer' ),
			'label' => __( 'Footer menu assigned', 'drumstudy' ),
			'link'  => $menus_url,
		],
		[
			'done'  => (int) wp_count_posts( 'drumstudy_service' )->publish > 0,
			'label' => __( 'At least one Service added', 'drumstudy' ),
			'link'  => admin_url( 'edit.php?post_type=drumstudy_service' ),
		],
		[
			'done'  => ! drumstudy_maintenance_active(),
			'label' => __( 'Maintenance mode OFF', 'drumstudy' ),
			'link'  => $options_url,
		],
	];

	$done_count  = count( array_filter( $items, fn( $item ) => $item['done'] ) );
	$total_count = count( $items );

	printf(
		'<p style="margin-bottom:0.75em; font-size:0.875rem; color:#666;">%d / %d %s</p>',
		$done_count,
		$total_count,
		esc_html__( 'items complete', 'drumstudy' )
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
