<?php
/**
 * Seeds each custom post type with the original demo content the first
 * time the theme is activated, so the site doesn't render empty
 * sections before anyone has edited anything. Safe to run more than
 * once — it checks each post type is empty first, and never touches
 * native posts/pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function iaempower_seed_post_type( $post_type, $items ) {
	$counts = wp_count_posts( $post_type );
	$existing = 0;
	foreach ( (array) $counts as $count ) {
		$existing += (int) $count;
	}
	if ( $existing > 0 ) {
		return;
	}

	foreach ( $items as $order => $item ) {
		$post_id = wp_insert_post( array(
			'post_type'    => $post_type,
			'post_title'   => $item['title'],
			'post_content' => isset( $item['content'] ) ? $item['content'] : '',
			'post_status'  => 'publish',
			'menu_order'   => $order,
		) );
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}
		foreach ( $item['meta'] as $key => $value ) {
			update_post_meta( $post_id, '_iaempower_' . $key, $value );
		}
	}
}

function iaempower_seed_all_content() {
	iaempower_seed_post_type( 'iaempower_service', array(
		array(
			'title'   => 'Agentes de IA a medida',
			'content' => 'Recupera horas de tu equipo: agentes que ejecutan tareas reales en tu CRM, tu correo o tu calendario, no solo responden — actúan.',
			'meta'    => array( 'icon' => 'agent' ),
		),
		array(
			'title'   => 'Automatización de procesos',
			'content' => 'Elimina el cuello de botella: flujos que conectan tus sistemas y quitan de en medio las tareas repetitivas, con trazabilidad completa.',
			'meta'    => array( 'icon' => 'automation' ),
		),
		array(
			'title'   => 'Chatbots y atención al cliente',
			'content' => 'Nunca más una consulta sin respuesta: conversación en español 24/7, entrenada con tu conocimiento, que escala a una persona cuando hace falta.',
			'meta'    => array( 'icon' => 'chat' ),
		),
		array(
			'title'   => 'Consultoría estratégica de IA',
			'content' => 'Invierte donde de verdad importa: auditoría de procesos y hoja de ruta priorizada por retorno de inversión, no por moda tecnológica.',
			'meta'    => array( 'icon' => 'strategy' ),
		),
		array(
			'title'   => 'Integración y datos',
			'content' => 'Un solo sistema fiable: conectamos IA con tus herramientas existentes — bases de datos, APIs, documentos — de forma segura y auditable.',
			'meta'    => array( 'icon' => 'integration' ),
		),
		array(
			'title'   => 'IA generativa aplicada',
			'content' => 'Contenido e informes al ritmo de tu negocio: generación adaptada a tu sector, con revisión humana en los puntos que importan.',
			'meta'    => array( 'icon' => 'generative' ),
		),
	) );

	iaempower_seed_post_type( 'iaempower_case', array(
		array(
			'title' => 'Agente de atención al cliente en WhatsApp',
			'meta'  => array(
				'sector'        => 'Retail regional',
				'reto'          => 'pedidos y consultas gestionados manualmente, con picos de espera en horas punta.',
				'solucion'      => 'agente de IA integrado en WhatsApp Business y el CRM, con escalado a personas para casos complejos.',
				'metric_prefix' => '−',
				'metric_value'  => '65',
				'metric_suffix' => '%',
				'metric_label'  => 'tiempo medio de respuesta',
			),
		),
		array(
			'title' => 'Automatización documental',
			'meta'  => array(
				'sector'        => 'Despacho profesional',
				'reto'          => 'horas semanales perdidas en clasificar y redactar documentación recurrente.',
				'solucion'      => 'flujo de generación y clasificación de documentos con IA, conectado al gestor documental existente.',
				'metric_prefix' => '',
				'metric_value'  => '120',
				'metric_suffix' => 'h',
				'metric_label'  => 'liberadas al mes para trabajo de valor',
			),
		),
		array(
			'title' => 'Asistente de citas por voz y chat',
			'meta'  => array(
				'sector'        => 'Clínica de salud',
				'reto'          => 'agenda gestionada por teléfono, con alta tasa de ausencias sin aviso previo.',
				'solucion'      => 'asistente conversacional para reservar, confirmar y recordar citas de forma proactiva.',
				'metric_prefix' => '−',
				'metric_value'  => '40',
				'metric_suffix' => '%',
				'metric_label'  => 'ausencias sin aviso',
			),
		),
	) );

	iaempower_seed_post_type( 'iaempower_team', array(
		array(
			'title'   => 'Marcos Herrera',
			'content' => 'Ingeniero de ML con más de una década construyendo sistemas de producción antes de fundar IAEMPOWER.',
			'meta'    => array( 'role' => 'Fundador & Director de IA' ),
		),
		array(
			'title'   => 'Elena Cabrera',
			'content' => 'Lidera el diagnóstico y la hoja de ruta de cada cliente, del proceso al retorno de inversión.',
			'meta'    => array( 'role' => 'Consultora de Estrategia IA' ),
		),
		array(
			'title'   => 'Diego Ramallo',
			'content' => 'Conecta agentes de IA con los sistemas reales de cada empresa: CRMs, ERPs y bases de datos.',
			'meta'    => array( 'role' => 'Ingeniero de Integraciones' ),
		),
	) );

	iaempower_seed_post_type( 'iaempower_price', array(
		array(
			'title' => 'Valida tu primer caso de uso',
			'meta'  => array(
				'tag_label'   => 'Impulso',
				'tagline'     => 'Un proceso, un resultado medible.',
				'amount'      => '1.200 €',
				'amount_unit' => '/ proyecto',
				'featured'    => '',
				'features'    => "Diagnóstico de procesos incluido\n1 automatización o agente de IA\nIntegración con una herramienta existente\n30 días de soporte tras la entrega",
				'cta_label'   => 'Empezar aquí',
			),
		),
		array(
			'title' => 'Escala la IA en tu operación',
			'meta'  => array(
				'tag_label'   => 'Aceleración — más elegido',
				'tagline'     => 'Varios flujos trabajando a la vez.',
				'amount'      => '3.800 €',
				'amount_unit' => '/ mes',
				'featured'    => '1',
				'features'    => "Hasta 3 flujos o agentes activos\nIntegración con tus sistemas principales\nSoporte prioritario y canal directo\nInforme mensual de impacto y horas ahorradas",
				'cta_label'   => 'Solicitar propuesta',
			),
		),
		array(
			'title' => 'Rediseña la operación con IA',
			'meta'  => array(
				'tag_label'   => 'Transformación',
				'tagline'     => 'Para equipos que van en serio.',
				'amount'      => 'A medida',
				'amount_unit' => '',
				'featured'    => '',
				'features'    => "Equipo dedicado durante el proyecto\nRoadmap anual de automatización\nIntegraciones ilimitadas\nSLA garantizado por contrato",
				'cta_label'   => 'Hablar con el equipo',
			),
		),
	) );

	iaempower_seed_post_type( 'iaempower_faq', array(
		array(
			'title'   => '¿Y si no somos una empresa tecnológica?',
			'content' => 'No importa. Nosotros ponemos la ingeniería; tú pones el conocimiento de tu negocio. No necesitas un equipo técnico interno para tener IA funcionando en producción.',
			'meta'    => array(),
		),
		array(
			'title'   => '¿No es muy caro implementar IA?',
			'content' => 'El diagnóstico inicial es gratuito y cada propuesta incluye el retorno esperado antes de invertir un euro. Empezamos por el proceso con mayor impacto, no por el más vistoso.',
			'meta'    => array(),
		),
		array(
			'title'   => '¿Perdemos el control de nuestros datos?',
			'content' => 'No. Trabajamos sobre tus sistemas existentes, con acceso auditable y reversible en cualquier momento. Tus datos no salen de donde tú decidas que vivan.',
			'meta'    => array(),
		),
		array(
			'title'   => '¿Cuánto se tarda en ver resultados?',
			'content' => 'El primer flujo suele estar en producción entre 2 y 4 semanas después del diagnóstico. A partir de ahí, medimos el impacto mes a mes.',
			'meta'    => array(),
		),
	) );
}
add_action( 'after_switch_theme', 'iaempower_seed_all_content' );
