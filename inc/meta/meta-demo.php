<?php
/**
 * Meta Boxes: drumstudy_demo
 *
 * Fields: video_url, thumbnail_override (ID), duration,
 *         cta_label, cta_url, video_category
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
			'drumstudy_demo_meta',
			__( 'Demo / Video Details', 'drumstudy' ),
			'drumstudy_demo_meta_cb',
			'drumstudy_demo',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Demo meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_demo_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_demo_meta', 'drumstudy_demo_nonce' );

	$video_url          = get_post_meta( $post->ID, 'video_url', true );
	$img_id             = absint( get_post_meta( $post->ID, 'thumbnail_override', true ) );
	$duration           = get_post_meta( $post->ID, 'duration', true );
	$cta_label          = get_post_meta( $post->ID, 'cta_label', true );
	$cta_url            = get_post_meta( $post->ID, 'cta_url', true );
	$video_category     = get_post_meta( $post->ID, 'video_category', true );
	$img_preview        = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';

	$categories = [
		''             => __( '— Select —', 'drumstudy' ),
		'Demo'         => __( 'Demo', 'drumstudy' ),
		'Testimonial'  => __( 'Testimonial', 'drumstudy' ),
		'Tutorial'     => __( 'Tutorial', 'drumstudy' ),
		'Reel'         => __( 'Reel', 'drumstudy' ),
		'Performance'  => __( 'Performance', 'drumstudy' ),
	];
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row tts-meta-row--full">
			<label for="video_url" class="tts-meta-label"><?php esc_html_e( 'Video URL', 'drumstudy' ); ?></label>
			<input type="url" id="video_url" name="video_url" value="<?php echo esc_attr( $video_url ); ?>" class="widefat" placeholder="https://youtube.com/watch?v=..." />
			<p class="description"><?php esc_html_e( 'Where it appears: Embedded player on video card and single page. YouTube or Vimeo URL.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="video_category" class="tts-meta-label"><?php esc_html_e( 'Video Category', 'drumstudy' ); ?></label>
			<select id="video_category" name="video_category" class="widefat">
				<?php foreach ( $categories as $val => $label ) : ?>
					<option value="<?php echo esc_attr( $val ); ?>" <?php selected( $video_category, $val ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="description"><?php esc_html_e( 'Where it appears: Filter tag on video section and archive.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="duration" class="tts-meta-label"><?php esc_html_e( 'Duration', 'drumstudy' ); ?></label>
			<input type="text" id="duration" name="duration" value="<?php echo esc_attr( $duration ); ?>" class="widefat" placeholder="e.g. 2:34" />
			<p class="description"><?php esc_html_e( 'Where it appears: Displayed on video card.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="cta_label" class="tts-meta-label"><?php esc_html_e( 'CTA Label', 'drumstudy' ); ?></label>
			<input type="text" id="cta_label" name="cta_label" value="<?php echo esc_attr( $cta_label ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="cta_url" class="tts-meta-label"><?php esc_html_e( 'CTA URL', 'drumstudy' ); ?></label>
			<input type="text" id="cta_url" name="cta_url" value="<?php echo esc_attr( $cta_url ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Thumbnail Override', 'drumstudy' ); ?></label>
			<input type="hidden" id="thumbnail_override" name="thumbnail_override" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="thumbnail_override_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="thumbnail_override" data-preview="thumbnail_override_preview"><?php esc_html_e( 'Select Thumbnail', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="thumbnail_override" data-preview="thumbnail_override_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Overrides the auto-generated YouTube/Vimeo thumbnail.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Demo meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_demo_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_demo_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_demo_nonce'] ), 'drumstudy_save_demo_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_demo' !== get_post_type( $post_id ) ) return;

	if ( isset( $_POST['video_url'] ) ) {
		update_post_meta( $post_id, 'video_url', esc_url_raw( wp_unslash( $_POST['video_url'] ) ) );
	}
	foreach ( [ 'duration', 'cta_label', 'video_category' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['cta_url'] ) ) {
		update_post_meta( $post_id, 'cta_url', sanitize_text_field( wp_unslash( $_POST['cta_url'] ) ) );
	}
	if ( isset( $_POST['thumbnail_override'] ) ) {
		update_post_meta( $post_id, 'thumbnail_override', absint( $_POST['thumbnail_override'] ) );
	}
}
add_action( 'save_post', 'drumstudy_save_demo_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'drumstudy_demo' === get_post_type( $post_id ) ) {
			$keys[] = 'thumbnail_override';
		}
		return $keys;
	},
	10,
	2
);
