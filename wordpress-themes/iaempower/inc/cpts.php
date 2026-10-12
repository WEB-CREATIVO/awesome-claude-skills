<?php
/**
 * Custom post types for repeatable content (servicios, casos, equipo, precios, faq)
 * and the generic metabox system that powers their editable fields.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iaempower_register_post_types() {

	register_post_type( 'iaempower_service', array(
		'label'        => 'Servicios',
		'labels'       => array(
			'name'          => 'Servicios',
			'singular_name' => 'Servicio',
			'add_new_item'  => 'Añadir servicio',
			'edit_item'     => 'Editar servicio',
		),
		'public'       => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-admin-generic',
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => array( 'slug' => 'servicios', 'with_front' => false ),
		'show_in_rest' => false,
	) );

	register_post_type( 'iaempower_case', array(
		'label'        => 'Casos de éxito',
		'labels'       => array(
			'name'          => 'Casos de éxito',
			'singular_name' => 'Caso de éxito',
			'add_new_item'  => 'Añadir caso de éxito',
			'edit_item'     => 'Editar caso de éxito',
		),
		'public'       => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-chart-line',
		'supports'     => array( 'title', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => false,
		'show_in_rest' => false,
	) );

	register_post_type( 'iaempower_team', array(
		'label'        => 'Equipo',
		'labels'       => array(
			'name'          => 'Equipo',
			'singular_name' => 'Miembro del equipo',
			'add_new_item'  => 'Añadir miembro del equipo',
			'edit_item'     => 'Editar miembro del equipo',
		),
		'public'       => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-groups',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => false,
		'show_in_rest' => false,
	) );

	register_post_type( 'iaempower_price', array(
		'label'        => 'Planes de precio',
		'labels'       => array(
			'name'          => 'Planes de precio',
			'singular_name' => 'Plan de precio',
			'add_new_item'  => 'Añadir plan',
			'edit_item'     => 'Editar plan',
		),
		'public'       => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-tag',
		'supports'     => array( 'title', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => false,
		'show_in_rest' => false,
	) );

	register_post_type( 'iaempower_faq', array(
		'label'        => 'Preguntas frecuentes',
		'labels'       => array(
			'name'          => 'Preguntas frecuentes',
			'singular_name' => 'Pregunta',
			'add_new_item'  => 'Añadir pregunta',
			'edit_item'     => 'Editar pregunta',
		),
		'public'       => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-editor-help',
		'supports'     => array( 'title', 'editor', 'page-attributes' ),
		'has_archive'  => false,
		'rewrite'      => false,
		'show_in_rest' => false,
	) );
}
add_action( 'init', 'iaempower_register_post_types' );

/**
 * Data-driven metabox fields per post type. Adding a field here is enough —
 * the render/save functions below handle any post type listed.
 */
function iaempower_meta_fields() {
	$service_icons = array(
		'agent'       => 'Agentes de IA',
		'automation'  => 'Automatización de procesos',
		'chat'        => 'Chatbots y atención al cliente',
		'strategy'    => 'Consultoría estratégica',
		'integration' => 'Integración y datos',
		'generative'  => 'IA generativa aplicada',
	);

	return array(
		'iaempower_service' => array(
			array( 'id' => 'icon', 'label' => 'Icono', 'type' => 'select', 'options' => $service_icons ),
		),
		'iaempower_case'    => array(
			array( 'id' => 'sector', 'label' => 'Sector', 'type' => 'text' ),
			array( 'id' => 'reto', 'label' => 'Reto', 'type' => 'textarea' ),
			array( 'id' => 'solucion', 'label' => 'Solución', 'type' => 'textarea' ),
			array( 'id' => 'metric_prefix', 'label' => 'Prefijo de la métrica (opcional, ej. −)', 'type' => 'text' ),
			array( 'id' => 'metric_value', 'label' => 'Valor numérico de la métrica', 'type' => 'text' ),
			array( 'id' => 'metric_suffix', 'label' => 'Sufijo (ej. %, h)', 'type' => 'text' ),
			array( 'id' => 'metric_label', 'label' => 'Descripción de la métrica', 'type' => 'text' ),
		),
		'iaempower_team'    => array(
			array( 'id' => 'role', 'label' => 'Cargo', 'type' => 'text' ),
			array( 'id' => 'photo', 'label' => 'Foto (si no subes ninguna, se usa una ilustración genérica)', 'type' => 'image' ),
		),
		'iaempower_price'   => array(
			array( 'id' => 'tag_label', 'label' => 'Etiqueta superior (ej. Impulso)', 'type' => 'text' ),
			array( 'id' => 'tagline', 'label' => 'Frase corta', 'type' => 'text' ),
			array( 'id' => 'amount', 'label' => 'Precio (ej. 1.200 € o A medida)', 'type' => 'text' ),
			array( 'id' => 'amount_unit', 'label' => 'Unidad (ej. / proyecto)', 'type' => 'text' ),
			array( 'id' => 'featured', 'label' => 'Destacar este plan', 'type' => 'checkbox' ),
			array( 'id' => 'features', 'label' => 'Características (una por línea)', 'type' => 'textarea' ),
			array( 'id' => 'cta_label', 'label' => 'Texto del botón', 'type' => 'text' ),
		),
	);
}

function iaempower_add_metaboxes() {
	foreach ( iaempower_meta_fields() as $post_type => $fields ) {
		add_meta_box(
			'iaempower_fields_' . $post_type,
			'Detalles',
			'iaempower_render_metabox',
			$post_type,
			'normal',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'iaempower_add_metaboxes' );

function iaempower_render_metabox( $post ) {
	$fields = iaempower_meta_fields();
	$post_type = $post->post_type;
	if ( empty( $fields[ $post_type ] ) ) {
		return;
	}
	wp_nonce_field( 'iaempower_save_meta_' . $post->ID, 'iaempower_meta_nonce' );
	$has_image_field = false;
	echo '<table class="form-table">';
	foreach ( $fields[ $post_type ] as $field ) {
		$key   = '_iaempower_' . $field['id'];
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr><th style="text-align:left;padding:8px 8px 8px 0;vertical-align:top;"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td style="padding:8px 0;">';
		switch ( $field['type'] ) {
			case 'textarea':
				echo '<textarea style="width:100%;max-width:520px;" rows="4" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">' . esc_textarea( $value ) . '</textarea>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1" ' . checked( $value, '1', false ) . '>';
				break;
			case 'select':
				echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '">';
				foreach ( $field['options'] as $opt_value => $opt_label ) {
					echo '<option value="' . esc_attr( $opt_value ) . '" ' . selected( $value, $opt_value, false ) . '>' . esc_html( $opt_label ) . '</option>';
				}
				echo '</select>';
				break;
			case 'image':
				$has_image_field = true;
				$image_url = $value ? wp_get_attachment_image_url( $value, 'medium' ) : '';
				echo '<div class="iaempower-image-field" data-input="' . esc_attr( $key ) . '">';
				echo '<div class="iaempower-image-preview" style="width:120px;height:120px;border:1px solid #dcdcde;border-radius:4px;overflow:hidden;background:#f0f0f1;display:flex;align-items:center;justify-content:center;margin-bottom:8px;' . ( $image_url ? '' : 'display:none;' ) . '">';
				echo '<img src="' . esc_url( $image_url ) . '" style="width:100%;height:100%;object-fit:cover;">';
				echo '</div>';
				echo '<input type="hidden" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
				echo '<button type="button" class="button iaempower-image-select">' . ( $image_url ? 'Cambiar foto' : 'Elegir foto' ) . '</button> ';
				echo '<button type="button" class="button iaempower-image-remove" style="' . ( $image_url ? '' : 'display:none;' ) . '">Quitar foto</button>';
				echo '</div>';
				break;
			default:
				echo '<input type="text" style="width:100%;max-width:520px;" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '">';
		}
		echo '</td></tr>';
	}
	echo '</table>';

	if ( $has_image_field ) {
		wp_enqueue_media();
		?>
		<script>
		( function() {
			document.querySelectorAll( '.iaempower-image-field' ).forEach( function( field ) {
				var input   = field.querySelector( 'input[type="hidden"]' );
				var preview = field.querySelector( '.iaempower-image-preview' );
				var img     = preview.querySelector( 'img' );
				var selectBtn = field.querySelector( '.iaempower-image-select' );
				var removeBtn = field.querySelector( '.iaempower-image-remove' );
				var frame;

				selectBtn.addEventListener( 'click', function( e ) {
					e.preventDefault();
					if ( frame ) { frame.open(); return; }
					frame = wp.media( { title: 'Elegir foto', multiple: false, library: { type: 'image' } } );
					frame.on( 'select', function() {
						var attachment = frame.state().get( 'selection' ).first().toJSON();
						var url = ( attachment.sizes && attachment.sizes.medium ) ? attachment.sizes.medium.url : attachment.url;
						input.value = attachment.id;
						img.src = url;
						preview.style.display = 'flex';
						selectBtn.textContent = 'Cambiar foto';
						removeBtn.style.display = '';
					} );
					frame.open();
				} );

				removeBtn.addEventListener( 'click', function( e ) {
					e.preventDefault();
					input.value = '';
					img.src = '';
					preview.style.display = 'none';
					selectBtn.textContent = 'Elegir foto';
					removeBtn.style.display = 'none';
				} );
			} );
		}() );
		</script>
		<?php
	}
}

function iaempower_save_metabox( $post_id ) {
	if ( ! isset( $_POST['iaempower_meta_nonce'] ) || ! wp_verify_nonce( $_POST['iaempower_meta_nonce'], 'iaempower_save_meta_' . $post_id ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	$fields    = iaempower_meta_fields();
	if ( empty( $fields[ $post_type ] ) ) {
		return;
	}

	foreach ( $fields[ $post_type ] as $field ) {
		$key = '_iaempower_' . $field['id'];
		if ( $field['type'] === 'checkbox' ) {
			update_post_meta( $post_id, $key, isset( $_POST[ $key ] ) ? '1' : '' );
			continue;
		}
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = wp_unslash( $_POST[ $key ] );
		if ( $field['type'] === 'image' ) {
			$clean = absint( $raw );
		} elseif ( $field['type'] === 'textarea' ) {
			$clean = sanitize_textarea_field( $raw );
		} else {
			$clean = sanitize_text_field( $raw );
		}
		update_post_meta( $post_id, $key, $clean );
	}
}
add_action( 'save_post', 'iaempower_save_metabox' );

/**
 * Small helper used by templates: get an _iaempower_ meta value with a fallback.
 */
function iaempower_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, '_iaempower_' . $key, true );
	return ( $value === '' || $value === false ) ? $default : $value;
}
