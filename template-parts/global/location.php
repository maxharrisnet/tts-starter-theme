<?php
/**
 * Location block — address + map embed from Admin Options
 *
 * @package tts-theme
 */

$address_1 = tts_get_option( 'tts_address_1' );
$address_2 = tts_get_option( 'tts_address_2' );
$city      = tts_get_option( 'tts_city' );
$state     = tts_get_option( 'tts_state' );
$postal    = tts_get_option( 'tts_postal' );
$phone     = tts_get_option( 'tts_phone' );
$email     = tts_get_option( 'tts_email' );
$hours     = tts_get_option( 'tts_hours' );
$map_embed = tts_get_option( 'tts_map_embed' );

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
				title="<?php esc_attr_e( 'Location map', 'tts-theme' ); ?>">
			</iframe>
		</div>
	<?php endif; ?>
</div>
