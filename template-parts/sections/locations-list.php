<?php
/**
 * Section: Locations List (Directory profile)
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'drumstudy_location',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="locations" aria-labelledby="locations-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="locations-heading" class="tts-section-heading__title">
				<?php echo esc_html( drumstudy_get_option( 'drumstudy_archive_header_locations' ) ?: __( 'Our Locations', 'drumstudy' ) ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-location' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
