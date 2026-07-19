<?php
/**
 * Section: Booking Location & Hours
 *
 * @package drumstudy
 */

if ( ! drumstudy_has_option( 'drumstudy_address_1' ) && ! drumstudy_has_option( 'drumstudy_hours' ) ) {
	return;
}

$business_name = drumstudy_get_option( 'drumstudy_business_name' );
$address_1     = drumstudy_get_option( 'drumstudy_address_1' );
$address_2     = drumstudy_get_option( 'drumstudy_address_2' );
$city          = drumstudy_get_option( 'drumstudy_city' );
$state         = drumstudy_get_option( 'drumstudy_state' );
$postal        = drumstudy_get_option( 'drumstudy_postal' );
$phone         = drumstudy_get_option( 'drumstudy_phone' );
$email         = drumstudy_get_option( 'drumstudy_email' );
$hours         = drumstudy_get_option( 'drumstudy_hours' );
$map_embed     = drumstudy_get_option( 'drumstudy_map_embed' );

$city_line = array_filter( [ $city, $state, $postal ] );
$maps_query = rawurlencode(
	trim(
		implode(
			', ',
			array_filter( [ $address_1, $address_2, implode( ' ', $city_line ) ] )
		)
	)
);
?>
<section class="tts-section tts-booking-location" aria-labelledby="booking-location-heading">
	<div class="tts-container">
		<div class="tts-section-heading">
			<h2 id="booking-location-heading" class="tts-section-heading__title">
				<?php esc_html_e( 'Location & Hours', 'drumstudy' ); ?>
			</h2>
		</div>

		<div class="tts-booking-location__grid">
			<div class="tts-booking-location__column">
				<h3 class="tts-booking-location__label"><?php esc_html_e( 'Location', 'drumstudy' ); ?></h3>
				<address class="tts-location__address not-italic">
					<?php if ( $business_name ) : ?>
						<p><?php echo esc_html( $business_name ); ?></p>
					<?php endif; ?>
					<?php if ( $address_1 ) : ?>
						<p>
							<?php echo esc_html( $address_1 ); ?><br>
							<?php if ( $address_2 ) : ?>
								<?php echo esc_html( $address_2 ); ?><br>
							<?php endif; ?>
							<?php
							if ( $city_line ) {
								echo esc_html( implode( ', ', $city_line ) );
							}
							?>
						</p>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<p>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>">
								<?php echo esc_html( $phone ); ?>
							</a>
						</p>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					<?php endif; ?>
				</address>

				<?php if ( $maps_query ) : ?>
					<p class="tts-booking-location__directions">
						<a href="<?php echo esc_url( 'https://maps.google.com/maps?q=' . $maps_query ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Get directions', 'drumstudy' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( $hours ) : ?>
				<div class="tts-booking-location__column">
					<h3 class="tts-booking-location__label"><?php esc_html_e( 'Hours', 'drumstudy' ); ?></h3>
					<div class="tts-location__hours">
						<pre class="whitespace-pre-wrap"><?php echo esc_html( $hours ); ?></pre>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $map_embed ) : ?>
			<div class="tts-location__map tts-embed-block tts-booking-location__map">
				<iframe
					src="<?php echo esc_url( $map_embed ); ?>"
					width="100%"
					height="400"
					style="border:0"
					allowfullscreen
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					title="<?php esc_attr_e( 'Location map', 'drumstudy' ); ?>">
				</iframe>
			</div>
		<?php endif; ?>
	</div>
</section>
