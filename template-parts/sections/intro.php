<?php
/**
 * Section: Intro / Lead Capture
 *
 * Renders the homepage intro block (home_intro_* meta). Guarded by
 * drumstudy_has_meta( 'home_intro_headline' ) per the calling template.
 *
 * @package drumstudy
 */

$post_id     = get_the_ID() ?: 0;
$headline    = get_post_meta( $post_id, 'home_intro_headline', true );
$body        = get_post_meta( $post_id, 'home_intro_body', true );
$img_id      = absint( get_post_meta( $post_id, 'home_intro_image', true ) );
$cta_label   = get_post_meta( $post_id, 'home_intro_cta_label', true );
$cta_url     = get_post_meta( $post_id, 'home_intro_cta_url', true );

if ( ! $headline ) {
	return;
}
?>
<section class="tts-section tts-section--sm" id="groove-assessment" aria-labelledby="intro-heading">
	<div class="tts-container">
		<div class="flex flex-col md:flex-row items-center gap-10">
			<?php if ( $img_id ) : ?>
				<div class="md:w-2/5 w-full">
					<?php
					echo wp_get_attachment_image(
						$img_id,
						'tts-card',
						false,
						[
							'class'   => 'w-full h-auto rounded-lg',
							'loading' => 'lazy',
							'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: '',
						]
					);
					?>
				</div>
			<?php endif; ?>
			<div class="<?php echo $img_id ? 'md:w-3/5 w-full text-center md:text-left' : 'tts-intro__text-only'; ?>">
				<?php if ( $img_id ) : ?>
					<h2 id="intro-heading" class="tts-section-heading__title">
						<?php echo esc_html( $headline ); ?>
					</h2>
				<?php else : ?>
					<div class="tts-section-heading">
						<h2 id="intro-heading" class="tts-section-heading__title">
							<?php echo esc_html( $headline ); ?>
						</h2>
					</div>
				<?php endif; ?>
				<?php if ( $body ) : ?>
					<?php echo wp_kses_post( wpautop( $body ) ); ?>
				<?php endif; ?>
				<?php if ( $cta_label && $cta_url ) : ?>
					<div class="tts-cta-strip <?php echo $img_id ? 'justify-center md:justify-start' : 'justify-center'; ?> mt-2">
						<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $cta_url ) ); ?>" class="tts-btn tts-btn--primary">
							<?php echo esc_html( $cta_label ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
