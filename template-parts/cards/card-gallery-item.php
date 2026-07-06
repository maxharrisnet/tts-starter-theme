<?php
/**
 * Card: Gallery Item
 *
 * @package drumstudy
 */

$post_id      = get_the_ID();
$img_id       = absint( get_post_meta( $post_id, 'gallery_image', true ) );
$caption      = get_post_meta( $post_id, 'caption', true );
$project_name = get_post_meta( $post_id, 'project_name', true );
$project_link = get_post_meta( $post_id, 'project_link', true );

if ( ! $img_id ) {
	return;
}

$alt = get_post_meta( $img_id, '_wp_attachment_image_alt', true )
	?: ( $caption ?: get_the_title() );
?>
<figure class="tts-card tts-card--gallery">
	<?php if ( $project_link ) : ?>
		<a href="<?php echo drumstudy_the_url( '', 0, $project_link ); ?>"
		   target="_blank"
		   rel="noopener noreferrer"
		   aria-label="<?php echo esc_attr( $project_name ?: get_the_title() ); ?>">
			<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
				'class'   => 'w-full h-auto',
				'loading' => 'lazy',
				'alt'     => $alt,
			] ); ?>
		</a>
	<?php else : ?>
		<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
			'class'   => 'w-full h-auto',
			'loading' => 'lazy',
			'alt'     => $alt,
		] ); ?>
	<?php endif; ?>

	<?php if ( $caption || $project_name ) : ?>
		<figcaption class="tts-card__caption">
			<?php echo esc_html( $project_name ?: $caption ); ?>
		</figcaption>
	<?php endif; ?>
</figure>
