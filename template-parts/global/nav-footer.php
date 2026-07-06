<?php
/**
 * Footer Navigation
 *
 * @package drumstudy
 */

if ( ! has_nav_menu( 'footer' ) ) {
	return;
}
?>
<nav role="navigation" aria-label="<?php esc_attr_e( 'Footer navigation', 'drumstudy' ); ?>">
	<?php
	wp_nav_menu( [
		'theme_location' => 'footer',
		'container'      => false,
		'menu_class'     => 'tts-nav tts-nav-footer',
		'fallback_cb'    => false,
	] );
	?>
</nav>
