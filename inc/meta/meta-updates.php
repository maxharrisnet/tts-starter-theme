<?php
/**
 * Meta Boxes: native Posts (relabeled as "Updates")
 *
 * Extra fields: external_url, source_outlet
 * Native: title, content, excerpt (standfirst), featured image, categories
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
			'drumstudy_update_meta',
			__( 'Update Source Info', 'drumstudy' ),
			'drumstudy_update_meta_cb',
			'post',
			'normal',
			'default'
		);
	}
);

/**
 * Render the Updates meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_update_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_update_meta', 'drumstudy_update_nonce' );

	$external_url  = get_post_meta( $post->ID, 'external_url', true );
	$source_outlet = get_post_meta( $post->ID, 'source_outlet', true );
	?>
	<p class="description"><?php esc_html_e( 'For press/syndicated content: link to the original article and note the source outlet. Leave blank for owned content.', 'drumstudy' ); ?></p>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="external_url" class="tts-meta-label"><?php esc_html_e( 'External Article URL', 'drumstudy' ); ?></label>
			<input type="url" id="external_url" name="external_url" value="<?php echo esc_attr( $external_url ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Read more link on update card. Overrides permalink when set.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="source_outlet" class="tts-meta-label"><?php esc_html_e( 'Source Outlet', 'drumstudy' ); ?></label>
			<input type="text" id="source_outlet" name="source_outlet" value="<?php echo esc_attr( $source_outlet ); ?>" class="widefat" placeholder="e.g. TechCrunch, Forbes" />
			<p class="description"><?php esc_html_e( 'Where it appears: Source attribution on update card.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Updates meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_update_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_update_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_update_nonce'] ), 'drumstudy_save_update_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'post' !== get_post_type( $post_id ) ) return;

	if ( isset( $_POST['external_url'] ) ) {
		update_post_meta( $post_id, 'external_url', esc_url_raw( wp_unslash( $_POST['external_url'] ) ) );
	}
	if ( isset( $_POST['source_outlet'] ) ) {
		update_post_meta( $post_id, 'source_outlet', sanitize_text_field( wp_unslash( $_POST['source_outlet'] ) ) );
	}
}
add_action( 'save_post', 'drumstudy_save_update_meta' );
