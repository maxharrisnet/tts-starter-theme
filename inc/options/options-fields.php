<?php
/**
 * Admin Options — Field Rendering
 *
 * Renders the full 6-tab options page. Tab switching is handled by admin.js.
 * All output is escaped. Image fields use the WP media uploader via admin.js.
 *
 * @package drumstudy
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
function drumstudy_options_text_field( string $key, string $label, string $description = '', string $type = 'text' ): void {
	$value = drumstudy_get_option( $key );
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
function drumstudy_options_textarea_field( string $key, string $label, string $description = '', int $rows = 4 ): void {
	$value = drumstudy_get_option( $key );
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
function drumstudy_options_checkbox_field( string $key, string $label, string $description = '' ): void {
	$checked = drumstudy_get_option( $key );
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
function drumstudy_options_image_field( string $key, string $label, string $description = '', string $size = 'thumbnail' ): void {
	$img_id  = absint( drumstudy_get_option( $key ) );
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
			data-title="<?php esc_attr_e( 'Select Image', 'drumstudy' ); ?>"
			data-button="<?php esc_attr_e( 'Use this image', 'drumstudy' ); ?>">
			<?php esc_html_e( 'Select Image', 'drumstudy' ); ?>
		</button>
		<?php if ( $img_id ) : ?>
			<button type="button"
				class="button tts-media-remove-btn"
				data-field="<?php echo esc_attr( $key ); ?>"
				data-preview="<?php echo esc_attr( $key ); ?>_preview">
				<?php esc_html_e( 'Remove', 'drumstudy' ); ?>
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
function drumstudy_options_select_field( string $key, string $label, array $options, string $description = '' ): void {
	$current = drumstudy_get_option( $key );
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
function drumstudy_options_section_heading( string $heading ): void {
	echo '<h3 class="drumstudy-options__section-heading">' . esc_html( $heading ) . '</h3>';
}

/**
 * Main render function — outputs the full options page.
 */
function drumstudy_render_options_fields(): void {
	$profile = drumstudy_get_profile();
	?>
	<div class="wrap drumstudy-options-page">
		<h1><?php esc_html_e( 'TTS Site Settings', 'drumstudy' ); ?></h1>

		<nav class="tts-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Settings tabs', 'drumstudy' ); ?>">
			<button class="tts-tab-btn" role="tab" data-tab="01" aria-selected="true"><?php esc_html_e( '01 Identity', 'drumstudy' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="02"><?php esc_html_e( '02 Business', 'drumstudy' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="03"><?php esc_html_e( '03 Social', 'drumstudy' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="04"><?php esc_html_e( '04 CTAs & Banner', 'drumstudy' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="05"><?php esc_html_e( '05 Integrations', 'drumstudy' ); ?></button>
			<button class="tts-tab-btn" role="tab" data-tab="06"><?php esc_html_e( '06 Profile', 'drumstudy' ); ?></button>
		</nav>

		<form method="post" action="options.php">
			<?php settings_fields( 'drumstudy_options' ); ?>

			<!-- ── Tab 01 — Identity ── -->
			<div id="tts-tab-01" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="drumstudy-options__grid">
					<?php
					drumstudy_options_select_field(
						'drumstudy_site_profile',
						__( 'Site Profile', 'drumstudy' ),
						[
							'booking'   => __( '01 Booking', 'drumstudy' ),
							'local'     => __( '02 Local Business', 'drumstudy' ),
							'creative'  => __( '03 Creative', 'drumstudy' ),
							'venture'   => __( '04 Venture', 'drumstudy' ),
							'sales'     => __( '05 Sales', 'drumstudy' ),
							'events'    => __( '06 Events', 'drumstudy' ),
							'directory' => __( '07 Directory', 'drumstudy' ),
							'community' => __( '08 Community', 'drumstudy' ),
						],
						__( 'Controls section rendering on the home template and profile-specific options in Tab 06.', 'drumstudy' )
					);
					drumstudy_options_image_field( 'drumstudy_logo', __( 'Logo', 'drumstudy' ), __( 'Appears in header and footer. Store as attachment ID.', 'drumstudy' ), 'tts-logo' );
					drumstudy_options_text_field( 'drumstudy_logo_alt', __( 'Logo Alt Text', 'drumstudy' ), __( 'Required when logo is set. Screen reader description of logo.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_color_primary',   __( 'Brand Color Primary', 'drumstudy' ),   __( 'Hex value, e.g. #F4F2EC. Used for primary text/UI color.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_color_secondary', __( 'Brand Color Secondary', 'drumstudy' ), __( 'Hex value, e.g. #121214. Used for the page/background color.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_color_accent',    __( 'Accent Color', 'drumstudy' ),          __( 'Hex value, e.g. #4E73AB. Used for highlights and CTAs.', 'drumstudy' ) );
					drumstudy_options_select_field(
						'drumstudy_font_pairing',
						__( 'Font Pairing', 'drumstudy' ),
						[
							'editorial'  => __( 'A — Editorial (Manrope / Manrope)', 'drumstudy' ),
							'expressive' => __( 'B — Expressive (Archivo / Manrope)', 'drumstudy' ),
						],
						__( 'Editorial suits Booking, Local, Sales, Directory. Expressive suits Creative, Venture, Events, Community.', 'drumstudy' )
					);
					drumstudy_options_select_field(
						'drumstudy_header_layout',
						__( 'Header Layout', 'drumstudy' ),
						[
							'standard' => __( 'Standard (logo left, nav center, CTA right)', 'drumstudy' ),
							'minimal'  => __( 'Minimal (logo only + single CTA)', 'drumstudy' ),
						]
					);
					drumstudy_options_select_field(
						'drumstudy_footer_layout',
						__( 'Footer Layout', 'drumstudy' ),
						[
							'standard' => __( 'Standard (logo, nav columns, contact, social)', 'drumstudy' ),
							'minimal'  => __( 'Minimal (logo, copyright, legal links)', 'drumstudy' ),
						]
					);
					drumstudy_options_checkbox_field( 'drumstudy_reduce_motion', __( 'Reduce Motion', 'drumstudy' ), __( 'Disables CSS animations and transitions sitewide for accessibility.', 'drumstudy' ) );
					?>
				</div>
			</div>

			<!-- ── Tab 02 — Business ── -->
			<div id="tts-tab-02" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="drumstudy-options__grid">
					<?php
					drumstudy_options_text_field( 'drumstudy_business_name', __( 'Business Name', 'drumstudy' ),    __( 'Appears in footer, schema, and page title. Never hard-coded.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_tagline',       __( 'Tagline', 'drumstudy' ),           __( 'Appears in footer and hero fallback.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_phone',         __( 'Phone', 'drumstudy' ),             __( 'Appears in footer, contact page, and schema.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_email',         __( 'Email', 'drumstudy' ),             __( 'Appears in footer, contact page, and schema.', 'drumstudy' ), 'email' );
					drumstudy_options_section_heading( __( 'Address', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_address_1', __( 'Address Line 1', 'drumstudy' ), __( 'Street address.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_address_2', __( 'Address Line 2', 'drumstudy' ), __( 'Suite, floor, etc. (optional).', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_city',     __( 'City', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_state',    __( 'State / Province', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_postal',   __( 'Postal Code', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_country',  __( 'Country', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_lat', __( 'Latitude', 'drumstudy' ),  __( 'Used in LocalBusiness schema geo field.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_lng', __( 'Longitude', 'drumstudy' ), __( 'Used in LocalBusiness schema geo field.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_map_embed', __( 'Google Maps Embed URL', 'drumstudy' ), __( 'Full embed URL from Google Maps → Share → Embed a map. Appears in Hours & Location section.', 'drumstudy' ), 'url' );
					drumstudy_options_textarea_field( 'drumstudy_hours', __( 'Business Hours', 'drumstudy' ), __( 'Displayed in Hours & Location section and footer. Plain text, one line per day.', 'drumstudy' ) );
					?>
				</div>
			</div>

			<!-- ── Tab 03 — Social ── -->
			<div id="tts-tab-03" class="tts-tab-panel" role="tabpanel" hidden>
				<p class="description"><?php esc_html_e( 'All social fields are optional. Only populated platforms will appear in the footer and social icons.', 'drumstudy' ); ?></p>
				<div class="drumstudy-options__grid">
					<?php
					drumstudy_options_text_field( 'drumstudy_social_facebook',   __( 'Facebook', 'drumstudy' ),   '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_instagram',  __( 'Instagram', 'drumstudy' ),  '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_x',          __( 'X / Twitter', 'drumstudy' ), '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_linkedin',   __( 'LinkedIn', 'drumstudy' ),   '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_youtube',    __( 'YouTube', 'drumstudy' ),    '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_tiktok',     __( 'TikTok', 'drumstudy' ),     '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_spotify',    __( 'Spotify', 'drumstudy' ),    '', 'url' );
					drumstudy_options_text_field( 'drumstudy_social_soundcloud', __( 'SoundCloud', 'drumstudy' ), '', 'url' );
					?>
				</div>
			</div>

			<!-- ── Tab 04 — CTAs & Banner ── -->
			<div id="tts-tab-04" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="drumstudy-options__grid">
					<?php
					drumstudy_options_section_heading( __( 'Global CTAs', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_cta_primary_label',   __( 'Primary CTA Label', 'drumstudy' ),   __( 'Default primary button label used site-wide when not overridden per-page.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_cta_primary_url',     __( 'Primary CTA URL', 'drumstudy' ),     __( 'Supports external URLs, relative paths (/about), and anchor links (#section).', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_cta_secondary_label', __( 'Secondary CTA Label', 'drumstudy' ), __( 'Default secondary button label.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_cta_secondary_url',   __( 'Secondary CTA URL', 'drumstudy' ) );
					drumstudy_options_section_heading( __( 'Header CTA Override', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_cta_header_label', __( 'Header CTA Label', 'drumstudy' ), __( 'Overrides the primary CTA in the header only. Leave blank to use Primary CTA.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_cta_header_url',   __( 'Header CTA URL', 'drumstudy' ) );
					drumstudy_options_section_heading( __( 'Announcement Banner', 'drumstudy' ) );
					drumstudy_options_checkbox_field( 'drumstudy_banner_active', __( 'Enable Banner', 'drumstudy' ), __( 'Shows the sticky announcement bar above the header.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_banner_text',      __( 'Banner Text', 'drumstudy' ),      __( 'Short announcement copy. Appears in the banner.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_banner_cta_label', __( 'Banner CTA Label', 'drumstudy' ), __( 'Optional link label inside the banner.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_banner_cta_url',   __( 'Banner CTA URL', 'drumstudy' ) );
					?>
				</div>
			</div>

			<!-- ── Tab 05 — Integrations ── -->
			<div id="tts-tab-05" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="drumstudy-options__grid">
					<?php
					drumstudy_options_section_heading( __( 'Analytics & Tracking', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_gtm_id',   __( 'Google Tag Manager ID', 'drumstudy' ), __( 'Format: GTM-XXXXXXX. Takes precedence over GA4 when both are set.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_ga_id',    __( 'Google Analytics 4 ID', 'drumstudy' ), __( 'Format: G-XXXXXXXXXX. Used only when no GTM ID is set.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_pixel_id', __( 'Facebook Pixel ID', 'drumstudy' ),     __( 'Inject via GTM is preferred. This field adds a standalone pixel.', 'drumstudy' ) );
					drumstudy_options_section_heading( __( 'Embed Codes', 'drumstudy' ) );
					drumstudy_options_textarea_field( 'drumstudy_embed_booking',  __( 'Booking Embed Code', 'drumstudy' ),  __( 'Calendly, Acuity, or other booking widget embed. Displayed in Booking Embed section.', 'drumstudy' ), 6 );
					drumstudy_options_textarea_field( 'drumstudy_embed_donation', __( 'Donation Embed Code', 'drumstudy' ), __( 'Donorbox, PayPal, or other donation widget embed. Displayed in Donate Embed section.', 'drumstudy' ), 6 );
					drumstudy_options_section_heading( __( 'Custom Scripts', 'drumstudy' ) );
					drumstudy_options_textarea_field( 'drumstudy_scripts_header', __( 'Custom Header Scripts', 'drumstudy' ), __( 'Injected before </head>. Use for third-party scripts not covered above.', 'drumstudy' ), 6 );
					drumstudy_options_textarea_field( 'drumstudy_scripts_footer', __( 'Custom Footer Scripts', 'drumstudy' ), __( 'Injected before </body>. Use for chat widgets, feedback tools, etc.', 'drumstudy' ), 6 );
					?>
				</div>
			</div>

			<!-- ── Tab 06 — Profile Settings ── -->
			<div id="tts-tab-06" class="tts-tab-panel" role="tabpanel" hidden>
				<div class="drumstudy-options__grid">
					<?php
					drumstudy_options_section_heading( __( 'Maintenance Mode', 'drumstudy' ) );
					drumstudy_options_checkbox_field( 'drumstudy_maintenance_active', __( 'Enable Maintenance Mode', 'drumstudy' ), __( 'Non-admin visitors will see the maintenance message and receive a 503 status. Logged-in admins see the site normally.', 'drumstudy' ) );
					drumstudy_options_textarea_field( 'drumstudy_maintenance_message', __( 'Maintenance Message', 'drumstudy' ), __( 'Displayed on the maintenance page.', 'drumstudy' ), 3 );

					drumstudy_options_section_heading( __( 'Archive Page Headings', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_archive_header_events',   __( 'Events Archive Heading', 'drumstudy' ),    __( 'H1 on the Events archive page.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_archive_header_services', __( 'Services Archive Heading', 'drumstudy' ),  __( 'H1 on the Services archive page.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_archive_header_locations',__( 'Locations Archive Heading', 'drumstudy' ), __( 'H1 on the Locations archive page.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_archive_header_demo',     __( 'Demo Archive Heading', 'drumstudy' ),      __( 'H1 on the Demo archive page.', 'drumstudy' ) );
					drumstudy_options_text_field( 'drumstudy_archive_header_team',     __( 'Team Archive Heading', 'drumstudy' ),      __( 'Used on About page team section header.', 'drumstudy' ) );

					drumstudy_options_section_heading( __( 'Profile-Specific Settings', 'drumstudy' ) );

					if ( in_array( $profile, [ 'events', 'community', 'booking' ], true ) ) :
						drumstudy_options_text_field( 'drumstudy_ticket_platform', __( 'Ticket Platform URL', 'drumstudy' ), __( 'Eventbrite, Dice, or similar. Default ticket link when no per-event URL is set.', 'drumstudy' ), 'url' );
					endif;

					if ( 'directory' === $profile ) :
						drumstudy_options_text_field( 'drumstudy_location_count', __( 'Number of Locations', 'drumstudy' ), __( 'Used in stats strip and hero badge.', 'drumstudy' ), 'number' );
					endif;

					if ( 'community' === $profile ) :
						drumstudy_options_text_field( 'drumstudy_donation_goal', __( 'Donation Goal Amount', 'drumstudy' ), __( 'Displayed on Donate page as the fundraising target.', 'drumstudy' ) );
					endif;

					if ( 'venture' === $profile ) :
						drumstudy_options_text_field( 'drumstudy_waitlist_url', __( 'Waitlist Platform URL', 'drumstudy' ), __( 'Mailchimp, Beehiiv, or similar waitlist link.', 'drumstudy' ), 'url' );
					endif;

					if ( 'creative' === $profile ) :
						drumstudy_options_image_field( 'drumstudy_press_kit_pdf', __( 'Press Kit PDF', 'drumstudy' ), __( 'Uploaded PDF. Displayed as a download link on the About page.', 'drumstudy' ) );
					endif;

					if ( 'booking' === $profile ) :
						drumstudy_options_text_field( 'drumstudy_booking_platform', __( 'Default Booking Platform', 'drumstudy' ), __( 'e.g. Calendly, Acuity. Informational — embed code goes in Tab 05.', 'drumstudy' ) );
					endif;
					?>
				</div>
			</div>

			<?php submit_button( __( 'Save Settings', 'drumstudy' ) ); ?>
		</form>
	</div>
	<?php
}
