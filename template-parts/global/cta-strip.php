<?php
/**
 * CTA Strip — two-button pattern
 *
 * Pass args via get_template_part() $args parameter:
 *   primary_label, primary_url, secondary_label, secondary_url
 *
 * Falls back to Admin Options values if args not provided.
 *
 * @package drumstudy
 */

$primary_label   = $args['primary_label']   ?? '';
$primary_url     = $args['primary_url']     ?? '';
$secondary_label = $args['secondary_label'] ?? '';
$secondary_url   = $args['secondary_url']   ?? '';

// Use drumstudy_render_cta which handles fallbacks
drumstudy_render_cta( $primary_label, $primary_url, $secondary_label, $secondary_url );
