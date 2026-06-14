<?php
/**
 * Card: Update / News Post (native WP post)
 *
 * @package tts-theme
 */

$post_id        = get_the_ID();
$external_url   = get_post_meta( $post_id, 'external_url', true );
$source_outlet  = get_post_meta( $post_id, 'source_outlet', true );
$permalink      = $external_url ? esc_url( $external_url ) : get_permalink();
$target         = $external_url ? ' target="_blank" rel="noopener noreferrer"' : '';
?>
<article class="tts-card tts-card--update" aria-label="<?php the_title_attribute(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="tts-card__image">
			<a href="<?php echo $permalink; ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> tabindex="-1" aria-hidden="true">
				<?php the_post_thumbnail( 'tts-card', [
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				] ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="tts-card__body">
		<?php
		$categories = get_the_category();
		if ( $categories ) :
			?>
			<span class="tts-card__category">
				<?php echo esc_html( $categories[0]->name ); ?>
			</span>
		<?php endif; ?>

		<h3 class="tts-card__title">
			<a href="<?php echo $permalink; ?>"<?php echo $target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
				<?php the_title(); ?>
			</a>
		</h3>

		<div class="tts-card__meta flex items-center gap-3">
			<time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
				<?php echo esc_html( get_the_date() ); ?>
			</time>
			<?php if ( $source_outlet ) : ?>
				<span class="tts-card__source"><?php echo esc_html( $source_outlet ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>
	</div>
</article>
