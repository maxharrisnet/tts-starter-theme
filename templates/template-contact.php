<?php
/**
 * Template Name: Contact
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );

$post_id           = get_the_ID();
$headline          = get_post_meta( $post_id, 'contact_headline', true );
$subheadline       = get_post_meta( $post_id, 'contact_subheadline', true );
$form_embed        = get_post_meta( $post_id, 'contact_form_embed', true );
$secondary_heading = get_post_meta( $post_id, 'contact_secondary_headline', true );
$secondary_body    = get_post_meta( $post_id, 'contact_secondary_body', true );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="contact-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<h1 id="contact-heading" class="tts-section-heading__title">
					<?php echo esc_html( $headline ?: drumstudy_placeholder( 'Contact Headline' ) ); ?>
				</h1>
				<?php if ( $subheadline ) : ?>
					<p class="tts-section-heading__sub"><?php echo esc_html( $subheadline ); ?></p>
				<?php endif; ?>
			</div>

			<div class="flex flex-col lg:flex-row gap-12">
				<div class="w-full lg:w-2/3">
					<?php if ( $form_embed ) : ?>
						<div class="tts-embed-block">
							<?php echo drumstudy_render_embed( $form_embed ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php else : ?>
						<p><?php echo esc_html( drumstudy_placeholder( 'Contact Form Embed' ) ); ?></p>
					<?php endif; ?>
				</div>

				<aside class="w-full lg:w-1/3">
					<?php get_template_part( 'template-parts/global/location' ); ?>

					<?php if ( $secondary_heading || $secondary_body ) : ?>
						<div class="tts-contact__secondary mt-8">
							<?php if ( $secondary_heading ) : ?>
								<h2><?php echo esc_html( $secondary_heading ); ?></h2>
							<?php endif; ?>
							<?php if ( $secondary_body ) : ?>
								<div class="tts-prose"><?php echo wp_kses_post( wpautop( $secondary_body ) ); ?></div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</aside>
			</div>
		</div>
	</section>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
