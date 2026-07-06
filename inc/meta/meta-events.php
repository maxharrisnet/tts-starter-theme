<?php
/**
 * Meta Boxes: drumstudy_event
 *
 * Fields: event_date (required), event_time, end_date, end_time,
 *         location_name, location_address, ticket_url, ticket_price,
 *         event_image (ID), organizer, embed_block
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
			'drumstudy_event_meta',
			__( 'Event Details', 'drumstudy' ),
			'drumstudy_event_meta_cb',
			'drumstudy_event',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Event meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_event_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_event_meta', 'drumstudy_event_nonce' );

	$event_date       = get_post_meta( $post->ID, 'event_date', true );
	$event_time       = get_post_meta( $post->ID, 'event_time', true );
	$end_date         = get_post_meta( $post->ID, 'end_date', true );
	$end_time         = get_post_meta( $post->ID, 'end_time', true );
	$location_name    = get_post_meta( $post->ID, 'location_name', true );
	$location_address = get_post_meta( $post->ID, 'location_address', true );
	$ticket_url       = get_post_meta( $post->ID, 'ticket_url', true );
	$ticket_price     = get_post_meta( $post->ID, 'ticket_price', true );
	$img_id           = absint( get_post_meta( $post->ID, 'event_image', true ) );
	$organizer        = get_post_meta( $post->ID, 'organizer', true );
	$embed_block      = get_post_meta( $post->ID, 'embed_block', true );
	$img_preview      = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="event_date" class="tts-meta-label"><?php esc_html_e( 'Event Date', 'drumstudy' ); ?> <span class="required">*</span></label>
			<input type="date" id="event_date" name="event_date" value="<?php echo esc_attr( $event_date ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Event card and archive. Required — controls upcoming/past sorting.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="event_time" class="tts-meta-label"><?php esc_html_e( 'Start Time', 'drumstudy' ); ?></label>
			<input type="text" id="event_time" name="event_time" value="<?php echo esc_attr( $event_time ); ?>" class="widefat" placeholder="e.g. 7:00 PM" />
		</div>
		<div class="tts-meta-row">
			<label for="end_date" class="tts-meta-label"><?php esc_html_e( 'End Date', 'drumstudy' ); ?></label>
			<input type="date" id="end_date" name="end_date" value="<?php echo esc_attr( $end_date ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Leave blank for single-day events.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="end_time" class="tts-meta-label"><?php esc_html_e( 'End Time', 'drumstudy' ); ?></label>
			<input type="text" id="end_time" name="end_time" value="<?php echo esc_attr( $end_time ); ?>" class="widefat" placeholder="e.g. 10:00 PM" />
		</div>
		<div class="tts-meta-row">
			<label for="location_name" class="tts-meta-label"><?php esc_html_e( 'Venue Name', 'drumstudy' ); ?></label>
			<input type="text" id="location_name" name="location_name" value="<?php echo esc_attr( $location_name ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Event card and single page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="location_address" class="tts-meta-label"><?php esc_html_e( 'Venue Address', 'drumstudy' ); ?></label>
			<input type="text" id="location_address" name="location_address" value="<?php echo esc_attr( $location_address ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="ticket_url" class="tts-meta-label"><?php esc_html_e( 'Ticket URL', 'drumstudy' ); ?></label>
			<input type="text" id="ticket_url" name="ticket_url" value="<?php echo esc_attr( $ticket_url ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Get tickets CTA on event card and single page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="ticket_price" class="tts-meta-label"><?php esc_html_e( 'Ticket Price', 'drumstudy' ); ?></label>
			<input type="text" id="ticket_price" name="ticket_price" value="<?php echo esc_attr( $ticket_price ); ?>" class="widefat" placeholder="e.g. $25 or Free" />
		</div>
		<div class="tts-meta-row">
			<label for="organizer" class="tts-meta-label"><?php esc_html_e( 'Organizer', 'drumstudy' ); ?></label>
			<input type="text" id="organizer" name="organizer" value="<?php echo esc_attr( $organizer ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Event Image', 'drumstudy' ); ?></label>
			<input type="hidden" id="event_image" name="event_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="event_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="event_image" data-preview="event_image_preview"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="event_image" data-preview="event_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="embed_block" class="tts-meta-label"><?php esc_html_e( 'Embed / Shortcode Block', 'drumstudy' ); ?></label>
			<textarea id="embed_block" name="embed_block" rows="4" class="widefat"><?php echo esc_textarea( $embed_block ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Below event description. Accepts shortcodes, embed URLs, iframe codes.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Event meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_event_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_event_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_event_nonce'] ), 'drumstudy_save_event_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_event' !== get_post_type( $post_id ) ) return;

	$text_fields = [ 'event_date', 'event_time', 'end_date', 'end_time', 'location_name', 'location_address', 'ticket_price', 'organizer' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['ticket_url'] ) ) {
		update_post_meta( $post_id, 'ticket_url', sanitize_text_field( wp_unslash( $_POST['ticket_url'] ) ) );
	}
	if ( isset( $_POST['event_image'] ) ) {
		update_post_meta( $post_id, 'event_image', absint( $_POST['event_image'] ) );
	}
	if ( isset( $_POST['embed_block'] ) ) {
		update_post_meta( $post_id, 'embed_block', wp_kses_post( wp_unslash( $_POST['embed_block'] ) ) );
	}
}
add_action( 'save_post', 'drumstudy_save_event_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'drumstudy_event' === get_post_type( $post_id ) ) {
			$keys[] = 'event_image';
		}
		return $keys;
	},
	10,
	2
);
