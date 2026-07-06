<?php
/**
 * Meta Boxes: drumstudy_faq
 *
 * Fields: answer (textarea), display_order (number)
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes',
	function (): void {
		add_meta_box(
			'drumstudy_faq_meta',
			__( 'FAQ Details', 'drumstudy' ),
			'drumstudy_faq_meta_cb',
			'drumstudy_faq',
			'normal',
			'high'
		);
	}
);

/**
 * Render the FAQ meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_faq_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_faq_meta', 'drumstudy_faq_nonce' );

	$answer = get_post_meta( $post->ID, 'answer', true );
	$order  = get_post_meta( $post->ID, 'display_order', true );
	?>
	<p class="description"><?php esc_html_e( 'The question is the post title above. Fill in the answer and display order here.', 'drumstudy' ); ?></p>
	<div class="tts-meta-grid">
		<div class="tts-meta-row tts-meta-row--full">
			<label for="answer" class="tts-meta-label"><?php esc_html_e( 'Answer', 'drumstudy' ); ?></label>
			<textarea id="answer" name="answer" rows="6" class="widefat"><?php echo esc_textarea( $answer ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Accordion panel in the FAQ section. Basic HTML allowed.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="display_order" class="tts-meta-label"><?php esc_html_e( 'Display Order', 'drumstudy' ); ?></label>
			<input type="number" id="display_order" name="display_order" value="<?php echo esc_attr( $order ); ?>" min="0" class="small-text" />
			<p class="description"><?php esc_html_e( 'Where it appears: Controls sort order in the FAQ section. Lower numbers appear first.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save FAQ meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_faq_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_faq_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_faq_nonce'] ), 'drumstudy_save_faq_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_faq' !== get_post_type( $post_id ) ) return;

	if ( isset( $_POST['answer'] ) ) {
		update_post_meta( $post_id, 'answer', wp_kses_post( wp_unslash( $_POST['answer'] ) ) );
	}
	if ( isset( $_POST['display_order'] ) ) {
		update_post_meta( $post_id, 'display_order', absint( $_POST['display_order'] ) );
	}
}
add_action( 'save_post', 'drumstudy_save_faq_meta' );
