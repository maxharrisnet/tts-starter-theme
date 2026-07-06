<?php
/**
 * Footer Layout: Minimal
 * Single row: logo, copyright, legal links.
 * For landing pages or minimal profiles.
 *
 * @package drumstudy
 */

$business_name = drumstudy_get_option( 'drumstudy_business_name' ) ?: get_bloginfo( 'name' );
$logo_id       = absint( drumstudy_get_option( 'drumstudy_logo' ) );
$logo_alt      = drumstudy_get_option( 'drumstudy_logo_alt' ) ?: $business_name;
?>
<footer class="tts-footer tts-footer--minimal" role="contentinfo">
	<div class="tts-container">
		<div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-2">
			<?php if ( $logo_id ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $business_name ); ?>">
					<?php echo wp_get_attachment_image( $logo_id, 'tts-logo', false, [ 'alt' => esc_attr( $logo_alt ), 'class' => 'h-8 w-auto' ] ); ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-inherit no-underline font-semibold">
					<?php echo esc_html( $business_name ); ?>
				</a>
			<?php endif; ?>

			<p class="m-0 text-sm opacity-60">
				&copy; <?php echo esc_html( (string) gmdate( 'Y' ) ); ?> <?php echo esc_html( $business_name ); ?>
			</p>

			<?php
			if ( has_nav_menu( 'footer-legal' ) ) {
				wp_nav_menu( [
					'theme_location' => 'footer-legal',
					'container'      => false,
					'menu_class'     => 'flex gap-4 text-sm',
					'fallback_cb'    => false,
				] );
			}
			?>
		</div>
	</div>
</footer>
