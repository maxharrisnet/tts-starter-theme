<?php
/**
 * Section: Events Feed (upcoming)
 *
 * @package tts-theme
 */

$events = tts_get_events( 'upcoming', 3 );

if ( ! $events->have_posts() ) {
	return;
}
?>
<section class="tts-section" id="events" aria-labelledby="events-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="events-heading" class="tts-section-heading__title">
				<?php echo esc_html( tts_get_option( 'tts_archive_header_events' ) ?: __( 'Upcoming Events', 'tts-theme' ) ); ?>
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
		$archive = get_post_type_archive_link( 'tts_event' );
		if ( $archive ) {
			tts_render_cta( __( 'View All Events', 'tts-theme' ), $archive );
		}
		?>
	</div>
</section>
