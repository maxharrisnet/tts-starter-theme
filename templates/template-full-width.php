<?php
/**
 * Template Name: Full Width
 * For legal pages, terms, privacy policy — native WP content, no sidebar.
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>
		<article class="tts-section" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="tts-container-prose">
				<header class="tts-article-header">
					<h1><?php the_title(); ?></h1>
					<p class="tts-article-header__meta">
						<?php
						printf(
							/* translators: %s: date */
							esc_html__( 'Last updated: %s', 'tts-theme' ),
							esc_html( get_the_modified_date() )
						);
						?>
					</p>
				</header>

				<div class="tts-prose">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
