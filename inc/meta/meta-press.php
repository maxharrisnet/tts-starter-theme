<?php
/**
 * Meta Boxes: drumstudy_press
 *
 * Fields: article_url, publish_date, outlet_logo (ID), pull_quote
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
			'drumstudy_press_meta',
			__( 'Press Item Details', 'drumstudy' ),
			'drumstudy_press_meta_cb',
			'drumstudy_press',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Press meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_press_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_press_meta', 'drumstudy_press_nonce' );

	$article_url  = get_post_meta( $post->ID, 'article_url', true );
	$publish_date = get_post_meta( $post->ID, 'publish_date', true );
	$img_id       = absint( get_post_meta( $post->ID, 'outlet_logo', true ) );
	$pull_quote   = get_post_meta( $post->ID, 'pull_quote', true );
	$img_preview  = $img_id ? wp_get_attachment_image( $img_id, 'tts-logo' ) : '';
	?>
	<p class="description"><?php esc_html_e( 'Post title = outlet name. Post content = article headline.', 'drumstudy' ); ?></p>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="article_url" class="tts-meta-label"><?php esc_html_e( 'Article URL', 'drumstudy' ); ?></label>
			<input type="url" id="article_url" name="article_url" value="<?php echo esc_attr( $article_url ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Press logo links to this article.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="publish_date" class="tts-meta-label"><?php esc_html_e( 'Publish Date', 'drumstudy' ); ?></label>
			<input type="date" id="publish_date" name="publish_date" value="<?php echo esc_attr( $publish_date ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Date shown on press card.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Outlet Logo', 'drumstudy' ); ?></label>
			<input type="hidden" id="outlet_logo" name="outlet_logo" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="outlet_logo_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="outlet_logo" data-preview="outlet_logo_preview"><?php esc_html_e( 'Select Logo', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="outlet_logo" data-preview="outlet_logo_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Press logo bar and press card.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="pull_quote" class="tts-meta-label"><?php esc_html_e( 'Pull Quote', 'drumstudy' ); ?></label>
			<textarea id="pull_quote" name="pull_quote" rows="3" class="widefat"><?php echo esc_textarea( $pull_quote ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Excerpt displayed on the press card. Optional.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Press meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_press_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_press_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_press_nonce'] ), 'drumstudy_save_press_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_press' !== get_post_type( $post_id ) ) return;

	if ( isset( $_POST['article_url'] ) ) {
		update_post_meta( $post_id, 'article_url', esc_url_raw( wp_unslash( $_POST['article_url'] ) ) );
	}
	if ( isset( $_POST['publish_date'] ) ) {
		update_post_meta( $post_id, 'publish_date', sanitize_text_field( wp_unslash( $_POST['publish_date'] ) ) );
	}
	if ( isset( $_POST['outlet_logo'] ) ) {
		update_post_meta( $post_id, 'outlet_logo', absint( $_POST['outlet_logo'] ) );
	}
	if ( isset( $_POST['pull_quote'] ) ) {
		update_post_meta( $post_id, 'pull_quote', wp_kses_post( wp_unslash( $_POST['pull_quote'] ) ) );
	}
}
add_action( 'save_post', 'drumstudy_save_press_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'drumstudy_press' === get_post_type( $post_id ) ) {
			$keys[] = 'outlet_logo';
		}
		return $keys;
	},
	10,
	2
);
