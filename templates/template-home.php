<?php
/**
 * Template Name: Home
 *
 * Profile-aware section inclusion. Profile conditionals are only allowed here
 * and in hero.php.
 *
 * @package drumstudy
 */

get_template_part( 'template-parts/global/header' );
?>
<main id="main-content" role="main">

	<?php get_template_part( 'template-parts/sections/hero' ); ?>

	<?php
	// Stats strip — render if any stats are set.
	get_template_part( 'template-parts/sections/stats' );

	// Profile-aware section order.
	$profile = drumstudy_get_profile();

	switch ( $profile ) {

		case 'booking':
			get_template_part( 'template-parts/sections/team' );
			get_template_part( 'template-parts/sections/services' );
			get_template_part( 'template-parts/sections/intro' );
			get_template_part( 'template-parts/sections/booking-embed' );
			get_template_part( 'template-parts/sections/gallery' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/faqs' );
			get_template_part( 'template-parts/sections/gear-guide' );
			get_template_part( 'template-parts/sections/hours-location' );
			break;

		case 'local':
			get_template_part( 'template-parts/sections/services' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/gallery' );
			get_template_part( 'template-parts/sections/team' );
			get_template_part( 'template-parts/sections/hours-location' );
			get_template_part( 'template-parts/sections/faqs' );
			break;

		case 'creative':
			get_template_part( 'template-parts/sections/gallery' );
			get_template_part( 'template-parts/sections/video-demo' );
			get_template_part( 'template-parts/sections/press-logos' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/updates-feed' );
			break;

		case 'venture':
			get_template_part( 'template-parts/sections/features' );
			get_template_part( 'template-parts/sections/team' );
			get_template_part( 'template-parts/sections/press-logos' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/faqs' );
			break;

		case 'sales':
			get_template_part( 'template-parts/sections/features' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/video-demo' );
			get_template_part( 'template-parts/sections/faqs' );
			break;

		case 'events':
			get_template_part( 'template-parts/sections/events-feed' );
			get_template_part( 'template-parts/sections/gallery' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/press-logos' );
			break;

		case 'directory':
			get_template_part( 'template-parts/sections/locations-list' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/faqs' );
			break;

		case 'community':
			get_template_part( 'template-parts/sections/donate-embed' );
			get_template_part( 'template-parts/sections/events-feed' );
			get_template_part( 'template-parts/sections/team' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/updates-feed' );
			break;

		default:
			// Fallback: show all populated sections.
			get_template_part( 'template-parts/sections/services' );
			get_template_part( 'template-parts/sections/testimonials' );
			get_template_part( 'template-parts/sections/team' );
			get_template_part( 'template-parts/sections/gallery' );
			get_template_part( 'template-parts/sections/faqs' );
			break;
	}

	// Flexible embed block always last.
	get_template_part( 'template-parts/sections/embed-block' );
	?>

</main>
<?php
get_template_part( 'template-parts/global/footer' );
