<?php
/**
 * Template Name: About
 *
 * @package drumstudy
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

	<!-- Hero -->
	<section class="tts-hero" aria-labelledby="about-heading">
		<?php if ( $img_id ) : ?>
			<?php
			echo wp_get_attachment_image( $img_id, 'tts-hero', false, [
				'class'         => 'tts-hero__bg',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'alt'           => '',
				'aria-hidden'   => 'true',
			] );
			?>
			<div class="tts-hero__overlay" aria-hidden="true"></div>
		<?php endif; ?>

		<div class="tts-container tts-hero__content">
			<div class="tts-hero__panel">
				<h1 id="about-heading" class="tts-hero__headline">
					<?php echo esc_html( $headline ?: drumstudy_placeholder( 'About Headline' ) ); ?>
				</h1>
			</div>
		</div>
	</section>

	<!-- Story -->
	<?php if ( $story || $img_secondary_id ) : ?>
		<section class="tts-section" aria-label="<?php esc_attr_e( 'About', 'drumstudy' ); ?>">
			<div class="tts-container">
				<div class="flex flex-col md:flex-row gap-12 items-start">
					<div class="w-full <?php echo $img_secondary_id ? 'md:w-1/2' : ''; ?>">
						<?php if ( $story ) : ?>
							<div class="tts-prose"><?php echo wp_kses_post( wpautop( $story ) ); ?></div>
						<?php else : ?>
							<p><?php echo esc_html( drumstudy_placeholder( 'About Story' ) ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $img_secondary_id ) : ?>
						<div class="w-full md:w-1/2">
							<?php echo wp_get_attachment_image( $img_secondary_id, 'tts-feature', false, [
								'class'   => 'w-full h-auto rounded',
								'loading' => 'lazy',
								'alt'     => get_post_meta( $img_secondary_id, '_wp_attachment_image_alt', true ) ?: '',
							] ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

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
						<div class="tts-card">
							<div class="tts-card__body">
								<h3 class="tts-card__title"><?php echo esc_html( $title ); ?></h3>
								<?php if ( $body ) : ?>
									<p><?php echo wp_kses_post( $body ); ?></p>
								<?php endif; ?>
							</div>
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
