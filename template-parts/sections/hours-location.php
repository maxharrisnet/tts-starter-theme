<?php
/**
 * Section: Hours & Location
 * Renders the location.php partial inside a section wrapper.
 *
 * @package tts-theme
 */

if ( ! tts_has_option( 'tts_address_1' ) && ! tts_has_option( 'tts_hours' ) ) {
	return;
}
?>
<section class="tts-section" id="location" aria-labelledby="location-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="location-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Find Us', 'tts-theme' ); ?>
			</h2>
		</div>
		<?php get_template_part( 'template-parts/global/location' ); ?>
	</div>
</section>
