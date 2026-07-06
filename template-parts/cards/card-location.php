<?php
/**
 * Card: Location (Directory)
 *
 * @package drumstudy
 */

$post_id  = get_the_ID();
$address1 = get_post_meta( $post_id, 'address_1', true );
$address2 = get_post_meta( $post_id, 'address_2', true );
$city     = get_post_meta( $post_id, 'city', true );
$state    = get_post_meta( $post_id, 'state', true );
$postal   = get_post_meta( $post_id, 'postal', true );
$phone    = get_post_meta( $post_id, 'location_phone', true );
$email    = sanitize_email( get_post_meta( $post_id, 'location_email', true ) );
$img_id   = absint( get_post_meta( $post_id, 'location_image', true ) );
$manager  = get_post_meta( $post_id, 'manager_name', true );
?>
<article class="tts-card tts-card--location" aria-label="<?php the_title_attribute(); ?>">
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
		<h3 class="tts-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<?php if ( $address1 ) : ?>
			<address class="tts-card__address not-italic">
				<span><?php echo esc_html( $address1 ); ?></span>
				<?php if ( $address2 ) : ?>
					<span><?php echo esc_html( $address2 ); ?></span>
				<?php endif; ?>
				<?php
				$city_line = array_filter( [ $city, $state, $postal ] );
				if ( $city_line ) :
					?>
					<span><?php echo esc_html( implode( ', ', $city_line ) ); ?></span>
				<?php endif; ?>
			</address>
		<?php endif; ?>

		<?php if ( $phone ) : ?>
			<p class="tts-card__phone">
				<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
					<?php echo esc_html( $phone ); ?>
				</a>
			</p>
		<?php endif; ?>

		<?php if ( $email ) : ?>
			<p class="tts-card__email">
				<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
			</p>
		<?php endif; ?>

		<?php if ( $manager ) : ?>
			<p class="tts-card__manager"><?php echo esc_html( $manager ); ?></p>
		<?php endif; ?>

		<a href="<?php the_permalink(); ?>" class="tts-btn tts-btn--secondary">
			<?php esc_html_e( 'View Location', 'drumstudy' ); ?>
		</a>
	</div>
</article>
