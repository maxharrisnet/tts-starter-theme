<?php
/**
 * Section: Press / As Seen In
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'drumstudy_press',
	'posts_per_page' => 8,
	'no_found_rows'  => true,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section tts-section--sm" id="press" aria-labelledby="press-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="press-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'As Seen In', 'drumstudy' ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-press' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
