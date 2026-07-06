<?php
/**
 * Card: Search Result
 * Generic card used in search.php for all matched post types.
 *
 * @package drumstudy
 */

$post_type       = get_post_type();
$post_type_obj   = get_post_type_object( $post_type );
$post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : '';
?>
<article class="tts-card tts-card--search-result" aria-label="<?php the_title_attribute(); ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="tts-card__image">
			<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
				<?php the_post_thumbnail( 'tts-card', [
					'class'   => 'w-full h-full object-cover',
					'loading' => 'lazy',
				] ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="tts-card__body">
		<?php if ( $post_type_label ) : ?>
			<span class="tts-card__type"><?php echo esc_html( $post_type_label ); ?></span>
		<?php endif; ?>

		<h2 class="tts-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<time class="tts-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>">
			<?php echo esc_html( get_the_date() ); ?>
		</time>

		<?php if ( has_excerpt() ) : ?>
			<p class="tts-card__excerpt"><?php the_excerpt(); ?></p>
		<?php endif; ?>
	</div>
</article>
