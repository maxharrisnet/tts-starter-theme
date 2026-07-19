<?php
/**
 * Template Name: Booking
 *
 * Shared booking flow for new-client consultation and existing-client lesson booking.
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<?php get_template_part( 'template-parts/sections/booking-hero' ); ?>
	<?php get_template_part( 'template-parts/sections/booking-menu' ); ?>
	<?php get_template_part( 'template-parts/sections/booking-location' ); ?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
