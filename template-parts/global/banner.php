<?php
/**
 * Announcement Banner
 *
 * Loads above nav when tts_banner_active is set.
 * Dismissed via sessionStorage in app.js.
 *
 * @package tts-theme
 */

if ( ! tts_get_option( 'tts_banner_active' ) ) {
	return;
}

$text      = tts_get_option( 'tts_banner_text' );
if ( ! $text ) {
	return;
}

$cta_label = tts_get_option( 'tts_banner_cta_label' );
$cta_url   = tts_get_option( 'tts_banner_cta_url' );
?>
<div class="tts-banner" role="region" aria-label="<?php esc_attr_e( 'Site announcement', 'tts-theme' ); ?>" id="tts-banner">
	<div class="tts-container flex items-center justify-between gap-4 py-2">
		<p class="m-0"><?php echo wp_kses_post( $text ); ?></p>
		<?php if ( $cta_label && $cta_url ) : ?>
			<a href="<?php echo esc_attr( tts_the_url( '', 0, $cta_url ) ); ?>"
			   class="tts-banner__cta shrink-0">
				<?php echo esc_html( $cta_label ); ?>
			</a>
		<?php endif; ?>
		<button type="button"
		        class="tts-banner__close ml-auto shrink-0"
		        aria-label="<?php esc_attr_e( 'Dismiss announcement', 'tts-theme' ); ?>">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
</div>
