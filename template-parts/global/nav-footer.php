<?php
/**
 * Footer Navigation
 *
 * @package tts-theme
 */

if ( ! has_nav_menu( 'footer' ) ) {
	return;
}
?>
<nav role="navigation" aria-label="<?php esc_attr_e( 'Footer navigation', 'tts-theme' ); ?>">
	<?php
	wp_nav_menu( [
		'theme_location' => 'footer',
		'container'      => false,
		'menu_class'     => 'tts-nav tts-nav-footer',
		'fallback_cb'    => false,
	] );
	?>
</nav>
