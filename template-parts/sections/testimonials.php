<?php
/**
 * Section: Testimonials
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'drumstudy_testim',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
] );

$review_url = drumstudy_get_option( 'drumstudy_google_review_url' );
$has_testimonials = $query->have_posts();

// Nothing to show either way — bail rather than render an empty section.
if ( ! $has_testimonials && ! $review_url ) {
	return;
}
?>
<section class="tts-section" id="testimonials" aria-labelledby="testimonials-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="testimonials-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'What People Say', 'drumstudy' ); ?>
			</h2>
		</div>

		<?php if ( $has_testimonials ) : ?>
			<div class="tts-grid-3">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					get_template_part( 'template-parts/cards/card-testimonial' );
				}
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>

		<?php if ( $review_url ) : ?>
			<p class="tts-review-prompt">
				<?php esc_html_e( 'Had a good experience?', 'drumstudy' ); ?>
				<a href="<?php echo esc_url( $review_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php esc_html_e( 'Leave us a review on Google', 'drumstudy' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</section>
