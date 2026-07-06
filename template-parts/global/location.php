<?php
/**
 * Location block — address + map embed from Admin Options
 *
 * @package drumstudy
 */

$address_1 = drumstudy_get_option( 'drumstudy_address_1' );
$address_2 = drumstudy_get_option( 'drumstudy_address_2' );
$city      = drumstudy_get_option( 'drumstudy_city' );
$state     = drumstudy_get_option( 'drumstudy_state' );
$postal    = drumstudy_get_option( 'drumstudy_postal' );
$phone     = drumstudy_get_option( 'drumstudy_phone' );
$email     = drumstudy_get_option( 'drumstudy_email' );
$hours     = drumstudy_get_option( 'drumstudy_hours' );
$map_embed = drumstudy_get_option( 'drumstudy_map_embed' );

if ( ! $address_1 ) {
	return;
}
?>
<div class="tts-location">
	<address class="tts-location__address not-italic">
		<p>
			<?php echo esc_html( $address_1 ); ?><br>
			<?php if ( $address_2 ) : ?>
				<?php echo esc_html( $address_2 ); ?><br>
			<?php endif; ?>
			<?php
			$city_line = array_filter( [ $city, $state, $postal ] );
			if ( $city_line ) {
				echo esc_html( implode( ', ', $city_line ) );
			}
			?>
		</p>
		<?php if ( $phone ) : ?>
			<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
		<?php endif; ?>
		<?php if ( $email ) : ?>
			<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
		<?php endif; ?>
	</address>

	<?php if ( $hours ) : ?>
		<div class="tts-location__hours">
			<pre class="whitespace-pre-wrap"><?php echo esc_html( $hours ); ?></pre>
		</div>
	<?php endif; ?>

	<?php if ( $map_embed ) : ?>
		<div class="tts-location__map tts-embed-block">
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
