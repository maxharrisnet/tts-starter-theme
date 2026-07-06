<?php
/**
 * Section: Embed Block (flexible shortcode/embed/iframe slot)
 *
 * Pass $args['meta_key'] to specify which meta field to read.
 * Falls back to 'home_embed_block' if not specified.
 *
 * @package drumstudy
 */

$meta_key = $args['meta_key'] ?? 'home_embed_block';
$post_id  = get_the_ID() ?: 0;
$embed    = get_post_meta( $post_id, $meta_key, true );

if ( ! $embed ) {
	return;
}
?>
<div class="tts-embed-block tts-section tts-section--sm">
	<div class="tts-container-prose">
		<?php echo drumstudy_render_embed( $embed ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</div>
