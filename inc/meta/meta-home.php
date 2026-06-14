<?php
/**
 * Meta Boxes: template-home.php
 *
 * Fields: hero, intro block, stats strip, embed block
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'add_meta_boxes_page',
	function ( WP_Post $post ): void {
		if ( 'templates/template-home.php' !== get_post_meta( $post->ID, '_wp_page_template', true ) ) {
			return;
		}
		add_meta_box( 'tts_home_hero',  __( 'Hero Section', 'tts-theme' ),      'tts_home_hero_cb',  'page', 'normal', 'high' );
		add_meta_box( 'tts_home_intro', __( 'Intro Block', 'tts-theme' ),        'tts_home_intro_cb', 'page', 'normal', 'default' );
		add_meta_box( 'tts_home_stats', __( 'Stats Strip', 'tts-theme' ),        'tts_home_stats_cb', 'page', 'normal', 'default' );
		add_meta_box( 'tts_home_embed', __( 'Embed / Shortcode Block', 'tts-theme' ), 'tts_home_embed_cb', 'page', 'normal', 'default' );
	}
);

// ── Hero ──────────────────────────────────────────────────────────────────────

/**
 * Render Hero meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_home_hero_cb( WP_Post $post ): void {
	wp_nonce_field( 'tts_save_home_meta', 'tts_home_nonce' );

	$headline     = get_post_meta( $post->ID, 'home_hero_headline', true );
	$subheadline  = get_post_meta( $post->ID, 'home_hero_subheadline', true );
	$bg_id        = absint( get_post_meta( $post->ID, 'home_hero_bg_image', true ) );
	$cta1_label   = get_post_meta( $post->ID, 'home_hero_cta1_label', true );
	$cta1_url     = get_post_meta( $post->ID, 'home_hero_cta1_url', true );
	$cta2_label   = get_post_meta( $post->ID, 'home_hero_cta2_label', true );
	$cta2_url     = get_post_meta( $post->ID, 'home_hero_cta2_url', true );
	$bg_preview   = $bg_id ? wp_get_attachment_image( $bg_id, 'thumbnail' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="home_hero_headline" class="tts-meta-label"><?php esc_html_e( 'Hero Headline', 'tts-theme' ); ?></label>
			<input type="text" id="home_hero_headline" name="home_hero_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Large text at the top of the Hero section on the homepage.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_subheadline" class="tts-meta-label"><?php esc_html_e( 'Hero Subheadline', 'tts-theme' ); ?></label>
			<input type="text" id="home_hero_subheadline" name="home_hero_subheadline" value="<?php echo esc_attr( $subheadline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Below the headline in the Hero section.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Hero Background Image', 'tts-theme' ); ?></label>
			<input type="hidden" id="home_hero_bg_image" name="home_hero_bg_image" value="<?php echo esc_attr( (string) $bg_id ); ?>" />
			<div id="home_hero_bg_image_preview" class="tts-image-preview"><?php echo $bg_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="home_hero_bg_image" data-preview="home_hero_bg_image_preview"><?php esc_html_e( 'Select Image', 'tts-theme' ); ?></button>
			<?php if ( $bg_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="home_hero_bg_image" data-preview="home_hero_bg_image_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Full-bleed background of the Hero section. Stored as attachment ID.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta1_label" class="tts-meta-label"><?php esc_html_e( 'Primary CTA Label', 'tts-theme' ); ?></label>
			<input type="text" id="home_hero_cta1_label" name="home_hero_cta1_label" value="<?php echo esc_attr( $cta1_label ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Primary button in Hero. Falls back to Admin Options CTAs if empty.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta1_url" class="tts-meta-label"><?php esc_html_e( 'Primary CTA URL', 'tts-theme' ); ?></label>
			<input type="text" id="home_hero_cta1_url" name="home_hero_cta1_url" value="<?php echo esc_attr( $cta1_url ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta2_label" class="tts-meta-label"><?php esc_html_e( 'Secondary CTA Label', 'tts-theme' ); ?></label>
			<input type="text" id="home_hero_cta2_label" name="home_hero_cta2_label" value="<?php echo esc_attr( $cta2_label ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta2_url" class="tts-meta-label"><?php esc_html_e( 'Secondary CTA URL', 'tts-theme' ); ?></label>
			<input type="text" id="home_hero_cta2_url" name="home_hero_cta2_url" value="<?php echo esc_attr( $cta2_url ); ?>" class="widefat" />
		</div>
	</div>
	<?php
}

// ── Intro ─────────────────────────────────────────────────────────────────────

/**
 * Render Intro Block meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_home_intro_cb( WP_Post $post ): void {
	$headline  = get_post_meta( $post->ID, 'home_intro_headline', true );
	$body      = get_post_meta( $post->ID, 'home_intro_body', true );
	$img_id    = absint( get_post_meta( $post->ID, 'home_intro_image', true ) );
	$img_prev  = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="home_intro_headline" class="tts-meta-label"><?php esc_html_e( 'Section Headline', 'tts-theme' ); ?></label>
			<input type="text" id="home_intro_headline" name="home_intro_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Section headline above intro text on homepage.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="home_intro_body" class="tts-meta-label"><?php esc_html_e( 'Body Copy', 'tts-theme' ); ?></label>
			<textarea id="home_intro_body" name="home_intro_body" rows="5" class="widefat"><?php echo esc_textarea( $body ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Body copy in the intro/about block on homepage.', 'tts-theme' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Intro Image', 'tts-theme' ); ?></label>
			<input type="hidden" id="home_intro_image" name="home_intro_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="home_intro_image_preview" class="tts-image-preview"><?php echo $img_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="home_intro_image" data-preview="home_intro_image_preview"><?php esc_html_e( 'Select Image', 'tts-theme' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="home_intro_image" data-preview="home_intro_image_preview"><?php esc_html_e( 'Remove', 'tts-theme' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Image beside intro text on homepage.', 'tts-theme' ); ?></p>
		</div>
	</div>
	<?php
}

// ── Stats ─────────────────────────────────────────────────────────────────────

/**
 * Render Stats Strip meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_home_stats_cb( WP_Post $post ): void {
	$stats = [];
	for ( $i = 1; $i <= 3; $i++ ) {
		$stats[ $i ] = [
			'number' => get_post_meta( $post->ID, "home_stat_{$i}_number", true ),
			'label'  => get_post_meta( $post->ID, "home_stat_{$i}_label", true ),
		];
	}
	?>
	<p class="description"><?php esc_html_e( 'Where it appears: Three-up stat strip on homepage. Leave all empty to hide the section.', 'tts-theme' ); ?></p>
	<div class="tts-meta-grid">
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="tts-meta-row">
				<label for="home_stat_<?php echo esc_attr( (string) $i ); ?>_number" class="tts-meta-label"><?php printf( esc_html__( 'Stat %d Number', 'tts-theme' ), $i ); ?></label>
				<input type="text" id="home_stat_<?php echo esc_attr( (string) $i ); ?>_number" name="home_stat_<?php echo esc_attr( (string) $i ); ?>_number" value="<?php echo esc_attr( $stats[ $i ]['number'] ); ?>" class="widefat" placeholder="e.g. 500+" />
			</div>
			<div class="tts-meta-row">
				<label for="home_stat_<?php echo esc_attr( (string) $i ); ?>_label" class="tts-meta-label"><?php printf( esc_html__( 'Stat %d Label', 'tts-theme' ), $i ); ?></label>
				<input type="text" id="home_stat_<?php echo esc_attr( (string) $i ); ?>_label" name="home_stat_<?php echo esc_attr( (string) $i ); ?>_label" value="<?php echo esc_attr( $stats[ $i ]['label'] ); ?>" class="widefat" placeholder="e.g. Happy Clients" />
			</div>
		<?php endfor; ?>
	</div>
	<?php
}

// ── Embed ─────────────────────────────────────────────────────────────────────

/**
 * Render Embed Block meta box.
 *
 * @param WP_Post $post Current post.
 */
function tts_home_embed_cb( WP_Post $post ): void {
	$embed = get_post_meta( $post->ID, 'home_embed_block', true );
	?>
	<div class="tts-meta-row tts-meta-row--full">
		<label for="home_embed_block" class="tts-meta-label"><?php esc_html_e( 'Embed / Shortcode', 'tts-theme' ); ?></label>
		<textarea id="home_embed_block" name="home_embed_block" rows="5" class="widefat"><?php echo esc_textarea( $embed ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Where it appears: Flexible slot below main homepage sections. Accepts shortcodes, embed URLs, and iframe codes.', 'tts-theme' ); ?></p>
	</div>
	<?php
}

// ── Save ──────────────────────────────────────────────────────────────────────

/**
 * Save Home page meta fields.
 *
 * @param int $post_id Post ID.
 */
function tts_save_home_meta( int $post_id ): void {
	if ( ! isset( $_POST['tts_home_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['tts_home_nonce'] ), 'tts_save_home_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( 'page' !== get_post_type( $post_id ) ) return;

	$text_fields = [
		'home_hero_headline', 'home_hero_subheadline',
		'home_hero_cta1_label', 'home_hero_cta1_url',
		'home_hero_cta2_label', 'home_hero_cta2_url',
		'home_intro_headline',
		'home_stat_1_number', 'home_stat_1_label',
		'home_stat_2_number', 'home_stat_2_label',
		'home_stat_3_number', 'home_stat_3_label',
	];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	foreach ( [ 'home_intro_body', 'home_embed_block' ] as $rich_key ) {
		if ( isset( $_POST[ $rich_key ] ) ) {
			update_post_meta( $post_id, $rich_key, wp_kses_post( wp_unslash( $_POST[ $rich_key ] ) ) );
		}
	}

	foreach ( [ 'home_hero_bg_image', 'home_intro_image' ] as $img_key ) {
		if ( isset( $_POST[ $img_key ] ) ) {
			update_post_meta( $post_id, $img_key, absint( $_POST[ $img_key ] ) );
		}
	}
}
add_action( 'save_post', 'tts_save_home_meta' );

add_filter(
	'tts_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'page' !== get_post_type( $post_id ) ) {
			return $keys;
		}
		if ( 'templates/template-home.php' === get_post_meta( $post_id, '_wp_page_template', true ) ) {
			$keys[] = 'home_hero_bg_image';
			$keys[] = 'home_intro_image';
		}
		return $keys;
	},
	10,
	2
);
