<?php
/**
 * Meta Boxes: tts_location
 *
 * Fields: address_1 (required), address_2, city, state, postal,
 *         location_phone, location_email, location_hours (textarea),
 *         map_embed, location_image (ID), manager_name
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
			'tts_location_meta',
			__( 'Location Details', 'tts-theme' ),
			'tts_location_meta_cb',
			'tts_location',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Location meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_location_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'tts_save_location_meta', 'tts_location_nonce' );

	$address_1        = get_post_meta( $post->ID, 'address_1', true );
	$address_2        = get_post_meta( $post->ID, 'address_2', true );
	$city             = get_post_meta( $post->ID, 'city', true );
	$state            = get_post_meta( $post->ID, 'state', true );
	$postal           = get_post_meta( $post->ID, 'postal', true );
	$location_phone   = get_post_meta( $post->ID, 'location_phone', true );
	$location_email   = get_post_meta( $post->ID, 'location_email', true );
	$location_hours   = get_post_meta( $post->ID, 'location_hours', true );
	$map_embed        = get_post_meta( $post->ID, 'map_embed', true );
	$img_id           = absint( get_post_meta( $post->ID, 'location_image', true ) );
	$manager_name     = get_post_meta( $post->ID, 'manager_name', true );
	$img_preview      = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="address_1" class="tts-meta-label"><?php esc_html_e( 'Address Line 1', 'tts-theme' ); ?> <span class="required">*</span></label>
			<input type="text" id="address_1" name="address_1" value="<?php echo esc_attr( $address_1 ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Location card and single page. Required.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="address_2" class="tts-meta-label"><?php esc_html_e( 'Address Line 2', 'tts-theme' ); ?></label>
			<input type="text" id="address_2" name="address_2" value="<?php echo esc_attr( $address_2 ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="city" class="tts-meta-label"><?php esc_html_e( 'City', 'tts-theme' ); ?></label>
			<input type="text" id="city" name="city" value="<?php echo esc_attr( $city ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="state" class="tts-meta-label"><?php esc_html_e( 'State / Province', 'tts-theme' ); ?></label>
			<input type="text" id="state" name="state" value="<?php echo esc_attr( $state ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="postal" class="tts-meta-label"><?php esc_html_e( 'Postal Code', 'tts-theme' ); ?></label>
			<input type="text" id="postal" name="postal" value="<?php echo esc_attr( $postal ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="location_phone" class="tts-meta-label"><?php esc_html_e( 'Phone', 'tts-theme' ); ?></label>
			<input type="text" id="location_phone" name="location_phone" value="<?php echo esc_attr( $location_phone ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="location_email" class="tts-meta-label"><?php esc_html_e( 'Email', 'tts-theme' ); ?></label>
			<input type="email" id="location_email" name="location_email" value="<?php echo esc_attr( $location_email ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="manager_name" class="tts-meta-label"><?php esc_html_e( 'Manager Name', 'tts-theme' ); ?></label>
			<input type="text" id="manager_name" name="manager_name" value="<?php echo esc_attr( $manager_name ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="map_embed" class="tts-meta-label"><?php esc_html_e( 'Google Maps Embed URL', 'tts-theme' ); ?></label>
			<input type="url" id="map_embed" name="map_embed" value="<?php echo esc_attr( $map_embed ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Embedded map on location single page. Paste URL from Google Maps → Share → Embed.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="location_hours" class="tts-meta-label"><?php esc_html_e( 'Hours', 'tts-theme' ); ?></label>
			<textarea id="location_hours" name="location_hours" rows="5" class="widefat"><?php echo esc_textarea( $location_hours ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Location card and single page. One line per day.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Location Image', 'tts-theme' ); ?></label>
			<input type="hidden" id="location_image" name="location_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="location_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="location_image" data-preview="location_image_preview"><?php esc_html_e( 'Select Image', 'tts-theme' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="location_image" data-preview="location_image_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Save Location meta fields.
 *
 * @param int $post_id Post ID.
 */
function tts_save_location_meta( int $post_id ): void {
	if ( ! isset( $_POST['tts_location_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['tts_location_nonce'] ), 'tts_save_location_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'tts_location' !== get_post_type( $post_id ) ) return;

	$text_fields = [ 'address_1', 'address_2', 'city', 'state', 'postal', 'location_phone', 'manager_name' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['location_email'] ) ) {
		update_post_meta( $post_id, 'location_email', sanitize_email( wp_unslash( $_POST['location_email'] ) ) );
	}
	if ( isset( $_POST['map_embed'] ) ) {
		update_post_meta( $post_id, 'map_embed', esc_url_raw( wp_unslash( $_POST['map_embed'] ) ) );
	}
	if ( isset( $_POST['location_hours'] ) ) {
		update_post_meta( $post_id, 'location_hours', wp_kses_post( wp_unslash( $_POST['location_hours'] ) ) );
	}
	if ( isset( $_POST['location_image'] ) ) {
		update_post_meta( $post_id, 'location_image', absint( $_POST['location_image'] ) );
	}
}
add_action( 'save_post', 'tts_save_location_meta' );

add_filter(
	'tts_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'tts_location' === get_post_type( $post_id ) ) {
			$keys[] = 'location_image';
		}
		return $keys;
	},
	10,
	2
);
