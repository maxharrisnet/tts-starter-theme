<?php
/**
 * Single: Service
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );

$post_id   = get_the_ID();
$price     = get_post_meta( $post_id, 'price', true );
$img_id    = absint( get_post_meta( $post_id, 'service_image', true ) );
$cta_label = get_post_meta( $post_id, 'cta_label', true ) ?: drumstudy_get_option( 'drumstudy_cta_primary_label' );
$cta_url   = get_post_meta( $post_id, 'cta_url', true ) ?: drumstudy_get_option( 'drumstudy_cta_primary_url' );
?>
<main id="main-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container">
				<div class="flex flex-col lg:flex-row gap-12">
					<div class="w-full lg:w-2/3">
						<header class="tts-article-header mb-8">
							<h1><?php the_title(); ?></h1>
							<?php if ( $price ) : ?>
								<p class="tts-price"><?php echo esc_html( $price ); ?></p>
							<?php endif; ?>
						</header>

						<div class="tts-prose">
							<?php the_content(); ?>
						</div>

						<?php if ( $cta_label && $cta_url ) : ?>
							<div class="tts-cta-strip mt-10">
								<?php drumstudy_render_cta( $cta_label, $cta_url ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $img_id ) : ?>
						<aside class="w-full lg:w-1/3">
							<?php echo wp_get_attachment_image( $img_id, 'tts-feature', false, [
								'class'   => 'w-full h-auto rounded',
								'loading' => 'eager',
								'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
							] ); ?>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</article>

	<?php endwhile; ?>

	<?php get_template_part( 'template-parts/sections/testimonials' ); ?>
	<?php get_template_part( 'template-parts/sections/faqs' ); ?>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
