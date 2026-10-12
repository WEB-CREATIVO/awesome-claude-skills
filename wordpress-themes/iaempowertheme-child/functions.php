<?php
/**
 * IAEMPOWER Child Theme - Functions
 *
 * Este archivo contiene:
 * - Registro de Custom Post Type "Servicios"
 * - Menú dinámico que incluye servicios
 * - Cambios permanentes que no se ven afectados por actualizaciones del padre
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ========== 1. REGISTRAR CUSTOM POST TYPE "SERVICIOS" ==========
 *
 * Permite crear páginas de servicios con URLs propias
 * Ej: /servicios/consultoria-chatgpt/
 */
function iaempower_child_register_servicios_cpt() {
	$labels = array(
		'name'                  => 'Servicios',
		'singular_name'         => 'Servicio',
		'menu_name'             => 'Servicios',
		'name_admin_bar'        => 'Servicio',
		'archives'              => 'Archivos de Servicios',
		'attributes'            => 'Atributos del Servicio',
		'parent_item_colon'     => 'Servicio Padre:',
		'all_items'             => 'Todos los Servicios',
		'add_new_item'          => 'Agregar Nuevo Servicio',
		'add_new'               => 'Agregar Nuevo',
		'new_item'              => 'Nuevo Servicio',
		'edit_item'             => 'Editar Servicio',
		'update_item'           => 'Actualizar Servicio',
		'view_item'             => 'Ver Servicio',
		'view_items'            => 'Ver Servicios',
		'search_items'          => 'Buscar Servicio',
		'not_found'             => 'No se encontraron servicios',
		'not_found_in_trash'    => 'No se encontraron servicios en la papelera',
		'featured_image'        => 'Imagen Destacada del Servicio',
		'set_featured_image'    => 'Establecer imagen destacada',
		'remove_featured_image' => 'Eliminar imagen destacada',
		'use_featured_image'    => 'Usar como imagen destacada',
		'insert_into_item'      => 'Insertar en servicio',
		'uploaded_to_this_item' => 'Cargado en este servicio',
		'items_list'            => 'Lista de servicios',
		'items_list_navigation' => 'Navegación de lista de servicios',
		'filter_items_list'     => 'Filtrar lista de servicios',
	);

	$args = array(
		'label'               => 'Servicios',
		'labels'              => $labels,
		'description'         => 'Servicios disponibles - Se muestran en menú dinámico',
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'servicios' ),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-briefcase',
		'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
		'show_in_rest'        => true, // Para Gutenberg
	);

	register_post_type( 'servicio', $args );
}
add_action( 'init', 'iaempower_child_register_servicios_cpt' );

/**
 * ========== 2. REGISTRAR TAXONOMÍA "CATEGORÍAS DE SERVICIOS" (OPCIONAL) ==========
 *
 * Permite agrupar servicios por categoría
 * Ej: Consultoría, Agencia Digital, E-commerce, etc.
 */
function iaempower_child_register_servicio_category() {
	$labels = array(
		'name'                       => 'Categorías de Servicios',
		'singular_name'              => 'Categoría de Servicio',
		'menu_name'                  => 'Categorías',
		'all_items'                  => 'Todas las Categorías',
		'parent_item'                => 'Categoría Padre',
		'parent_item_colon'          => 'Categoría Padre:',
		'new_item_name'              => 'Nuevo Nombre de Categoría',
		'add_new_item'               => 'Agregar Nueva Categoría',
		'edit_item'                  => 'Editar Categoría',
		'update_item'                => 'Actualizar Categoría',
		'view_item'                  => 'Ver Categoría',
		'separate_items_with_commas' => 'Separa categorías con comas',
		'add_or_remove_items'        => 'Agregar o eliminar categorías',
		'choose_from_most_used'      => 'Elige entre las categorías más usadas',
		'popular_items'              => 'Categorías Populares',
		'search_items'               => 'Buscar Categorías',
		'not_found'                  => 'No se encontraron categorías',
		'no_terms'                   => 'Sin categorías',
		'items_list'                 => 'Lista de categorías',
		'items_list_navigation'      => 'Navegación de lista de categorías',
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'categoria-servicio' ),
	);

	register_taxonomy( 'categoria_servicio', 'servicio', $args );
}
add_action( 'init', 'iaempower_child_register_servicio_category' );

/**
 * ========== 3. FLUSH REWRITE RULES AL ACTIVAR EL CHILD THEME ==========
 *
 * Asegura que las URLs de los servicios funcionen correctamente
 */
function iaempower_child_flush_rewrite_rules() {
	iaempower_child_register_servicios_cpt();
	iaempower_child_register_servicio_category();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'iaempower_child_flush_rewrite_rules' );

/**
 * ========== 4. AGREGAR SERVICIOS AL MENÚ DINÁMICO ==========
 *
 * Modifica el menú principal para incluir un enlace a servicios
 * y opcionalmente enlaces a cada servicio individual
 */
function iaempower_child_custom_nav_menu( $items, $menu, $args ) {
	// Solo modificar el menú principal
	if ( $menu->name !== 'Menú principal (anclas de la página de inicio)' ) {
		return $items;
	}

	// Obtener servicios publicados
	$servicios = get_posts( array(
		'post_type'      => 'servicio',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );

	// Si hay servicios, agregar un enlace a cada uno
	if ( ! empty( $servicios ) ) {
		foreach ( $servicios as $servicio ) {
			$link = sprintf(
				'<li><a href="%s">%s</a></li>',
				esc_url( get_permalink( $servicio->ID ) ),
				esc_html( $servicio->post_title )
			);
			$items .= $link;
		}
	}

	return $items;
}
// COMENTADO POR DEFECTO - Descomenta cuando quieras activar
// add_filter( 'wp_nav_menu_items', 'iaempower_child_custom_nav_menu', 10, 3 );

/**
 * ========== 5. ALTERNATIVA: WIDGET CUSTOM PARA MOSTRAR SERVICIOS ==========
 *
 * En lugar de modificar el menú, puedes usar un widget para mostrar servicios
 * Más seguro y flexible
 */
function iaempower_child_register_servicios_widget() {
	if ( ! function_exists( 'register_widget' ) ) {
		return;
	}

	class Servicios_Widget extends WP_Widget {
		public function __construct() {
			parent::__construct(
				'iaempower_servicios_widget',
				'Servicios IAEMPOWER',
				array( 'description' => 'Muestra lista de servicios' )
			);
		}

		public function widget( $args, $instance ) {
			echo $args['before_widget'];
			echo $args['before_title'] . 'Nuestros Servicios' . $args['after_title'];

			$servicios = get_posts( array(
				'post_type'      => 'servicio',
				'posts_per_page' => -1,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'post_status'    => 'publish',
			) );

			if ( ! empty( $servicios ) ) {
				echo '<ul>';
				foreach ( $servicios as $servicio ) {
					printf(
						'<li><a href="%s">%s</a></li>',
						esc_url( get_permalink( $servicio->ID ) ),
						esc_html( $servicio->post_title )
					);
				}
				echo '</ul>';
			}

			echo $args['after_widget'];
		}

		public function form( $instance ) {
			// Formulario del widget (opcional)
		}

		public function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
	}

	register_widget( 'Servicios_Widget' );
}
add_action( 'widgets_init', 'iaempower_child_register_servicios_widget' );

/**
 * ========== 6. HOOK PARA DEBUG (OPCIONAL) ==========
 *
 * Descomenta para verificar que el child theme está activo
 */
// add_action( 'wp_footer', function() {
// 	if ( current_user_can( 'manage_options' ) ) {
// 		echo '<!-- IAEMPOWER Child Theme Activo -->';
// 	}
// } );
