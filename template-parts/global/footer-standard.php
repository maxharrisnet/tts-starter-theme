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
		<div class="flex flex-col md:flex-row gap-8 md:gap-12">

			<!-- Brand column -->
			<div class="flex-shrink-0 md:w-64">
				<?php if ( $logo_id ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( $business_name ); ?>" class="inline-block mb-4">
						<?php echo wp_get_attachment_image( $logo_id, 'tts-logo', false, [ 'alt' => esc_attr( $logo_alt ), 'class' => 'h-10 w-auto' ] ); ?>
					</a>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="tts-header__logo-text block mb-4 text-inherit">
						<?php echo esc_html( $business_name ); ?>
					</a>
				<?php endif; ?>

				<?php if ( $tagline ) : ?>
					<p class="text-sm opacity-70 mb-4"><?php echo esc_html( $tagline ); ?></p>
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

				<?php if ( $review_url ) : ?>
					<p class="mt-4">
						<a href="<?php echo esc_url( $review_url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Leave us a review on Google', 'drumstudy' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>

			<!-- Nav column -->
			<div class="flex-1">
				<?php get_template_part( 'template-parts/global/nav-footer' ); ?>
			</div>

			<!-- Contact column -->
			<?php if ( $phone || $email ) : ?>
				<div class="flex-shrink-0">
					<h3 class="text-sm font-semibold uppercase tracking-wider opacity-60 mb-3"><?php esc_html_e( 'Contact', 'drumstudy' ); ?></h3>
					<?php if ( $phone ) : ?>
						<p class="mb-2"><a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
					<?php endif; ?>
					<?php if ( $email ) : ?>
						<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

		<!-- Legal bar -->
		<div class="tts-footer__legal flex flex-col sm:flex-row justify-between items-center gap-4">
			<p class="m-0">
				&copy; <?php echo esc_html( (string) gmdate( 'Y' ) ); ?> <?php echo esc_html( $business_name ); ?>. <?php esc_html_e( 'All rights reserved.', 'drumstudy' ); ?>
			</p>
			<?php
			if ( has_nav_menu( 'footer-legal' ) ) {
				wp_nav_menu( [
					'theme_location' => 'footer-legal',
					'container'      => false,
					'menu_class'     => 'tts-nav tts-nav-legal flex gap-4',
					'fallback_cb'    => false,
				] );
			}
			?>
		</div>
	</div>
</footer>
