<?php
/**
 * Admin Options — Field Rendering
 *
 * Renders the full 6-tab options page. Tab switching is handled by admin.js.
 * All output is escaped. Image fields use the WP media uploader via admin.js.
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Render a two-column text field row.
 *
 * @param string $key         Option key.
 * @param string $label       Field label.
 * @param string $description Where it appears on the frontend.
 * @param string $type        Input type (text|url|email|number).
 */
function tts_options_text_field( string $key, string $label, string $description = '', string $type = 'text' ): void {
	$value = tts_get_option( $key );
	?>
	<div class="tts-field">
		<label for="<?php echo esc_attr( $key ); ?>" class="tts-field__label">
			<?php echo esc_html( $label ); ?>
		</label>
		<input
			type="<?php echo esc_attr( $type ); ?>"
			id="<?php echo esc_attr( $key ); ?>"
			name="<?php echo esc_attr( $key ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			class="tts-field__input regular-text"
		/>
		<?php if ( $description ) : ?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render a full-width textarea field.
 *
 * @param string $key         Option key.
 * @param string $label       Field label.
 * @param string $description Where it appears on the frontend.
 * @param int    $rows        Number of visible rows.
 */
function tts_options_textarea_field( string $key, string $label, string $description = '', int $rows = 4 ): void {
	$value = tts_get_option( $key );
	?>
	<div class="tts-field tts-field--full">
		<label for="<?php echo esc_attr( $key ); ?>" class="tts-field__label">
			<?php echo esc_html( $label ); ?>
		</label>
		<textarea
			id="<?php echo esc_attr( $key ); ?>"
			name="<?php echo esc_attr( $key ); ?>"
			rows="<?php echo absint( $rows ); ?>"
			class="tts-field__textarea large-text"
		><?php echo esc_textarea( $value ); ?></textarea>
		<?php if ( $description ) : ?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render a checkbox field.
 *
 * @param string $key         Option key.
 * @param string $label       Field label.
 * @param string $description Descriptive hint.
 */
function tts_options_checkbox_field( string $key, string $label, string $description = '' ): void {
	$checked = tts_get_option( $key );
	?>
	<div class="tts-field">
		<label class="tts-field__label tts-field__label--checkbox">
			<input
				type="checkbox"
				id="<?php echo esc_attr( $key ); ?>"
				name="<?php echo esc_attr( $key ); ?>"
				value="1"
				<?php checked( $checked, '1' ); ?>
			/>
			<?php echo esc_html( $label ); ?>
		</label>
		<?php if ( $description ) : ?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render an image picker field (WP media uploader).
 *
 * @param string $key         Option key (stores attachment ID).
 * @param string $label       Field label.
 * @param string $description Where the image appears.
 * @param string $size        Preview image size.
 */
function tts_options_image_field( string $key, string $label, string $description = '', string $size = 'thumbnail' ): void {
	$img_id  = absint( tts_get_option( $key ) );
	$preview = $img_id ? wp_get_attachment_image( $img_id, $size ) : '';
	?>
	<div class="tts-field tts-field--image">
		<p class="tts-field__label"><?php echo esc_html( $label ); ?></p>
		<input type="hidden" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( (string) $img_id ); ?>" />
		<div id="<?php echo esc_attr( $key ); ?>_preview" class="tts-image-preview">
			<?php echo $preview; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() is safe ?>
		</div>
		<button type="button"
			class="button tts-media-upload-btn"
			data-field="<?php echo esc_attr( $key ); ?>"
			data-preview="<?php echo esc_attr( $key ); ?>_preview"
			data-title="<?php esc_attr_e( 'Select Image', 'tts-theme' ); ?>"
			data-button="<?php esc_attr_e( 'Use this image', 'tts-theme' ); ?>">
			<?php esc_html_e( 'Select Image', 'tts-theme' ); ?>
		</button>
		<?php if ( $img_id ) : ?>
			<button type="button"
				class="button tts-media-remove-btn"
				data-field="<?php echo esc_attr( $key ); ?>"
				data-preview="<?php echo esc_attr( $key ); ?>_preview">
				<?php esc_html_e( 'Remove', 'tts-theme' ); ?>
			</button>
		<?php endif; ?>
		<?php if ( $description ) : ?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render a select/dropdown field.
 *
 * @param string               $key         Option key.
 * @param string               $label       Field label.
 * @param array<string,string> $options     Value => Label pairs.
 * @param string               $description Descriptive hint.
 */
function tts_options_select_field( string $key, string $label, array $options, string $description = '' ): void {
	$current = tts_get_option( $key );
	?>
	<div class="tts-field">
		<label for="<?php echo esc_attr( $key ); ?>" class="tts-field__label">
			<?php echo esc_html( $label ); ?>
		</label>
		<select id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" class="tts-field__select">
			<?php foreach ( $options as $value => $option_label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, $value ); ?>>
					<?php echo esc_html( $option_label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php if ( $description ) : ?>
			<p class="description"><?php echo esc_html( $description ); ?></p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Render a section heading within a tab.
 *
 * @param string $heading Section heading text.
 */
function tts_options_section_heading( string $heading ): void {
	echo '<h3 class="tts-options__section-heading">' . esc_html( $heading ) . '</h3>';
}

/**
 * Main render function — outputs the full options page.
 */
function tts_render_options_fields(): void {
	$profile = tts_get_profile();
	?>
	<div class="wrap tts-options-page">
		<h1><?php esc_html_e( 'TTS Site Settings', 'tts-theme' ); ?></h1>

		<nav class="tts-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Settings tabs', 'tts-theme' ); ?>">
			<button class="tts-tab-btn" role="tab" data-tab="01" aria-selected="true"><?php esc_html_e( '01 Identity', 'tts-theme' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="02"><?php esc_html_e( '02 Business', 'tts-theme' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="03"><?php esc_html_e( '03 Social', 'tts-theme' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="04"><?php esc_html_e( '04 CTAs & Banner', 'tts-theme' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="05"><?php esc_html_e( '05 Integrations', 'tts-theme' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="06"><?php esc_html_e( '06 Profile', 'tts-theme' ); ?></button>
		</nav>

		<form method="post" action="options.php">
			<?php settings_fields( 'tts_options' ); ?>

			<!-- ── Tab 01 — Identity ── -->
			<div id="tts-tab-01" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="tts-options__grid">
					<?php
					tts_options_select_field(
						'tts_site_profile',
						__( 'Site Profile', 'tts-theme' ),
						[
							'booking'   => __( '01 Booking', 'tts-theme' ),
							'local'     => __( '02 Local Business', 'tts-theme' ),
							'creative'  => __( '03 Creative', 'tts-theme' ),
							'venture'   => __( '04 Venture', 'tts-theme' ),
							'sales'     => __( '05 Sales', 'tts-theme' ),
							'events'    => __( '06 Events', 'tts-theme' ),
							'directory' => __( '07 Directory', 'tts-theme' ),
							'community' => __( '08 Community', 'tts-theme' ),
						],
						__( 'Controls section rendering on the home template and profile-specific options in Tab 06.', 'tts-theme' )
					);
					tts_options_image_field( 'tts_logo', __( 'Logo', 'tts-theme' ), __( 'Appears in header and footer. Store as attachment ID.', 'tts-theme' ), 'tts-logo' );
					tts_options_text_field( 'tts_logo_alt', __( 'Logo Alt Text', 'tts-theme' ), __( 'Required when logo is set. Screen reader description of logo.', 'tts-theme' ) );
					tts_options_text_field( 'tts_color_primary',   __( 'Brand Color Primary', 'tts-theme' ),   __( 'Hex value, e.g. #1a1a2e. Used for primary UI color.', 'tts-theme' ) );
					tts_options_text_field( 'tts_color_secondary', __( 'Brand Color Secondary', 'tts-theme' ), __( 'Hex value, e.g. #ffffff. Used for secondary/background color.', 'tts-theme' ) );
					tts_options_text_field( 'tts_color_accent',    __( 'Accent Color', 'tts-theme' ),          __( 'Hex value, e.g. #4ade80. Used for highlights and CTAs.', 'tts-theme' ) );
					tts_options_select_field(
						'tts_font_pairing',
						__( 'Font Pairing', 'tts-theme' ),
						[
							'editorial'  => __( 'A — Editorial (DM Serif Display / Manrope)', 'tts-theme' ),
							'expressive' => __( 'B — Expressive (Zalando Sans / Figtree)', 'tts-theme' ),
						],
						__( 'Editorial suits Booking, Local, Sales, Directory. Expressive suits Creative, Venture, Events, Community.', 'tts-theme' )
					);
					tts_options_select_field(
						'tts_header_layout',
						__( 'Header Layout', 'tts-theme' ),
						[
							'standard' => __( 'Standard (logo left, nav center, CTA right)', 'tts-theme' ),
							'minimal'  => __( 'Minimal (logo only + single CTA)', 'tts-theme' ),
						]
					);
					tts_options_select_field(
						'tts_footer_layout',
						__( 'Footer Layout', 'tts-theme' ),
						[
							'standard' => __( 'Standard (logo, nav columns, contact, social)', 'tts-theme' ),
							'minimal'  => __( 'Minimal (logo, copyright, legal links)', 'tts-theme' ),
						]
					);
					tts_options_checkbox_field( 'tts_reduce_motion', __( 'Reduce Motion', 'tts-theme' ), __( 'Disables CSS animations and transitions sitewide for accessibility.', 'tts-theme' ) );
					?>
				</div>
			</div>

			<!-- ── Tab 02 — Business ── -->
			<div id="tts-tab-02" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="tts-options__grid">
					<?php
					tts_options_text_field( 'tts_business_name', __( 'Business Name', 'tts-theme' ),    __( 'Appears in footer, schema, and page title. Never hard-coded.', 'tts-theme' ) );
					tts_options_text_field( 'tts_tagline',       __( 'Tagline', 'tts-theme' ),           __( 'Appears in footer and hero fallback.', 'tts-theme' ) );
					tts_options_text_field( 'tts_phone',         __( 'Phone', 'tts-theme' ),             __( 'Appears in footer, contact page, and schema.', 'tts-theme' ) );
					tts_options_text_field( 'tts_email',         __( 'Email', 'tts-theme' ),             __( 'Appears in footer, contact page, and schema.', 'tts-theme' ), 'email' );
					tts_options_section_heading( __( 'Address', 'tts-theme' ) );
					tts_options_text_field( 'tts_address_1', __( 'Address Line 1', 'tts-theme' ), __( 'Street address.', 'tts-theme' ) );
					tts_options_text_field( 'tts_address_2', __( 'Address Line 2', 'tts-theme' ), __( 'Suite, floor, etc. (optional).', 'tts-theme' ) );
					tts_options_text_field( 'tts_city',     __( 'City', 'tts-theme' ) );
					tts_options_text_field( 'tts_state',    __( 'State / Province', 'tts-theme' ) );
					tts_options_text_field( 'tts_postal',   __( 'Postal Code', 'tts-theme' ) );
					tts_options_text_field( 'tts_country',  __( 'Country', 'tts-theme' ) );
					tts_options_text_field( 'tts_lat', __( 'Latitude', 'tts-theme' ),  __( 'Used in LocalBusiness schema geo field.', 'tts-theme' ) );
					tts_options_text_field( 'tts_lng', __( 'Longitude', 'tts-theme' ), __( 'Used in LocalBusiness schema geo field.', 'tts-theme' ) );
					tts_options_text_field( 'tts_map_embed', __( 'Google Maps Embed URL', 'tts-theme' ), __( 'Full embed URL from Google Maps → Share → Embed a map. Appears in Hours & Location section.', 'tts-theme' ), 'url' );
					tts_options_textarea_field( 'tts_hours', __( 'Business Hours', 'tts-theme' ), __( 'Displayed in Hours & Location section and footer. Plain text, one line per day.', 'tts-theme' ) );
					?>
				</div>
			</div>

			<!-- ── Tab 03 — Social ── -->
			<div id="tts-tab-03" class="tts-tab-panel" role="tabpanel" hidden>
				<p class="description"><?php esc_html_e( 'All social fields are optional. Only populated platforms will appear in the footer and social icons.', 'tts-theme' ); ?></p>
				<div class="tts-options__grid">
					<?php
					tts_options_text_field( 'tts_social_facebook',   __( 'Facebook', 'tts-theme' ),   '', 'url' );
					tts_options_text_field( 'tts_social_instagram',  __( 'Instagram', 'tts-theme' ),  '', 'url' );
					tts_options_text_field( 'tts_social_x',          __( 'X / Twitter', 'tts-theme' ), '', 'url' );
					tts_options_text_field( 'tts_social_linkedin',   __( 'LinkedIn', 'tts-theme' ),   '', 'url' );
					tts_options_text_field( 'tts_social_youtube',    __( 'YouTube', 'tts-theme' ),    '', 'url' );
					tts_options_text_field( 'tts_social_tiktok',     __( 'TikTok', 'tts-theme' ),     '', 'url' );
					tts_options_text_field( 'tts_social_spotify',    __( 'Spotify', 'tts-theme' ),    '', 'url' );
					tts_options_text_field( 'tts_social_soundcloud', __( 'SoundCloud', 'tts-theme' ), '', 'url' );
					?>
				</div>
			</div>

			<!-- ── Tab 04 — CTAs & Banner ── -->
			<div id="tts-tab-04" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="tts-options__grid">
					<?php
					tts_options_section_heading( __( 'Global CTAs', 'tts-theme' ) );
					tts_options_text_field( 'tts_cta_primary_label',   __( 'Primary CTA Label', 'tts-theme' ),   __( 'Default primary button label used site-wide when not overridden per-page.', 'tts-theme' ) );
					tts_options_text_field( 'tts_cta_primary_url',     __( 'Primary CTA URL', 'tts-theme' ),     __( 'Supports external URLs, relative paths (/about), and anchor links (#section).', 'tts-theme' ) );
					tts_options_text_field( 'tts_cta_secondary_label', __( 'Secondary CTA Label', 'tts-theme' ), __( 'Default secondary button label.', 'tts-theme' ) );
					tts_options_text_field( 'tts_cta_secondary_url',   __( 'Secondary CTA URL', 'tts-theme' ) );
					tts_options_section_heading( __( 'Header CTA Override', 'tts-theme' ) );
					tts_options_text_field( 'tts_cta_header_label', __( 'Header CTA Label', 'tts-theme' ), __( 'Overrides the primary CTA in the header only. Leave blank to use Primary CTA.', 'tts-theme' ) );
					tts_options_text_field( 'tts_cta_header_url',   __( 'Header CTA URL', 'tts-theme' ) );
					tts_options_section_heading( __( 'Announcement Banner', 'tts-theme' ) );
					tts_options_checkbox_field( 'tts_banner_active', __( 'Enable Banner', 'tts-theme' ), __( 'Shows the sticky announcement bar above the header.', 'tts-theme' ) );
					tts_options_text_field( 'tts_banner_text',      __( 'Banner Text', 'tts-theme' ),      __( 'Short announcement copy. Appears in the banner.', 'tts-theme' ) );
					tts_options_text_field( 'tts_banner_cta_label', __( 'Banner CTA Label', 'tts-theme' ), __( 'Optional link label inside the banner.', 'tts-theme' ) );
					tts_options_text_field( 'tts_banner_cta_url',   __( 'Banner CTA URL', 'tts-theme' ) );
					?>
				</div>
			</div>

			<!-- ── Tab 05 — Integrations ── -->
			<div id="tts-tab-05" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="tts-options__grid">
					<?php
					tts_options_section_heading( __( 'Analytics & Tracking', 'tts-theme' ) );
					tts_options_text_field( 'tts_gtm_id',   __( 'Google Tag Manager ID', 'tts-theme' ), __( 'Format: GTM-XXXXXXX. Takes precedence over GA4 when both are set.', 'tts-theme' ) );
					tts_options_text_field( 'tts_ga_id',    __( 'Google Analytics 4 ID', 'tts-theme' ), __( 'Format: G-XXXXXXXXXX. Used only when no GTM ID is set.', 'tts-theme' ) );
					tts_options_text_field( 'tts_pixel_id', __( 'Facebook Pixel ID', 'tts-theme' ),     __( 'Inject via GTM is preferred. This field adds a standalone pixel.', 'tts-theme' ) );
					tts_options_section_heading( __( 'Embed Codes', 'tts-theme' ) );
					tts_options_textarea_field( 'tts_embed_booking',  __( 'Booking Embed Code', 'tts-theme' ),  __( 'Calendly, Acuity, or other booking widget embed. Displayed in Booking Embed section.', 'tts-theme' ), 6 );
					tts_options_textarea_field( 'tts_embed_donation', __( 'Donation Embed Code', 'tts-theme' ), __( 'Donorbox, PayPal, or other donation widget embed. Displayed in Donate Embed section.', 'tts-theme' ), 6 );
					tts_options_section_heading( __( 'Custom Scripts', 'tts-theme' ) );
					tts_options_textarea_field( 'tts_scripts_header', __( 'Custom Header Scripts', 'tts-theme' ), __( 'Injected before </head>. Use for third-party scripts not covered above.', 'tts-theme' ), 6 );
					tts_options_textarea_field( 'tts_scripts_footer', __( 'Custom Footer Scripts', 'tts-theme' ), __( 'Injected before </body>. Use for chat widgets, feedback tools, etc.', 'tts-theme' ), 6 );
					?>
				</div>
			</div>

			<!-- ── Tab 06 — Profile Settings ── -->
			<div id="tts-tab-06" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="tts-options__grid">
					<?php
					tts_options_section_heading( __( 'Maintenance Mode', 'tts-theme' ) );
					tts_options_checkbox_field( 'tts_maintenance_active', __( 'Enable Maintenance Mode', 'tts-theme' ), __( 'Non-admin visitors will see the maintenance message and receive a 503 status. Logged-in admins see the site normally.', 'tts-theme' ) );
					tts_options_textarea_field( 'tts_maintenance_message', __( 'Maintenance Message', 'tts-theme' ), __( 'Displayed on the maintenance page.', 'tts-theme' ), 3 );

					tts_options_section_heading( __( 'Archive Page Headings', 'tts-theme' ) );
					tts_options_text_field( 'tts_archive_header_events',   __( 'Events Archive Heading', 'tts-theme' ),    __( 'H1 on the Events archive page.', 'tts-theme' ) );
					tts_options_text_field( 'tts_archive_header_services', __( 'Services Archive Heading', 'tts-theme' ),  __( 'H1 on the Services archive page.', 'tts-theme' ) );
					tts_options_text_field( 'tts_archive_header_locations',__( 'Locations Archive Heading', 'tts-theme' ), __( 'H1 on the Locations archive page.', 'tts-theme' ) );
					tts_options_text_field( 'tts_archive_header_demo',     __( 'Demo Archive Heading', 'tts-theme' ),      __( 'H1 on the Demo archive page.', 'tts-theme' ) );
					tts_options_text_field( 'tts_archive_header_team',     __( 'Team Archive Heading', 'tts-theme' ),      __( 'Used on About page team section header.', 'tts-theme' ) );

					tts_options_section_heading( __( 'Profile-Specific Settings', 'tts-theme' ) );

					if ( in_array( $profile, [ 'events', 'community', 'booking' ], true ) ) :
						tts_options_text_field( 'tts_ticket_platform', __( 'Ticket Platform URL', 'tts-theme' ), __( 'Eventbrite, Dice, or similar. Default ticket link when no per-event URL is set.', 'tts-theme' ), 'url' );
					endif;

					if ( 'directory' === $profile ) :
						tts_options_text_field( 'tts_location_count', __( 'Number of Locations', 'tts-theme' ), __( 'Used in stats strip and hero badge.', 'tts-theme' ), 'number' );
					endif;

					if ( 'community' === $profile ) :
						tts_options_text_field( 'tts_donation_goal', __( 'Donation Goal Amount', 'tts-theme' ), __( 'Displayed on Donate page as the fundraising target.', 'tts-theme' ) );
					endif;

					if ( 'venture' === $profile ) :
						tts_options_text_field( 'tts_waitlist_url', __( 'Waitlist Platform URL', 'tts-theme' ), __( 'Mailchimp, Beehiiv, or similar waitlist link.', 'tts-theme' ), 'url' );
					endif;

					if ( 'creative' === $profile ) :
						tts_options_image_field( 'tts_press_kit_pdf', __( 'Press Kit PDF', 'tts-theme' ), __( 'Uploaded PDF. Displayed as a download link on the About page.', 'tts-theme' ) );
					endif;

					if ( 'booking' === $profile ) :
						tts_options_text_field( 'tts_booking_platform', __( 'Default Booking Platform', 'tts-theme' ), __( 'e.g. Calendly, Acuity. Informational — embed code goes in Tab 05.', 'tts-theme' ) );
					endif;
					?>
				</div>
			</div>

			<?php submit_button( __( 'Save Settings', 'tts-theme' ) ); ?>
		</form>
	</div>
	<?php
}
