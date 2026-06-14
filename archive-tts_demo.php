<?php
/**
 * Archive: Demos / Videos
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="archive-heading">
		<div class="tts-container">
			<header class="tts-section-heading">
				<h1 id="archive-heading" class="tts-section-heading__title">
					<?php echo esc_html( tts_get_option( 'tts_archive_header_demos' ) ?: __( 'Videos &amp; Demos', 'tts-theme' ) ); ?>
				</h1>
			</header>

			<?php if ( have_posts() ) : ?>
				<div class="tts-grid-3">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/cards/card-demo' );
					}
					?>
				</div>
				<?php get_template_part( 'template-parts/global/pagination' ); ?>
			<?php else : ?>
				<p class="py-12 text-center"><?php esc_html_e( 'No videos found.', 'tts-theme' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
