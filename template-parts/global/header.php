<?php
/**
 * Header dispatcher — loads layout partial based on Admin Options.
 *
 * @package tts-theme
 */

$layout = sanitize_key( tts_get_option( 'tts_header_layout' ) ?: 'standard' );
get_template_part( 'template-parts/global/header-' . $layout );
