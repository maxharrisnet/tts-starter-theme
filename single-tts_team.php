<?php
/**
 * Single: Team Member
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );

$post_id  = get_the_ID();
$role     = get_post_meta( $post_id, 'role', true );
$img_id   = absint( get_post_meta( $post_id, 'team_image', true ) );
$email    = sanitize_email( get_post_meta( $post_id, 'email', true ) );
$linkedin = get_post_meta( $post_id, 'linkedin', true );
$twitter  = get_post_meta( $post_id, 'twitter', true );
?>
<main id="main-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container">
				<div class="flex flex-col lg:flex-row gap-12">

					<?php if ( $img_id ) : ?>
						<div class="w-full lg:w-1/3">
							<?php echo wp_get_attachment_image( $img_id, 'tts-feature', false, [
								'class'   => 'w-full h-auto rounded',
								'loading' => 'eager',
								'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
							] ); ?>
						</div>
					<?php endif; ?>

					<div class="w-full lg:w-2/3">
						<header class="tts-article-header mb-8">
							<h1><?php the_title(); ?></h1>
							<?php if ( $role ) : ?>
								<p class="tts-role"><?php echo esc_html( $role ); ?></p>
							<?php endif; ?>
						</header>

						<div class="tts-prose">
							<?php the_content(); ?>
						</div>

						<?php if ( $email || $linkedin || $twitter ) : ?>
							<nav class="tts-team-links flex gap-4 mt-8" aria-label="<?php esc_attr_e( 'Team member links', 'tts-theme' ); ?>">
								<?php if ( $email ) : ?>
									<a href="<?php echo esc_url( 'mailto:' . $email ); ?>">
										<?php esc_html_e( 'Email', 'tts-theme' ); ?>
									</a>
								<?php endif; ?>
								<?php if ( $linkedin ) : ?>
									<a href="<?php echo esc_url( $linkedin ); ?>"
									   target="_blank"
									   rel="noopener noreferrer">LinkedIn</a>
								<?php endif; ?>
								<?php if ( $twitter ) : ?>
									<a href="<?php echo esc_url( $twitter ); ?>"
									   target="_blank"
									   rel="noopener noreferrer">X</a>
								<?php endif; ?>
							</nav>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</article>

	<?php endwhile; ?>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
