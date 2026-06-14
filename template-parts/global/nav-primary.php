<?php
/**
 * Primary Navigation
 *
 * @package tts-theme
 */
?>
<nav role="navigation" aria-label="<?php esc_attr_e( 'Primary navigation', 'tts-theme' ); ?>">
	<button class="tts-nav-toggle"
	        aria-expanded="false"
	        aria-controls="tts-primary-menu"
	        aria-label="<?php esc_attr_e( 'Toggle navigation', 'tts-theme' ); ?>">
		<svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
			<line x1="3" y1="6" x2="21" y2="6"/>
			<line x1="3" y1="12" x2="21" y2="12"/>
			<line x1="3" y1="18" x2="21" y2="18"/>
		</svg>
	</button>

	<?php
	wp_nav_menu( [
		'theme_location' => 'primary',
		'container'      => false,
		'menu_id'        => 'tts-primary-menu',
		'menu_class'     => 'tts-nav tts-nav-primary',
		'fallback_cb'    => false,
	] );
	?>
</nav>
