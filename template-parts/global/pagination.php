<?php
/**
 * Pagination
 *
 * Wraps paginate_links() with accessible markup.
 *
 * @package drumstudy
 */

$pagination = paginate_links( [
	'prev_text' => sprintf(
		'<span aria-hidden="true">&laquo;</span><span class="sr-only">%s</span>',
		esc_html__( 'Previous page', 'drumstudy' )
	),
	'next_text' => sprintf(
		'<span aria-hidden="true">&raquo;</span><span class="sr-only">%s</span>',
		esc_html__( 'Next page', 'drumstudy' )
	),
	'type'      => 'list',
] );

if ( $pagination ) :
	?>
	<nav class="tts-pagination-wrap" aria-label="<?php esc_attr_e( 'Page navigation', 'drumstudy' ); ?>">
		<?php echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — paginate_links() is safe ?>
	</nav>
<?php endif; ?>
