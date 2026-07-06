<?php
/**
 * Section: Events Feed (upcoming)
 *
 * @package drumstudy
 */

$events = drumstudy_get_events( 'upcoming', 3 );

if ( ! $events->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="events" aria-labelledby="events-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="events-heading" class="tts-section-heading__title">
				<?php echo esc_html( drumstudy_get_option( 'drumstudy_archive_header_events' ) ?: __( 'Upcoming Events', 'drumstudy' ) ); ?>
			</h2>
		</div>

		<div class="tts-grid-3">
			<?php
			while ( $events->have_posts() ) {
				$events->the_post();
				get_template_part( 'template-parts/cards/card-event' );
			}
			wp_reset_postdata();
			?>
		</div>

		<?php
		$archive = get_post_type_archive_link( 'drumstudy_event' );
		if ( $archive ) {
			drumstudy_render_cta( __( 'View All Events', 'drumstudy' ), $archive );
		}
		?>
	</div>
</section>
