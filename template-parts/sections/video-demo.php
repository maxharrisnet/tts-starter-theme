<?php
/**
 * Section: Video / Demo
 *
 * @package tts-theme
 */

$query = new WP_Query( [
	'post_type'      => 'tts_demo',
	'posts_per_page' => 3,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="demos" aria-labelledby="demos-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="demos-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'See It in Action', 'tts-theme' ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-demo' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
