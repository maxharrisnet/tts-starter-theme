<?php
/**
 * Single: Gallery
 * Gallery CPT is not publicly queryable. This template is a safety fallback —
 * direct URLs should 404 via WP routing before reaching here.
 *
 * @package tts-theme
 */

// Redirect to home if someone somehow lands here directly.
wp_redirect( home_url( '/' ), 301 );
exit;
