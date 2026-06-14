<?php
/**
 * Meta Boxes: template-features.php
 *
 * Fields: page headline, subheadline, intro, 6 feature slots (icon, headline, body),
 *         CTA, embed block
 *
 * Features render only when headline is populated (Section 11).
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes_page',
	function ( WP_Post $post ): void {
		if ( 'templates/template-features.php' !== get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			return;
		}
		add_meta_box( 'tts_features_header',   __( 'Features Page Header', 'tts-theme' ),    'tts_features_header_cb',   'page', 'normal', 'high' );
		add_meta_box( 'tts_features_slots',    __( 'Feature Slots (1–6)', 'tts-theme' ),      'tts_features_slots_cb',    'page', 'normal', 'high' );
		add_meta_box( 'tts_features_footer',   __( 'Features CTA & Embed', 'tts-theme' ),     'tts_features_footer_cb',   'page', 'normal', 'default' );
	}
);

/**
 * Render Features header meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_features_header_cb( WP_Post $post ): void {
	wp_nonce_field( 'tts_save_features_meta', 'tts_features_nonce' );

	$headline    = get_post_meta( $post->ID, 'features_headline', true );
	$subheadline = get_post_meta( $post->ID, 'features_subheadline', true );
	$intro       = get_post_meta( $post->ID, 'features_intro', true );
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="features_headline" class="tts-meta-label"><?php esc_html_e( 'Page Headline', 'tts-theme' ); ?></label>
			<input type="text" id="features_headline" name="features_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Top of the Features page.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="features_subheadline" class="tts-meta-label"><?php esc_html_e( 'Subheadline', 'tts-theme' ); ?></label>
			<input type="text" id="features_subheadline" name="features_subheadline" value="<?php echo esc_attr( $subheadline ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="features_intro" class="tts-meta-label"><?php esc_html_e( 'Intro Paragraph', 'tts-theme' ); ?></label>
			<textarea id="features_intro" name="features_intro" rows="4" class="widefat"><?php echo esc_textarea( $intro ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Intro paragraph above the feature blocks.', 'tts-theme' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Render Feature Slots meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_features_slots_cb( WP_Post $post ): void {
	?>
	<p class="description"><?php esc_html_e( 'Each slot renders only when its headline is populated. Leave empty to hide.', 'tts-theme' ); ?></p>
	<?php for ( $i = 1; $i <= 6; $i++ ) :
		$icon_id  = absint( get_post_meta( $post->ID, "feature_{$i}_icon", true ) );
		$headline = get_post_meta( $post->ID, "feature_{$i}_headline", true );
		$body     = get_post_meta( $post->ID, "feature_{$i}_body", true );
		$icon_prev = $icon_id ? wp_get_attachment_image( $icon_id, 'thumbnail' ) : '';
		?>
		<fieldset style="border: 1px solid #ddd; padding: 12px; margin-bottom: 12px; border-radius: 4px;">
			<legend><?php printf( esc_html__( 'Feature %d', 'tts-theme' ), $i ); ?></legend>
			<div class="tts-meta-grid">
				<div class="tts-meta-row">
					<label for="feature_<?php echo esc_attr( (string) $i ); ?>_headline" class="tts-meta-label"><?php printf( esc_html__( 'Feature %d Headline', 'tts-theme' ), $i ); ?></label>
					<input type="text" id="feature_<?php echo esc_attr( (string) $i ); ?>_headline" name="feature_<?php echo esc_attr( (string) $i ); ?>_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
				</div>
				<div class="tts-meta-row">
					<label class="tts-meta-label"><?php printf( esc_html__( 'Feature %d Icon/Image', 'tts-theme' ), $i ); ?></label>
					<input type="hidden" id="feature_<?php echo esc_attr( (string) $i ); ?>_icon" name="feature_<?php echo esc_attr( (string) $i ); ?>_icon" value="<?php echo esc_attr( (string) $icon_id ); ?>" />
					<div id="feature_<?php echo esc_attr( (string) $i ); ?>_icon_preview" class="tts-image-preview"><?php echo $icon_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<button type="button" class="button tts-media-upload-btn" data-field="feature_<?php echo esc_attr( (string) $i ); ?>_icon" data-preview="feature_<?php echo esc_attr( (string) $i ); ?>_icon_preview"><?php esc_html_e( 'Select', 'tts-theme' ); ?></button>
					<?php if ( $icon_id ) : ?>
						<button type="button" class="button tts-media-remove-btn" data-field="feature_<?php echo esc_attr( (string) $i ); ?>_icon" data-preview="feature_<?php echo esc_attr( (string) $i ); ?>_icon_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
					<?php endif; ?>
				</div>
				<div class="tts-meta-row tts-meta-row--full">
					<label for="feature_<?php echo esc_attr( (string) $i ); ?>_body" class="tts-meta-label"><?php printf( esc_html__( 'Feature %d Description', 'tts-theme' ), $i ); ?></label>
					<textarea id="feature_<?php echo esc_attr( (string) $i ); ?>_body" name="feature_<?php echo esc_attr( (string) $i ); ?>_body" rows="3" class="widefat"><?php echo esc_textarea( $body ); ?></textarea>
				</div>
			</div>
		</fieldset>
	<?php endfor; ?>
	<?php
}

/**
 * Render Features CTA & Embed meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_features_footer_cb( WP_Post $post ): void {
	$cta_label   = get_post_meta( $post->ID, 'features_cta_label', true );
	$cta_url     = get_post_meta( $post->ID, 'features_cta_url', true );
	$embed_block = get_post_meta( $post->ID, 'features_embed_block', true );
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="features_cta_label" class="tts-meta-label"><?php esc_html_e( 'CTA Label', 'tts-theme' ); ?></label>
			<input type="text" id="features_cta_label" name="features_cta_label" value="<?php echo esc_attr( $cta_label ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: CTA button below all feature blocks.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="features_cta_url" class="tts-meta-label"><?php esc_html_e( 'CTA URL', 'tts-theme' ); ?></label>
			<input type="text" id="features_cta_url" name="features_cta_url" value="<?php echo esc_attr( $cta_url ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="features_embed_block" class="tts-meta-label"><?php esc_html_e( 'Embed / Shortcode Below CTA', 'tts-theme' ); ?></label>
			<textarea id="features_embed_block" name="features_embed_block" rows="4" class="widefat"><?php echo esc_textarea( $embed_block ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Optional shortcode or embed below the CTA on the Features page.', 'tts-theme' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Features page meta fields.
 *
 * @param int $post_id Post ID.
 */
function tts_save_features_meta( int $post_id ): void {
	if ( ! isset( $_POST['tts_features_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['tts_features_nonce'] ), 'tts_save_features_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'page' !== get_post_type( $post_id ) ) return;

	$text_fields = [ 'features_headline', 'features_subheadline', 'features_cta_label', 'features_cta_url' ];
	for ( $i = 1; $i <= 6; $i++ ) {
		$text_fields[] = "feature_{$i}_headline";
	}
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	$textarea_fields = [ 'features_intro', 'features_embed_block' ];
	for ( $i = 1; $i <= 6; $i++ ) {
		$textarea_fields[] = "feature_{$i}_body";
	}
	foreach ( $textarea_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	for ( $i = 1; $i <= 6; $i++ ) {
		$icon_key = "feature_{$i}_icon";
		if ( isset( $_POST[ $icon_key ] ) ) {
			update_post_meta( $post_id, $icon_key, absint( $_POST[ $icon_key ] ) );
		}
	}
}
add_action( 'save_post', 'tts_save_features_meta' );

add_filter(
	'tts_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'page' !== get_post_type( $post_id ) ) {
			return $keys;
		}
		if ( 'templates/template-features.php' === get_post_meta( $post_id, '_wp_page_template', true ) ) {
			for ( $i = 1; $i <= 6; $i++ ) {
				$keys[] = "feature_{$i}_icon";
			}
		}
		return $keys;
	},
	10,
	2
);
