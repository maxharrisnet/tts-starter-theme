<?php
/**
 * Pagination
 *
 * Wraps paginate_links() with accessible markup.
 *
 * @package tts-theme
 */

$pagination = paginate_links( [
	'prev_text' => sprintf(
		'<span aria-hidden="true">&laquo;</span><span class="sr-only">%s</span>',
		esc_html__( 'Previous page', 'tts-theme' )
	),
	'next_text' => sprintf(
		'<span aria-hidden="true">&raquo;</span><span class="sr-only">%s</span>',
		esc_html__( 'Next page', 'tts-theme' )
	),
	'type'      => 'list',
] );

if ( $pagination ) :
	?>
	<nav class="tts-pagination-wrap" aria-label="<?php esc_attr_e( 'Page navigation', 'tts-theme' ); ?>">
		<?php echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — paginate_links() is safe ?>
	</nav>
<?php endif; ?>
