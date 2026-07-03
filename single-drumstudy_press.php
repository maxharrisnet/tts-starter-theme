<?php
/**
 * Single: Press Item
 * Press CPT has no public archive. Singles redirect to the article URL if set.
 *
 * @package drumstudy
 */

$article_url = get_post_meta( get_the_ID(), 'article_url', true );
if ( $article_url ) {
	wp_redirect( esc_url_raw( $article_url ), 301 );
	exit;
}

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container-prose">
				<h1><?php the_title(); ?></h1>
				<div class="tts-prose"><?php the_content(); ?></div>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
