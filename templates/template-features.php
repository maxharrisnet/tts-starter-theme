<?php
/**
 * Template Name: Features
 * 6 fixed feature slots — renders only if headline is populated.
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );

$post_id      = get_the_ID();
$headline     = get_post_meta( $post_id, 'features_headline', true );
$subheadline  = get_post_meta( $post_id, 'features_subheadline', true );
$intro        = get_post_meta( $post_id, 'features_intro', true );
$cta_label    = get_post_meta( $post_id, 'features_cta_label', true );
$cta_url      = get_post_meta( $post_id, 'features_cta_url', true );
?>
<main id="main-content" role="main">

	<section class="tts-section" aria-labelledby="features-page-heading">
		<div class="tts-container">
			<div class="tts-section-heading">
				<h1 id="features-page-heading" class="tts-section-heading__title">
					<?php echo esc_html( $headline ?: drumstudy_placeholder( 'Features Headline' ) ); ?>
				</h1>
				<?php if ( $subheadline ) : ?>
					<p class="tts-section-heading__sub"><?php echo esc_html( $subheadline ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $intro ) : ?>
				<div class="tts-container-prose mx-auto mb-12">
					<div class="tts-prose"><?php echo wp_kses_post( wpautop( $intro ) ); ?></div>
				</div>
			<?php endif; ?>

			<div class="tts-grid-3">
				<?php
				for ( $i = 1; $i <= 6; $i++ ) :
					$feat_headline = get_post_meta( $post_id, "feature_{$i}_headline", true );
					if ( ! $feat_headline ) continue;
					$feat_body   = get_post_meta( $post_id, "feature_{$i}_body", true );
					$feat_icon   = absint( get_post_meta( $post_id, "feature_{$i}_icon", true ) );
					?>
					<div class="tts-feature-item flex flex-col gap-4">
						<?php if ( $feat_icon ) : ?>
							<div class="tts-feature-item__icon w-12 h-12">
								<?php echo wp_get_attachment_image( $feat_icon, 'thumbnail', false, [
									'class'   => 'w-full h-full object-contain',
									'loading' => 'lazy',
									'alt'     => '',
								] ); ?>
							</div>
						<?php endif; ?>
						<h2 class="tts-card__title"><?php echo esc_html( $feat_headline ); ?></h2>
						<?php if ( $feat_body ) : ?>
							<p><?php echo wp_kses_post( $feat_body ); ?></p>
						<?php endif; ?>
					</div>
				<?php endfor; ?>
			</div>

			<?php if ( $cta_label && $cta_url ) : ?>
				<div class="tts-cta-strip mt-12">
					<?php drumstudy_render_cta( $cta_label, $cta_url ); ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/sections/embed-block', null, [ 'meta_key' => 'features_embed_block' ] ); ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
