<?php
/**
 * Section: Booking Embed
 * Renders the booking platform embed from Admin Options Tab 05.
 *
 * @package drumstudy
 */

$embed = drumstudy_get_option( 'drumstudy_embed_booking' );
if ( ! $embed ) {
	return;
}
?>
<section class="tts-section" id="booking" aria-labelledby="booking-heading">
	<div class="tts-container-prose">
		<div class="tts-section-heading">
			<h2 id="booking-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Book a Time', 'drumstudy' ); ?>
			</h2>
		</div>
		<div class="tts-embed-block">
			<?php echo drumstudy_render_embed( $embed ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — drumstudy_render_embed() sanitizes via do_shortcode/wp_kses_post ?>
		</div>
	</div>
</section>
