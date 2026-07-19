<?php
/**
 * Card: Booking Service
 *
 * @package drumstudy
 *
 * @var array $args {
 *     @type int   $post_id Page ID for embed meta lookup.
 *     @type array $service Service definition from drumstudy_get_booking_services().
 * }
 */

$post_id = absint( $args['post_id'] ?? get_the_ID() );
$service = $args['service'] ?? [];

if ( ! $service ) {
	return;
}

$slug          = $service['slug'] ?? sanitize_key( $service['embed_meta_key'] ?? 'service' );
$title         = $service['title'] ?? '';
$description   = $service['description'] ?? '';
$price         = $service['price'] ?? '';
$duration      = $service['duration'] ?? '';
$embed_meta_key = $service['embed_meta_key'] ?? '';
$embed_markup  = drumstudy_get_booking_service_embed( $post_id, $embed_meta_key, $title );
$card_id       = 'booking-service-' . $slug;
?>
<article class="tts-card tts-booking-service" aria-labelledby="<?php echo esc_attr( $card_id ); ?>">
	<div class="tts-card__body tts-booking-service__body">
		<h3 id="<?php echo esc_attr( $card_id ); ?>" class="tts-card__title tts-booking-service__title">
			<?php echo esc_html( $title ); ?>
		</h3>

		<?php if ( $description ) : ?>
			<div class="tts-booking-service__description tts-prose">
				<p><?php echo esc_html( $description ); ?></p>
			</div>
		<?php endif; ?>

		<div class="tts-booking-service__embed">
			<?php echo $embed_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — drumstudy_get_booking_service_embed() escapes placeholder output ?>
		</div>

		<footer class="tts-booking-service__footer">
			<a href="#<?php echo esc_attr( 'square-embed-' . sanitize_key( $embed_meta_key ) ); ?>" class="tts-booking-service__cta">
				<?php esc_html_e( 'Book now', 'drumstudy' ); ?>
			</a>

			<?php if ( $price || $duration ) : ?>
				<p class="tts-booking-service__meta">
					<?php
					echo esc_html(
						implode(
							' • ',
							array_filter( [ $price, $duration ] )
						)
					);
					?>
				</p>
			<?php endif; ?>
		</footer>
	</div>
</article>
