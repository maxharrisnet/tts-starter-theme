<?php
/**
 * Section: Testimonials
 *
 * @package tts-theme
 */

$query = new WP_Query( [
	'post_type'      => 'tts_testimonial',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="testimonials" aria-labelledby="testimonials-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="testimonials-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'What People Say', 'tts-theme' ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-testimonial' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
