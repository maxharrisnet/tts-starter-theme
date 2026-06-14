<?php
/**
 * Single: Location (Directory)
 *
 * @package tts-theme
 */

get_template_part( 'template-parts/global/header' );

$post_id   = get_the_ID();
$address1  = get_post_meta( $post_id, 'address_1', true );
$address2  = get_post_meta( $post_id, 'address_2', true );
$city      = get_post_meta( $post_id, 'city', true );
$state     = get_post_meta( $post_id, 'state', true );
$postal    = get_post_meta( $post_id, 'postal', true );
$phone     = get_post_meta( $post_id, 'location_phone', true );
$email     = sanitize_email( get_post_meta( $post_id, 'location_email', true ) );
$hours     = get_post_meta( $post_id, 'location_hours', true );
$map_embed = get_post_meta( $post_id, 'map_embed', true );
$img_id    = absint( get_post_meta( $post_id, 'location_image', true ) );
$manager   = get_post_meta( $post_id, 'manager_name', true );
?>
<main id="main-content" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class( 'tts-section' ); ?>>
			<div class="tts-container">
				<div class="flex flex-col lg:flex-row gap-12">
					<div class="w-full lg:w-2/3">
						<header class="tts-article-header mb-8">
							<h1><?php the_title(); ?></h1>
							<?php if ( $manager ) : ?>
								<p class="tts-manager"><?php echo esc_html( $manager ); ?></p>
							<?php endif; ?>
						</header>

						<div class="tts-prose">
							<?php the_content(); ?>
						</div>

						<?php if ( $map_embed ) : ?>
							<div class="tts-map-embed mt-8">
								<iframe src="<?php echo esc_url( $map_embed ); ?>"
								        width="100%"
								        height="350"
								        style="border:0;"
								        allowfullscreen=""
								        loading="lazy"
								        referrerpolicy="no-referrer-when-downgrade"
								        title="<?php printf( esc_attr__( 'Map for %s', 'tts-theme' ), get_the_title() ); ?>">
								</iframe>
							</div>
						<?php endif; ?>
					</div>

					<aside class="w-full lg:w-1/3">
						<?php if ( $img_id ) : ?>
							<?php echo wp_get_attachment_image( $img_id, 'tts-card', false, [
								'class'   => 'w-full h-auto rounded mb-6',
								'loading' => 'lazy',
								'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title(),
							] ); ?>
						<?php endif; ?>

						<?php if ( $address1 ) : ?>
							<address class="tts-address not-italic mb-6">
								<strong><?php esc_html_e( 'Address', 'tts-theme' ); ?></strong>
								<span><?php echo esc_html( $address1 ); ?></span>
								<?php if ( $address2 ) : ?>
									<span><?php echo esc_html( $address2 ); ?></span>
								<?php endif; ?>
								<?php
								$city_line = array_filter( [ $city, $state, $postal ] );
								if ( $city_line ) :
									?>
									<span><?php echo esc_html( implode( ', ', $city_line ) ); ?></span>
								<?php endif; ?>
							</address>
						<?php endif; ?>

						<?php if ( $phone ) : ?>
							<p>
								<strong><?php esc_html_e( 'Phone', 'tts-theme' ); ?></strong>
								<a href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>">
									<?php echo esc_html( $phone ); ?>
								</a>
							</p>
						<?php endif; ?>

						<?php if ( $email ) : ?>
							<p>
								<strong><?php esc_html_e( 'Email', 'tts-theme' ); ?></strong>
								<a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
							</p>
						<?php endif; ?>

						<?php if ( $hours ) : ?>
							<div class="tts-hours">
								<strong><?php esc_html_e( 'Hours', 'tts-theme' ); ?></strong>
								<pre><?php echo esc_html( $hours ); ?></pre>
							</div>
						<?php endif; ?>
					</aside>
				</div>
			</div>
		</article>

	<?php endwhile; ?>
</main>
<?php
get_template_part( 'template-parts/global/footer' );
