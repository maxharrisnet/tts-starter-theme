<?php
/**
 * Section: Hours & Location
 * Renders the location.php partial inside a section wrapper.
 *
 * @package drumstudy
 */

if ( ! drumstudy_has_option( 'drumstudy_address_1' ) && ! drumstudy_has_option( 'drumstudy_hours' ) ) {
	return;
}
?>
<section class="tts-section" id="location" aria-labelledby="location-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="location-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Find Us', 'drumstudy' ); ?>
			</h2>
		</div>
		<?php get_template_part( 'template-parts/global/location' ); ?>
	</div>
</section>
