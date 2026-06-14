<?php
/**
 * Template Name: Donate
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );

$post_id     = get_the_ID();
$headline    = get_post_meta( $post_id, 'donate_headline', true );
$subheadline = get_post_meta( $post_id, 'donate_subheadline', true );
$body        = get_post_meta( $post_id, 'donate_body', true );
$embed       = get_post_meta( $post_id, 'donate_embed', true );

$impacts = [];
for ( $i = 1; $i <= 3; $i++ ) {
	$number = get_post_meta( $post_id, "donate_impact_{$i}_number", true );
	$label  = get_post_meta( $post_id, "donate_impact_{$i}_label", true );
	if ( $number ) {
		$impacts[] = compact( 'number', 'label' );
	}
}
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="donate-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<h1 id="donate-heading" class="tts-section-heading__title">
					<?php echo esc_html( $headline ?: tts_placeholder( 'Donate Headline' ) ); ?>
				</h1>
				<?php if ( $subheadline ) : ?>
					<p class="tts-section-heading__sub"><?php echo esc_html( $subheadline ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $body ) : ?>
				<div class="tts-container-prose mx-auto mb-12">
					<div class="tts-prose"><?php echo wp_kses_post( wpautop( $body ) ); ?></div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $impacts ) ) : ?>
				<div class="tts-stats mb-12" role="list">
					<?php foreach ( $impacts as $impact ) : ?>
						<div class="tts-stats__item" role="listitem">
							<div class="tts-stats__number"><?php echo esc_html( $impact['number'] ); ?></div>
							<?php if ( $impact['label'] ) : ?>
								<div class="tts-stats__label"><?php echo esc_html( $impact['label'] ); ?></div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $embed ) : ?>
				<div class="tts-embed-block">
					<?php echo tts_render_embed( $embed ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php else : ?>
				<div class="tts-embed-block">
					<p><?php echo esc_html( tts_placeholder( 'Donation Platform Embed' ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/sections/testimonials' ); ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
