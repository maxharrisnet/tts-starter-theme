<?php
/**
 * Template Name: Portfolio
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );

$post_id      = get_the_ID();
$headline     = get_post_meta( $post_id, 'portfolio_headline', true );
$intro        = get_post_meta( $post_id, 'portfolio_intro', true );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="portfolio-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<h1 id="portfolio-heading" class="tts-section-heading__title">
					<?php echo esc_html( $headline ?: drumstudy_placeholder( 'Portfolio Headline' ) ); ?>
				</h1>
				<?php if ( $intro ) : ?>
					<p class="tts-section-heading__sub"><?php echo wp_kses_post( $intro ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/sections/gallery' ); ?>
	<?php get_template_part( 'template-parts/sections/video-demo' ); ?>
	<?php get_template_part( 'template-parts/sections/testimonials' ); ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
