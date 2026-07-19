<?php
/**
 * Meta Boxes: template-booking.php
 *
 * Shared booking template for new-client consultation and existing-client lesson booking.
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
			'drumstudy_booking_meta',
			__( 'Booking Page Settings', 'drumstudy' ),
			'drumstudy_booking_meta_cb',
			'page',
			'normal',
			'high'
		);
	}
);

/**
 * Render Booking page meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_booking_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_booking_meta', 'drumstudy_booking_nonce' );

	$audience            = get_post_meta( $post->ID, 'booking_audience', true ) ?: 'new_client';
	$hero_headline       = get_post_meta( $post->ID, 'booking_hero_headline', true );
	$hero_subheadline    = get_post_meta( $post->ID, 'booking_hero_subheadline', true );
	$hero_bg_id          = absint( get_post_meta( $post->ID, 'booking_hero_bg_image', true ) );
	$menu_headline       = get_post_meta( $post->ID, 'booking_menu_headline', true );
	$menu_intro          = get_post_meta( $post->ID, 'booking_menu_intro', true );
	$my_bookings_url     = get_post_meta( $post->ID, 'booking_my_bookings_url', true );
	$crosslink_label     = get_post_meta( $post->ID, 'booking_crosslink_label', true );
	$crosslink_url       = get_post_meta( $post->ID, 'booking_crosslink_url', true );
	$hero_bg_preview     = $hero_bg_id ? wp_get_attachment_image( $hero_bg_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row tts-meta-row--full">
			<label for="booking_audience" class="tts-meta-label"><?php esc_html_e( 'Booking Audience', 'drumstudy' ); ?></label>
			<select id="booking_audience" name="booking_audience" class="widefat">
				<option value="new_client" <?php selected( $audience, 'new_client' ); ?>>
					<?php esc_html_e( 'New clients — phone consultation funnel', 'drumstudy' ); ?>
				</option>
				<option value="existing_client" <?php selected( $audience, 'existing_client' ); ?>>
					<?php esc_html_e( 'Existing clients — lesson booking menu', 'drumstudy' ); ?>
				</option>
			</select>
			<p class="description">
				<?php esc_html_e( 'Where it appears: Controls which service cards render on this page. Use two pages — one per audience.', 'drumstudy' ); ?>
			</p>
		</div>

		<div class="tts-meta-row tts-meta-row--full">
			<label for="booking_hero_headline" class="tts-meta-label"><?php esc_html_e( 'Hero Headline', 'drumstudy' ); ?></label>
			<input type="text" id="booking_hero_headline" name="booking_hero_headline" value="<?php echo esc_attr( $hero_headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Large headline in the booking page hero.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row tts-meta-row--full">
			<label for="booking_hero_subheadline" class="tts-meta-label"><?php esc_html_e( 'Hero Subheadline', 'drumstudy' ); ?></label>
			<input type="text" id="booking_hero_subheadline" name="booking_hero_subheadline" value="<?php echo esc_attr( $hero_subheadline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Supporting line below the hero headline.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row tts-meta-row--full">
			<label class="tts-meta-label"><?php esc_html_e( 'Hero Background Image', 'drumstudy' ); ?></label>
			<input type="hidden" id="booking_hero_bg_image" name="booking_hero_bg_image" value="<?php echo esc_attr( (string) $hero_bg_id ); ?>" />
			<div id="booking_hero_bg_image_preview" class="tts-image-preview"><?php echo $hero_bg_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="booking_hero_bg_image" data-preview="booking_hero_bg_image_preview">
				<?php esc_html_e( 'Select Image', 'drumstudy' ); ?>
			</button>
			<?php if ( $hero_bg_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="booking_hero_bg_image" data-preview="booking_hero_bg_image_preview">
					<?php esc_html_e( 'Remove', 'drumstudy' ); ?>
				</button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Full-width hero background on the booking page.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row">
			<label for="booking_menu_headline" class="tts-meta-label"><?php esc_html_e( 'Services Section Headline', 'drumstudy' ); ?></label>
			<input type="text" id="booking_menu_headline" name="booking_menu_headline" value="<?php echo esc_attr( $menu_headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Title above the service cards.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row">
			<label for="booking_my_bookings_url" class="tts-meta-label"><?php esc_html_e( 'My Bookings URL', 'drumstudy' ); ?></label>
			<input type="text" id="booking_my_bookings_url" name="booking_my_bookings_url" value="<?php echo esc_attr( $my_bookings_url ); ?>" class="widefat" placeholder="https://" />
			<p class="description"><?php esc_html_e( 'Where it appears: Secondary button linking to Square customer bookings.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row tts-meta-row--full">
			<label for="booking_menu_intro" class="tts-meta-label"><?php esc_html_e( 'Services Intro Copy', 'drumstudy' ); ?></label>
			<textarea id="booking_menu_intro" name="booking_menu_intro" rows="3" class="widefat"><?php echo esc_textarea( $menu_intro ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Optional paragraph below the services headline.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row">
			<label for="booking_crosslink_label" class="tts-meta-label"><?php esc_html_e( 'Cross-link Label', 'drumstudy' ); ?></label>
			<input type="text" id="booking_crosslink_label" name="booking_crosslink_label" value="<?php echo esc_attr( $crosslink_label ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Subtle text link to the other booking page (e.g. “Already a student? Book your next lesson”).', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row">
			<label for="booking_crosslink_url" class="tts-meta-label"><?php esc_html_e( 'Cross-link URL', 'drumstudy' ); ?></label>
			<input type="text" id="booking_crosslink_url" name="booking_crosslink_url" value="<?php echo esc_attr( $crosslink_url ); ?>" class="widefat" placeholder="/book-lessons" />
			<p class="description"><?php esc_html_e( 'Where it appears: Destination for the cross-link. Use a relative path to the other booking page.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row tts-meta-row--full">
			<p class="description">
				<?php
				printf(
					/* translators: %s: URL to the Services admin list */
					wp_kses_post( __( 'Square booking embed codes are managed per service, not per page — check "New clients" and/or "Existing clients" on each <a href="%s">Service</a> to control which cards appear here.', 'drumstudy' ) ),
					esc_url( admin_url( 'edit.php?post_type=drumstudy_service' ) )
				);
				?>
			</p>
		</div>
	</div>
	<?php
}

/**
 * Save Booking page meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_booking_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_booking_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_booking_nonce'] ), 'drumstudy_save_booking_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( 'page' !== get_post_type( $post_id ) ) {
		return;
	}
	if ( 'templates/template-booking.php' !== get_post_meta( $post_id, '_wp_page_template', true ) ) {
		return;
	}

	if ( isset( $_POST['booking_audience'] ) ) {
		$audience = sanitize_key( wp_unslash( $_POST['booking_audience'] ) );
		update_post_meta(
			$post_id,
			'booking_audience',
			in_array( $audience, [ 'new_client', 'existing_client' ], true ) ? $audience : 'new_client'
		);
	}

	$text_fields = [
		'booking_hero_headline',
		'booking_hero_subheadline',
		'booking_menu_headline',
		'booking_my_bookings_url',
		'booking_crosslink_label',
		'booking_crosslink_url',
	];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	if ( isset( $_POST['booking_hero_bg_image'] ) ) {
		update_post_meta( $post_id, 'booking_hero_bg_image', absint( wp_unslash( $_POST['booking_hero_bg_image'] ) ) );
	}

	if ( isset( $_POST['booking_menu_intro'] ) ) {
		update_post_meta( $post_id, 'booking_menu_intro', wp_kses_post( wp_unslash( $_POST['booking_menu_intro'] ) ) );
	}

	// Embed codes are saved per-service in drumstudy_save_service_meta()
	// (inc/meta/meta-services.php) — nothing embed-related to save here.
}
add_action( 'save_post', 'drumstudy_save_booking_meta' );
