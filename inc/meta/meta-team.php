<?php
/**
 * Meta Boxes: drumstudy_team
 *
 * Fields: role (required), team_image (ID), email, phone, linkedin, twitter
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
			'drumstudy_team_meta',
			__( 'Team Member Details', 'drumstudy' ),
			'drumstudy_team_meta_cb',
			'drumstudy_team',
			'normal',
			'high'
		);
	}
);

/**
 * Render the Team Member meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_team_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_team_meta', 'drumstudy_team_nonce' );

	$role        = get_post_meta( $post->ID, 'role', true );
	$img_id      = absint( get_post_meta( $post->ID, 'team_image', true ) );
	$email       = get_post_meta( $post->ID, 'email', true );
	$phone       = get_post_meta( $post->ID, 'phone', true );
	$linkedin    = get_post_meta( $post->ID, 'linkedin', true );
	$twitter     = get_post_meta( $post->ID, 'twitter', true );
	$img_preview = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="role" class="tts-meta-label"><?php esc_html_e( 'Role / Title', 'drumstudy' ); ?> <span class="required">*</span></label>
			<input type="text" id="role" name="role" value="<?php echo esc_attr( $role ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Below name on team card. Required.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Headshot', 'drumstudy' ); ?></label>
			<input type="hidden" id="team_image" name="team_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="team_image_preview" class="tts-image-preview"><?php echo $img_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="team_image" data-preview="team_image_preview"><?php esc_html_e( 'Select Headshot', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="team_image" data-preview="team_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Team card photo. Stored as attachment ID.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="email" class="tts-meta-label"><?php esc_html_e( 'Email', 'drumstudy' ); ?></label>
			<input type="email" id="email" name="email" value="<?php echo esc_attr( $email ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Contact link on team member page (optional).', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="phone" class="tts-meta-label"><?php esc_html_e( 'Phone', 'drumstudy' ); ?></label>
			<input type="text" id="phone" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="linkedin" class="tts-meta-label"><?php esc_html_e( 'LinkedIn URL', 'drumstudy' ); ?></label>
			<input type="url" id="linkedin" name="linkedin" value="<?php echo esc_attr( $linkedin ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Social icon link on team card.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="twitter" class="tts-meta-label"><?php esc_html_e( 'X / Twitter URL', 'drumstudy' ); ?></label>
			<input type="url" id="twitter" name="twitter" value="<?php echo esc_attr( $twitter ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Social icon link on team card.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Team Member meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_team_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_team_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_team_nonce'] ), 'drumstudy_save_team_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'drumstudy_team' !== get_post_type( $post_id ) ) return;

	$text_fields = [ 'role', 'phone' ];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['email'] ) ) {
		update_post_meta( $post_id, 'email', sanitize_email( wp_unslash( $_POST['email'] ) ) );
	}
	foreach ( [ 'linkedin', 'twitter' ] as $url_field ) {
		if ( isset( $_POST[ $url_field ] ) ) {
			update_post_meta( $post_id, $url_field, esc_url_raw( wp_unslash( $_POST[ $url_field ] ) ) );
		}
	}
	if ( isset( $_POST['team_image'] ) ) {
		update_post_meta( $post_id, 'team_image', absint( $_POST['team_image'] ) );
	}
}
add_action( 'save_post', 'drumstudy_save_team_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'drumstudy_team' === get_post_type( $post_id ) ) {
			$keys[] = 'team_image';
		}
		return $keys;
	},
	10,
	2
);
