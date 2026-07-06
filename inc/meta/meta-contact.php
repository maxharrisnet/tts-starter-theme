<?php
/**
 * Meta Boxes: template-contact.php
 *
 * Fields: headline, subheadline, form embed, secondary contact block
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes_page',
	function ( WP_Post $post ): void {
		if ( 'templates/template-contact.php' !== get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			return;
		}
		add_meta_box( 'drumstudy_contact_meta', __( 'Contact Page Settings', 'drumstudy' ), 'drumstudy_contact_meta_cb', 'page', 'normal', 'high' );
	}
);

/**
 * Render Contact meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_contact_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_contact_meta', 'drumstudy_contact_nonce' );

	$headline             = get_post_meta( $post->ID, 'contact_headline', true );
	$subheadline          = get_post_meta( $post->ID, 'contact_subheadline', true );
	$form_embed           = get_post_meta( $post->ID, 'contact_form_embed', true );
	$secondary_headline   = get_post_meta( $post->ID, 'contact_secondary_headline', true );
	$secondary_body       = get_post_meta( $post->ID, 'contact_secondary_body', true );
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="contact_headline" class="tts-meta-label"><?php esc_html_e( 'Page Headline', 'drumstudy' ); ?></label>
			<input type="text" id="contact_headline" name="contact_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Top of the Contact page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="contact_subheadline" class="tts-meta-label"><?php esc_html_e( 'Subheadline', 'drumstudy' ); ?></label>
			<input type="text" id="contact_subheadline" name="contact_subheadline" value="<?php echo esc_attr( $subheadline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Below the headline on the Contact page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="contact_form_embed" class="tts-meta-label"><?php esc_html_e( 'Form Shortcode or Embed', 'drumstudy' ); ?></label>
			<textarea id="contact_form_embed" name="contact_form_embed" rows="4" class="widefat"><?php echo esc_textarea( $form_embed ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Main contact form on the page. Paste a CF7 shortcode, Gravity Forms shortcode, or any embed code.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="contact_secondary_headline" class="tts-meta-label"><?php esc_html_e( 'Secondary Block Headline', 'drumstudy' ); ?></label>
			<input type="text" id="contact_secondary_headline" name="contact_secondary_headline" value="<?php echo esc_attr( $secondary_headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Headline of the secondary contact info block.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="contact_secondary_body" class="tts-meta-label"><?php esc_html_e( 'Secondary Block Body', 'drumstudy' ); ?></label>
			<textarea id="contact_secondary_body" name="contact_secondary_body" rows="4" class="widefat"><?php echo esc_textarea( $secondary_body ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Additional contact copy below the form.', 'drumstudy' ); ?></p>
		</div>
	</div>
	<?php
}

/**
 * Save Contact page meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_contact_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_contact_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_contact_nonce'] ), 'drumstudy_save_contact_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'page' !== get_post_type( $post_id ) ) return;

	foreach ( [ 'contact_headline', 'contact_subheadline', 'contact_secondary_headline' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	foreach ( [ 'contact_form_embed', 'contact_secondary_body' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post', 'drumstudy_save_contact_meta' );
