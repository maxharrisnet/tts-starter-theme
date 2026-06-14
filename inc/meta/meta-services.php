<?php
/**
 * Meta Boxes: tts_service
 *
 * Fields: price, service_image (ID), cta_label, cta_url
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
			'tts_service_meta',
			__( 'Service Details', 'tts-theme' ),
			'tts_service_meta_cb',
			'tts_service',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Service meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_service_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'tts_save_service_meta', 'tts_service_nonce' );

	$price       = get_post_meta( $post->ID, 'price', true );
	$img_id      = absint( get_post_meta( $post->ID, 'service_image', true ) );
	$cta_label   = get_post_meta( $post->ID, 'cta_label', true );
	$cta_url     = get_post_meta( $post->ID, 'cta_url', true );
	$img_preview = $img_id ? wp_get_attachment_image( $img_id, 'thumbnail' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="price" class="tts-meta-label"><?php esc_html_e( 'Price', 'tts-theme' ); ?></label>
			<input type="text" id="price" name="price" value="<?php echo esc_attr( $price ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Service card and single page. Any format, e.g. $99/mo or Free.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Service Image', 'tts-theme' ); ?></label>
			<input type="hidden" id="service_image" name="service_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="service_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="service_image" data-preview="service_image_preview" data-title="<?php esc_attr_e( 'Select Service Image', 'tts-theme' ); ?>"><?php esc_html_e( 'Select Image', 'tts-theme' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="service_image" data-preview="service_image_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Service card thumbnail. Stored as attachment ID.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="cta_label" class="tts-meta-label"><?php esc_html_e( 'CTA Label', 'tts-theme' ); ?></label>
			<input type="text" id="cta_label" name="cta_label" value="<?php echo esc_attr( $cta_label ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Button on service card. Falls back to global CTA if empty.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="cta_url" class="tts-meta-label"><?php esc_html_e( 'CTA URL', 'tts-theme' ); ?></label>
			<input type="text" id="cta_url" name="cta_url" value="<?php echo esc_attr( $cta_url ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: CTA button link. Supports external URLs, relative paths, anchor links.', 'tts-theme' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Service meta fields.
 *
 * @param int $post_id Post ID.
 */
function tts_save_service_meta( int $post_id ): void {
	if ( ! isset( $_POST['tts_service_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['tts_service_nonce'] ), 'tts_save_service_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'tts_service' !== get_post_type( $post_id ) ) return;

	$fields = [
		'price'         => 'sanitize_text_field',
		'service_image' => 'absint',
		'cta_label'     => 'sanitize_text_field',
		'cta_url'       => 'sanitize_text_field',
	];

	foreach ( $fields as $key => $sanitizer ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, $sanitizer( wp_unslash( $_POST[ $key ] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
	}
}
add_action( 'save_post', 'tts_save_service_meta' );

add_filter(
	'tts_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'tts_service' === get_post_type( $post_id ) ) {
			$keys[] = 'service_image';
		}
		return $keys;
	},
	10,
	2
);
