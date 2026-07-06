<?php
/**
 * Section: Gear Guide
 *
 * Fixed-slot "Beginner Starter Kit" — 3 permanent, developer-defined items
 * (not a repeater; see CLAUDE.md's fixed-slot precedent in template-features.php).
 *
 * @package drumstudy
 */

$post_id = get_the_ID() ?: 0;
$items   = [];
for ( $i = 1; $i <= 3; $i++ ) {
	$label = get_post_meta( $post_id, "gear_{$i}_label", true );
	if ( ! $label ) {
		continue;
	}
	$items[] = [
		'label' => $label,
		'body'  => get_post_meta( $post_id, "gear_{$i}_body", true ),
		'url'   => get_post_meta( $post_id, "gear_{$i}_url", true ),
		'image' => absint( get_post_meta( $post_id, "gear_{$i}_image", true ) ),
	];
}

if ( empty( $items ) ) {
	return;
}
?>
<section class="tts-section" id="gear-guide" aria-labelledby="gear-guide-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="gear-guide-heading" class="tts-section-heading__title">
				<?php echo esc_html( get_post_meta( $post_id, 'gear_guide_headline', true ) ?: __( 'Beginner Starter Kit', 'drumstudy' ) ); ?>
			</h2>
			<?php $subtitle = get_post_meta( $post_id, 'gear_guide_subtitle', true ); ?>
			<?php if ( $subtitle ) : ?>
				<p class="tts-section-heading__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>
		</div>

		<div class="tts-grid-3">
			<?php foreach ( $items as $item ) : ?>
				<div class="tts-card">
					<?php if ( $item['image'] ) : ?>
						<div class="tts-card__image">
							<?php
							echo wp_get_attachment_image(
								$item['image'],
								'tts-card',
								false,
								[
									'loading' => 'lazy',
									'alt'     => get_post_meta( $item['image'], '_wp_attachment_image_alt', true ) ?: '',
								]
							);
							?>
						</div>
					<?php endif; ?>
					<div class="tts-card__body">
						<h3 class="tts-card__title">
							<?php if ( $item['url'] ) : ?>
								<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $item['url'] ) ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $item['label'] ); ?>
							<?php endif; ?>
						</h3>
						<?php if ( $item['body'] ) : ?>
							<p><?php echo wp_kses_post( $item['body'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
