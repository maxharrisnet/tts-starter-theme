<?php
/**
 * Meta Boxes: template-about.php
 *
 * Fields: headline, story, images, values (3 blocks)
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes_page',
	function ( WP_Post $post ): void {
		if ( 'templates/template-about.php' !== get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			return;
		}
		add_meta_box( 'drumstudy_about_main',   __( 'About Page Content', 'drumstudy' ), 'drumstudy_about_main_cb',   'page', 'normal', 'high' );
		add_meta_box( 'drumstudy_about_values', __( 'Values Section', 'drumstudy' ),     'drumstudy_about_values_cb', 'page', 'normal', 'default' );
	}
);

/**
 * Render About main meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_about_main_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_about_meta', 'drumstudy_about_nonce' );

	$headline   = get_post_meta( $post->ID, 'about_headline', true );
	$story      = get_post_meta( $post->ID, 'about_story', true );
	$img_id     = absint( get_post_meta( $post->ID, 'about_image', true ) );
	$img2_id    = absint( get_post_meta( $post->ID, 'about_image_secondary', true ) );
	$img_prev   = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	$img2_prev  = $img2_id ? wp_get_attachment_image( $img2_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row tts-meta-row--full">
			<label for="about_headline" class="tts-meta-label"><?php esc_html_e( 'Page Headline', 'drumstudy' ); ?></label>
			<input type="text" id="about_headline" name="about_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Top of the About page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="about_story" class="tts-meta-label"><?php esc_html_e( 'Brand Story / Body Copy', 'drumstudy' ); ?></label>
			<textarea id="about_story" name="about_story" rows="8" class="widefat"><?php echo esc_textarea( $story ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Main body copy on the About page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Primary About Image', 'drumstudy' ); ?></label>
			<input type="hidden" id="about_image" name="about_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="about_image_preview" class="tts-image-preview"><?php echo $img_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="about_image" data-preview="about_image_preview"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="about_image" data-preview="about_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Secondary Image (optional)', 'drumstudy' ); ?></label>
			<input type="hidden" id="about_image_secondary" name="about_image_secondary" value="<?php echo esc_attr( (string) $img2_id ); ?>" />
			<div id="about_image_secondary_preview" class="tts-image-preview"><?php echo $img2_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="about_image_secondary" data-preview="about_image_secondary_preview"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
			<?php if ( $img2_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="about_image_secondary" data-preview="about_image_secondary_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Side/secondary image on About page. Optional.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Render About Values meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_about_values_cb( WP_Post $post ): void {
	$values_headline = get_post_meta( $post->ID, 'about_values_headline', true );
	$values          = [];
	for ( $i = 1; $i <= 3; $i++ ) {
		$values[ $i ] = [
			'title' => get_post_meta( $post->ID, "about_value_{$i}_title", true ),
			'body'  => get_post_meta( $post->ID, "about_value_{$i}_body", true ),
		];
	}
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row tts-meta-row--full">
			<label for="about_values_headline" class="tts-meta-label"><?php esc_html_e( 'Values Section Headline', 'drumstudy' ); ?></label>
			<input type="text" id="about_values_headline" name="about_values_headline" value="<?php echo esc_attr( $values_headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Headline above the three value blocks.', 'drumstudy' ); ?></p>
		</div>
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="tts-meta-row">
				<label for="about_value_<?php echo esc_attr( (string) $i ); ?>_title" class="tts-meta-label"><?php printf( esc_html__( 'Value %d Title', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="about_value_<?php echo esc_attr( (string) $i ); ?>_title" name="about_value_<?php echo esc_attr( (string) $i ); ?>_title" value="<?php echo esc_attr( $values[ $i ]['title'] ); ?>" class="widefat" />
			</div>
			<div class="tts-meta-row">
				<label for="about_value_<?php echo esc_attr( (string) $i ); ?>_body" class="tts-meta-label"><?php printf( esc_html__( 'Value %d Description', 'drumstudy' ), $i ); ?></label>
				<textarea id="about_value_<?php echo esc_attr( (string) $i ); ?>_body" name="about_value_<?php echo esc_attr( (string) $i ); ?>_body" rows="3" class="widefat"><?php echo esc_textarea( $values[ $i ]['body'] ); ?></textarea>
			</div>
		<?php endfor; ?>
	</div>
	<?php
}

/**
 * Save About page meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_about_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_about_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_about_nonce'] ), 'drumstudy_save_about_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'page' !== get_post_type( $post_id ) ) return;

	$text_fields = [
		'about_headline', 'about_values_headline',
		'about_value_1_title', 'about_value_2_title', 'about_value_3_title',
	];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	$textarea_fields = [ 'about_story', 'about_value_1_body', 'about_value_2_body', 'about_value_3_body' ];
	foreach ( $textarea_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	foreach ( [ 'about_image', 'about_image_secondary' ] as $img_key ) {
		if ( isset( $_POST[ $img_key ] ) ) {
			update_post_meta( $post_id, $img_key, absint( $_POST[ $img_key ] ) );
		}
	}
}
add_action( 'save_post', 'drumstudy_save_about_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'page' !== get_post_type( $post_id ) ) {
			return $keys;
		}
		if ( 'templates/template-about.php' === get_post_meta( $post_id, '_wp_page_template', true ) ) {
			$keys[] = 'about_image';
			$keys[] = 'about_image_secondary';
		}
		return $keys;
	},
	10,
	2
);
