<?php
/**
 * Archive: Events
 * Supports ?event_view=past toggle.
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );

$view   = ( isset( $_GET['event_view'] ) && 'past' === sanitize_key( $_GET['event_view'] ) ) ? 'past' : 'upcoming'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$events = tts_get_events( $view, -1 );

$upcoming_url = remove_query_arg( 'event_view' );
$past_url     = add_query_arg( 'event_view', 'past' );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="archive-heading">
		<div class="tts-container">
			<header class="tts-section-heading">
				<h1 id="archive-heading" class="tts-section-heading__title">
					<?php echo esc_html( tts_get_option( 'tts_archive_header_events' ) ?: __( 'Events', 'tts-theme' ) ); ?>
				</h1>
			</header>

			<nav class="tts-event-tabs flex gap-4 mb-8" aria-label="<?php esc_attr_e( 'Event view toggle', 'tts-theme' ); ?>">
				<a href="<?php echo esc_url( $upcoming_url ); ?>"
				   class="tts-btn <?php echo 'upcoming' === $view ? 'tts-btn--primary' : 'tts-btn--secondary'; ?>"
				   <?php echo 'upcoming' === $view ? 'aria-current="page"' : ''; ?>>
					<?php esc_html_e( 'Upcoming', 'tts-theme' ); ?>
				</a>
				<a href="<?php echo esc_url( $past_url ); ?>"
				   class="tts-btn <?php echo 'past' === $view ? 'tts-btn--primary' : 'tts-btn--secondary'; ?>"
				   <?php echo 'past' === $view ? 'aria-current="page"' : ''; ?>>
					<?php esc_html_e( 'Past Events', 'tts-theme' ); ?>
				</a>
			</nav>

			<?php if ( $events->have_posts() ) : ?>
				<div class="tts-grid-3">
					<?php
					while ( $events->have_posts() ) {
						$events->the_post();
						get_template_part( 'template-parts/cards/card-event' );
					}
					wp_reset_postdata();
					?>
				</div>
			<?php else : ?>
				<p class="py-12 text-center">
					<?php echo 'past' === $view
						? esc_html__( 'No past events found.', 'tts-theme' )
						: esc_html__( 'No upcoming events found.', 'tts-theme' );
					?>
				</p>
			<?php endif; ?>
		</div>
	</section>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
