<?php
/**
 * Section: Booking Service Menu
 *
 * @package drumstudy
 */

$post_id           = get_the_ID();
$audience          = get_post_meta( $post_id, 'booking_audience', true ) ?: 'new_client';
$menu_headline     = get_post_meta( $post_id, 'booking_menu_headline', true );
$menu_intro        = get_post_meta( $post_id, 'booking_menu_intro', true );
$my_bookings_url   = get_post_meta( $post_id, 'booking_my_bookings_url', true );
$crosslink_label   = get_post_meta( $post_id, 'booking_crosslink_label', true );
$crosslink_url     = get_post_meta( $post_id, 'booking_crosslink_url', true );
$services          = drumstudy_get_booking_services( $audience );

$business_name = drumstudy_get_option( 'drumstudy_business_name' );
$phone         = drumstudy_get_option( 'drumstudy_phone' );
$email         = drumstudy_get_option( 'drumstudy_email' );
$address_1     = drumstudy_get_option( 'drumstudy_address_1' );
$city          = drumstudy_get_option( 'drumstudy_city' );
$state         = drumstudy_get_option( 'drumstudy_state' );
$postal        = drumstudy_get_option( 'drumstudy_postal' );

if ( ! $menu_headline ) {
	$menu_headline = ( 'existing_client' === $audience )
		? __( 'Book a Lesson', 'drumstudy' )
		: __( 'New Client Consultation', 'drumstudy' );
}

$contact_bits = array_filter( [
	$phone ? sprintf(
		'<a href="tel:%1$s">%2$s</a>',
		esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ),
		esc_html( $phone )
	) : '',
	$email ? sprintf(
		'<a href="mailto:%1$s">%2$s</a>',
		esc_attr( $email ),
		esc_html( $email )
	) : '',
] );

$city_line = array_filter( [ $city, $state, $postal ] );
$address_line = array_filter( [
	$business_name,
	trim( $address_1 . ( $city_line ? ', ' . implode( ' ', $city_line ) : '' ) ),
] );
?>
<section class="tts-section tts-booking-menu" aria-labelledby="booking-menu-heading">
	<div class="tts-container">
		<div class="tts-booking-menu__header">
			<div class="tts-booking-menu__intro">
				<h2 id="booking-menu-heading" class="tts-section-heading__title">
					<?php echo esc_html( $menu_headline ); ?>
				</h2>

				<?php if ( $contact_bits ) : ?>
					<p class="tts-booking-menu__contact">
						<?php echo wp_kses_post( implode( ' <span aria-hidden="true">•</span> ', $contact_bits ) ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $address_line ) : ?>
					<p class="tts-booking-menu__address"><?php echo esc_html( implode( ' • ', $address_line ) ); ?></p>
				<?php endif; ?>

				<?php if ( $menu_intro ) : ?>
					<div class="tts-booking-menu__body tts-prose"><?php echo wp_kses_post( wpautop( $menu_intro ) ); ?></div>
				<?php endif; ?>

				<?php if ( $crosslink_label && $crosslink_url ) : ?>
					<p class="tts-booking-menu__crosslink">
						<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $crosslink_url ) ); ?>" class="tts-booking-menu__crosslink-link">
							<?php echo esc_html( $crosslink_label ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>

			<div class="tts-booking-menu__actions">
				<a href="#booking-services" class="tts-btn tts-btn--primary">
					<?php esc_html_e( 'Book', 'drumstudy' ); ?>
				</a>
				<?php if ( $my_bookings_url ) : ?>
					<a href="<?php echo esc_attr( drumstudy_the_url( '', 0, $my_bookings_url ) ); ?>" class="tts-btn tts-btn--secondary">
						<?php esc_html_e( 'My bookings', 'drumstudy' ); ?>
					</a>
				<?php else : ?>
					<span class="tts-btn tts-btn--secondary tts-booking-menu__placeholder-btn" aria-disabled="true">
						<?php echo esc_html( drumstudy_placeholder( 'My Bookings URL' ) ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>

		<div id="booking-services" class="tts-booking-menu__services">
			<h3 class="tts-booking-menu__services-label"><?php esc_html_e( 'Services', 'drumstudy' ); ?></h3>

			<?php if ( $services ) : ?>
				<div class="tts-grid-2 tts-booking-menu__grid">
					<?php
					foreach ( $services as $service ) {
						get_template_part(
							'template-parts/cards/card-booking-service',
							null,
							[
								'post_id' => $post_id,
								'service' => $service,
							]
						);
					}
					?>
				</div>
			<?php else : ?>
				<p><?php echo esc_html( drumstudy_placeholder( 'Booking Services' ) ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
