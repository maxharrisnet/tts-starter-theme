<?php
/**
 * Card: Team Member
 *
 * @package drumstudy
 */

$post_id  = get_the_ID();
$role     = get_post_meta( $post_id, 'role', true );
$img_id   = absint( get_post_meta( $post_id, 'team_image', true ) );
$email    = sanitize_email( get_post_meta( $post_id, 'email', true ) );
$linkedin = get_post_meta( $post_id, 'linkedin', true );
$twitter  = get_post_meta( $post_id, 'twitter', true );
?>
<article class="tts-card tts-card--team" aria-label="<?php the_title_attribute(); ?>">
	<?php if ( $img_id ) : ?>
		<div class="tts-card__image tts-card__image--portrait">
			<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
				'class'   => 'w-full h-full object-cover',
				'loading' => 'lazy',
				'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
			] ); ?>
		</div>
	<?php endif; ?>

	<div class="tts-card__body">
		<h3 class="tts-card__title"><?php the_title(); ?></h3>

		<?php if ( $role ) : ?>
			<p class="tts-card__role"><?php echo esc_html( $role ); ?></p>
		<?php endif; ?>

		<?php if ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>

		<?php if ( $email || $linkedin || $twitter ) : ?>
			<div class="tts-card__links flex gap-3">
				<?php if ( $email ) : ?>
					<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"
					   aria-label="<?php printf( esc_attr__( 'Email %s', 'drumstudy' ), get_the_title() ); ?>">
						<?php esc_html_e( 'Email', 'drumstudy' ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $linkedin ) : ?>
					<a href="<?php echo esc_url( $linkedin ); ?>"
					   target="_blank"
					   rel="noopener noreferrer"
					   aria-label="<?php printf( esc_attr__( '%s on LinkedIn', 'drumstudy' ), get_the_title() ); ?>">
						LinkedIn
					</a>
				<?php endif; ?>
				<?php if ( $twitter ) : ?>
					<a href="<?php echo esc_url( $twitter ); ?>"
					   target="_blank"
					   rel="noopener noreferrer"
					   aria-label="<?php printf( esc_attr__( '%s on X / Twitter', 'drumstudy' ), get_the_title() ); ?>">
						X
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
