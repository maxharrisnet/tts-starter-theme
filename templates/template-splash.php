<?php
/**
 * Template Name: Splash
 * Coming soon / pre-launch page. Standalone — no standard header/footer.
 *
 * @package tts-theme
 */

$post_id       = get_the_ID();
$headline      = get_post_meta( $post_id, 'splash_headline', true );
$subheadline   = get_post_meta( $post_id, 'splash_subheadline', true );
$body          = get_post_meta( $post_id, 'splash_body', true );
$logo_id       = absint( get_post_meta( $post_id, 'splash_logo_override', true ) );
$bg_id         = absint( get_post_meta( $post_id, 'splash_bg_image', true ) );
$cta1_label    = get_post_meta( $post_id, 'splash_cta1_label', true );
$cta1_url      = get_post_meta( $post_id, 'splash_cta1_url', true );
$cta2_label    = get_post_meta( $post_id, 'splash_cta2_label', true );
$cta2_url      = get_post_meta( $post_id, 'splash_cta2_url', true );
$embed         = get_post_meta( $post_id, 'splash_embed_block', true );
$pdf_id        = absint( get_post_meta( $post_id, 'splash_pdf', true ) );

// Logo fallback: splash override → site logo → business name
if ( $logo_id ) {
	$logo_img = wp_get_attachment_image( $logo_id, 'tts-logo', false, [
		'class' => 'tts-splash__logo-img',
		'alt'   => get_post_meta( $logo_id, '_wp_attachment_image_alt', true ) ?: tts_get_option( 'tts_logo_alt' ) ?: tts_get_option( 'tts_business_name' ),
	] );
} else {
	$site_logo_id = absint( tts_get_option( 'tts_logo' ) );
	$logo_img = $site_logo_id
		? wp_get_attachment_image( $site_logo_id, 'tts-logo', false, [
			'class' => 'tts-splash__logo-img',
			'alt'   => tts_get_option( 'tts_logo_alt' ) ?: tts_get_option( 'tts_business_name' ),
		] )
		: '<span class="tts-splash__logo-text">' . esc_html( tts_get_option( 'tts_business_name' ) ) . '</span>';
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'tts-splash-page' ); ?>>
<?php wp_body_open(); ?>

<a href="#main-content"
   class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-white focus:text-black">
	<?php esc_html_e( 'Skip to main content', 'tts-theme' ); ?>
</a>

<main id="main-content" role="main" class="tts-splash">
	<?php if ( $bg_id ) : ?>
		<?php echo wp_get_attachment_image( $bg_id, 'tts-hero', false, [
			'class'         => 'tts-splash__bg',
			'loading'       => 'eager',
			'fetchpriority' => 'high',
			'alt'           => '',
			'aria-hidden'   => 'true',
		] ); ?>
		<div class="tts-splash__overlay" aria-hidden="true"></div>
	<?php endif; ?>

	<div class="tts-container-prose tts-splash__content">
		<div class="tts-splash__logo">
			<?php echo $logo_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<h1 class="tts-splash__headline">
			<?php echo esc_html( $headline ?: tts_placeholder( 'Splash Headline' ) ); ?>
		</h1>

		<?php if ( $subheadline ) : ?>
			<p class="tts-splash__subheadline"><?php echo esc_html( $subheadline ); ?></p>
		<?php endif; ?>

		<?php if ( $body ) : ?>
			<div class="tts-prose"><?php echo wp_kses_post( wpautop( $body ) ); ?></div>
		<?php endif; ?>

		<?php if ( $embed ) : ?>
			<div class="tts-embed-block">
				<?php echo tts_render_embed( $embed ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<?php if ( $cta1_label || $cta2_label ) : ?>
			<?php tts_render_cta( $cta1_label, $cta1_url, $cta2_label, $cta2_url ); ?>
		<?php endif; ?>

		<?php if ( $pdf_id && get_post_mime_type( $pdf_id ) === 'application/pdf' ) : ?>
			<p class="tts-splash__pdf">
				<a href="<?php echo esc_url( wp_get_attachment_url( $pdf_id ) ); ?>"
				   target="_blank"
				   rel="noopener noreferrer">
					<?php esc_html_e( 'Download PDF', 'tts-theme' ); ?>
				</a>
			</p>
		<?php endif; ?>
	</div>
</main>

<?php wp_footer(); ?>
</body>
</html>
