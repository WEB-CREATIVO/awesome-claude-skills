<?php
/**
 * Editable main menu (Apariencia → Menús) and service page URLs.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu links written as bare anchors ("#servicios") behave like the
 * hardcoded ones: they jump back to the front page from inner pages and
 * keep the scroll-spy highlight (data-nav) on the front page.
 */
function iaempower_menu_link_attributes( $atts, $item, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $atts;
	}
	$href = isset( $atts['href'] ) ? $atts['href'] : '';
	if ( strpos( $href, '#' ) === 0 && strlen( $href ) > 1 ) {
		$atts['href']     = iaempower_anchor_url( $href );
		$atts['data-nav'] = 'true';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'iaempower_menu_link_attributes', 10, 3 );

/**
 * Assigning a menu to "Menú principal" replaces the built-in links, so an
 * admin adding one page would silently lose Servicios, Casos… Create the
 * menu once with exactly the current links, so adding a page is the only
 * change. Never touches a menu the admin already assigned or created.
 */
function iaempower_seed_primary_menu() {
	if ( get_option( 'iaempower_primary_menu_seeded' ) || has_nav_menu( 'primary' ) ) {
		return;
	}
	update_option( 'iaempower_primary_menu_seeded', 1 );

	$name = 'Menú principal';
	if ( wp_get_nav_menu_object( $name ) ) {
		return;
	}
	$menu_id = wp_create_nav_menu( $name );
	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$anchors = array(
		'Servicios' => '#servicios',
		'Casos'     => '#casos',
		'Equipo'    => '#equipo',
		'Precios'   => '#precios',
		'Recursos'  => '#recursos',
	);
	$position = 1;
	foreach ( $anchors as $title => $url ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'    => $title,
			'menu-item-url'      => $url,
			'menu-item-type'     => 'custom',
			'menu-item-status'   => 'publish',
			'menu-item-position' => $position++,
		) );
	}

	$blog_page = (int) get_option( 'page_for_posts' );
	if ( $blog_page ) {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'     => 'Blog',
			'menu-item-object'    => 'page',
			'menu-item-object-id' => $blog_page,
			'menu-item-type'      => 'post_type',
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position,
		) );
	} else {
		wp_update_nav_menu_item( $menu_id, 0, array(
			'menu-item-title'    => 'Blog',
			'menu-item-url'      => '#recursos',
			'menu-item-type'     => 'custom',
			'menu-item-status'   => 'publish',
			'menu-item-position' => $position,
		) );
	}

	$locations            = get_theme_mod( 'nav_menu_locations', array() );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}
add_action( 'admin_init', 'iaempower_seed_primary_menu' );

/**
 * Service URLs (/servicios/…) need WordPress's rewrite rules rebuilt once
 * after this version is installed by overwriting files over FTP, since no
 * theme activation happens then.
 */
function iaempower_maybe_flush_rewrites() {
	if ( get_option( 'iaempower_rewrite_version' ) !== IAEMPOWER_VERSION ) {
		flush_rewrite_rules( false );
		update_option( 'iaempower_rewrite_version', IAEMPOWER_VERSION );
	}
}
add_action( 'init', 'iaempower_maybe_flush_rewrites', 99 );
