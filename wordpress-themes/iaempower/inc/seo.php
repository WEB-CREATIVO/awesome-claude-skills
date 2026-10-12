<?php
/**
 * Technical SEO: meta description / Open Graph / Twitter Card tags, and
 * schema.org JSON-LD (Organization, Service, FAQPage, Person,
 * BreadcrumbList).
 *
 * The site runs Rank Math SEO in production, which already owns the
 * <title>, meta description, canonical, OG and Twitter tags for whatever
 * pages it's configured on. So the plain meta tags below only fire when
 * Rank Math isn't active — pure fallback, never a duplicate. The JSON-LD
 * schema always fires: Rank Math has no way to know about this theme's
 * custom post types (services, FAQs, team), so there's nothing to
 * collide with there.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iaempower_seo_org_logo_url() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return get_template_directory_uri() . '/assets/images/iaempower-icon.png';
}

function iaempower_seo_share_image_url() {
	$image = iaempower_mod( 'seo_share_image' );
	return $image ? $image : iaempower_seo_org_logo_url();
}

/**
 * Plain meta tags — skipped entirely when Rank Math (or any plugin that
 * defines this constant) is active, so we never emit a second
 * description/canonical/OG set alongside it.
 */
function iaempower_seo_meta_tags() {
	if ( defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}

	$description = is_front_page()
		? iaempower_mod( 'seo_meta_description' )
		: wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 30, '…' );

	if ( ! $description ) {
		$description = iaempower_mod( 'seo_meta_description' );
	}

	$title = wp_get_document_title();
	$url   = is_front_page() ? home_url( '/' ) : get_permalink();
	$image = iaempower_seo_share_image_url();

	echo "\n<!-- IAEMPOWER SEO fallback (Rank Math not detected) -->\n";
	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:type" content="' . ( is_front_page() ? 'website' : 'article' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'iaempower_seo_meta_tags', 1 );

/**
 * Builds the JSON-LD @graph for the front page: Organization (also
 * typed as ProfessionalService), one Service node per iaempower_service
 * post, one Person node per iaempower_team member, and a FAQPage built
 * from iaempower_faq posts.
 */
function iaempower_seo_front_page_graph() {
	/*
	 * Rank Math's own "SEO local" panel (Titres & Meta → SEO local) emits
	 * its own Organization/LocalBusiness node once configured — that's
	 * what the "Persona o empresa" / "Nombre de la web" / "Descripción"
	 * fields in wp-admin are for. Duplicating that node here would put
	 * two competing Organization entities on the same page, which is
	 * worse for E-E-A-T than not having ours at all. So the full node
	 * only appears when Rank Math isn't active; either way, Service and
	 * Person nodes reference the organization by a plain inline object
	 * instead of a shared @id, so they're valid on their own regardless
	 * of which branch ran.
	 */
	$org_ref = array(
		'@type' => 'Organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url( '/' ),
	);

	$graph = array();

	if ( ! defined( 'RANK_MATH_VERSION' ) ) {
		$org = array(
			'@type'     => array( 'Organization', 'ProfessionalService' ),
			'@id'       => home_url( '/#organization' ),
			'name'      => get_bloginfo( 'name' ),
			'url'       => home_url( '/' ),
			'logo'      => iaempower_seo_org_logo_url(),
			'image'     => iaempower_seo_share_image_url(),
			'telephone' => iaempower_mod( 'contact_phone1' ),
			'email'     => iaempower_mod( 'contact_email' ),
			'address'   => array(
				'@type'          => 'PostalAddress',
				'streetAddress'  => iaempower_mod( 'contact_address' ),
				'addressCountry' => 'ES',
			),
		);
		$social_links = array_values( iaempower_social_links() );
		if ( $social_links ) {
			$org['sameAs'] = $social_links;
		}
		$graph[] = $org;
	}

	$services = new WP_Query( array(
		'post_type'      => 'iaempower_service',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );
	while ( $services->have_posts() ) {
		$services->the_post();
		$graph[] = array(
			'@type'       => 'Service',
			'name'        => get_the_title(),
			'url'         => get_permalink(),
			'description' => wp_trim_words( wp_strip_all_tags( get_the_content() ), 40, '…' ),
			'provider'    => $org_ref,
			'areaServed'  => iaempower_seo_area_served(),
		);
	}
	wp_reset_postdata();

	$team = new WP_Query( array(
		'post_type'      => 'iaempower_team',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );
	while ( $team->have_posts() ) {
		$team->the_post();
		$photo_id = absint( iaempower_meta( get_the_ID(), 'photo' ) );
		$person   = array(
			'@type'    => 'Person',
			'name'     => get_the_title(),
			'jobTitle' => iaempower_meta( get_the_ID(), 'role' ),
			'worksFor' => $org_ref,
		);
		if ( $photo_id ) {
			$photo_url = wp_get_attachment_image_url( $photo_id, 'medium' );
			if ( $photo_url ) {
				$person['image'] = $photo_url;
			}
		}
		$graph[] = $person;
	}
	wp_reset_postdata();

	$faqs = new WP_Query( array(
		'post_type'      => 'iaempower_faq',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );
	if ( $faqs->have_posts() ) {
		$questions = array();
		while ( $faqs->have_posts() ) {
			$faqs->the_post();
			$questions[] = array(
				'@type'          => 'Question',
				'name'           => get_the_title(),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( get_the_content() ),
				),
			);
		}
		$graph[] = array(
			'@type'      => 'FAQPage',
			'mainEntity' => $questions,
		);
	}
	wp_reset_postdata();

	return $graph;
}

/**
 * Service area, in priority order per the business's own positioning
 * (not a ranking weight — schema.org has no such concept, areaServed is
 * just a flat list): Collado Villalba first (the physical office and
 * Google Business Profile location), then the affluent NW Madrid /
 * Sierra corridor the agency wants to reach, then Spain nationwide as a
 * secondary, lower-priority market that still shouldn't be excluded.
 */
function iaempower_seo_area_served() {
	return array(
		array( '@type' => 'City', 'name' => 'Collado Villalba' ),
		array( '@type' => 'City', 'name' => 'Majadahonda' ),
		array( '@type' => 'City', 'name' => 'Las Rozas de Madrid' ),
		array( '@type' => 'City', 'name' => 'Torrelodones' ),
		array( '@type' => 'AdministrativeArea', 'name' => 'Sierra de Guadarrama' ),
		array( '@type' => 'AdministrativeArea', 'name' => 'Sierra Norte de Madrid' ),
		array( '@type' => 'AdministrativeArea', 'name' => 'Comunidad de Madrid' ),
		array( '@type' => 'Country', 'name' => 'España' ),
	);
}

/**
 * BreadcrumbList for single posts (Inicio > título del artículo) — the
 * front page has no breadcrumb, it's the root.
 */
function iaempower_seo_breadcrumb_graph() {
	return array(
		array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(
				array(
					'@type'    => 'ListItem',
					'position' => 1,
					'name'     => 'Inicio',
					'item'     => home_url( '/' ),
				),
				array(
					'@type'    => 'ListItem',
					'position' => 2,
					'name'     => get_the_title(),
					'item'     => get_permalink(),
				),
			),
		),
	);
}

function iaempower_seo_jsonld() {
	if ( is_front_page() ) {
		$graph = iaempower_seo_front_page_graph();
	} elseif ( is_single() ) {
		$graph = iaempower_seo_breadcrumb_graph();
	} else {
		return;
	}

	echo '<script type="application/ld+json">'
		. wp_json_encode( array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'iaempower_seo_jsonld', 5 );
