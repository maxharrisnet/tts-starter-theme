<?php
/**
 * Footer dispatcher — loads layout partial based on Admin Options.
 *
 * @package tts-theme
 */

$layout = sanitize_key( tts_get_option( 'tts_footer_layout' ) ?: 'standard' );
get_template_part( 'template-parts/global/footer-' . $layout );
