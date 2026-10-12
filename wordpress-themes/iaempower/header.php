<?php
/**
 * The header: opens the document, prints the sticky nav, and opens
 * <main id="main"> — closed together with everything else in footer.php.
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" lang="es">
<div id="grain"></div>
<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>
<a class="skip-link" href="#main">Saltar al contenido</a>

<header class="site-header" data-header>
	<div class="header-inner">
		<?php
		/**
		 * LOGO EDITABLE - CAMBIAR AQUÍ
		 * Reemplaza la URL del logo según necesites
		 * Formato: https://dominio.com/ruta/al/logo.png (con transparencia)
		 */
		$iaempower_logo_url = 'https://iaempoweragency.com/wp-content/uploads/2026/09/LOGO-IAEMPOWER-TRANSPARENTE.png';
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="brand" data-gtag="click" data-gtag-event="logo_click">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<img class="brand-logo" src="<?php echo esc_url( $iaempower_logo_url ); ?>" alt="<?php bloginfo( 'name' ); ?>" loading="lazy">
			<?php endif; ?>
		</a>

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav class="main-nav" aria-label="Principal">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => false,
					'items_wrap'     => '<ul>%3$s</ul>',
					'link_before'    => '',
					'add_li_class'   => '',
					'depth'          => 1,
				) );
				?>
			</nav>
		<?php else : ?>
			<nav class="main-nav" aria-label="Principal">
				<ul>
					<li><a href="<?php echo esc_url( iaempower_anchor_url( '#servicios' ) ); ?>" data-nav>Servicios</a></li>
					<li><a href="<?php echo esc_url( iaempower_anchor_url( '#casos' ) ); ?>" data-nav>Casos</a></li>
					<li><a href="<?php echo esc_url( iaempower_anchor_url( '#equipo' ) ); ?>" data-nav>Equipo</a></li>
					<li><a href="<?php echo esc_url( iaempower_anchor_url( '#precios' ) ); ?>" data-nav>Precios</a></li>
					<li><a href="<?php echo esc_url( iaempower_anchor_url( '#recursos' ) ); ?>" data-nav>Recursos</a></li>
					<li><a href="<?php echo esc_url( iaempower_blog_url() ); ?>">Blog</a></li>
				</ul>
			</nav>
		<?php endif; ?>

		<a href="<?php echo esc_url( iaempower_anchor_url( '#contacto' ) ); ?>" class="btn btn-accent nav-cta" data-magnetic>Contacto</a>
		<button class="menu-toggle" id="menuToggle" aria-expanded="false" aria-controls="mobileNav" aria-label="Abrir menú">
			<span></span>
		</button>
	</div>
	<nav class="mobile-nav" id="mobileNav" aria-label="Móvil">
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'items_wrap'     => '<ul>%3$s<li><a href="' . str_replace( '%', '%%', esc_url( iaempower_anchor_url( '#contacto' ) ) ) . '" data-nav>Contacto</a></li></ul>',
				'depth'          => 1,
			) );
			?>
		<?php else : ?>
		<ul>
			<li><a href="<?php echo esc_url( iaempower_anchor_url( '#servicios' ) ); ?>" data-nav>Servicios</a></li>
			<li><a href="<?php echo esc_url( iaempower_anchor_url( '#casos' ) ); ?>" data-nav>Casos de éxito</a></li>
			<li><a href="<?php echo esc_url( iaempower_anchor_url( '#equipo' ) ); ?>" data-nav>Equipo</a></li>
			<li><a href="<?php echo esc_url( iaempower_anchor_url( '#precios' ) ); ?>" data-nav>Precios</a></li>
			<li><a href="<?php echo esc_url( iaempower_anchor_url( '#recursos' ) ); ?>" data-nav>Recursos</a></li>
			<li><a href="<?php echo esc_url( iaempower_blog_url() ); ?>">Blog</a></li>
			<li><a href="<?php echo esc_url( iaempower_anchor_url( '#contacto' ) ); ?>" data-nav>Contacto</a></li>
		</ul>
		<?php endif; ?>
	</nav>
</header>

<main id="main">
