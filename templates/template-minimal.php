<?php
/**
 * Template Name: Minimal
 * For landing pages — minimal header, no footer nav clutter.
 *
 * @package drumstudy
 */

// Use minimal header/footer directly, bypassing the Admin Options layout setting.
get_template_part( 'template-parts/global/header-minimal' );
?>
<main id="main-content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container-prose">
				<h1><?php the_title(); ?></h1>
				<div class="tts-prose">
					<?php the_content(); ?>
				</div>
			</div>
		</article>
	<?php endwhile; ?>

</main>
<?php
get_template_part( 'template-parts/global/footer-minimal' );
