<?php
/**
 * Card: Service
 *
 * @package drumstudy
 */

$post_id   = get_the_ID();
$price     = get_post_meta( $post_id, 'price', true );
$img_id    = absint( get_post_meta( $post_id, 'service_image', true ) );
$cta_label = get_post_meta( $post_id, 'cta_label', true ) ?: drumstudy_get_option( 'drumstudy_cta_primary_label' );
$cta_url   = get_post_meta( $post_id, 'cta_url', true ) ?: get_permalink();
?>
<article class="tts-card" aria-label="<?php the_title_attribute(); ?>">
	<?php if ( $img_id ) : ?>
		<div class="tts-card__image">
			<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'lazy',
				'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
			] ); ?>
		</div>
	<?php endif; ?>

	<div class="tts-card__body">
		<h3 class="tts-card__title"><?php the_title(); ?></h3>

		<?php if ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>

		<?php if ( $price ) : ?>
			<p class="tts-card__price"><?php echo esc_html( $price ); ?></p>
		<?php endif; ?>

		<?php if ( $cta_label && $cta_url ) : ?>
			<a href="<?php echo drumstudy_the_url( '', 0, $cta_url ); ?>"
			   class="tts-btn tts-btn--primary">
				<?php echo esc_html( $cta_label ); ?>
			</a>
		<?php endif; ?>
	</div>
</article>
