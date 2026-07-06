<?php
/**
 * Archive: Native Posts (Updates / News)
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="archive-heading">
		<div class="tts-container">
			<header class="tts-section-heading">
				<h1 id="archive-heading" class="tts-section-heading__title">
					<?php
					if ( is_category() ) {
						echo esc_html( single_cat_title( '', false ) );
					} elseif ( is_tag() ) {
						printf( esc_html__( 'Tag: %s', 'drumstudy' ), single_tag_title( '', false ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					} elseif ( is_post_type_archive() ) {
						post_type_archive_title();
					} else {
						esc_html_e( 'Updates', 'drumstudy' );
					}
					?>
				</h1>
				<?php the_archive_description( '<p class="tts-section-heading__sub">', '</p>' ); ?>
			</header>

			<?php if ( have_posts() ) : ?>
				<div class="tts-grid-3">
					<?php
					while ( have_posts() ) {
						the_post();
						get_template_part( 'template-parts/cards/card-update' );
					}
					?>
				</div>
				<?php get_template_part( 'template-parts/global/pagination' ); ?>
			<?php else : ?>
				<p class="py-12 text-center"><?php esc_html_e( 'No posts found.', 'drumstudy' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
