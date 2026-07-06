<?php
/**
 * Section: Donation Embed
 * Renders the donation platform embed from Admin Options Tab 05.
 *
 * @package drumstudy
 */

$embed = drumstudy_get_option( 'drumstudy_embed_donation' );
if ( ! $embed ) {
	return;
}
?>
<section class="tts-section" id="donate" aria-labelledby="donate-heading">
	<div class="tts-container-prose">
		<div class="tts-section-heading">
			<h2 id="donate-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Make a Donation', 'drumstudy' ); ?>
			</h2>
		</div>
		<div class="tts-embed-block">
			<?php echo drumstudy_render_embed( $embed ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
