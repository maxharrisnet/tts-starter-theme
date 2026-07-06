<?php
/**
 * Header dispatcher — outputs the document shell, then loads the layout
 * partial based on Admin Options. Every entry template (index.php, single*.php,
 * archive*.php, search.php, 404.php, templates/template-*.php) calls this
 * first, so the <!DOCTYPE>/<html>/<head>/<body> shell lives here rather than
 * being duplicated across every template file.
 *
 * @package drumstudy
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/global/skip-nav' ); ?>
<?php
$layout = sanitize_key( drumstudy_get_option( 'drumstudy_header_layout' ) ?: 'standard' );
get_template_part( 'template-parts/global/header-' . $layout );
