<?php
/**
 * Footer dispatcher — loads the layout partial based on Admin Options, then
 * closes the document shell opened in template-parts/global/header.php.
 *
 * @package drumstudy
 */

$layout = sanitize_key( drumstudy_get_option( 'drumstudy_footer_layout' ) ?: 'standard' );
get_template_part( 'template-parts/global/footer-' . $layout );
?>
<?php wp_footer(); ?>
</body>
</html>
