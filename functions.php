<?php
/**
 * Functions: Bootstrap
 *
 * Defines theme constants and requires all inc/ files in dependency order.
 * No logic in this file — only constants and require() calls.
 *
 * @package drumstudy
 */

define( 'DRUMSTUDY_THEME_VERSION', '1.0.0' );
define( 'DRUMSTUDY_THEME_DIR',     get_template_directory() );
define( 'DRUMSTUDY_THEME_URI',     get_template_directory_uri() );

// Core infrastructure
require DRUMSTUDY_THEME_DIR . '/inc/helpers.php';
require DRUMSTUDY_THEME_DIR . '/inc/setup.php';
require DRUMSTUDY_THEME_DIR . '/inc/enqueue.php';

// Custom Post Types
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-services.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-testimonials.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-team.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-gallery.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-faqs.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-events.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-locations.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-press.php';
require DRUMSTUDY_THEME_DIR . '/inc/cpt/cpt-demo.php';

// Admin Options
require DRUMSTUDY_THEME_DIR . '/inc/options/options-sanitize.php';
require DRUMSTUDY_THEME_DIR . '/inc/options/options-page.php';
require DRUMSTUDY_THEME_DIR . '/inc/options/options-fields.php';

// Meta Boxes
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-home.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-about.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-contact.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-booking.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-features.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-donate.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-splash.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-services.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-testimonials.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-team.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-gallery.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-faqs.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-events.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-locations.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-press.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-demo.php';
require DRUMSTUDY_THEME_DIR . '/inc/meta/meta-updates.php';

// SEO, maintenance, and integrations
require DRUMSTUDY_THEME_DIR . '/inc/seo.php';
require DRUMSTUDY_THEME_DIR . '/inc/maintenance.php';
require DRUMSTUDY_THEME_DIR . '/inc/rest-api.php';
require DRUMSTUDY_THEME_DIR . '/inc/shortcodes.php';
require DRUMSTUDY_THEME_DIR . '/inc/dashboard.php';
