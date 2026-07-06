<?php
/**
 * Meta Boxes: template-home.php
 *
 * Fields: hero, intro block, stats strip, gear guide, embed block
 *
 * @package drumstudy
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
		add_meta_box( 'drumstudy_home_hero',  __( 'Hero Section', 'drumstudy' ),      'drumstudy_home_hero_cb',  'page', 'normal', 'high' );
		add_meta_box( 'drumstudy_home_intro', __( 'Intro Block', 'drumstudy' ),        'drumstudy_home_intro_cb', 'page', 'normal', 'default' );
		add_meta_box( 'drumstudy_home_stats', __( 'Stats Strip', 'drumstudy' ),        'drumstudy_home_stats_cb', 'page', 'normal', 'default' );
		add_meta_box( 'drumstudy_home_gear',  __( 'Gear Guide (3 fixed items)', 'drumstudy' ), 'drumstudy_home_gear_cb', 'page', 'normal', 'default' );
		add_meta_box( 'drumstudy_home_embed', __( 'Embed / Shortcode Block', 'drumstudy' ), 'drumstudy_home_embed_cb', 'page', 'normal', 'default' );
	}
);

// ── Hero ──────────────────────────────────────────────────────────────────────

/**
 * Render Hero meta box.
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_home_hero_cb( WP_Post $post ): void {
	wp_nonce_field( 'drumstudy_save_home_meta', 'drumstudy_home_nonce' );

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
			<label for="home_hero_headline" class="tts-meta-label"><?php esc_html_e( 'Hero Headline', 'drumstudy' ); ?></label>
			<input type="text" id="home_hero_headline" name="home_hero_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Large text at the top of the Hero section on the homepage.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_subheadline" class="tts-meta-label"><?php esc_html_e( 'Hero Subheadline', 'drumstudy' ); ?></label>
			<input type="text" id="home_hero_subheadline" name="home_hero_subheadline" value="<?php echo esc_attr( $subheadline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Below the headline in the Hero section.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Hero Background Image', 'drumstudy' ); ?></label>
			<input type="hidden" id="home_hero_bg_image" name="home_hero_bg_image" value="<?php echo esc_attr( (string) $bg_id ); ?>" />
			<div id="home_hero_bg_image_preview" class="tts-image-preview"><?php echo $bg_preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="home_hero_bg_image" data-preview="home_hero_bg_image_preview"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
			<?php if ( $bg_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="home_hero_bg_image" data-preview="home_hero_bg_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Full-bleed background of the Hero section. Stored as attachment ID.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta1_label" class="tts-meta-label"><?php esc_html_e( 'Primary CTA Label', 'drumstudy' ); ?></label>
			<input type="text" id="home_hero_cta1_label" name="home_hero_cta1_label" value="<?php echo esc_attr( $cta1_label ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Primary button in Hero. Falls back to Admin Options CTAs if empty.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta1_url" class="tts-meta-label"><?php esc_html_e( 'Primary CTA URL', 'drumstudy' ); ?></label>
			<input type="text" id="home_hero_cta1_url" name="home_hero_cta1_url" value="<?php echo esc_attr( $cta1_url ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta2_label" class="tts-meta-label"><?php esc_html_e( 'Secondary CTA Label', 'drumstudy' ); ?></label>
			<input type="text" id="home_hero_cta2_label" name="home_hero_cta2_label" value="<?php echo esc_attr( $cta2_label ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="home_hero_cta2_url" class="tts-meta-label"><?php esc_html_e( 'Secondary CTA URL', 'drumstudy' ); ?></label>
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
function drumstudy_home_intro_cb( WP_Post $post ): void {
	$headline  = get_post_meta( $post->ID, 'home_intro_headline', true );
	$body      = get_post_meta( $post->ID, 'home_intro_body', true );
	$img_id    = absint( get_post_meta( $post->ID, 'home_intro_image', true ) );
	$img_prev  = $img_id ? wp_get_attachment_image( $img_id, 'tts-card' ) : '';
	?>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="home_intro_headline" class="tts-meta-label"><?php esc_html_e( 'Section Headline', 'drumstudy' ); ?></label>
			<input type="text" id="home_intro_headline" name="home_intro_headline" value="<?php echo esc_attr( $headline ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Section headline above intro text on homepage.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row tts-meta-row--full">
			<label for="home_intro_body" class="tts-meta-label"><?php esc_html_e( 'Body Copy', 'drumstudy' ); ?></label>
			<textarea id="home_intro_body" name="home_intro_body" rows="5" class="widefat"><?php echo esc_textarea( $body ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Where it appears: Body copy in the intro/about block on homepage.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label class="tts-meta-label"><?php esc_html_e( 'Intro Image', 'drumstudy' ); ?></label>
			<input type="hidden" id="home_intro_image" name="home_intro_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
			<div id="home_intro_image_preview" class="tts-image-preview"><?php echo $img_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button type="button" class="button tts-media-upload-btn" data-field="home_intro_image" data-preview="home_intro_image_preview"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
			<?php if ( $img_id ) : ?>
				<button type="button" class="button tts-media-remove-btn" data-field="home_intro_image" data-preview="home_intro_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
			<?php endif; ?>
			<p class="description"><?php esc_html_e( 'Where it appears: Image beside intro text on homepage.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_intro_cta_label" class="tts-meta-label"><?php esc_html_e( 'Intro CTA Label', 'drumstudy' ); ?></label>
			<input type="text" id="home_intro_cta_label" name="home_intro_cta_label" value="<?php echo esc_attr( get_post_meta( $post->ID, 'home_intro_cta_label', true ) ); ?>" class="widefat" />
			<p class="description"><?php esc_html_e( 'Where it appears: Single button below the intro/lead-capture block.', 'drumstudy' ); ?></p>
		</div>
		<div class="tts-meta-row">
			<label for="home_intro_cta_url" class="tts-meta-label"><?php esc_html_e( 'Intro CTA URL', 'drumstudy' ); ?></label>
			<input type="text" id="home_intro_cta_url" name="home_intro_cta_url" value="<?php echo esc_attr( get_post_meta( $post->ID, 'home_intro_cta_url', true ) ); ?>" class="widefat" />
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
function drumstudy_home_stats_cb( WP_Post $post ): void {
	$stats = [];
	for ( $i = 1; $i <= 3; $i++ ) {
		$stats[ $i ] = [
			'number' => get_post_meta( $post->ID, "home_stat_{$i}_number", true ),
			'label'  => get_post_meta( $post->ID, "home_stat_{$i}_label", true ),
		];
	}
	?>
	<p class="description"><?php esc_html_e( 'Where it appears: Three-up stat strip on homepage. Leave all empty to hide the section.', 'drumstudy' ); ?></p>
	<div class="tts-meta-grid">
		<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<div class="tts-meta-row">
				<label for="home_stat_<?php echo esc_attr( (string) $i ); ?>_number" class="tts-meta-label"><?php printf( esc_html__( 'Stat %d Number', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="home_stat_<?php echo esc_attr( (string) $i ); ?>_number" name="home_stat_<?php echo esc_attr( (string) $i ); ?>_number" value="<?php echo esc_attr( $stats[ $i ]['number'] ); ?>" class="widefat" placeholder="e.g. 500+" />
			</div>
			<div class="tts-meta-row">
				<label for="home_stat_<?php echo esc_attr( (string) $i ); ?>_label" class="tts-meta-label"><?php printf( esc_html__( 'Stat %d Label', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="home_stat_<?php echo esc_attr( (string) $i ); ?>_label" name="home_stat_<?php echo esc_attr( (string) $i ); ?>_label" value="<?php echo esc_attr( $stats[ $i ]['label'] ); ?>" class="widefat" placeholder="e.g. Happy Clients" />
			</div>
		<?php endfor; ?>
	</div>
	<?php
}

// ── Gear Guide ────────────────────────────────────────────────────────────────

/**
 * Render Gear Guide meta box — 3 fixed slots (practice pad, sticks, metronome
 * by default, but the labels/copy/links are fully editable).
 *
 * @param WP_Post $post Current post.
 */
function drumstudy_home_gear_cb( WP_Post $post ): void {
	?>
	<p class="description"><?php esc_html_e( 'Where it appears: gear guide section on homepage. Leave Item 1 label empty to hide the whole section.', 'drumstudy' ); ?></p>
	<div class="tts-meta-grid">
		<div class="tts-meta-row">
			<label for="gear_guide_headline" class="tts-meta-label"><?php esc_html_e( 'Section Headline', 'drumstudy' ); ?></label>
			<input type="text" id="gear_guide_headline" name="gear_guide_headline" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gear_guide_headline', true ) ); ?>" class="widefat" />
		</div>
		<div class="tts-meta-row">
			<label for="gear_guide_subtitle" class="tts-meta-label"><?php esc_html_e( 'Section Subtitle', 'drumstudy' ); ?></label>
			<input type="text" id="gear_guide_subtitle" name="gear_guide_subtitle" value="<?php echo esc_attr( get_post_meta( $post->ID, 'gear_guide_subtitle', true ) ); ?>" class="widefat" />
		</div>
		<?php for ( $i = 1; $i <= 3; $i++ ) :
			$label   = get_post_meta( $post->ID, "gear_{$i}_label", true );
			$body    = get_post_meta( $post->ID, "gear_{$i}_body", true );
			$url     = get_post_meta( $post->ID, "gear_{$i}_url", true );
			$img_id  = absint( get_post_meta( $post->ID, "gear_{$i}_image", true ) );
			$img_prev = $img_id ? wp_get_attachment_image( $img_id, 'thumbnail' ) : '';
			?>
			<div class="tts-meta-row">
				<label for="gear_<?php echo esc_attr( (string) $i ); ?>_label" class="tts-meta-label"><?php printf( esc_html__( 'Item %d Label', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="gear_<?php echo esc_attr( (string) $i ); ?>_label" name="gear_<?php echo esc_attr( (string) $i ); ?>_label" value="<?php echo esc_attr( $label ); ?>" class="widefat" />
			</div>
			<div class="tts-meta-row">
				<label for="gear_<?php echo esc_attr( (string) $i ); ?>_url" class="tts-meta-label"><?php printf( esc_html__( 'Item %d URL', 'drumstudy' ), $i ); ?></label>
				<input type="text" id="gear_<?php echo esc_attr( (string) $i ); ?>_url" name="gear_<?php echo esc_attr( (string) $i ); ?>_url" value="<?php echo esc_attr( $url ); ?>" class="widefat" />
			</div>
			<div class="tts-meta-row tts-meta-row--full">
				<label for="gear_<?php echo esc_attr( (string) $i ); ?>_body" class="tts-meta-label"><?php printf( esc_html__( 'Item %d "Why you need it" Copy', 'drumstudy' ), $i ); ?></label>
				<textarea id="gear_<?php echo esc_attr( (string) $i ); ?>_body" name="gear_<?php echo esc_attr( (string) $i ); ?>_body" rows="3" class="widefat"><?php echo esc_textarea( $body ); ?></textarea>
			</div>
			<div class="tts-meta-row">
				<label class="tts-meta-label"><?php printf( esc_html__( 'Item %d Image', 'drumstudy' ), $i ); ?></label>
				<input type="hidden" id="gear_<?php echo esc_attr( (string) $i ); ?>_image" name="gear_<?php echo esc_attr( (string) $i ); ?>_image" value="<?php echo esc_attr( (string) $img_id ); ?>" />
				<div id="gear_<?php echo esc_attr( (string) $i ); ?>_image_preview" class="tts-image-preview"><?php echo $img_prev; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<button type="button" class="button tts-media-upload-btn" data-field="gear_<?php echo esc_attr( (string) $i ); ?>_image" data-preview="gear_<?php echo esc_attr( (string) $i ); ?>_image_preview"><?php esc_html_e( 'Select Image', 'drumstudy' ); ?></button>
				<?php if ( $img_id ) : ?>
					<button type="button" class="button tts-media-remove-btn" data-field="gear_<?php echo esc_attr( (string) $i ); ?>_image" data-preview="gear_<?php echo esc_attr( (string) $i ); ?>_image_preview"><?php esc_html_e( 'Remove', 'drumstudy' ); ?></button>
				<?php endif; ?>
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
function drumstudy_home_embed_cb( WP_Post $post ): void {
	$embed = get_post_meta( $post->ID, 'home_embed_block', true );
	?>
	<div class="tts-meta-row tts-meta-row--full">
		<label for="home_embed_block" class="tts-meta-label"><?php esc_html_e( 'Embed / Shortcode', 'drumstudy' ); ?></label>
		<textarea id="home_embed_block" name="home_embed_block" rows="5" class="widefat"><?php echo esc_textarea( $embed ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Where it appears: Flexible slot below main homepage sections. Accepts shortcodes, embed URLs, and iframe codes.', 'drumstudy' ); ?></p>
	</div>
	<?php
}

// ── Save ──────────────────────────────────────────────────────────────────────

/**
 * Save Home page meta fields.
 *
 * @param int $post_id Post ID.
 */
function drumstudy_save_home_meta( int $post_id ): void {
	if ( ! isset( $_POST['drumstudy_home_nonce'] )
		|| ! wp_verify_nonce( sanitize_key( $_POST['drumstudy_home_nonce'] ), 'drumstudy_save_home_meta' ) ) {
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
		'home_intro_cta_label', 'home_intro_cta_url',
		'home_stat_1_number', 'home_stat_1_label',
		'home_stat_2_number', 'home_stat_2_label',
		'home_stat_3_number', 'home_stat_3_label',
		'gear_guide_headline', 'gear_guide_subtitle',
		'gear_1_label', 'gear_1_url',
		'gear_2_label', 'gear_2_url',
		'gear_3_label', 'gear_3_url',
	];
	foreach ( $text_fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	foreach ( [ 'home_intro_body', 'home_embed_block', 'gear_1_body', 'gear_2_body', 'gear_3_body' ] as $rich_key ) {
		if ( isset( $_POST[ $rich_key ] ) ) {
			update_post_meta( $post_id, $rich_key, wp_kses_post( wp_unslash( $_POST[ $rich_key ] ) ) );
		}
	}

	foreach ( [ 'home_hero_bg_image', 'home_intro_image', 'gear_1_image', 'gear_2_image', 'gear_3_image' ] as $img_key ) {
		if ( isset( $_POST[ $img_key ] ) ) {
			update_post_meta( $post_id, $img_key, absint( $_POST[ $img_key ] ) );
		}
	}
}
add_action( 'save_post', 'drumstudy_save_home_meta' );

add_filter(
	'drumstudy_image_meta_keys',
	function ( array $keys, int $post_id ): array {
		if ( 'page' !== get_post_type( $post_id ) ) {
			return $keys;
		}
		if ( 'templates/template-home.php' === get_post_meta( $post_id, '_wp_page_template', true ) ) {
			$keys[] = 'home_hero_bg_image';
			$keys[] = 'home_intro_image';
			$keys[] = 'gear_1_image';
			$keys[] = 'gear_2_image';
			$keys[] = 'gear_3_image';
		}
		return $keys;
	},
	10,
	2
);
