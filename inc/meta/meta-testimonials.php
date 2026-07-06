<?php
/**
 * Meta Boxes: drumstudy_testim
 *
 * Fields: quote (required), author_name (required), author_role,
 *         author_image (ID), rating (1-5), source
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
			'drumstudy_testimonial_meta',
			__( 'Testimonial Details', 'drumstudy' ),
			'drumstudy_testimonial_meta_cb',
			'drumstudy_testim',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Testimonial meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_testimonial_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_testimonial_meta', 'drumstudy_testimonial_nonce' );

	$quote       = get_post_meta( $post->ID, 'quote', true );
	$author_name = get_post_meta( $post->ID, 'author_name', true );
	$author_role = get_post_meta( $post->ID, 'author_role', true );
	$img_id      = absint( get_post_meta( $post->ID, 'author_image', true ) );
	$rating      = absint( get_post_meta( $post->ID, 'rating', true ) );
	$source      = get_post_meta( $post->ID, 'source', true );
	$img_preview = $img_id ? wp_get_attachment_image( $img_id, 'tts-thumb' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row tts-meta-row--full">
			<label for="quote" class="tts-meta-label"><?php esc_html_e( 'Quote', 'drumstudy' ); ?> <span class="required">*</span></label>
			<textarea id="quote" name="quote" rows="4" class="widefat"><?php echo esc_textarea( $quote ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Body of the testimonial card. Required.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="author_name" class="tts-meta-label"><?php esc_html_e( 'Author Name', 'drumstudy' ); ?> <span class="required">*</span></label>
			<input type="text" id="author_name" name="author_name" value="<?php echo esc_attr( $author_name ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Testimonial card attribution. Required.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="author_role" class="tts-meta-label"><?php esc_html_e( 'Author Role / Company', 'drumstudy' ); ?></label>
			<input type="text" id="author_role" name="author_role" value="<?php echo esc_attr( $author_role ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Below author name on the testimonial card.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="rating" class="tts-meta-label"><?php esc_html_e( 'Rating (1–5)', 'drumstudy' ); ?></label>
			<input type="number" id="rating" name="rating" value="<?php echo esc_attr( (string) $rating ); ?>" min="1" max="5" class="small-text" />
			<p class="description"><?php esc_html_e( 'Where it appears: Star rating on testimonial card. Leave 0 to omit.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="source" class="tts-meta-label"><?php esc_html_e( 'Source', 'drumstudy' ); ?></label>
			<input type="text" id="source" name="source" value="<?php echo esc_attr( $source ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Platform or publication, e.g. Google, Yelp.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Author Photo', 'drumstudy' ); ?></label>
			<input type="hidden" id="author_image" name="author_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="author_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="author_image" data-preview="author_image_preview"><?php esc_html_e( 'Select Photo', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="author_image" data-preview="author_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Avatar on testimonial card.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Testimonial meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_testimonial_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_testimonial_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_testimonial_nonce'] ), 'drumstudy_save_testimonial_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_testim' !== get_post_type( $post_id ) ) return;

	if ( isset( $_POST['quote'] ) ) {
		update_post_meta( $post_id, 'quote', wp_kses_post( wp_unslash( $_POST['quote'] ) ) );
	}
	$text_fields = [ 'author_name', 'author_role', 'source' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['rating'] ) ) {
		update_post_meta( $post_id, 'rating', min( 5, max( 0, absint( $_POST['rating'] ) ) ) );
	}
	if ( isset( $_POST['author_image'] ) ) {
		update_post_meta( $post_id, 'author_image', absint( $_POST['author_image'] ) );
	}
}
add_action( 'save_post', 'drumstudy_save_testimonial_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'drumstudy_testim' === get_post_type( $post_id ) ) {
			$keys[] = 'author_image';
		}
		return $keys;
	},
	10,
	2
);
