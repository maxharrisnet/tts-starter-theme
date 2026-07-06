<?php
/**
 * 404 Not Found
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">
	<div class="tts-container tts-container-prose text-center py-24">
		<h1><?php esc_html_e( 'Page Not Found', 'drumstudy' ); ?></h1>
		<p>
			<?php esc_html_e( "Sorry, the page you're looking for doesn't exist or has been moved.", 'drumstudy' ); ?>
		</p>
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( [
				'theme_location' => 'primary',
				'menu_class'     => 'tts-404-nav',
			] );
		}
		drumstudy_render_cta(
			__( 'Go to Homepage', 'drumstudy' ),
			home_url( '/' )
		);
		?>
	</div>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
