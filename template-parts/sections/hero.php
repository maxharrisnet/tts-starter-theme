<?php
/**
 * Section: Hero
 *
 * Profile-aware: headline/CTA variants based on site profile.
 * Background image uses loading="eager" (above the fold).
 *
 * @package drumstudy
 */

$post_id     = get_the_ID() ?: 0;
$headline    = get_post_meta( $post_id, 'home_hero_headline', true );
$subheadline = get_post_meta( $post_id, 'home_hero_subheadline', true );
$bg_id       = absint( get_post_meta( $post_id, 'home_hero_bg_image', true ) );
$cta1_label  = get_post_meta( $post_id, 'home_hero_cta1_label', true );
$cta1_url    = get_post_meta( $post_id, 'home_hero_cta1_url', true );
$cta2_label  = get_post_meta( $post_id, 'home_hero_cta2_label', true );
$cta2_url    = get_post_meta( $post_id, 'home_hero_cta2_url', true );

$profile = drumstudy_get_profile();

// Profile-aware headline fallback
if ( ! $headline ) {
	$headline = drumstudy_get_option( 'drumstudy_tagline' ) ?: drumstudy_placeholder( 'Hero Headline' );
}
?>
<section class="tts-hero" aria-label="<?php esc_attr_e( 'Hero', 'drumstudy' ); ?>">

	<?php if ( $bg_id ) : ?>
		<?php
		echo wp_get_attachment_image( $bg_id, 'tts-hero', false, [
			'class'   => 'tts-hero__bg',
			'loading' => 'eager',
			'fetchpriority' => 'high',
			'alt'     => '',
			'aria-hidden' => 'true',
		] );
		?>
		<div class="tts-hero__overlay" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="tts-container tts-hero__content">
		<div class="tts-hero__panel">
		<h1 class="tts-hero__headline"><?php echo esc_html( $headline ); ?></h1>

		<?php if ( $subheadline ) : ?>
			<p class="tts-hero__subheadline"><?php echo esc_html( $subheadline ); ?></p>
		<?php endif; ?>

		<?php
		// Profile-aware CTA override
		$final_cta1_label = $cta1_label;
		$final_cta1_url   = $cta1_url;
		$final_cta2_label = $cta2_label;
		$final_cta2_url   = $cta2_url;

		if ( drumstudy_is_profile( 'booking' ) && ! $final_cta1_url ) {
			$embed_url = drumstudy_get_option( 'drumstudy_embed_booking' );
			if ( $embed_url ) {
				$final_cta1_label = $final_cta1_label ?: __( 'Book Now', 'drumstudy' );
				$final_cta1_url   = '#booking';
			}
		}

		drumstudy_render_cta( $final_cta1_label, $final_cta1_url, $final_cta2_label, $final_cta2_url );
		?>
		</div>
	</div>
</section>
