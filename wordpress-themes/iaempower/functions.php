<?php
/**
 * IAEMPOWER theme setup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'IAEMPOWER_VERSION', '1.13.0' );

require get_template_directory() . '/inc/cpts.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/contact-form.php';
require get_template_directory() . '/inc/seed-content.php';
require get_template_directory() . '/inc/reorder.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/whatsapp.php';
require get_template_directory() . '/inc/menu.php';

function iaempower_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 60,
		'width'       => 60,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus( array(
		'primary' => 'Menú principal (anclas de la página de inicio)',
		'footer'  => 'Menú del pie de página',
	) );
}
add_action( 'after_setup_theme', 'iaempower_setup' );

/**
 * Splits a post/page title at its last colon so the part after it gets
 * the same gradient "em" treatment as the hero headline on the front
 * page — mirrors how titles are already written ("Tema: subtítulo")
 * instead of needing a separate field to mark the highlighted part by
 * hand. Titles without a colon render exactly as before.
 */
function iaempower_title_with_emphasis( $title ) {
	$pos = strrpos( $title, ':' );
	if ( $pos === false ) {
		return esc_html( $title );
	}
	$lead = substr( $title, 0, $pos + 1 );
	$rest = trim( substr( $title, $pos + 1 ) );
	if ( $rest === '' ) {
		return esc_html( $title );
	}
	return esc_html( $lead ) . ' <em>' . esc_html( $rest ) . '</em>';
}

/**
 * Cache-busting: use each file's own last-modified time as its version
 * instead of a hand-maintained constant. That constant only changes
 * when someone remembers to bump it — this way every theme update
 * (including ones delivered by overwriting the theme's files directly,
 * without reactivating anything) forces browsers and caching plugins
 * like WP Rocket to fetch the new CSS/JS instead of serving a stale
 * cached copy.
 */
function iaempower_asset_version( $relative_path ) {
	$file = get_template_directory() . $relative_path;
	return file_exists( $file ) ? filemtime( $file ) : IAEMPOWER_VERSION;
}

/**
 * Anchors like #servicios only work on the front page, where those
 * section ids actually exist. On any other page (a blog post, a
 * static Aviso Legal page…) the same bare href just rewrites the
 * current page's hash without navigating anywhere — the link looks
 * broken because there's nothing on that page for it to jump to.
 * Prefixing the home URL there sends the visitor back to the front
 * page and lands on the right section once it loads.
 */
function iaempower_anchor_url( $anchor ) {
	return is_front_page() ? $anchor : home_url( '/' ) . $anchor;
}

/**
 * URL of the real blog archive (all posts, paginated) — the "Página de
 * entradas" set in Ajustes → Lectura. Falls back to the Recursos preview
 * on the front page if that hasn't been configured yet, so the nav link
 * never points nowhere.
 */
function iaempower_blog_url() {
	$page_for_posts = (int) get_option( 'page_for_posts' );
	if ( $page_for_posts ) {
		return get_permalink( $page_for_posts );
	}
	return iaempower_anchor_url( '#recursos' );
}

/**
 * Configured social profile URLs, keyed by platform — only the ones the
 * admin has actually filled in (Personalizar → Redes sociales). Used for
 * the footer icons and for the Organization schema's `sameAs`, so both
 * stay in sync from one place.
 */
function iaempower_social_links() {
	$platforms = array(
		'linkedin'  => iaempower_mod( 'social_linkedin' ),
		'instagram' => iaempower_mod( 'social_instagram' ),
		'tiktok'    => iaempower_mod( 'social_tiktok' ),
	);
	return array_filter( $platforms );
}

function iaempower_assets() {
	wp_enqueue_style( 'iaempower-style', get_stylesheet_uri(), array(), iaempower_asset_version( '/style.css' ) );
	wp_enqueue_script( 'iaempower-main', get_template_directory_uri() . '/assets/js/main.js', array(), iaempower_asset_version( '/assets/js/main.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'iaempower_assets' );

/**
 * The theme is built around a single front page — nudge a fresh install
 * in the right direction instead of showing the default blog loop.
 */
function iaempower_maybe_suggest_static_front_page() {
	if ( get_option( 'show_on_front' ) !== 'page' && current_user_can( 'manage_options' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-warning"><p><strong>IAEMPOWER:</strong> este tema muestra la página de inicio con <code>front-page.php</code>. Ve a Ajustes → Lectura y elige "Una página estática" (puede ser cualquier página, la plantilla la genera el tema) para que se muestre correctamente.</p></div>';
		} );
	}
}
add_action( 'admin_init', 'iaempower_maybe_suggest_static_front_page' );

/**
 * The "Blog" nav link needs a real archive page to point to. Without a
 * "Página de entradas" configured it silently falls back to the Recursos
 * preview anchor instead — nudge the admin to finish the one-time setup.
 */
function iaempower_maybe_suggest_posts_page() {
	if ( get_option( 'show_on_front' ) === 'page' && ! get_option( 'page_for_posts' ) && current_user_can( 'manage_options' ) ) {
		add_action( 'admin_notices', function () {
			echo '<div class="notice notice-warning"><p><strong>IAEMPOWER:</strong> el enlace "Blog" del menú necesita una página de entradas. Crea una página (por ejemplo, "Blog") y asígnala en Ajustes → Lectura → "Página de entradas" para que muestre todos tus artículos.</p></div>';
		} );
	}
}
add_action( 'admin_init', 'iaempower_maybe_suggest_posts_page' );

/**
 * Blog posts (Recursos) look best with a short excerpt — trim it if the
 * author didn't set one manually.
 */
function iaempower_excerpt_length( $length ) {
	return 22;
}
add_filter( 'excerpt_length', 'iaempower_excerpt_length' );

function iaempower_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'iaempower_excerpt_more' );

/**
 * Fallback avatar illustration (a small SVG "mountain" pattern) used on
 * team cards when no featured image has been set.
 */
function iaempower_team_avatar_svg( $seed ) {
	$patterns = array(
		'0,150 30,90 55,120 85,60 120,110 150,75 200,150',
		'0,150 40,100 70,130 100,70 140,115 200,150',
		'0,150 25,110 60,140 95,80 130,120 165,90 200,150',
	);
	$points = $patterns[ $seed % count( $patterns ) ];
	return '<svg viewBox="0 0 200 150" preserveAspectRatio="xMidYMid slice" aria-hidden="true">'
		. '<rect width="200" height="150" fill="var(--ground-2)"></rect>'
		. '<polygon points="' . esc_attr( $points ) . '" fill="var(--ground)" opacity=".7"></polygon>'
		. '<circle cx="' . ( 40 + ( $seed * 55 ) % 130 ) . '" cy="40" r="18" fill="var(--accent)" opacity=".18"></circle>'
		. '</svg>';
}

/**
 * Minimal line icons for the footer's social links — same stroke style
 * as the service icons, kept in-house instead of pulling in an icon
 * font/library for three glyphs.
 */
function iaempower_social_icon_svg( $platform ) {
	$icons = array(
		'linkedin'  => '<rect x="3" y="9" width="4" height="12"></rect><circle cx="5" cy="4" r="2"></circle><path d="M11 21v-8M11 13c0-2.2 1.8-4 4-4s4 1.8 4 4v8"></path>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><circle cx="17.5" cy="6.5" r=".8" fill="currentColor" stroke="none"></circle>',
		'tiktok'    => '<path d="M14 3v10.8a3.6 3.6 0 1 1-3.2-3.58"></path><path d="M14 3c.45 2.55 2.3 4.4 5 4.75"></path>',
	);
	if ( ! isset( $icons[ $platform ] ) ) {
		return '';
	}
	return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">' . $icons[ $platform ] . '</svg>';
}

/**
 * The six service icons used by iaempower_service posts (selected per
 * post via the "icon" metabox field — see inc/cpts.php).
 */
function iaempower_service_icon_svg( $key ) {
	$icons = array(
		'agent' => '<circle cx="12" cy="7" r="3.2"></circle><path d="M5 20c0-3.9 3.13-7 7-7s7 3.1 7 7"></path><path d="M19 8.5c1.4.5 2.4 1.8 2.4 3.4"></path><path d="M5 8.5c-1.4.5-2.4 1.8-2.4 3.4"></path>',
		'automation' => '<path d="M4 6h11a3 3 0 013 3v1"></path><path d="M15 7l3 3-3 3"></path><path d="M20 18H9a3 3 0 01-3-3v-1"></path><path d="M9 20l-3-3 3-3"></path>',
		'chat' => '<path d="M4 5h16v10H8l-4 4V5z"></path><path d="M8 9h8M8 12h5"></path>',
		'strategy' => '<path d="M12 3l8 4-8 4-8-4 8-4z"></path><path d="M4 11l8 4 8-4"></path><path d="M4 16l8 4 8-4"></path>',
		'integration' => '<rect x="4" y="4" width="7" height="7" rx=".5"></rect><rect x="13" y="4" width="7" height="7" rx=".5"></rect><rect x="4" y="13" width="7" height="7" rx=".5"></rect><rect x="13" y="13" width="7" height="7" rx=".5"></rect>',
		'generative' => '<path d="M12 2v4M12 18v4M4.9 4.9l2.8 2.8M16.3 16.3l2.8 2.8M2 12h4M18 12h4M4.9 19.1l2.8-2.8M16.3 7.7l2.8-2.8"></path><circle cx="12" cy="12" r="3.4"></circle>',
	);
	$path = isset( $icons[ $key ] ) ? $icons[ $key ] : $icons['agent'];
	return '<svg class="service-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" aria-hidden="true">' . $path . '</svg>';
}

/**
 * ANALYTICS CLEAN URLs
 * Rastrear TODOS los clicks sin parámetros GA4 en la URL
 * Mantiene tracking completo en Google Analytics 4
 */
function iaempower_enqueue_analytics_clean() {
	wp_enqueue_script(
		'iaempower-analytics-clean',
		get_template_directory_uri() . '/assets/js/analytics-clean.js',
		array(),
		IAEMPOWER_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'iaempower_enqueue_analytics_clean' );
