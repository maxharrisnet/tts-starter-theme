<?php
/**
 * Meta Boxes: drumstudy_service
 *
 * Fields: price, service_image (ID), cta_label, cta_url, show_on_marketing,
 * booking_audiences, booking_description
 *
 * drumstudy_service is the single source of truth for every bookable line
 * item — marketing service cards (home + archive) and the booking page menu
 * (template-booking.php) both read from this post type. See
 * drumstudy_get_booking_services() in inc/helpers.php.
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
			'drumstudy_service_meta',
			__( 'Service Details', 'drumstudy' ),
			'drumstudy_service_meta_cb',
			'drumstudy_service',
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
function drumstudy_service_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_service_meta', 'drumstudy_service_nonce' );

	$price              = get_post_meta( $post->ID, 'price', true );
	$img_id             = absint( get_post_meta( $post->ID, 'service_image', true ) );
	$cta_label          = get_post_meta( $post->ID, 'cta_label', true );
	$cta_url            = get_post_meta( $post->ID, 'cta_url', true );
	$show_on_marketing  = get_post_meta( $post->ID, 'show_on_marketing', true );
	$show_on_marketing  = '' === $show_on_marketing ? true : (bool) $show_on_marketing;
	$booking_audiences  = array_filter( explode( ',', get_post_meta( $post->ID, 'booking_audiences', true ) ) );
	$booking_description = get_post_meta( $post->ID, 'booking_description', true );
	$img_preview        = $img_id ? wp_get_attachment_image( $img_id, 'thumbnail' ) : '';
	$embed_key          = 'booking_embed_' . sanitize_key( $post->post_name );
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="price" class="tts-meta-label"><?php esc_html_e( 'Price', 'drumstudy' ); ?></label>
			<input type="text" id="price" name="price" value="<?php echo esc_attr( $price ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Service card, single page, and the booking page price column. Any format, e.g. $99/mo, Free, or $45 per 30 minutes.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Service Image', 'drumstudy' ); ?></label>
			<input type="hidden" id="service_image" name="service_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="service_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="service_image" data-preview="service_image_preview" data-title="<?php esc_attr_e( 'Select Service Image', 'drumstudy' ); ?>"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="service_image" data-preview="service_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Service card thumbnail. Stored as attachment ID.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="cta_label" class="tts-meta-label"><?php esc_html_e( 'CTA Label', 'drumstudy' ); ?></label>
			<input type="text" id="cta_label" name="cta_label" value="<?php echo esc_attr( $cta_label ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Button on service card. Falls back to global CTA if empty.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="cta_url" class="tts-meta-label"><?php esc_html_e( 'CTA URL', 'drumstudy' ); ?></label>
			<input type="text" id="cta_url" name="cta_url" value="<?php echo esc_attr( $cta_url ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: CTA button link. Supports external URLs, relative paths, anchor links.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label class="tts-meta-label"><?php esc_html_e( 'Show on marketing pages', 'drumstudy' ); ?></label>
			<label>
				<input type="checkbox" id="show_on_marketing" name="show_on_marketing" value="1" <?php checked( $show_on_marketing ); ?> />
				<?php esc_html_e( 'Include this service in the homepage and archive service grids.', 'drumstudy' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'Uncheck for booking-only line items (e.g. a phone consultation or a surcharge) that should not appear as a marketing card.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label class="tts-meta-label"><?php esc_html_e( 'Show on booking page(s)', 'drumstudy' ); ?></label>
			<label style="margin-right: 1.5em;">
				<input type="checkbox" name="booking_audiences[]" value="new_client" <?php checked( in_array( 'new_client', $booking_audiences, true ) ); ?> />
				<?php esc_html_e( 'New clients (consultation funnel)', 'drumstudy' ); ?>
			</label>
			<label>
				<input type="checkbox" name="booking_audiences[]" value="existing_client" <?php checked( in_array( 'existing_client', $booking_audiences, true ) ); ?> />
				<?php esc_html_e( 'Existing clients (lesson booking menu)', 'drumstudy' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'Where it appears: Adds this service as a bookable card on the matching booking page(s). Leave both unchecked to keep it marketing-only.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="booking_description" class="tts-meta-label"><?php esc_html_e( 'Booking Page Description', 'drumstudy' ); ?></label>
			<textarea id="booking_description" name="booking_description" rows="2" class="widefat"><?php echo esc_textarea( $booking_description ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Plain-text blurb on the booking card (shorter, more literal than the marketing excerpt above). Falls back to a stripped-down version of the excerpt if left blank.', 'drumstudy' ); ?></p>
		</div>
		<?php if ( $booking_audiences ) : ?>
			<div class="tts-meta-row tts-meta-row--full">
				<label for="<?php echo esc_attr( $embed_key ); ?>" class="tts-meta-label">
					<?php esc_html_e( 'Square Booking Embed', 'drumstudy' ); ?>
				</label>
				<textarea id="<?php echo esc_attr( $embed_key ); ?>" name="<?php echo esc_attr( $embed_key ); ?>" rows="4" class="widefat"><?php echo esc_textarea( get_post_meta( $post->ID, $embed_key, true ) ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Where it appears: Inline booking widget on the booking page card. Leave blank to show a placeholder until the Square embed code is ready. Stored on this service, keyed to its slug — so it stays attached even if the service is added to or removed from a booking page.', 'drumstudy' ); ?></p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Save Service meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_service_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_service_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_service_nonce'] ), 'drumstudy_save_service_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_service' !== get_post_type( $post_id ) ) return;

	$fields = [
		'price'                => 'sanitize_text_field',
		'service_image'        => 'absint',
		'cta_label'            => 'sanitize_text_field',
		'cta_url'              => 'sanitize_text_field',
		'booking_description'  => 'sanitize_textarea_field',
	];

	foreach ( $fields as $key => $sanitizer ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, $sanitizer( wp_unslash( $_POST[ $key ] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		}
	}

	// Checkbox: absent from $_POST entirely when unchecked.
	update_post_meta( $post_id, 'show_on_marketing', isset( $_POST['show_on_marketing'] ) ? '1' : '0' );

	$audiences = array_intersect(
		array_map( 'sanitize_key', wp_unslash( (array) ( $_POST['booking_audiences'] ?? [] ) ) ),
		[ 'new_client', 'existing_client' ]
	);
	update_post_meta( $post_id, 'booking_audiences', implode( ',', $audiences ) );

	// Embed code lives on the service itself, keyed to its own slug, so it
	// survives the service being added to or removed from a booking page.
	if ( $audiences ) {
		$embed_key = 'booking_embed_' . sanitize_key( get_post_field( 'post_name', $post_id ) );
		if ( isset( $_POST[ $embed_key ] ) ) {
			update_post_meta( $post_id, $embed_key, wp_kses_post( wp_unslash( $_POST[ $embed_key ] ) ) );
		}
	}
}
add_action( 'save_post', 'drumstudy_save_service_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'drumstudy_service' === get_post_type( $post_id ) ) {
			$keys[] = 'service_image';
		}
		return $keys;
	},
	10,
	2
);
