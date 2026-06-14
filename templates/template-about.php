<?php
/**
 * Template Name: About
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );

$post_id              = get_the_ID();
$headline             = get_post_meta( $post_id, 'about_headline', true );
$story                = get_post_meta( $post_id, 'about_story', true );
$img_id               = absint( get_post_meta( $post_id, 'about_image', true ) );
$img_secondary_id     = absint( get_post_meta( $post_id, 'about_image_secondary', true ) );
$values_headline      = get_post_meta( $post_id, 'about_values_headline', true );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="about-heading">
		<div class="tts-container">
			<div class="flex flex-col md:flex-row gap-12 items-start">
				<div class="w-full md:w-1/2">
					<h1 id="about-heading">
						<?php echo esc_html( $headline ?: tts_placeholder( 'About Headline' ) ); ?>
					</h1>
					<?php if ( $story ) : ?>
						<div class="tts-prose"><?php echo wp_kses_post( wpautop( $story ) ); ?></div>
					<?php else : ?>
						<p><?php echo esc_html( tts_placeholder( 'About Story' ) ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $img_id ) : ?>
					<div class="w-full md:w-1/2">
						<?php echo wp_get_attachment_image( $img_id, 'tts-feature', false, [
							'class'   => 'w-full h-auto rounded',
							'loading' => 'eager',
							'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: esc_attr( $headline ),
						] ); ?>
						<?php if ( $img_secondary_id ) : ?>
							<?php echo wp_get_attachment_image( $img_secondary_id, 'tts-card', false, [
								'class'   => 'w-full h-auto rounded mt-4',
								'loading' => 'lazy',
								'alt'     => get_post_meta( $img_secondary_id, '_wp_attachment_image_alt', true ) ?: '',
							] ); ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
	// Values section — only if at least one value headline is set
	$has_values = false;
	for ( $i = 1; $i <= 3; $i++ ) {
		if ( get_post_meta( $post_id, "about_value_{$i}_title", true ) ) {
			$has_values = true;
			break;
		}
	}
	if ( $has_values ) :
		?>
		<section class="tts-section" aria-labelledby="values-heading">
			<div class="tts-container">
				<?php if ( $values_headline ) : ?>
					<div class="tts-section-heading">
						<h2 id="values-heading" class="tts-section-heading__title">
							<?php echo esc_html( $values_headline ); ?>
						</h2>
					</div>
				<?php endif; ?>

				<div class="tts-grid-3">
					<?php for ( $i = 1; $i <= 3; $i++ ) :
						$title = get_post_meta( $post_id, "about_value_{$i}_title", true );
						$body  = get_post_meta( $post_id, "about_value_{$i}_body", true );
						if ( ! $title ) continue;
						?>
						<div class="tts-card flex flex-col gap-3">
							<h3 class="tts-card__title"><?php echo esc_html( $title ); ?></h3>
							<?php if ( $body ) : ?>
								<p><?php echo wp_kses_post( $body ); ?></p>
							<?php endif; ?>
						</div>
					<?php endfor; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/sections/team' ); ?>
	<?php get_template_part( 'template-parts/sections/testimonials' ); ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
