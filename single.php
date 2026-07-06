<?php
/**
 * Single: Native Posts (Updates)
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );

$post_id       = get_the_ID();
$external_url  = get_post_meta( $post_id, 'external_url', true );
$source_outlet = get_post_meta( $post_id, 'source_outlet', true );
?>
<main id="main-content" role="main">

	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container-prose">

				<header class="tts-article-header mb-8">
					<?php
					$categories = get_the_category();
					if ( $categories ) :
						?>
						<p class="tts-card__category"><?php echo esc_html( $categories[0]->name ); ?></p>
					<?php endif; ?>

					<h1><?php the_title(); ?></h1>

					<div class="tts-article-header__meta flex gap-4">
						<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
						<?php if ( $source_outlet ) : ?>
							<span><?php echo esc_html( $source_outlet ); ?></span>
						<?php endif; ?>
					</div>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="tts-article-thumbnail mb-8">
						<?php the_post_thumbnail( 'tts-feature', [
							'class'   => 'w-full h-auto rounded',
							'loading' => 'eager',
						] ); ?>
					</div>
				<?php endif; ?>

				<div class="tts-prose">
					<?php the_content(); ?>
				</div>

				<?php if ( $external_url ) : ?>
					<div class="tts-cta-strip mt-10">
						<a href="<?php echo esc_url( $external_url ); ?>"
						   target="_blank"
						   rel="noopener noreferrer"
						   class="tts-btn tts-btn--secondary">
							<?php esc_html_e( 'Read Full Article', 'drumstudy' ); ?>
						</a>
					</div>
				<?php endif; ?>

				<nav class="tts-post-nav flex justify-between gap-4 mt-12 pt-8 border-t"
				     aria-label="<?php esc_attr_e( 'Post navigation', 'drumstudy' ); ?>">
					<?php
					$prev = get_previous_post();
					$next = get_next_post();
					?>
					<?php if ( $prev ) : ?>
						<a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" rel="prev">
							&laquo; <?php echo esc_html( get_the_title( $prev ) ); ?>
						</a>
					<?php else : ?>
						<span></span>
					<?php endif; ?>
					<?php if ( $next ) : ?>
						<a href="<?php echo esc_url( get_permalink( $next ) ); ?>" rel="next">
							<?php echo esc_html( get_the_title( $next ) ); ?> &raquo;
						</a>
					<?php endif; ?>
				</nav>

			</div>
		</article>
	<?php endwhile; ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
