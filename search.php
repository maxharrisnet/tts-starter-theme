<?php
/**
 * Search Results
 *
 * Gallery is excluded from search in inc/setup.php via pre_get_posts.
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="search-heading">
		<div class="tts-container">
			<header class="tts-section-heading">
				<h1 id="search-heading" class="tts-section-heading__title">
					<?php
					printf(
						/* translators: %s: search query */
						esc_html__( 'Search: %s', 'drumstudy' ),
						'<span>' . get_search_query() . '</span>'
					);
					?>
				</h1>
				<?php if ( have_posts() ) : ?>
					<p class="tts-section-heading__sub">
						<?php
						global $wp_query;
						printf(
							/* translators: %d: number of results */
							esc_html( _n( '%d result found.', '%d results found.', $wp_query->found_posts, 'drumstudy' ) ),
							absint( $wp_query->found_posts )
						);
						?>
					</p>
				<?php endif; ?>
			</header>

			<?php if ( have_posts() ) : ?>
				<div class="tts-grid-3">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/cards/card-search-result' );
					}
					?>
				</div>
				<?php get_template_part( 'template-parts/global/pagination' ); ?>

			<?php else : ?>
				<div class="tts-container-prose py-16 text-center mx-auto">
					<h2><?php esc_html_e( 'No results found', 'drumstudy' ); ?></h2>
					<p>
						<?php
						printf(
							esc_html__( 'Nothing matched your search for &ldquo;%s&rdquo;. Try a different term or browse below.', 'drumstudy' ),
							get_search_query()
						);
						?>
					</p>
					<?php
					$profile   = drumstudy_get_profile();
					$cpt_links = [
						'booking'   => [ 'drumstudy_service', __( 'Browse Services', 'drumstudy' ) ],
						'events'    => [ 'drumstudy_event',   __( 'Browse Events', 'drumstudy' ) ],
						'directory' => [ 'drumstudy_location', __( 'Browse Locations', 'drumstudy' ) ],
						'community' => [ 'drumstudy_event',   __( 'Browse Events', 'drumstudy' ) ],
					];
					if ( isset( $cpt_links[ $profile ] ) ) {
						[ $cpt, $label ] = $cpt_links[ $profile ];
						$url = get_post_type_archive_link( $cpt );
						if ( $url ) {
							drumstudy_render_cta( $label, $url );
						}
					} else {
						drumstudy_render_cta( __( 'Go to Homepage', 'drumstudy' ), home_url( '/' ) );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
