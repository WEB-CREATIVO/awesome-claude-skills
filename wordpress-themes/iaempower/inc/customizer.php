<?php
/**
 * Customizer settings for the page copy that isn't repeatable content
 * (hero, manifesto band, the four value pillars, the antes/despues
 * lists and the contact details). Repeatable content lives in the
 * custom post types instead — see inc/cpts.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iaempower_customizer_fields() {
	return array(
		'iaempower_hero' => array(
			'label'    => 'Hero',
			'settings' => array(
				'hero_eyebrow'           => array( 'label' => 'Texto pequeño superior', 'type' => 'text', 'default' => 'IAEMPOWER · Collado Villalba, Madrid · Consultoría e ingeniería de IA' ),
				'hero_headline_plain'    => array( 'label' => 'Titular (parte normal)', 'type' => 'text', 'default' => 'Transforma el caos operativo en' ),
				'hero_headline_emphasis' => array( 'label' => 'Titular (parte con degradado)', 'type' => 'text', 'default' => 'crecimiento predecible' ),
				'hero_lede'              => array( 'label' => 'Subtítulo', 'type' => 'textarea', 'default' => 'En IAEMPOWER automatizamos los procesos que frenan tu negocio. Reducimos costes, liberamos a tu equipo de tareas manuales y escalamos tu operación sin fricción — con un retorno medible desde el primer mes.' ),
				'hero_cta_primary'       => array( 'label' => 'Botón principal', 'type' => 'text', 'default' => 'Quiero optimizar mi operación' ),
				'hero_cta_secondary'     => array( 'label' => 'Botón secundario', 'type' => 'text', 'default' => 'Ver cómo lo hacemos' ),
				'hero_stat1_value'       => array( 'label' => 'Estadística 1 — valor', 'type' => 'text', 'default' => '85', 'no_partial' => true ),
				'hero_stat1_suffix'      => array( 'label' => 'Estadística 1 — sufijo', 'type' => 'text', 'default' => 'h', 'no_partial' => true ),
				'hero_stat1_label'       => array( 'label' => 'Estadística 1 — descripción', 'type' => 'text', 'default' => 'Horas/mes liberadas de media' ),
				'hero_stat2_value'       => array( 'label' => 'Estadística 2 — valor', 'type' => 'text', 'default' => '32', 'no_partial' => true ),
				'hero_stat2_suffix'      => array( 'label' => 'Estadística 2 — sufijo', 'type' => 'text', 'default' => '%', 'no_partial' => true ),
				'hero_stat2_label'       => array( 'label' => 'Estadística 2 — descripción', 'type' => 'text', 'default' => 'Reducción media de costes operativos' ),
				'hero_stat3_value'       => array( 'label' => 'Estadística 3 — valor', 'type' => 'text', 'default' => '3', 'no_partial' => true ),
				'hero_stat3_suffix'      => array( 'label' => 'Estadística 3 — sufijo', 'type' => 'text', 'default' => ' sem', 'no_partial' => true ),
				'hero_stat3_label'       => array( 'label' => 'Estadística 3 — descripción', 'type' => 'text', 'default' => 'Primer flujo en producción' ),
				'hero_card_meta'         => array( 'label' => 'Tarjeta "sistema en vivo" — etiqueta superior', 'type' => 'text', 'default' => 'Sistema operativo' ),
				'hero_card_status'       => array( 'label' => 'Tarjeta "sistema en vivo" — indicador de estado', 'type' => 'text', 'default' => 'En vivo' ),
				'hero_card_title'        => array(
					'label'            => 'Tarjeta "sistema en vivo" — título (segunda línea con degradado; usa un salto de línea para separar)',
					'type'             => 'textarea',
					'default'          => "Tu operación,\nen modo control.",
					'partial_callback' => function () {
						$lines = explode( "\n", iaempower_mod( 'hero_card_title' ), 2 );
						$lead  = trim( $lines[0] );
						$rest  = isset( $lines[1] ) ? trim( $lines[1] ) : '';
						$html  = esc_html( $lead );
						if ( $rest !== '' ) {
							$html .= '<br><em>' . esc_html( $rest ) . '</em>';
						}
						return $html;
					},
				),
				'hero_card_metric1_value' => array( 'label' => 'Tarjeta "sistema en vivo" — métrica 1, valor', 'type' => 'text', 'default' => '1.284' ),
				'hero_card_metric1_label' => array( 'label' => 'Tarjeta "sistema en vivo" — métrica 1, descripción', 'type' => 'text', 'default' => 'Tareas/día' ),
				'hero_card_metric2_value' => array( 'label' => 'Tarjeta "sistema en vivo" — métrica 2, valor', 'type' => 'text', 'default' => '−42%' ),
				'hero_card_metric2_label' => array( 'label' => 'Tarjeta "sistema en vivo" — métrica 2, descripción', 'type' => 'text', 'default' => 'Carga manual' ),
			),
		),
		'iaempower_manifesto' => array(
			'label'    => 'Manifiesto',
			'settings' => array(
				'manifesto_quote' => array(
					'label'            => 'Frase (una línea por salto de línea)',
					'type'             => 'textarea',
					'default'          => "La IA no es magia negra.\nEs tu ventaja competitiva.",
					'partial_callback' => function () {
						return nl2br( esc_html( iaempower_mod( 'manifesto_quote' ) ) );
					},
				),
				'manifesto_cite'  => array( 'label' => 'Atribución', 'type' => 'text', 'default' => 'Así pensamos cada proyecto en IAEMPOWER' ),
			),
		),
		'iaempower_pillars' => array(
			'label'    => 'Pilares de valor',
			'settings' => array(
				'pillar1_tag'   => array( 'label' => 'Pilar 1 — etiqueta', 'type' => 'text', 'default' => 'Liberación' ),
				'pillar1_title' => array( 'label' => 'Pilar 1 — título', 'type' => 'text', 'default' => 'De operador de tareas a visionario estratégico' ),
				'pillar1_text'  => array( 'label' => 'Pilar 1 — texto', 'type' => 'textarea', 'default' => 'No vendemos automatización: vendemos tiempo y foco. La IA se encarga de la fricción operativa; tu equipo recupera la capacidad de pensar y crear.' ),
				'pillar2_tag'   => array( 'label' => 'Pilar 2 — etiqueta', 'type' => 'text', 'default' => 'Escalabilidad' ),
				'pillar2_title' => array( 'label' => 'Pilar 2 — título', 'type' => 'text', 'default' => 'Escala tu operación, no tu nómina' ),
				'pillar2_text'  => array( 'label' => 'Pilar 2 — texto', 'type' => 'textarea', 'default' => 'Diseñamos sistemas que absorben más demanda sin exigir más recursos ni más plantilla.' ),
				'pillar3_tag'   => array( 'label' => 'Pilar 3 — etiqueta', 'type' => 'text', 'default' => 'Control' ),
				'pillar3_title' => array( 'label' => 'Pilar 3 — título', 'type' => 'text', 'default' => 'La IA no es magia negra. Es tu ventaja competitiva' ),
				'pillar3_text'  => array( 'label' => 'Pilar 3 — texto', 'type' => 'textarea', 'default' => 'Democratizamos la tecnología para que tengas el control real, sin necesitar un equipo de ingeniería interno.' ),
				'pillar4_tag'   => array( 'label' => 'Pilar 4 — etiqueta', 'type' => 'text', 'default' => 'ROI medible' ),
				'pillar4_title' => array( 'label' => 'Pilar 4 — título', 'type' => 'text', 'default' => 'Optimización que se ve en la cuenta de resultados' ),
				'pillar4_value' => array( 'label' => 'Pilar 4 — cifra grande', 'type' => 'text', 'default' => '100%' ),
				'pillar4_label' => array( 'label' => 'Pilar 4 — descripción de la cifra', 'type' => 'text', 'default' => 'de los proyectos con métrica de retorno definida antes de empezar' ),
			),
		),
		'iaempower_contrast' => array(
			'label'    => 'Antes / Después',
			'settings' => array(
				'contrast_before_items' => array(
					'label'            => 'Sin IAEMPOWER (una frase por línea)',
					'type'             => 'textarea',
					'default'          => "Correos y pedidos gestionados a mano, uno a uno.\nDatos duplicados entre herramientas que no se hablan.\nEquipo quemado apagando incendios operativos.\nCostes fijos que suben cada vez que creces.\nLa sensación de que la IA es \"cosa de otros\".",
					'partial_selector' => '.contrast-before ul',
					'partial_callback' => function () {
						return iaempower_render_contrast_list( 'contrast_before_items', 'M6 6l12 12M18 6L6 18' );
					},
				),
				'contrast_after_items' => array(
					'label'            => 'Con IAEMPOWER (una frase por línea)',
					'type'             => 'textarea',
					'default'          => "Flujos que se ejecutan solos, con trazabilidad completa.\nUn único sistema de datos conectado y fiable.\nEquipo enfocado en estrategia, no en tareas repetitivas.\nMárgenes que mejoran con cada proceso optimizado.\nControl real de tu tecnología, sin depender de terceros.",
					'partial_selector' => '.contrast-after ul',
					'partial_callback' => function () {
						return iaempower_render_contrast_list( 'contrast_after_items', 'M5 13l4 4L19 7' );
					},
				),
			),
		),
		'iaempower_leak_calc' => array(
			'label'    => 'Calculadora de fuga operativa',
			'settings' => array(
				'leak_enabled'         => array( 'label' => 'Mostrar la calculadora', 'type' => 'checkbox', 'default' => true, 'no_partial' => true ),
				'leak_eyebrow'         => array( 'label' => 'Texto pequeño superior', 'type' => 'text', 'default' => 'Calculadora' ),
				'leak_title'           => array( 'label' => 'Título', 'type' => 'textarea', 'default' => 'Pon en números la fricción de tu operación.' ),
				'leak_lead'            => array( 'label' => 'Texto de apoyo', 'type' => 'textarea', 'default' => 'Ajusta las variables con tus cifras reales y obtén una primera lectura económica antes de hablar con nosotros.' ),
				'leak_hours_default'   => array( 'label' => 'Horas manuales/semana — valor inicial del control (máximo fijo: 40h)', 'type' => 'text', 'default' => '20', 'no_partial' => true ),
				'leak_people_default'  => array( 'label' => 'Personas implicadas — valor inicial del control (rango 1-10)', 'type' => 'text', 'default' => '3', 'no_partial' => true ),
				'leak_cost_default'    => array( 'label' => 'Coste hora medio (€) — valor inicial del control (rango 10€-500€)', 'type' => 'text', 'default' => '35', 'no_partial' => true ),
				'leak_weeks_per_year'  => array( 'label' => 'Semanas laborables al año (asunción de cálculo, ej. 47 descontando vacaciones/festivos)', 'type' => 'text', 'default' => '47', 'no_partial' => true ),
				'leak_recovery_pct'    => array( 'label' => '% del coste anual que IAEMPOWER suele recuperar (asunción de cálculo)', 'type' => 'text', 'default' => '35', 'no_partial' => true ),
				'leak_avg_investment'  => array( 'label' => 'Inversión media de referencia en € (para estimar el payback en meses)', 'type' => 'text', 'default' => '3000', 'no_partial' => true ),
				'leak_disclaimer'      => array( 'label' => 'Aviso bajo los resultados', 'type' => 'text', 'default' => 'Estimación orientativa — validamos la línea base real en el diagnóstico.', 'no_partial' => true ),
				'leak_cta'             => array( 'label' => 'Texto del botón', 'type' => 'text', 'default' => 'Solicitar diagnóstico con estos datos', 'no_partial' => true ),
			),
		),
		'iaempower_architecture' => array(
			'label'    => 'Arquitectura (cómo lo hacemos)',
			'settings' => array(
				'architecture_eyebrow' => array( 'label' => 'Texto pequeño superior', 'type' => 'text', 'default' => 'Cómo lo hacemos' ),
				'architecture_title'   => array( 'label' => 'Título', 'type' => 'textarea', 'default' => 'Cada conexión tiene un porqué.' ),
				'architecture_lead'    => array( 'label' => 'Texto de apoyo', 'type' => 'textarea', 'default' => 'Diseñamos el sistema alrededor de la realidad de tu organización: personas, datos, permisos y decisiones — no al revés.' ),
				'architecture_cta'     => array( 'label' => 'Texto del botón', 'type' => 'text', 'default' => 'Diseñar mi sistema' ),
				'architecture_node1'   => array( 'label' => 'Nodo 1', 'type' => 'text', 'default' => 'Personas', 'no_partial' => true ),
				'architecture_node2'   => array( 'label' => 'Nodo 2', 'type' => 'text', 'default' => 'Datos', 'no_partial' => true ),
				'architecture_node3'   => array( 'label' => 'Nodo 3 (destacado, centro del diagrama)', 'type' => 'text', 'default' => 'IA', 'no_partial' => true ),
				'architecture_node4'   => array( 'label' => 'Nodo 4', 'type' => 'text', 'default' => 'Resultado', 'no_partial' => true ),
			),
		),
		'iaempower_contact' => array(
			'label'    => 'Contacto',
			'settings' => array(
				'contact_address'      => array( 'label' => 'Dirección', 'type' => 'text', 'default' => 'Collado Villalba, 28400, Madrid, España' ),
				'contact_email'        => array( 'label' => 'Email público', 'type' => 'text', 'default' => 'info@iaempoweragency.com' ),
				'contact_phone1'       => array( 'label' => 'Teléfono 1', 'type' => 'text', 'default' => '+34 674 53 57 88' ),
				'contact_phone2'       => array( 'label' => 'Teléfono 2', 'type' => 'text', 'default' => '+34 687 89 89 90' ),
				'contact_hours'        => array( 'label' => 'Horario', 'type' => 'text', 'default' => 'Lunes a viernes, 9:00–18:00 (CET)' ),
				'contact_notify_email' => array( 'label' => 'Email donde llegan las solicitudes del formulario', 'type' => 'text', 'default' => 'info@iaempoweragency.com', 'no_partial' => true ),
			),
		),
		'iaempower_social' => array(
			'label'    => 'Redes sociales',
			'settings' => array(
				'social_linkedin'  => array( 'label' => 'LinkedIn (URL completa, vacío para ocultar)', 'type' => 'text', 'default' => 'https://www.linkedin.com/company/146228969/', 'no_partial' => true ),
				'social_instagram' => array( 'label' => 'Instagram (URL completa, vacío para ocultar)', 'type' => 'text', 'default' => 'https://www.instagram.com/iaempower_agency/', 'no_partial' => true ),
				'social_tiktok'    => array( 'label' => 'TikTok (URL completa, vacío para ocultar)', 'type' => 'text', 'default' => 'https://www.tiktok.com/@iaempower', 'no_partial' => true ),
			),
		),
		'iaempower_section_headers' => array(
			'label'    => 'Títulos de secciones',
			'settings' => array(
				'pilares_eyebrow'    => array( 'label' => 'Pilares — texto pequeño', 'type' => 'text', 'default' => 'Por qué IAEMPOWER' ),
				'pilares_title'      => array( 'label' => 'Pilares — título', 'type' => 'text', 'default' => 'No vendemos IA. Vendemos en quién te conviertes con ella' ),
				'pilares_lede'       => array( 'label' => 'Pilares — texto de apoyo', 'type' => 'textarea', 'default' => 'Cuatro promesas concretas, no cuatro conceptos técnicos.' ),
				'contraste_eyebrow'  => array( 'label' => 'Antes/Después — texto pequeño', 'type' => 'text', 'default' => 'El punto de partida' ),
				'contraste_title'    => array( 'label' => 'Antes/Después — título', 'type' => 'text', 'default' => '¿Tu empresa crece, pero el caos diario también?' ),
				'contraste_lede'     => array( 'label' => 'Antes/Después — texto de apoyo', 'type' => 'textarea', 'default' => 'Es hora de romper el cuello de botella operativo.' ),
				'servicios_eyebrow'  => array( 'label' => 'Servicios — texto pequeño', 'type' => 'text', 'default' => 'Servicios' ),
				'servicios_title'    => array( 'label' => 'Servicios — título', 'type' => 'text', 'default' => 'Seis formas de amplificar tu operación' ),
				'servicios_lede'     => array( 'label' => 'Servicios — texto de apoyo', 'type' => 'textarea', 'default' => 'Cada servicio parte de un proceso concreto de tu empresa y se mide por el tiempo y el dinero que deja de perderse.' ),
				'casos_eyebrow'      => array( 'label' => 'Casos de éxito — texto pequeño', 'type' => 'text', 'default' => 'Casos de éxito' ),
				'casos_title'        => array( 'label' => 'Casos de éxito — título', 'type' => 'text', 'default' => 'Resultados representativos por sector' ),
				'casos_lede'         => array( 'label' => 'Casos de éxito — texto de apoyo', 'type' => 'textarea', 'default' => 'Tres perfiles de proyecto habituales en nuestra cartera. Los datos concretos de tu empresa se validan en el diagnóstico inicial.' ),
				'equipo_eyebrow'     => array( 'label' => 'Equipo — texto pequeño', 'type' => 'text', 'default' => 'Equipo' ),
				'equipo_title'       => array( 'label' => 'Equipo — título', 'type' => 'text', 'default' => 'Un equipo pequeño, cerca de tu operación' ),
				'equipo_lede'        => array( 'label' => 'Equipo — texto de apoyo', 'type' => 'textarea', 'default' => 'Con sede en Collado Villalba, trabajamos con clientes de toda la Comunidad de Madrid sin la capa burocrática de una consultora grande.' ),
				'precios_eyebrow'    => array( 'label' => 'Precios — texto pequeño', 'type' => 'text', 'default' => 'Precios' ),
				'precios_title'      => array( 'label' => 'Precios — título', 'type' => 'text', 'default' => 'Tres formas de empezar' ),
				'precios_lede'       => array( 'label' => 'Precios — texto de apoyo', 'type' => 'textarea', 'default' => 'Precios de referencia. El alcance final se ajusta siempre en el diagnóstico gratuito inicial.' ),
				'objeciones_eyebrow' => array( 'label' => 'FAQ — texto pequeño', 'type' => 'text', 'default' => 'Antes de que preguntes' ),
				'objeciones_title'   => array( 'label' => 'FAQ — título', 'type' => 'text', 'default' => 'Lo que normalmente nos preguntan' ),
				'objeciones_lede'    => array( 'label' => 'FAQ — texto de apoyo', 'type' => 'textarea', 'default' => 'Sin letra pequeña. Si tienes otra duda, la resolvemos en el diagnóstico gratuito.' ),
				'recursos_eyebrow'   => array( 'label' => 'Recursos — texto pequeño', 'type' => 'text', 'default' => 'Recursos' ),
				'recursos_title'     => array( 'label' => 'Recursos — título', 'type' => 'text', 'default' => 'Notas sobre IA aplicada a empresas' ),
				'recursos_lede'      => array( 'label' => 'Recursos — texto de apoyo', 'type' => 'textarea', 'default' => 'Lo que aprendemos en cada proyecto, escrito para quien decide, no para quien programa.' ),
				'contacto_eyebrow'   => array( 'label' => 'Contacto — texto pequeño', 'type' => 'text', 'default' => 'Contacto' ),
				'contacto_title'     => array( 'label' => 'Contacto — título', 'type' => 'text', 'default' => 'Agenda tu diagnóstico de caos operativo' ),
				'contacto_lede'      => array( 'label' => 'Contacto — texto de apoyo', 'type' => 'textarea', 'default' => 'Cuéntanos qué proceso te quita más tiempo. En menos de 48 horas laborables te decimos si la IA puede resolverlo y cómo.' ),
			),
		),
		'iaempower_misc_text' => array(
			'label'    => 'Otros textos (marquee, antes/después, blog)',
			'settings' => array(
				'marquee_label'        => array( 'label' => 'Marquee — texto pequeño', 'type' => 'text', 'default' => 'Sectores donde ya trabajamos' ),
				'marquee_items'        => array(
					'label'            => 'Marquee — sectores (uno por línea)',
					'type'             => 'textarea',
					'default'          => "Retail\nServicios profesionales\nSalud\nLegal\nIndustria\nE-commerce\nHostelería\nEducación",
					'partial_selector' => '.marquee-track',
					'partial_callback' => function () {
						$items = array_filter( array_map( 'trim', explode( "\n", iaempower_mod( 'marquee_items' ) ) ) );
						$html  = '';
						foreach ( array_merge( $items, $items ) as $item ) {
							$html .= '<span>' . esc_html( $item ) . '</span>';
						}
						return $html;
					},
				),
				'contrast_before_title' => array( 'label' => 'Antes/Después — título columna izquierda', 'type' => 'text', 'default' => 'Sin IAEMPOWER' ),
				'contrast_after_title'  => array( 'label' => 'Antes/Después — título columna derecha', 'type' => 'text', 'default' => 'Con IAEMPOWER' ),
				'contrast_vs_label'     => array( 'label' => 'Antes/Después — separador central', 'type' => 'text', 'default' => 'VS' ),
				'case_reto_label'       => array( 'label' => 'Casos de éxito — etiqueta "Reto"', 'type' => 'text', 'default' => 'Reto —', 'partial_selector' => '.case-reto-label' ),
				'case_solucion_label'   => array( 'label' => 'Casos de éxito — etiqueta "Solución"', 'type' => 'text', 'default' => 'Solución —', 'partial_selector' => '.case-solucion-label' ),
				'blog_read_more'        => array( 'label' => 'Blog — texto del enlace "leer más"', 'type' => 'text', 'default' => 'Leer artículo →', 'partial_selector' => '.blog-read-more' ),
			),
		),
		'iaempower_contact_extra' => array(
			'label'    => 'Contacto — etiquetas y pie de página',
			'settings' => array(
				'contact_mark_caption'  => array( 'label' => 'Ilustración del mapa — texto', 'type' => 'text', 'default' => 'SIERRA DE GUADARRAMA' ),
				'contact_mark_aria'     => array( 'label' => 'Ilustración del mapa — descripción accesible', 'type' => 'text', 'default' => 'Sede de IAEMPOWER en la Sierra de Guadarrama', 'no_partial' => true ),
				'contact_label_address' => array( 'label' => 'Etiqueta "Dirección"', 'type' => 'text', 'default' => 'Dirección' ),
				'contact_label_email'   => array( 'label' => 'Etiqueta "Email"', 'type' => 'text', 'default' => 'Email' ),
				'contact_label_phone'   => array( 'label' => 'Etiqueta "Teléfono"', 'type' => 'text', 'default' => 'Teléfono' ),
				'contact_label_hours'   => array( 'label' => 'Etiqueta "Horario"', 'type' => 'text', 'default' => 'Horario' ),
				'footer_legal_text'     => array( 'label' => 'Pie de página — aviso legal (ya no se muestra; los enlaces reales están más abajo, en "Páginas legales")', 'type' => 'text', 'default' => 'Aviso legal · Política de privacidad · Cookies' ),
				'footer_credit_text'    => array( 'label' => 'Pie de página — crédito de diseño (enlaza a Contacto)', 'type' => 'text', 'default' => 'Diseñado por IAEmpower Agency' ),
			),
		),
		'iaempower_legal' => array(
			'label'    => 'Páginas legales (pie de página)',
			'settings' => array(
				'legal_url_aviso'       => array( 'label' => 'URL de "Aviso legal" (crea la página en Páginas → Añadir nueva y pega aquí su enlace)', 'type' => 'text', 'default' => '#', 'no_partial' => true ),
				'legal_url_privacidad'  => array( 'label' => 'URL de "Política de privacidad"', 'type' => 'text', 'default' => '#', 'no_partial' => true ),
				'legal_url_cookies'     => array( 'label' => 'URL de "Política de cookies"', 'type' => 'text', 'default' => '#', 'no_partial' => true ),
			),
		),
		'iaempower_form' => array(
			'label'    => 'Formulario de contacto',
			'settings' => array(
				'form_label_name'     => array( 'label' => 'Campo "Nombre"', 'type' => 'text', 'default' => 'Nombre*' ),
				'form_label_email'    => array( 'label' => 'Campo "Email"', 'type' => 'text', 'default' => 'Email*' ),
				'form_label_company'  => array( 'label' => 'Campo "Empresa"', 'type' => 'text', 'default' => 'Empresa' ),
				'form_label_phone'    => array( 'label' => 'Campo "Teléfono"', 'type' => 'text', 'default' => 'Teléfono' ),
				'form_label_message'  => array( 'label' => 'Campo del mensaje', 'type' => 'text', 'default' => '¿Qué proceso te gustaría automatizar?*' ),
				'form_privacy_notice' => array(
					'label'      => 'Aviso de primera capa (RGPD/LOPD) — quién trata los datos, para qué y con qué base; revísalo con un abogado antes de publicar, sustituye "IAEMPOWER (autónomo)" por tu razón social/NIF reales si los tienes',
					'type'       => 'textarea',
					'default'    => 'Responsable: IAEMPOWER (autónomo). Finalidad: gestionar tu solicitud de contacto y responderte. Legitimación: tu consentimiento. No cedemos tus datos a terceros salvo obligación legal. Puedes ejercer tus derechos de acceso, rectificación y supresión, entre otros, como se explica en la política de privacidad.',
					'no_partial' => true,
				),
				'form_consent_text'   => array(
					'label'            => 'Texto de la casilla de aceptación (antes del enlace a la política de privacidad, que se añade automáticamente)',
					'type'             => 'textarea',
					'default'          => 'He leído y acepto la',
					'partial_callback' => function () {
						return esc_html( iaempower_mod( 'form_consent_text' ) ) . ' <a href="' . esc_url( iaempower_mod( 'legal_url_privacidad' ) ) . '" target="_blank" rel="noopener">Política de privacidad</a>.';
					},
				),
				'form_submit_label'   => array( 'label' => 'Texto del botón de envío', 'type' => 'text', 'default' => 'Enviar solicitud' ),
				'form_note'           => array( 'label' => 'Nota bajo el botón', 'type' => 'text', 'default' => 'Respondemos en menos de 48 horas laborables.' ),
				'form_error_text'     => array( 'label' => 'Mensaje de error (antes del email de contacto)', 'type' => 'textarea', 'default' => 'No hemos podido enviar tu solicitud. Revisa los campos obligatorios o escríbenos directamente a' ),
				'form_success_title'  => array( 'label' => 'Título de "solicitud recibida"', 'type' => 'text', 'default' => 'Solicitud recibida' ),
				'form_success_text'   => array( 'label' => 'Texto de "solicitud recibida"', 'type' => 'textarea', 'default' => 'Gracias — revisaremos tu caso y te responderemos por email en menos de 48 horas laborables.' ),
			),
		),
		'iaempower_whatsapp' => array(
			'label'    => 'Botón flotante de WhatsApp',
			'settings' => array(
				'whatsapp_enabled'    => array( 'label' => 'Mostrar el botón flotante de WhatsApp', 'type' => 'checkbox', 'default' => true, 'no_partial' => true ),
				'whatsapp_number'     => array( 'label' => 'Número de WhatsApp (con prefijo de país, ej. +34603296076)', 'type' => 'text', 'default' => '+34603296076', 'no_partial' => true ),
				'whatsapp_message'    => array( 'label' => 'Mensaje predefinido (editable por el visitante antes de enviarlo; déjalo vacío para abrir el chat sin texto)', 'type' => 'textarea', 'default' => 'Hola, vengo desde la web de IAEMPOWER y me gustaría más información.', 'no_partial' => true ),
				'whatsapp_aria_label' => array( 'label' => 'Texto accesible del botón (lector de pantalla)', 'type' => 'text', 'default' => 'Escríbenos por WhatsApp', 'no_partial' => true ),
			),
		),
		'iaempower_ai_transparency' => array(
			'label'    => 'Transparencia y cumplimiento IA (RIA)',
			'settings' => array(
				'ai_transparency_enabled' => array( 'label' => 'Mostrar la nota de transparencia sobre IA en el pie de página', 'type' => 'checkbox', 'default' => true, 'no_partial' => true ),
				'ai_transparency_text'    => array(
					'label'      => 'Texto de la nota (Reglamento (UE) 2024/1689 de Inteligencia Artificial)',
					'type'       => 'textarea',
					'default'    => 'En cumplimiento del Reglamento (UE) 2024/1689 de Inteligencia Artificial: en IAEMPOWER actuamos como implementadores de sistemas de IA para nuestros clientes (y, en los proyectos en que así se acuerde, como proveedores de la solución final), sin operar en sectores considerados de alto riesgo por el Reglamento. No diseñamos ni desplegamos sistemas de IA con fines de manipulación subliminal, engaño o explotación de vulnerabilidades, ni para influir de forma encubierta en el comportamiento de las personas usuarias. Mantenemos una formación continua en la materia y todo contenido de este sitio generado con apoyo de IA pasa por revisión editorial humana antes de su publicación.',
					'no_partial' => true,
				),
			),
		),
		'iaempower_seo' => array(
			'label'    => 'SEO y compartir en redes',
			'settings' => array(
				'seo_meta_description' => array(
					'label'      => 'Meta descripción (150-160 caracteres — resume qué hace IAEMPOWER; solo se usa si no tienes un plugin SEO como Rank Math activo o configurado en la portada)',
					'type'       => 'textarea',
					'default'    => 'IAEMPOWER: consultoría e ingeniería de IA en Madrid. Automatizamos procesos, reducimos costes y liberamos a tu equipo de tareas manuales, con retorno medible desde el primer mes.',
					'no_partial' => true,
				),
				'seo_share_image' => array(
					'label'      => 'Imagen para compartir en redes sociales (Facebook, LinkedIn, WhatsApp — recomendado 1200×630px; si no subes ninguna se usa el logo)',
					'type'       => 'image',
					'default'    => '',
					'no_partial' => true,
				),
			),
		),
	);
}

/**
 * Renders one of the antes/despues <li> lists — shared by the normal
 * front-page.php output and by the contrast_*_items selective-refresh
 * partials so both stay in sync.
 */
function iaempower_render_contrast_list( $mod_id, $svg_path ) {
	$items = array_filter( array_map( 'trim', explode( "\n", iaempower_mod( $mod_id ) ) ) );
	$html  = '';
	foreach ( $items as $item ) {
		$html .= '<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="' . esc_attr( $svg_path ) . '"></path></svg>' . esc_html( $item ) . '</li>';
	}
	return $html;
}

/**
 * Registers every Customizer setting/control, and — for the ones with a
 * visible 1:1 spot in the markup — a Selective Refresh partial. That's
 * what gives the "click the text in the live preview, jump straight to
 * its field" experience: WordPress core (customize-selective-refresh.js)
 * shows an edit pencil over any element matched by a partial's selector
 * and focuses the right control when it's clicked, with no custom JS
 * needed on our side.
 *
 * By default the partial's selector is just "#<setting id>" and its
 * render callback re-echoes the (escaped) theme mod — which covers the
 * large majority of fields, since front-page.php gives each of those
 * elements a matching id. Fields that don't map to a single plain-text
 * element (the hero stat numbers, the antes/despues lists, the marquee)
 * either opt out via 'no_partial' or provide their own
 * 'partial_selector' / 'partial_callback'.
 */
/**
 * Sanitizer for the theme's only checkbox setting so far (the WhatsApp
 * on/off toggle) — WordPress core doesn't ship one for this control type.
 */
function iaempower_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true == $checked );
}

function iaempower_customize_register( $wp_customize ) {
	foreach ( iaempower_customizer_fields() as $section_id => $section ) {
		$wp_customize->add_section( $section_id, array(
			'title'    => 'IAEMPOWER — ' . $section['label'],
			'priority' => 30,
		) );

		foreach ( $section['settings'] as $id => $field ) {
			$has_partial = empty( $field['no_partial'] );
			$is_image    = ( $field['type'] === 'image' );
			$is_checkbox = ( $field['type'] === 'checkbox' );

			$sanitize = 'sanitize_text_field';
			if ( $field['type'] === 'textarea' ) {
				$sanitize = 'sanitize_textarea_field';
			} elseif ( $is_image ) {
				$sanitize = 'esc_url_raw';
			} elseif ( $is_checkbox ) {
				$sanitize = 'iaempower_sanitize_checkbox';
			}

			$wp_customize->add_setting( $id, array(
				'default'           => $field['default'],
				'sanitize_callback' => $sanitize,
				'transport'         => $has_partial ? 'postMessage' : 'refresh',
			) );

			if ( $is_image && class_exists( 'WP_Customize_Image_Control' ) ) {
				$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $id, array(
					'label'   => $field['label'],
					'section' => $section_id,
				) ) );
			} else {
				$wp_customize->add_control( $id, array(
					'label'   => $field['label'],
					'section' => $section_id,
					'type'    => $is_checkbox ? 'checkbox' : ( ( $field['type'] === 'textarea' ) ? 'textarea' : 'text' ),
				) );
			}

			if ( $has_partial && isset( $wp_customize->selective_refresh ) ) {
				$callback = isset( $field['partial_callback'] ) ? $field['partial_callback'] : function () use ( $id ) {
					return esc_html( iaempower_mod( $id ) );
				};
				$wp_customize->selective_refresh->add_partial( $id, array(
					'selector'        => isset( $field['partial_selector'] ) ? $field['partial_selector'] : ( '#' . $id ),
					'settings'        => array( $id ),
					'render_callback' => $callback,
				) );
			}
		}
	}

	// The hero stat number + suffix share one <dd data-count> element, so
	// they get one compound partial each instead of two separate ones.
	if ( isset( $wp_customize->selective_refresh ) ) {
		foreach ( array( 1, 2, 3 ) as $n ) {
			$value_id  = "hero_stat{$n}_value";
			$suffix_id = "hero_stat{$n}_suffix";
			$wp_customize->selective_refresh->add_partial( "hero_stat{$n}_dd", array(
				'selector'           => "#hero_stat{$n}_dd",
				'settings'           => array( $value_id, $suffix_id ),
				'container_inclusive' => true,
				'render_callback'    => function () use ( $n, $value_id, $suffix_id ) {
					$value  = iaempower_mod( $value_id );
					$suffix = iaempower_mod( $suffix_id );
					return '<dd id="hero_stat' . $n . '_dd" data-count="' . esc_attr( $value ) . '" data-suffix="' . esc_attr( $suffix ) . '">' . esc_html( $value . $suffix ) . '</dd>';
				},
			) );
		}
	}
}
add_action( 'customize_register', 'iaempower_customize_register' );

/**
 * Flat id => default lookup built from iaempower_customizer_fields(),
 * so every call site gets the right fallback without repeating it.
 */
function iaempower_customizer_default( $id ) {
	static $flat = null;
	if ( $flat === null ) {
		$flat = array();
		foreach ( iaempower_customizer_fields() as $section ) {
			foreach ( $section['settings'] as $key => $field ) {
				$flat[ $key ] = $field['default'];
			}
		}
	}
	return isset( $flat[ $id ] ) ? $flat[ $id ] : '';
}

/**
 * Small helper used by templates: get a Customizer value, falling back
 * to the field's registered default (or an explicit $fallback) when
 * nothing has been saved yet — e.g. right after installing the theme,
 * before anyone has opened the Customizer.
 */
function iaempower_mod( $id, $fallback = null ) {
	if ( $fallback === null ) {
		$fallback = iaempower_customizer_default( $id );
	}
	return get_theme_mod( $id, $fallback );
}

/**
 * Belt-and-suspenders: actually save the registered defaults as real
 * theme_mods so a) the Customizer's own fields aren't blank the first
 * time someone opens it, and b) the site is correct even if a future
 * change to this file removes the runtime fallback above. Runs on
 * theme activation and, since re-running is harmless (it only fills
 * in what's missing), on every wp-admin load too — so updating the
 * theme's files alone is enough to self-heal an already-active site.
 */
function iaempower_seed_theme_mods() {
	foreach ( iaempower_customizer_fields() as $section ) {
		foreach ( $section['settings'] as $id => $field ) {
			if ( get_theme_mod( $id, null ) === null ) {
				set_theme_mod( $id, $field['default'] );
			}
		}
	}
}
add_action( 'after_switch_theme', 'iaempower_seed_theme_mods' );
add_action( 'admin_init', 'iaempower_seed_theme_mods' );
