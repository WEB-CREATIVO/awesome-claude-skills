<?php
/**
 * Drag-and-drop reordering for the 5 custom post types, right from
 * their list screens (Equipo, Servicios, Casos de éxito, Planes de
 * precio, Preguntas frecuentes) — no plugin needed. Front-page.php
 * already queries every one of these with orderby => menu_order, so
 * dragging a row here is exactly "sube o baja este miembro/servicio/
 * plan en la página".
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iaempower_orderable_post_types() {
	return array( 'iaempower_service', 'iaempower_case', 'iaempower_team', 'iaempower_price', 'iaempower_faq' );
}

/**
 * The list screen defaults to newest-first; switch it to the same
 * menu_order the front page uses, so what you see is what you can drag.
 */
function iaempower_admin_list_order( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( isset( $_GET['orderby'] ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || $screen->base !== 'edit' ) {
		return;
	}
	if ( in_array( $screen->post_type, iaempower_orderable_post_types(), true ) ) {
		$query->set( 'orderby', 'menu_order title' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'iaempower_admin_list_order' );

/**
 * A narrow drag-handle column, right after the checkbox, on each of
 * the 5 list screens.
 */
function iaempower_reorder_add_handle_column( $columns ) {
	$new = array();
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( $key === 'cb' ) {
			$new['iaempower_order'] = '';
		}
	}
	return $new;
}

function iaempower_reorder_render_handle_column( $column, $post_id ) {
	if ( $column === 'iaempower_order' ) {
		echo '<span class="iaempower-drag-handle dashicons dashicons-menu" title="Arrastra para reordenar" style="cursor:grab;color:#a7aaad;"></span>';
	}
}

function iaempower_reorder_register_columns() {
	foreach ( iaempower_orderable_post_types() as $post_type ) {
		add_filter( "manage_edit-{$post_type}_columns", 'iaempower_reorder_add_handle_column' );
		add_action( "manage_{$post_type}_posts_custom_column", 'iaempower_reorder_render_handle_column', 10, 2 );
	}
}
add_action( 'admin_init', 'iaempower_reorder_register_columns' );

/**
 * jQuery UI Sortable (bundled with WP core) on the list table body,
 * posting the new order back via admin-ajax on drop.
 */
function iaempower_reorder_assets( $hook ) {
	if ( $hook !== 'edit.php' ) {
		return;
	}
	$post_type = isset( $_GET['post_type'] ) ? sanitize_key( $_GET['post_type'] ) : 'post';
	if ( ! in_array( $post_type, iaempower_orderable_post_types(), true ) ) {
		return;
	}

	wp_enqueue_script( 'jquery-ui-sortable' );

	$data = array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'iaempower_reorder' ),
	);

	$script = 'var IAEMPOWER_REORDER = ' . wp_json_encode( $data ) . ";\n" . <<<'JS'
jQuery( function( $ ) {
	var list = $( '#the-list' );
	if ( ! list.length ) { return; }
	list.sortable( {
		items: 'tr',
		axis: 'y',
		cursor: 'grabbing',
		handle: '.iaempower-drag-handle',
		helper: function( e, tr ) {
			tr.children().each( function() { $( this ).width( $( this ).width() ); } );
			return tr;
		},
		update: function() {
			var ids = list.children( 'tr' ).map( function() {
				return $( this ).attr( 'id' ).replace( 'post-', '' );
			} ).get();
			$.post( IAEMPOWER_REORDER.ajaxUrl, {
				action: 'iaempower_reorder',
				nonce: IAEMPOWER_REORDER.nonce,
				order: ids
			} );
		}
	} );
} );
JS;

	wp_register_script( 'iaempower-reorder', false, array( 'jquery', 'jquery-ui-sortable' ), IAEMPOWER_VERSION, true );
	wp_enqueue_script( 'iaempower-reorder' );
	wp_add_inline_script( 'iaempower-reorder', $script );
}
add_action( 'admin_enqueue_scripts', 'iaempower_reorder_assets' );

function iaempower_ajax_reorder() {
	check_ajax_referer( 'iaempower_reorder', 'nonce' );
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error();
	}
	global $wpdb;
	$order = isset( $_POST['order'] ) ? (array) $_POST['order'] : array();
	foreach ( $order as $position => $post_id ) {
		$post_id = absint( $post_id );
		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			continue;
		}
		$wpdb->update( $wpdb->posts, array( 'menu_order' => (int) $position ), array( 'ID' => $post_id ) );
		clean_post_cache( $post_id );
	}
	wp_send_json_success();
}
add_action( 'wp_ajax_iaempower_reorder', 'iaempower_ajax_reorder' );
