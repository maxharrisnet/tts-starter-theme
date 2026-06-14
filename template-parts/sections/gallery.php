<?php
/**
 * Section: Gallery
 *
 * @package tts-theme
 */

$query = new WP_Query( [
	'post_type'      => 'tts_gallery',
	'posts_per_page' => 9,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="gallery" aria-labelledby="gallery-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="gallery-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Gallery', 'tts-theme' ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-gallery-item' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
