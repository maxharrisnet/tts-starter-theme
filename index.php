<?php
/**
 * The main template file — WordPress fallback.
 *
 * All content is served via specific page templates, CPT singles, and archives.
 * This file handles any unmatched template requests.
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">
	<div class="tts-container py-16">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-12' ); ?>>
					<h2 class="mb-4">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
			<?php get_template_part( 'template-parts/global/pagination' ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'drumstudy' ); ?></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
