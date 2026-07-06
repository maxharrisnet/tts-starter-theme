<?php
/**
 * Section: Updates / News Feed
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="updates" aria-labelledby="updates-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="updates-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Latest Updates', 'drumstudy' ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-update' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
