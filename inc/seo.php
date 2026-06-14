<?php
/**
 * SEO, Open Graph, Schema.org, Sitemap, GTM/GA, llms.txt
 *
 * @package tts-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Title Tag ─────────────────────────────────────────────────────────────────

add_filter(
	'document_title_separator',
	function (): string {
		return '|';
	}
);

add_filter(
	'document_title_parts',
	function ( array $title ): array {
		if ( is_front_page() ) {
			$tagline = tts_get_option( 'tts_tagline' );
			if ( $tagline ) {
				$title['tagline'] = $tagline;
			}
		}
		return $title;
	}
);

// ── Meta Description & Canonical ──────────────────────────────────────────────

add_action(
	'wp_head',
	function (): void {
		global $post;

		// Meta description
		$description = '';
		if ( is_singular() && $post ) {
			$description = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
		}
		if ( ! $description ) {
			$description = tts_get_option( 'tts_tagline' );
		}
		if ( $description ) {
			printf(
				'<meta name="description" content="%s" />' . "\n",
				esc_attr( wp_strip_all_tags( $description ) )
			);
		}

		// Canonical
		$canonical = '';
		if ( is_singular() ) {
			$canonical = get_permalink();
		} elseif ( is_front_page() ) {
			$canonical = home_url( '/' );
		} elseif ( is_home() ) {
			$canonical = get_permalink( get_option( 'page_for_posts' ) );
		} elseif ( is_post_type_archive() ) {
			$canonical = get_post_type_archive_link( get_post_type() );
		}
		if ( $canonical ) {
			printf( '<link rel="canonical" href="%s" />' . "\n", esc_url( $canonical ) );
		}

		// Robots — respect WP's blog_public setting
		if ( ! get_option( 'blog_public' ) ) {
			echo '<meta name="robots" content="noindex, nofollow" />' . "\n";
		}
	},
	5
);

// ── Open Graph & Twitter Card ──────────────────────────────────────────────────

add_action(
	'wp_head',
	function (): void {
		global $post;

		$site_name   = tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' );
		$og_type     = is_singular() ? 'article' : 'website';
		$og_title    = is_singular() && $post ? get_the_title( $post ) : get_bloginfo( 'name' );
		$og_url      = is_singular() && $post ? get_permalink( $post ) : home_url( '/' );
		$og_desc     = '';

		if ( is_singular() && $post ) {
			$og_desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : '';
		}
		if ( ! $og_desc ) {
			$og_desc = tts_get_option( 'tts_tagline' );
		}

		// OG image — featured image → site logo
		$og_image_url = '';
		if ( is_singular() && $post && has_post_thumbnail( $post ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'tts-og' );
			if ( $src ) {
				$og_image_url = $src[0];
			}
		}
		if ( ! $og_image_url ) {
			$logo_id = absint( tts_get_option( 'tts_logo' ) );
			if ( $logo_id ) {
				$src = wp_get_attachment_image_src( $logo_id, 'tts-og' );
				if ( $src ) {
					$og_image_url = $src[0];
				}
			}
		}

		echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $og_url ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";

		if ( $og_desc ) {
			echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( $og_desc ) ) . '" />' . "\n";
		}
		if ( $og_image_url ) {
			echo '<meta property="og:image" content="' . esc_url( $og_image_url ) . '" />' . "\n";
			echo '<meta property="og:image:width" content="1200" />' . "\n";
			echo '<meta property="og:image:height" content="630" />' . "\n";
		}

		// Twitter/X Card
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
		if ( $og_desc ) {
			echo '<meta name="twitter:description" content="' . esc_attr( wp_strip_all_tags( $og_desc ) ) . '" />' . "\n";
		}
		if ( $og_image_url ) {
			echo '<meta name="twitter:image" content="' . esc_url( $og_image_url ) . '" />' . "\n";
		}
		$twitter = tts_get_option( 'tts_social_x' );
		if ( $twitter ) {
			// Extract handle from URL if full URL provided
			$handle = preg_replace( '#^https?://(www\.)?x\.com/(twitter\.com/)?#', '', rtrim( $twitter, '/' ) );
			if ( $handle ) {
				echo '<meta name="twitter:site" content="@' . esc_attr( $handle ) . '" />' . "\n";
			}
		}
	},
	6
);

// ── Google Tag Manager ────────────────────────────────────────────────────────

/**
 * GTM head snippet — fires in <head>.
 */
function tts_gtm_head(): void {
	$gtm_id = tts_get_option( 'tts_gtm_id' );
	if ( ! $gtm_id ) {
		return;
	}
	printf(
		"<!-- Google Tag Manager -->\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','%s');</script>\n<!-- End Google Tag Manager -->\n",
		esc_attr( $gtm_id )
	);
}
add_action( 'wp_head', 'tts_gtm_head', 2 );

/**
 * Fallback GA4 — only fires when no GTM ID is set.
 */
function tts_ga_head(): void {
	if ( tts_has_option( 'tts_gtm_id' ) ) {
		return;
	}
	$ga_id = tts_get_option( 'tts_ga_id' );
	if ( ! $ga_id ) {
		return;
	}
	printf(
		'<script async src="https://www.googletagmanager.com/gtag/js?id=%1$s"></script>' . "\n" .
		'<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config","%1$s");</script>' . "\n",
		esc_attr( $ga_id )
	);
}
add_action( 'wp_head', 'tts_ga_head', 3 );

/**
 * Facebook Pixel.
 */
function tts_pixel_head(): void {
	$pixel_id = tts_get_option( 'tts_pixel_id' );
	if ( ! $pixel_id ) {
		return;
	}
	printf(
		'<script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version=\'2.0\';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,\'script\',\'https://connect.facebook.net/en_US/fbevents.js\');fbq(\'init\',\'%s\');fbq(\'track\',\'PageView\');</script>' . "\n",
		esc_attr( $pixel_id )
	);
}
add_action( 'wp_head', 'tts_pixel_head', 4 );

/**
 * GTM noscript — must fire immediately after <body> opens.
 * Called from header.php via wp_body_open().
 */
function tts_gtm_body(): void {
	$gtm_id = tts_get_option( 'tts_gtm_id' );
	if ( ! $gtm_id ) {
		return;
	}
	printf(
		'<!-- GTM noscript --><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n",
		esc_attr( $gtm_id )
	);
}
add_action( 'wp_body_open', 'tts_gtm_body' );

// ── Schema.org (JSON-LD) ─────────────────────────────────────────────────────

add_action(
	'wp_head',
	function (): void {
		$schema = tts_build_schema();
		if ( empty( $schema ) ) {
			return;
		}
		echo '<script type="application/ld+json">' . "\n";
		echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
		echo "\n" . '</script>' . "\n";
	},
	10
);

/**
 * Build the appropriate Schema.org graph for the current page.
 *
 * @return array<string, mixed>
 */
function tts_build_schema(): array {
	$business_name = tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' );
	$phone         = tts_get_option( 'tts_phone' );
	$email         = tts_get_option( 'tts_email' );
	$address_1     = tts_get_option( 'tts_address_1' );
	$city          = tts_get_option( 'tts_city' );
	$state         = tts_get_option( 'tts_state' );
	$postal        = tts_get_option( 'tts_postal' );
	$country       = tts_get_option( 'tts_country' );
	$social_links  = array_values( tts_social_links() );

	// Logo
	$logo_url = '';
	$logo_id  = absint( tts_get_option( 'tts_logo' ) );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $src ) {
			$logo_url = $src[0];
		}
	}

	// Base org type by profile
	$profile  = tts_get_profile();
	$org_type = match ( $profile ) {
		'community' => 'NGO',
		'local'     => 'LocalBusiness',
		'booking'   => 'LocalBusiness',
		'directory' => 'LocalBusiness',
		default     => 'Organization',
	};

	$schema = [
		'@context' => 'https://schema.org',
		'@type'    => $org_type,
		'name'     => $business_name,
		'url'      => home_url( '/' ),
	];

	if ( $logo_url ) {
		$schema['logo'] = $logo_url;
	}

	if ( $phone || $email ) {
		$contact = [ '@type' => 'ContactPoint' ];
		if ( $phone ) {
			$contact['telephone'] = $phone;
		}
		if ( $email ) {
			$contact['email'] = $email;
		}
		$schema['contactPoint'] = $contact;
	}

	if ( $address_1 ) {
		$schema['address'] = array_filter( [
			'@type'           => 'PostalAddress',
			'streetAddress'   => $address_1,
			'addressLocality' => $city,
			'addressRegion'   => $state,
			'postalCode'      => $postal,
			'addressCountry'  => $country,
		] );
	}

	if ( ! empty( $social_links ) ) {
		$schema['sameAs'] = $social_links;
	}

	// Single event schema
	if ( is_singular( 'tts_event' ) ) {
		$event_date = get_post_meta( get_the_ID(), 'event_date', true );
		$event_time = get_post_meta( get_the_ID(), 'event_time', true );
		$ticket_url = get_post_meta( get_the_ID(), 'ticket_url', true );
		$location   = get_post_meta( get_the_ID(), 'location_name', true );

		$event_schema = [
			'@context'  => 'https://schema.org',
			'@type'     => 'Event',
			'name'      => get_the_title(),
			'startDate' => $event_date . ( $event_time ? 'T' . $event_time : '' ),
			'url'       => get_permalink(),
		];
		if ( $location ) {
			$event_schema['location'] = [
				'@type' => 'Place',
				'name'  => $location,
			];
		}
		if ( $ticket_url ) {
			$event_schema['offers'] = [
				'@type' => 'Offer',
				'url'   => $ticket_url,
			];
		}
		// Return event schema on event singles instead of org schema
		return $event_schema;
	}

	return $schema;
}

// ── Sitemap customization ─────────────────────────────────────────────────────

add_filter(
	'wp_sitemaps_post_types',
	function ( array $post_types ): array {
		unset( $post_types['tts_gallery'] );
		unset( $post_types['tts_testimonial'] );
		unset( $post_types['tts_faq'] );
		return $post_types;
	}
);

add_filter(
	'wp_sitemaps_taxonomies',
	function ( array $taxonomies ): array {
		unset( $taxonomies['post_tag'] );
		unset( $taxonomies['post_format'] );
		return $taxonomies;
	}
);

add_filter(
	'wp_sitemaps_add_provider',
	function ( $provider, string $name ) {
		return 'users' === $name ? false : $provider;
	},
	10,
	2
);

// ── llms.txt ──────────────────────────────────────────────────────────────────

add_action(
	'init',
	function (): void {
		add_rewrite_rule( '^llms\.txt$', 'index.php?tts_llms_txt=1', 'top' );
	}
);

add_filter(
	'query_vars',
	function ( array $vars ): array {
		$vars[] = 'tts_llms_txt';
		return $vars;
	}
);

add_action(
	'template_redirect',
	function (): void {
		if ( ! get_query_var( 'tts_llms_txt' ) ) {
			return;
		}

		$business_name = tts_get_option( 'tts_business_name' ) ?: get_bloginfo( 'name' );
		$tagline       = tts_get_option( 'tts_tagline' );
		$phone         = tts_get_option( 'tts_phone' );
		$email         = tts_get_option( 'tts_email' );
		$address_1     = tts_get_option( 'tts_address_1' );
		$city          = tts_get_option( 'tts_city' );
		$state         = tts_get_option( 'tts_state' );

		$services_query = new WP_Query( [
			'post_type'      => 'tts_service',
			'posts_per_page' => -1,
			'no_found_rows'  => true,
			'fields'         => 'ids',
		] );

		header( 'Content-Type: text/plain; charset=utf-8' );

		echo '# ' . esc_html( $business_name ) . "\n";
		if ( $tagline ) {
			echo esc_html( $tagline ) . "\n";
		}
		echo "\n";
		echo '## About' . "\n";
		echo esc_html( $business_name ) . ' — ' . esc_html( home_url( '/' ) ) . "\n\n";

		if ( $services_query->have_posts() ) {
			echo '## Services' . "\n";
			foreach ( $services_query->posts as $service_id ) {
				echo '- ' . esc_html( get_the_title( (int) $service_id ) ) . "\n";
			}
			echo "\n";
			wp_reset_postdata();
		}

		echo '## Contact' . "\n";
		if ( $phone ) {
			echo 'Phone: ' . esc_html( $phone ) . "\n";
		}
		if ( $email ) {
			echo 'Email: ' . esc_html( $email ) . "\n";
		}
		if ( $address_1 ) {
			$location_parts = array_filter( [ $address_1, $city, $state ] );
			echo 'Address: ' . esc_html( implode( ', ', $location_parts ) ) . "\n";
		}
		echo 'Website: ' . esc_url( home_url( '/' ) ) . "\n";

		exit;
	}
);
