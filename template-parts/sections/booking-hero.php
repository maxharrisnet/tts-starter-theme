<?php
/**
 * Section: Booking Page Hero
 *
 * @package drumstudy
 */

$post_id          = get_the_ID();
$audience         = get_post_meta( $post_id, 'booking_audience', true ) ?: 'new_client';
$headline         = get_post_meta( $post_id, 'booking_hero_headline', true );
$subheadline      = get_post_meta( $post_id, 'booking_hero_subheadline', true );
$bg_id            = absint( get_post_meta( $post_id, 'booking_hero_bg_image', true ) );

if ( ! $headline ) {
	$headline = ( 'existing_client' === $audience )
		? drumstudy_placeholder( 'Existing Client Hero Headline' )
		: drumstudy_placeholder( 'New Client Hero Headline' );
}
?>
<section class="tts-hero tts-booking-hero" aria-labelledby="booking-hero-heading">
	<?php if ( $bg_id ) : ?>
		<?php
		echo wp_get_attachment_image( $bg_id, 'tts-hero', false, [
			'class'         => 'tts-hero__bg',
			'loading'       => 'eager',
			'fetchpriority' => 'high',
			'alt'           => '',
			'aria-hidden'   => 'true',
		] );
		?>
		<div class="tts-hero__overlay" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="tts-container tts-hero__content">
		<div class="tts-hero__panel">
			<h1 id="booking-hero-heading" class="tts-hero__headline"><?php echo esc_html( $headline ); ?></h1>

			<?php if ( $subheadline ) : ?>
				<p class="tts-hero__subheadline"><?php echo esc_html( $subheadline ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</section>
