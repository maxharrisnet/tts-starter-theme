<?php
/**
 * Card: Testimonial
 *
 * @package tts-theme
 */

$post_id     = get_the_ID();
$quote       = get_post_meta( $post_id, 'quote', true );
$author      = get_post_meta( $post_id, 'author_name', true );
$role        = get_post_meta( $post_id, 'author_role', true );
$img_id      = absint( get_post_meta( $post_id, 'author_image', true ) );
$rating      = absint( get_post_meta( $post_id, 'rating', true ) );

if ( ! $quote ) {
	return;
}
?>
<figure class="tts-card tts-card--testimonial">
	<?php if ( $rating >= 1 && $rating <= 5 ) : ?>
		<div class="tts-card__rating" aria-label="<?php printf( esc_attr__( '%d out of 5 stars', 'tts-theme' ), $rating ); ?>">
			<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<span aria-hidden="true"><?php echo $i <= $rating ? '★' : '☆'; ?></span>
			<?php endfor; ?>
		</div>
	<?php endif; ?>

	<blockquote class="tts-card__quote">
		<p><?php echo wp_kses_post( $quote ); ?></p>
	</blockquote>

	<figcaption class="tts-card__author">
		<?php if ( $img_id ) : ?>
			<div class="tts-card__avatar">
				<?php echo wp_get_attachment_image( $img_id, 'tts-thumb', false, [
					'class'   => 'w-full h-full object-cover rounded-full',
					'loading' => 'lazy',
					'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: esc_attr( $author ),
				] ); ?>
			</div>
		<?php endif; ?>
		<div>
			<?php if ( $author ) : ?>
				<cite class="tts-card__author-name"><?php echo esc_html( $author ); ?></cite>
			<?php endif; ?>
			<?php if ( $role ) : ?>
				<p class="tts-card__author-role"><?php echo esc_html( $role ); ?></p>
			<?php endif; ?>
		</div>
	</figcaption>
</figure>
