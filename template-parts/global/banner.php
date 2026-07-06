<?php
/**
 * Announcement Banner
 *
 * Loads above nav when drumstudy_banner_active is set.
 * Dismissed via sessionStorage in app.js.
 *
 * @package drumstudy
 */

if ( ! drumstudy_get_option( 'drumstudy_banner_active' ) ) {
	return;
}

$text      = drumstudy_get_option( 'drumstudy_banner_text' );
if ( ! $text ) {
	return;
}

$cta_label = drumstudy_get_option( 'drumstudy_banner_cta_label' );
$cta_url   = drumstudy_get_option( 'drumstudy_banner_cta_url' );
?>
<div class="tts-banner" role="region" aria-label="<?php esc_attr_e( 'Site announcement', 'drumstudy' ); ?>" id="tts-banner">
	<div class="tts-container flex items-center justify-between gap-4 py-2">
		<p class="m-0"><?php echo wp_kses_post( $text ); ?></p>
		<?php if ( $cta_label && $cta_url ) : ?>
			<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $cta_url ) ); ?>"
			   class="tts-banner__cta shrink-0">
				<?php echo esc_html( $cta_label ); ?>
			</a>
		<?php endif; ?>
		<button type="button"
		        class="tts-banner__close ml-auto shrink-0"
		        aria-label="<?php esc_attr_e( 'Dismiss announcement', 'drumstudy' ); ?>">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
</div>
