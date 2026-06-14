<?php
/**
 * Meta Boxes: template-splash.php
 *
 * Fields: headline, subheadline, body, logo override (ID), bg image (ID),
 *         cta1/cta2, embed block, PDF download (ID)
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes_page',
	function ( WP_Post $post ): void {
		if ( 'templates/template-splash.php' !== get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			return;
		}
		add_meta_box( 'tts_splash_meta', __( 'Splash / Coming Soon Settings', 'tts-theme' ), 'tts_splash_meta_cb', 'page', 'normal', 'high' );
	}
);

/**
 * Render Splash meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_splash_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'tts_save_splash_meta', 'tts_splash_nonce' );

	$headline      = get_post_meta( $post->ID, 'splash_headline', true );
	$subheadline   = get_post_meta( $post->ID, 'splash_subheadline', true );
	$body          = get_post_meta( $post->ID, 'splash_body', true );
	$logo_id       = absint( get_post_meta( $post->ID, 'splash_logo_override', true ) );
	$bg_id         = absint( get_post_meta( $post->ID, 'splash_bg_image', true ) );
	$cta1_label    = get_post_meta( $post->ID, 'splash_cta1_label', true );
	$cta1_url      = get_post_meta( $post->ID, 'splash_cta1_url', true );
	$cta2_label    = get_post_meta( $post->ID, 'splash_cta2_label', true );
	$cta2_url      = get_post_meta( $post->ID, 'splash_cta2_url', true );
	$embed_block   = get_post_meta( $post->ID, 'splash_embed_block', true );
	$pdf_id        = absint( get_post_meta( $post->ID, 'splash_pdf', true ) );

	$logo_prev  = $logo_id ? wp_get_attachment_image( $logo_id, 'tts-logo' ) : '';
	$bg_prev    = $bg_id ? wp_get_attachment_image( $bg_id, 'thumbnail' ) : '';
	$pdf_title  = $pdf_id ? get_the_title( $pdf_id ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="splash_headline" class="tts-meta-label"><?php esc_html_e( 'Main Headline', 'tts-theme' ); ?></label>
			<input type="text" id="splash_headline" name="splash_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Primary headline on the splash/coming soon page.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="splash_subheadline" class="tts-meta-label"><?php esc_html_e( 'Subheadline', 'tts-theme' ); ?></label>
			<input type="text" id="splash_subheadline" name="splash_subheadline" value="<?php echo esc_attr( $subheadline ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="splash_body" class="tts-meta-label"><?php esc_html_e( 'Supporting Copy', 'tts-theme' ); ?></label>
			<textarea id="splash_body" name="splash_body" rows="4" class="widefat"><?php echo esc_textarea( $body ); ?></textarea>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Logo Override (optional)', 'tts-theme' ); ?></label>
			<input type="hidden" id="splash_logo_override" name="splash_logo_override" value="<?php echo esc_attr( (string) $logo_id ); ?>" />
			<div id="splash_logo_override_preview" class="tts-image-preview"><?php echo $logo_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="splash_logo_override" data-preview="splash_logo_override_preview"><?php esc_html_e( 'Select Logo', 'tts-theme' ); ?></button>
			<?php if ( $logo_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="splash_logo_override" data-preview="splash_logo_override_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Overrides the global logo for the splash page only.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Background Image', 'tts-theme' ); ?></label>
			<input type="hidden" id="splash_bg_image" name="splash_bg_image" value="<?php echo esc_attr( (string) $bg_id ); ?>" />
			<div id="splash_bg_image_preview" class="tts-image-preview"><?php echo $bg_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="splash_bg_image" data-preview="splash_bg_image_preview"><?php esc_html_e( 'Select Image', 'tts-theme' ); ?></button>
			<?php if ( $bg_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="splash_bg_image" data-preview="splash_bg_image_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
		</div>
		<div class="tts-meta-row">
			<label for="splash_cta1_label" class="tts-meta-label"><?php esc_html_e( 'Primary CTA Label', 'tts-theme' ); ?></label>
			<input type="text" id="splash_cta1_label" name="splash_cta1_label" value="<?php echo esc_attr( $cta1_label ); ?>" class="widefat" placeholder="e.g. Notify Me" />
		</div>
		<div class="tts-meta-row">
			<label for="splash_cta1_url" class="tts-meta-label"><?php esc_html_e( 'Primary CTA URL', 'tts-theme' ); ?></label>
			<input type="text" id="splash_cta1_url" name="splash_cta1_url" value="<?php echo esc_attr( $cta1_url ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="splash_cta2_label" class="tts-meta-label"><?php esc_html_e( 'Secondary CTA Label (optional)', 'tts-theme' ); ?></label>
			<input type="text" id="splash_cta2_label" name="splash_cta2_label" value="<?php echo esc_attr( $cta2_label ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="splash_cta2_url" class="tts-meta-label"><?php esc_html_e( 'Secondary CTA URL', 'tts-theme' ); ?></label>
			<input type="text" id="splash_cta2_url" name="splash_cta2_url" value="<?php echo esc_attr( $cta2_url ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="splash_embed_block" class="tts-meta-label"><?php esc_html_e( 'Embed / Waitlist Form', 'tts-theme' ); ?></label>
			<textarea id="splash_embed_block" name="splash_embed_block" rows="4" class="widefat"><?php echo esc_textarea( $embed_block ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Below CTAs on splash page. Accepts form shortcodes, waitlist embeds, or custom code.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label class="tts-meta-label"><?php esc_html_e( 'PDF Download (optional)', 'tts-theme' ); ?></label>
			<input type="hidden" id="splash_pdf" name="splash_pdf" value="<?php echo esc_attr( (string) $pdf_id ); ?>" />
			<?php if ( $pdf_id ) : ?>
				<p><?php echo esc_html( $pdf_title ); ?></p>
			<?php endif; ?>
			<button type="button" class="button tts-media-upload-btn" data-field="splash_pdf" data-preview="" data-type="application/pdf"><?php esc_html_e( 'Select PDF', 'tts-theme' ); ?></button>
			<?php if ( $pdf_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="splash_pdf" data-preview=""><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: PDF download link on splash page. Accepts PDF files only. Stored as attachment ID.', 'tts-theme' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Splash page meta fields.
 *
 * @param int $post_id Post ID.
 */
function tts_save_splash_meta( int $post_id ): void {
	if ( ! isset( $_POST['tts_splash_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['tts_splash_nonce'] ), 'tts_save_splash_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'page' !== get_post_type( $post_id ) ) return;

	$text_fields = [
		'splash_headline', 'splash_subheadline',
		'splash_cta1_label', 'splash_cta1_url',
		'splash_cta2_label', 'splash_cta2_url',
	];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	foreach ( [ 'splash_body', 'splash_embed_block' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	foreach ( [ 'splash_logo_override', 'splash_bg_image', 'splash_pdf' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			$id = absint( $_POST[ $key ] );
			// Validate PDF MIME type
			if ( 'splash_pdf' === $key && $id && 'application/pdf' !== get_post_mime_type( $id ) ) {
				return;
			}
			update_post_meta( $post_id, $key, $id );
		}
	}
}
add_action( 'save_post', 'tts_save_splash_meta' );

add_filter(
	'tts_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'page' !== get_post_type( $post_id ) ) {
			return $keys;
		}
		if ( 'templates/template-splash.php' === get_post_meta( $post_id, '_wp_page_template', true ) ) {
			$keys[] = 'splash_logo_override';
			$keys[] = 'splash_bg_image';
		}
		return $keys;
	},
	10,
	2
);
