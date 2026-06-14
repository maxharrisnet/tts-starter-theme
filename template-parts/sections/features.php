<?php
/**
 * Section: Features (homepage variant — 3-up teaser)
 *
 * Full 6-slot version lives in template-features.php.
 * This section shows 3 feature highlights on the homepage.
 *
 * @package tts-theme
 */

$post_id = get_the_ID() ?: 0;

// Check at least one slot is populated
$has_features = false;
for ( $i = 1; $i <= 3; $i++ ) {
	if ( get_post_meta( $post_id, "feature_{$i}_headline", true ) ) {
		$has_features = true;
		break;
	}
}

if ( ! $has_features ) {
	return;
}
?>
<section class="tts-section" id="features" aria-labelledby="features-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="features-heading" class="tts-section-heading__title">
				<?php echo esc_html( get_post_meta( $post_id, 'features_headline', true ) ?: __( 'Why Choose Us', 'tts-theme' ) ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php for ( $i = 1; $i <= 3; $i++ ) :
				$headline = get_post_meta( $post_id, "feature_{$i}_headline", true );
				$body     = get_post_meta( $post_id, "feature_{$i}_body", true );
				$icon_id  = absint( get_post_meta( $post_id, "feature_{$i}_icon", true ) );
				if ( ! $headline ) continue;
				?>
				<div class="tts-feature-item flex flex-col gap-4">
					<?php if ( $icon_id ) : ?>
						<div class="tts-feature-item__icon w-12 h-12">
							<?php echo wp_get_attachment_image( $icon_id, 'thumbnail', false, [ 'class' => 'w-full h-full object-contain', 'loading' => 'lazy', 'alt' => '' ] ); ?>
						</div>
					<?php endif; ?>
					<h3 class="tts-card__title"><?php echo esc_html( $headline ); ?></h3>
					<?php if ( $body ) : ?>
						<p><?php echo wp_kses_post( $body ); ?></p>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>
	</div>
</section>
