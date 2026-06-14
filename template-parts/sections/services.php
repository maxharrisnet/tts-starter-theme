<?php
/**
 * Section: Services
 *
 * @package tts-theme
 */

$query = new WP_Query( [
	'post_type'      => 'tts_service',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="services" aria-labelledby="services-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="services-heading" class="tts-section-heading__title">
				<?php echo esc_html( tts_get_option( 'tts_archive_header_services' ) ?: __( 'Our Services', 'tts-theme' ) ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( 'template-parts/cards/card-service' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
