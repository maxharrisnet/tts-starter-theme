<?php
/**
 * Meta Boxes: tts_gallery
 *
 * Fields: gallery_image (ID), caption, category, project_link, project_name
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes',
	function (): void {
		add_meta_box(
			'tts_gallery_meta',
			__( 'Gallery Item Details', 'tts-theme' ),
			'tts_gallery_meta_cb',
			'tts_gallery',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Gallery meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_gallery_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'tts_save_gallery_meta', 'tts_gallery_nonce' );

	$img_id       = absint( get_post_meta( $post->ID, 'gallery_image', true ) );
	$caption      = get_post_meta( $post->ID, 'caption', true );
	$category     = get_post_meta( $post->ID, 'category', true );
	$project_link = get_post_meta( $post->ID, 'project_link', true );
	$project_name = get_post_meta( $post->ID, 'project_name', true );
	$img_preview  = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Gallery Image', 'tts-theme' ); ?></label>
			<input type="hidden" id="gallery_image" name="gallery_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="gallery_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="gallery_image" data-preview="gallery_image_preview"><?php esc_html_e( 'Select Image', 'tts-theme' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="gallery_image" data-preview="gallery_image_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Gallery grid. Primary display image.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="caption" class="tts-meta-label"><?php esc_html_e( 'Caption', 'tts-theme' ); ?></label>
			<input type="text" id="caption" name="caption" value="<?php echo esc_attr( $caption ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Overlay or below image in gallery grid.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="category" class="tts-meta-label"><?php esc_html_e( 'Category', 'tts-theme' ); ?></label>
			<input type="text" id="category" name="category" value="<?php echo esc_attr( $category ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Gallery filter tag. Group items by category.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="project_name" class="tts-meta-label"><?php esc_html_e( 'Project Name', 'tts-theme' ); ?></label>
			<input type="text" id="project_name" name="project_name" value="<?php echo esc_attr( $project_name ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="project_link" class="tts-meta-label"><?php esc_html_e( 'Project Link', 'tts-theme' ); ?></label>
			<input type="text" id="project_link" name="project_link" value="<?php echo esc_attr( $project_link ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Link on gallery item. Optional.', 'tts-theme' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Gallery meta fields.
 *
 * @param int $post_id Post ID.
 */
function tts_save_gallery_meta( int $post_id ): void {
	if ( ! isset( $_POST['tts_gallery_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['tts_gallery_nonce'] ), 'tts_save_gallery_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'tts_gallery' !== get_post_type( $post_id ) ) return;

	foreach ( [ 'caption', 'category', 'project_name' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['project_link'] ) ) {
		update_post_meta( $post_id, 'project_link', sanitize_text_field( wp_unslash( $_POST['project_link'] ) ) );
	}
	if ( isset( $_POST['gallery_image'] ) ) {
		update_post_meta( $post_id, 'gallery_image', absint( $_POST['gallery_image'] ) );
	}
}
add_action( 'save_post', 'tts_save_gallery_meta' );

add_filter(
	'tts_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'tts_gallery' === get_post_type( $post_id ) ) {
			$keys[] = 'gallery_image';
		}
		return $keys;
	},
	10,
	2
);
