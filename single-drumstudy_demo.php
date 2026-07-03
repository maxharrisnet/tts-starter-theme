<?php
/**
 * Single: Demo / Video
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );

$post_id   = get_the_ID();
$video_url = get_post_meta( $post_id, 'video_url', true );
$thumb_id  = absint( get_post_meta( $post_id, 'thumbnail_override', true ) );
$duration  = get_post_meta( $post_id, 'duration', true );
$cta_label = get_post_meta( $post_id, 'cta_label', true );
$cta_url   = get_post_meta( $post_id, 'cta_url', true );
$category  = get_post_meta( $post_id, 'video_category', true );
?>
<main id="main-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container">
				<header class="tts-article-header mb-8">
					<?php if ( $category ) : ?>
						<span class="tts-card__category"><?php echo esc_html( $category ); ?></span>
					<?php endif; ?>
					<h1><?php the_title(); ?></h1>
					<?php if ( $duration ) : ?>
						<p class="tts-duration"><?php echo esc_html( $duration ); ?></p>
					<?php endif; ?>
				</header>

				<?php if ( $video_url ) : ?>
					<div class="tts-embed-block mb-8">
						<?php echo drumstudy_render_embed( $video_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php elseif ( $thumb_id || has_post_thumbnail() ) :
					$display_id = $thumb_id ?: get_post_thumbnail_id();
					?>
					<div class="tts-embed-block mb-8">
						<?php echo wp_get_attachment_image( $display_id, 'tts-feature', false, [
							'class'   => 'w-full h-auto rounded',
							'loading' => 'eager',
							'alt'     => get_post_meta( $display_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
						] ); ?>
					</div>
				<?php endif; ?>

				<div class="tts-container-prose tts-prose mx-0">
					<?php the_content(); ?>
				</div>

				<?php if ( $cta_label && $cta_url ) : ?>
					<div class="tts-cta-strip mt-10">
						<?php drumstudy_render_cta( $cta_label, $cta_url ); ?>
					</div>
				<?php endif; ?>
			</div>
		</article>

	<?php endwhile; ?>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
