<?php
/**
 * Card: Event
 *
 * @package drumstudy
 */

$post_id     = get_the_ID();
$event_date  = get_post_meta( $post_id, 'event_date', true );
$event_time  = get_post_meta( $post_id, 'event_time', true );
$location    = get_post_meta( $post_id, 'location_name', true );
$ticket_url  = get_post_meta( $post_id, 'ticket_url', true );
$ticket_price = get_post_meta( $post_id, 'ticket_price', true );
$img_id      = absint( get_post_meta( $post_id, 'event_image', true ) );

$is_past = $event_date && $event_date < gmdate( 'Y-m-d' );

$formatted_date = '';
if ( $event_date ) {
	$timestamp      = strtotime( $event_date );
	$formatted_date = $timestamp ? date_i18n( get_option( 'date_format' ), $timestamp ) : $event_date;
}
?>
<article class="tts-card tts-card--event<?php echo $is_past ? ' tts-card--past' : ''; ?>"
         aria-label="<?php the_title_attribute(); ?>">
	<?php if ( $img_id ) : ?>
		<div class="tts-card__image">
			<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'lazy',
				'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
			] ); ?>
		</div>
	<?php endif; ?>

	<div class="tts-card__body">
		<?php if ( $formatted_date ) : ?>
			<time class="tts-card__date" datetime="<?php echo esc_attr( $event_date ); ?>">
				<?php echo esc_html( $formatted_date ); ?>
				<?php if ( $event_time ) : ?>
					<span class="tts-card__time"><?php echo esc_html( $event_time ); ?></span>
				<?php endif; ?>
			</time>
		<?php endif; ?>

		<h3 class="tts-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $location ) : ?>
			<p class="tts-card__location"><?php echo esc_html( $location ); ?></p>
		<?php endif; ?>

		<?php if ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>

		<?php if ( ! $is_past && $ticket_url ) : ?>
			<div class="tts-card__footer flex items-center gap-3">
				<?php if ( $ticket_price ) : ?>
					<span class="tts-card__price"><?php echo esc_html( $ticket_price ); ?></span>
				<?php endif; ?>
				<a href="<?php echo drumstudy_the_url( '', 0, $ticket_url ); ?>"
				   class="tts-btn tts-btn--primary"
				   target="_blank"
				   rel="noopener noreferrer">
					<?php esc_html_e( 'Get Tickets', 'drumstudy' ); ?>
				</a>
			</div>
		<?php elseif ( $is_past ) : ?>
			<p class="tts-card__past-notice"><?php esc_html_e( 'This event has passed.', 'drumstudy' ); ?></p>
		<?php endif; ?>
	</div>
</article>
