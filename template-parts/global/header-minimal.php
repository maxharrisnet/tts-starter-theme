<?php
/**
 * Header Layout: Minimal
 * Logo centered or left, no full nav (or condensed), single CTA.
 * For landing pages, splash, minimal profile sites.
 *
 * @package drumstudy
 */
?>
<header class="tts-header tts-header--minimal" role="banner">
	<div class="tts-container">
		<div class="tts-header__inner justify-center">
			<div class="tts-header__logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				   rel="home"
				   aria-label="<?php echo esc_attr( drumstudy_get_option( 'drumstudy_business_name' ) ?: get_bloginfo( 'name' ) ); ?>">
					<?php
					$logo_id  = absint( drumstudy_get_option( 'drumstudy_logo' ) );
					$logo_alt = drumstudy_get_option( 'drumstudy_logo_alt' ) ?: get_bloginfo( 'name' );
					if ( $logo_id ) :
						echo wp_get_attachment_image( $logo_id, 'tts-logo', false, [
							'class' => 'tts-header__logo-img',
							'alt'   => esc_attr( $logo_alt ),
						] );
					else :
						?>
						<span class="tts-header__logo-text"><?php echo esc_html( drumstudy_get_option( 'drumstudy_business_name' ) ?: get_bloginfo( 'name' ) ); ?></span>
					<?php endif; ?>
				</a>
			</div>
		</div>
	</div>
</header>
