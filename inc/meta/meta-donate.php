<?php
/**
 * Meta Boxes: template-donate.php
 *
 * Fields: headline, subheadline, body, embed, 3 impact stats
 *
 * @package drumstudy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes_page',
	function ( WP_Post $post ): void {
		if ( 'templates/template-donate.php' !== get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			return;
		}
		add_meta_box( 'drumstudy_donate_meta', __( 'Donate Page Settings', 'drumstudy' ), 'drumstudy_donate_meta_cb', 'page', 'normal', 'high' );
	}
);

/**
 * Render Donate meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_donate_meta_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_donate_meta', 'drumstudy_donate_nonce' );

	$headline    = get_post_meta( $post->ID, 'donate_headline', true );
	$subheadline = get_post_meta( $post->ID, 'donate_subheadline', true );
	$body        = get_post_meta( $post->ID, 'donate_body', true );
	$embed       = get_post_meta( $post->ID, 'donate_embed', true );

	$impact = [];
	for ( $i = 1; $i <= 3; $i++ ) {
		$impact[ $i ] = [
			'number' => get_post_meta( $post->ID, "donate_impact_{$i}_number", true ),
			'label'  => get_post_meta( $post->ID, "donate_impact_{$i}_label", true ),
		];
	}
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="donate_headline" class="tts-meta-label"><?php esc_html_e( 'Page Headline', 'drumstudy' ); ?></label>
			<input type="text" id="donate_headline" name="donate_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Top of the Donate page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="donate_subheadline" class="tts-meta-label"><?php esc_html_e( 'Subheadline', 'drumstudy' ); ?></label>
			<input type="text" id="donate_subheadline" name="donate_subheadline" value="<?php echo esc_attr( $subheadline ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="donate_body" class="tts-meta-label"><?php esc_html_e( 'Body Copy', 'drumstudy' ); ?></label>
			<textarea id="donate_body" name="donate_body" rows="6" class="widefat"><?php echo esc_textarea( $body ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Main body copy on the Donate page.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="donate_embed" class="tts-meta-label"><?php esc_html_e( 'Donation Platform Embed', 'drumstudy' ); ?></label>
			<textarea id="donate_embed" name="donate_embed" rows="5" class="widefat"><?php echo esc_textarea( $embed ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Donation form/widget on the Donate page. Accepts Donorbox, PayPal, or any embed shortcode.', 'drumstudy' ); ?></p>
		</div>

		<div class="tts-meta-row tts-meta-row--full">
			<h4 style="margin: 1em 0 0.5em;"><?php esc_html_e( 'Impact Stats (optional)', 'drumstudy' ); ?></h4>
			<p class="description"><?php esc_html_e( 'Where it appears: Three-up stat strip above the embed on the Donate page.', 'drumstudy' ); ?></p>
		</div>
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="tts-meta-row">
				<label for="donate_impact_<?php echo esc_attr( (string) $i ); ?>_number" class="tts-meta-label"><?php printf( esc_html__( 'Impact %d Number', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="donate_impact_<?php echo esc_attr( (string) $i ); ?>_number" name="donate_impact_<?php echo esc_attr( (string) $i ); ?>_number" value="<?php echo esc_attr( $impact[ $i ]['number'] ); ?>" class="widefat" placeholder="e.g. $120k" />
			</div>
			<div class="tts-meta-row">
				<label for="donate_impact_<?php echo esc_attr( (string) $i ); ?>_label" class="tts-meta-label"><?php printf( esc_html__( 'Impact %d Label', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="donate_impact_<?php echo esc_attr( (string) $i ); ?>_label" name="donate_impact_<?php echo esc_attr( (string) $i ); ?>_label" value="<?php echo esc_attr( $impact[ $i ]['label'] ); ?>" class="widefat" placeholder="e.g. Raised this year" />
			</div>
		<?php endfor; ?>
	</div>
	<?php
}

/**
 * Save Donate page meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_donate_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_donate_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_donate_nonce'] ), 'drumstudy_save_donate_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'page' !== get_post_type( $post_id ) ) return;

	$text_fields = [ 'donate_headline', 'donate_subheadline' ];
	for ( $i = 1; $i <= 3; $i++ ) {
		$text_fields[] = "donate_impact_{$i}_number";
		$text_fields[] = "donate_impact_{$i}_label";
	}
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	foreach ( [ 'donate_body', 'donate_embed' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, wp_kses_post( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post', 'drumstudy_save_donate_meta' );
