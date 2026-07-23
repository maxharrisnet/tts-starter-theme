<?php
/**
 * Section: Services
 *
 * @package drumstudy
 */

$query = new WP_Query( [
	'post_type'      => 'drumstudy_service',
	'posts_per_page' => 6,
	'no_found_rows'  => true,
	'orderby'        => 'menu_order date',
	'order'          => 'ASC',
	'meta_query'     => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		[
			'relation' => 'OR',
			[ 'key' => 'show_on_marketing', 'compare' => 'NOT EXISTS' ],
			[ 'key' => 'show_on_marketing', 'value' => '0', 'compare' => '!=' ],
		],
	],
] );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="services" aria-labelledby="services-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="services-heading" class="tts-section-heading__title">
				<?php echo esc_html( drumstudy_get_option( 'drumstudy_archive_header_services' ) ?: __( 'Our Services', 'drumstudy' ) ); ?>
			</h2>
		</div>

		<div class="tts-grid-2">
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
