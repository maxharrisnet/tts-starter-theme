<?php
/**
 * Card: Press Item
 *
 * @package drumstudy
 */

$post_id      = get_the_ID();
$article_url  = get_post_meta( $post_id, 'article_url', true );
$publish_date = get_post_meta( $post_id, 'publish_date', true );
$logo_id      = absint( get_post_meta( $post_id, 'outlet_logo', true ) );
$pull_quote   = get_post_meta( $post_id, 'pull_quote', true );

$formatted_date = '';
if ( $publish_date ) {
	$timestamp      = strtotime( $publish_date );
	$formatted_date = $timestamp ? date_i18n( get_option( 'date_format' ), $timestamp ) : $publish_date;
}

$outlet_name = get_the_title();
?>
<article class="tts-card tts-card--press" aria-label="<?php the_title_attribute(); ?>">
	<div class="tts-card__body">
		<?php if ( $logo_id ) : ?>
			<div class="tts-card__logo">
				<?php echo wp_get_attachment_image( $logo_id, 'tts-logo', false, [
					'class'   => 'h-10 w-auto object-contain',
					'loading' => 'lazy',
					'alt'     => get_post_meta( $logo_id, '_wp_attachment_image_alt', true ) ?: $outlet_name,
				] ); ?>
			</div>
		<?php else : ?>
			<p class="tts-card__outlet"><?php echo esc_html( $outlet_name ); ?></p>
		<?php endif; ?>

		<?php if ( $pull_quote ) : ?>
			<blockquote class="tts-card__quote">
				<p><?php echo wp_kses_post( $pull_quote ); ?></p>
			</blockquote>
		<?php elseif ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>

		<div class="tts-card__footer flex items-center justify-between gap-3">
			<?php if ( $formatted_date ) : ?>
				<time class="tts-card__date" datetime="<?php echo esc_attr( $publish_date ); ?>">
					<?php echo esc_html( $formatted_date ); ?>
				</time>
			<?php endif; ?>
			<?php if ( $article_url ) : ?>
				<a href="<?php echo esc_url( $article_url ); ?>"
				   target="_blank"
				   rel="noopener noreferrer"
				   class="tts-btn tts-btn--secondary"
				   aria-label="<?php printf( esc_attr__( 'Read article in %s', 'drumstudy' ), $outlet_name ); ?>">
					<?php esc_html_e( 'Read Article', 'drumstudy' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</article>
