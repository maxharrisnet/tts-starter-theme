<?php
/**
 * Card: Demo / Video
 *
 * @package drumstudy
 */

$post_id    = get_the_ID();
$video_url  = get_post_meta( $post_id, 'video_url', true );
$thumb_id   = absint( get_post_meta( $post_id, 'thumbnail_override', true ) );
$duration   = get_post_meta( $post_id, 'duration', true );
$cta_label  = get_post_meta( $post_id, 'cta_label', true );
$cta_url    = get_post_meta( $post_id, 'cta_url', true );
$category   = get_post_meta( $post_id, 'video_category', true );
?>
<article class="tts-card tts-card--demo" aria-label="<?php the_title_attribute(); ?>">
	<?php if ( $thumb_id || has_post_thumbnail() ) :
		$img_id = $thumb_id ?: get_post_thumbnail_id();
		?>
		<div class="tts-card__image tts-card__image--video">
			<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'lazy',
				'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
			] ); ?>
			<?php if ( $video_url ) : ?>
				<span class="tts-card__play-icon" aria-hidden="true">▶</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="tts-card__body">
		<?php if ( $category ) : ?>
			<span class="tts-card__category"><?php echo esc_html( $category ); ?></span>
		<?php endif; ?>

		<h3 class="tts-card__title"><?php the_title(); ?></h3>

		<?php if ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>

		<div class="tts-card__footer flex items-center justify-between gap-3">
			<?php if ( $duration ) : ?>
				<span class="tts-card__duration"><?php echo esc_html( $duration ); ?></span>
			<?php endif; ?>
			<?php if ( $video_url ) : ?>
				<a href="<?php echo esc_url( $video_url ); ?>"
				   target="_blank"
				   rel="noopener noreferrer"
				   class="tts-btn tts-btn--primary"
				   aria-label="<?php printf( esc_attr__( 'Watch: %s', 'drumstudy' ), get_the_title() ); ?>">
					<?php echo esc_html( $cta_label ?: __( 'Watch Now', 'drumstudy' ) ); ?>
				</a>
			<?php elseif ( $cta_url ) : ?>
				<a href="<?php echo drumstudy_the_url( '', 0, $cta_url ); ?>"
				   class="tts-btn tts-btn--primary">
					<?php echo esc_html( $cta_label ?: __( 'Learn More', 'drumstudy' ) ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</article>
