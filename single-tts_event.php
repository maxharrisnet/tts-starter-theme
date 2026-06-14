<?php
/**
 * Single: Event
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );

$post_id         = get_the_ID();
$event_date      = get_post_meta( $post_id, 'event_date', true );
$event_time      = get_post_meta( $post_id, 'event_time', true );
$end_date        = get_post_meta( $post_id, 'end_date', true );
$end_time        = get_post_meta( $post_id, 'end_time', true );
$location_name   = get_post_meta( $post_id, 'location_name', true );
$location_addr   = get_post_meta( $post_id, 'location_address', true );
$ticket_url      = get_post_meta( $post_id, 'ticket_url', true );
$ticket_price    = get_post_meta( $post_id, 'ticket_price', true );
$img_id          = absint( get_post_meta( $post_id, 'event_image', true ) );
$organizer       = get_post_meta( $post_id, 'organizer', true );
$embed_block     = get_post_meta( $post_id, 'embed_block', true );

$is_past = $event_date && $event_date < gmdate( 'Y-m-d' );

$formatted_start = '';
if ( $event_date ) {
	$ts              = strtotime( $event_date );
	$formatted_start = $ts ? date_i18n( get_option( 'date_format' ), $ts ) : $event_date;
}
?>
<main id="main-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container">

				<?php if ( $is_past ) : ?>
					<div class="tts-notice tts-notice--past mb-6" role="status">
						<?php esc_html_e( 'This event has passed.', 'tts-theme' ); ?>
					</div>
				<?php endif; ?>

				<div class="flex flex-col lg:flex-row gap-12">
					<div class="w-full lg:w-2/3">
						<header class="tts-article-header mb-8">
							<h1><?php the_title(); ?></h1>

							<dl class="tts-event-meta">
								<?php if ( $formatted_start ) : ?>
									<div class="tts-event-meta__item">
										<dt><?php esc_html_e( 'Date', 'tts-theme' ); ?></dt>
										<dd>
											<time datetime="<?php echo esc_attr( $event_date ); ?>">
												<?php echo esc_html( $formatted_start ); ?>
												<?php if ( $event_time ) : ?>
													<?php echo ' &mdash; ' . esc_html( $event_time ); ?>
												<?php endif; ?>
											</time>
											<?php if ( $end_date && $end_date !== $event_date ) :
												$ts_end      = strtotime( $end_date );
												$fmt_end     = $ts_end ? date_i18n( get_option( 'date_format' ), $ts_end ) : $end_date;
												?>
												<span> <?php esc_html_e( 'to', 'tts-theme' ); ?> </span>
												<time datetime="<?php echo esc_attr( $end_date ); ?>">
													<?php echo esc_html( $fmt_end ); ?>
													<?php if ( $end_time ) echo ' &mdash; ' . esc_html( $end_time ); ?>
												</time>
											<?php endif; ?>
										</dd>
									</div>
								<?php endif; ?>

								<?php if ( $location_name || $location_addr ) : ?>
									<div class="tts-event-meta__item">
										<dt><?php esc_html_e( 'Location', 'tts-theme' ); ?></dt>
										<dd>
											<?php if ( $location_name ) echo esc_html( $location_name ); ?>
											<?php if ( $location_addr ) echo '<br>' . esc_html( $location_addr ); ?>
										</dd>
									</div>
								<?php endif; ?>

								<?php if ( $organizer ) : ?>
									<div class="tts-event-meta__item">
										<dt><?php esc_html_e( 'Organizer', 'tts-theme' ); ?></dt>
										<dd><?php echo esc_html( $organizer ); ?></dd>
									</div>
								<?php endif; ?>

								<?php if ( $ticket_price ) : ?>
									<div class="tts-event-meta__item">
										<dt><?php esc_html_e( 'Price', 'tts-theme' ); ?></dt>
										<dd><?php echo esc_html( $ticket_price ); ?></dd>
									</div>
								<?php endif; ?>
							</dl>
						</header>

						<div class="tts-prose">
							<?php the_content(); ?>
						</div>

						<?php if ( $embed_block ) : ?>
							<div class="tts-embed-block mt-8">
								<?php echo tts_render_embed( $embed_block ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>

						<?php if ( ! $is_past && $ticket_url ) : ?>
							<div class="tts-cta-strip mt-10">
								<?php tts_render_cta( __( 'Get Tickets', 'tts-theme' ), $ticket_url ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $img_id ) : ?>
						<aside class="w-full lg:w-1/3">
							<?php echo wp_get_attachment_image( $img_id, 'tts-feature', false, [
								'class'   => 'w-full h-auto rounded',
								'loading' => 'eager',
								'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
							] ); ?>
						</aside>
					<?php endif; ?>
				</div>
			</div>
		</article>

	<?php endwhile; ?>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
