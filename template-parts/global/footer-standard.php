<?php
/**
 * Footer Layout: Standard
 * Logo + tagline, nav columns, contact info, social icons.
 * Footer Legal menu at bottom. Copyright auto-generated.
 *
 * @package drumstudy
 */

$business_name = drumstudy_get_option( 'drumstudy_business_name' ) ?: get_bloginfo( 'name' );
$tagline       = drumstudy_get_option( 'drumstudy_tagline' );
$phone         = drumstudy_get_option( 'drumstudy_phone' );
$email         = drumstudy_get_option( 'drumstudy_email' );
$logo_id       = absint( drumstudy_get_option( 'drumstudy_logo' ) );
$logo_alt      = drumstudy_get_option( 'drumstudy_logo_alt' ) ?: $business_name;
$social_links  = drumstudy_social_links();
$review_url    = drumstudy_get_option( 'drumstudy_google_review_url' );
?>
<footer class="tts-footer" role="contentinfo">
	<div class="tts-container">
		<div class="tts-footer__grid">

			<!-- Brand + contact -->
			<div class="tts-footer__brand">
				<?php if ( $logo_id ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $business_name ); ?>" class="tts-footer__logo">
						<?php echo wp_get_attachment_image( $logo_id, 'tts-logo', false, [ 'alt' => esc_attr( $logo_alt ) ] ); ?>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tts-header__logo-text tts-footer__logo">
						<?php echo esc_html( $business_name ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $tagline ) : ?>
					<p class="tts-footer__tagline"><?php echo esc_html( $tagline ); ?></p>
				<?php endif; ?>

				<?php if ( $phone || $email ) : ?>
					<div class="tts-footer__contact">
						<?php if ( $phone ) : ?>
							<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
						<?php endif; ?>
						<?php if ( $email ) : ?>
							<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $social_links ) ) : ?>
					<ul class="tts-social-links">
						<?php foreach ( $social_links as $platform => $url ) : ?>
							<li>
								<a href="<?php echo esc_url( $url ); ?>"
								   target="_blank"
								   rel="noopener noreferrer"
								   aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
									<span aria-hidden="true"><?php echo esc_html( substr( ucfirst( $platform ), 0, 2 ) ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<!-- Explore -->
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
				<div class="tts-footer__col">
					<h2 class="tts-footer__heading"><?php esc_html_e( 'Explore', 'drumstudy' ); ?></h2>
					<?php get_template_part( 'template-parts/global/nav-footer' ); ?>
				</div>
			<?php endif; ?>

			<!-- Google review callout -->
			<?php if ( $review_url ) : ?>
				<div class="tts-footer__col">
					<div class="tts-footer__review">
						<div class="tts-footer__review-stars" aria-hidden="true">★★★★★</div>
						<p class="tts-footer__review-text"><?php esc_html_e( 'Enjoying your lessons? A quick review helps other drummers find us.', 'drumstudy' ); ?></p>
						<a href="<?php echo esc_url( $review_url ); ?>" class="tts-footer__review-link" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Review on Google', 'drumstudy' ); ?>
							<span class="tts-footer__review-arrow" aria-hidden="true">&rarr;</span>
						</a>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Legal bar -->
		<div class="tts-footer__legal">
			<p>
				&copy; <?php echo esc_html( (string) gmdate( 'Y' ) ); ?> <?php echo esc_html( $business_name ); ?>. <?php esc_html_e( 'All rights reserved.', 'drumstudy' ); ?>
			</p>
			<?php
			if ( has_nav_menu( 'footer-legal' ) ) {
				wp_nav_menu( [
					'theme_location' => 'footer-legal',
					'container'      => false,
					'menu_class'     => 'tts-nav tts-nav-legal',
					'fallback_cb'    => false,
				] );
			}
			?>
		</div>
	</div>
</footer>
