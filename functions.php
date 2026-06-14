<?php
/**
 * Functions: Bootstrap
 *
 * Defines theme constants and requires all inc/ files in dependency order.
 * No logic in this file — only constants and require() calls.
 *
 * @package tts-theme
 */

define( 'TTS_THEME_VERSION', '1.0.0' );
define( 'TTS_THEME_DIR',     get_template_directory() );
define( 'TTS_THEME_URI',     get_template_directory_uri() );

// Core infrastructure
require TTS_THEME_DIR . '/inc/helpers.php';
require TTS_THEME_DIR . '/inc/setup.php';
require TTS_THEME_DIR . '/inc/enqueue.php';

// Custom Post Types
require TTS_THEME_DIR . '/inc/cpt/cpt-services.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-testimonials.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-team.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-gallery.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-faqs.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-events.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-locations.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-press.php';
require TTS_THEME_DIR . '/inc/cpt/cpt-demo.php';

// Admin Options
require TTS_THEME_DIR . '/inc/options/options-sanitize.php';
require TTS_THEME_DIR . '/inc/options/options-page.php';
require TTS_THEME_DIR . '/inc/options/options-fields.php';

// Meta Boxes
require TTS_THEME_DIR . '/inc/meta/meta-home.php';
require TTS_THEME_DIR . '/inc/meta/meta-about.php';
require TTS_THEME_DIR . '/inc/meta/meta-contact.php';
require TTS_THEME_DIR . '/inc/meta/meta-features.php';
require TTS_THEME_DIR . '/inc/meta/meta-donate.php';
require TTS_THEME_DIR . '/inc/meta/meta-splash.php';
require TTS_THEME_DIR . '/inc/meta/meta-services.php';
require TTS_THEME_DIR . '/inc/meta/meta-testimonials.php';
require TTS_THEME_DIR . '/inc/meta/meta-team.php';
require TTS_THEME_DIR . '/inc/meta/meta-gallery.php';
require TTS_THEME_DIR . '/inc/meta/meta-faqs.php';
require TTS_THEME_DIR . '/inc/meta/meta-events.php';
require TTS_THEME_DIR . '/inc/meta/meta-locations.php';
require TTS_THEME_DIR . '/inc/meta/meta-press.php';
require TTS_THEME_DIR . '/inc/meta/meta-demo.php';
require TTS_THEME_DIR . '/inc/meta/meta-updates.php';

// SEO, maintenance, and integrations
require TTS_THEME_DIR . '/inc/seo.php';
require TTS_THEME_DIR . '/inc/maintenance.php';
require TTS_THEME_DIR . '/inc/rest-api.php';
require TTS_THEME_DIR . '/inc/shortcodes.php';
require TTS_THEME_DIR . '/inc/dashboard.php';
