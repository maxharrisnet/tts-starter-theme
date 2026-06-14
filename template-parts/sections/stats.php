<?php
/**
 * Section: Stats Strip
 *
 * @package tts-theme
 */

$post_id = get_the_ID() ?: 0;
$stats   = [];
for ( $i = 1; $i <= 3; $i++ ) {
	$number = get_post_meta( $post_id, "home_stat_{$i}_number", true );
	$label  = get_post_meta( $post_id, "home_stat_{$i}_label", true );
	if ( $number ) {
		$stats[] = compact( 'number', 'label' );
	}
}

if ( empty( $stats ) ) {
	return;
}
?>
<section class="tts-section tts-section--sm" aria-label="<?php esc_attr_e( 'Key statistics', 'tts-theme' ); ?>">
	<div class="tts-container">
		<div class="tts-stats" role="list">
			<?php foreach ( $stats as $stat ) : ?>
				<div class="tts-stats__item" role="listitem">
					<div class="tts-stats__number" aria-label="<?php echo esc_attr( $stat['number'] ); ?>">
						<?php echo esc_html( $stat['number'] ); ?>
					</div>
					<?php if ( $stat['label'] ) : ?>
						<div class="tts-stats__label"><?php echo esc_html( $stat['label'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
