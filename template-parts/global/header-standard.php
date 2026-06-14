<?php
/**
 * Header Layout: Standard
 * Logo left, primary nav center/right, CTA button far right.
 * Above-nav banner slot. Sticky on scroll.
 *
 * @package tts-theme
 */
?>
<header class="tts-header" role="banner">
	<?php get_template_part( 'template-parts/global/banner' ); ?>

	<div class="tts-container">
		<div class="tts-header__inner">
			<!-- Logo -->
			<div class="tts-header__logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				   rel="home"
				   aria-label="<?php echo esc_attr( tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' ) ); ?>">
					<?php
					$logo    = tts_get_image_option( 'tts_logo', 'tts-logo' );
					$logo_alt = tts_get_option( 'tts_logo_alt' ) ?: get_bloginfo( 'name' );
					if ( $logo ) :
						$logo_id = absint( tts_get_option( 'tts_logo' ) );
						echo wp_get_attachment_image( $logo_id, 'tts-logo', false, [
							'class' => 'tts-header__logo-img',
							'alt'   => esc_attr( $logo_alt ),
						] );
					else :
						?>
						<span class="tts-header__logo-text"><?php echo esc_html( tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' ) ); ?></span>
					<?php endif; ?>
				</a>
			</div>

			<!-- Primary nav -->
			<?php get_template_part( 'template-parts/global/nav-primary' ); ?>

			<!-- Header CTA -->
			<?php
			$cta_label = tts_get_option( 'tts_cta_header_label' ) ?: tts_get_option( 'tts_cta_primary_label' );
			$cta_url   = tts_get_option( 'tts_cta_header_url' )   ?: tts_get_option( 'tts_cta_primary_url' );
			if ( $cta_label && $cta_url ) :
				?>
				<a href="<?php echo esc_attr( tts_the_url( '', 0, $cta_url ) ); ?>"
				   class="tts-btn tts-btn--primary hidden md:inline-flex">
					<?php echo esc_html( $cta_label ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</header>
