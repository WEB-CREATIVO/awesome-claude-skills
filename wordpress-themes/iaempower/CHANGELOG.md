# Changelog — Tema IAEMPOWER

## 1.13.0
- Nuevo: el menú principal se gestiona desde Apariencia → Menús. Al instalar esta versión se crea automáticamente "Menú principal" con exactamente los mismos enlaces que antes (Servicios, Casos, Equipo, Precios, Recursos, Blog), así que la web se ve igual y añadir una página es solo arrastrarla al menú.
- Nuevo: el menú móvil usa ese mismo menú (más el enlace Contacto), en vez de una lista fija que nunca mostraba las páginas añadidas. Su altura máxima se adapta a la pantalla para que no se corte con más enlaces.
- Nuevo: los enlaces de menú tipo ancla (`#servicios`, `#casos`…) llevan a la portada desde cualquier página interior y mantienen el resaltado al hacer scroll.
- Nuevo: cada Servicio tiene URL propia (`/servicios/nombre-del-servicio/`) con su plantilla, imagen destacada opcional y botón de contacto. El schema JSON-LD de cada servicio incluye ahora esa URL.
- Nuevo: campo "Extracto" en Servicios. Si se rellena, es el texto de la tarjeta en la portada y el contenido completo queda para la página del servicio. Si se deja vacío, la tarjeta se ve exactamente como antes.
- Las reglas de enlaces permanentes se regeneran solas una vez al actualizar por FTP, sin reactivar el tema.

## 1.12.2
- Corregido: las versiones 1.12.0 y 1.12.1 se empaquetaron sin `screenshot.png` por un error al preparar el zip (se excluyó por accidente junto con archivos de prueba) — la miniatura del tema en Apariencia → Temas se veía en blanco. El archivo nunca se perdió del repositorio, solo faltaba en esos dos zips concretos. Corregido en el empaquetado, sin cambios de código.

## 1.12.1
- Corregido: el `areaServed` del schema de cada Servicio decía `"Worldwide"` — contradecía el posicionamiento local que persigue toda la estrategia SEO en curso. Se cambia a una lista por prioridad de negocio: Collado Villalba (sede) primero, después el corredor de alto standing del noroeste de Madrid (Majadahonda, Las Rozas de Madrid, Torrelodones) y la Sierra de Guadarrama / Sierra Norte, y por último Comunidad de Madrid y España como mercado nacional secundario (no se descarta, solo queda en segundo plano). No afecta a la ficha de Google Business Profile ni a Rank Math — solo al schema que genera el propio theme.

## 1.12.0
- Nuevo: enlaces a redes sociales (LinkedIn, Instagram, TikTok) — iconos en el pie de página, editables (o desactivables dejando el campo vacío) desde Personalizar → Redes sociales.
- Nuevo: esas mismas URLs alimentan automáticamente el `sameAs` del schema de Organización (JSON-LD), reforzando la señal de identidad de marca para buscadores y motores de IA (GEO/E-E-A-T). Solo se emite en las páginas donde el theme genera su propio schema (si Rank Math SEO está activo, ese mismo dato se gestiona desde su panel de Redes Sociales, para no duplicarlo).

## 1.11.0
- Nuevo: pestaña "Blog" en el menú (cabecera y pie de página), que lleva a una página real con **todas** las entradas publicadas, paginada — hasta ahora solo se podían ver las 4 más recientes en la sección "Recursos" de la portada. Requiere un paso único en el panel de WordPress: crear una página (cualquier título, por ejemplo "Blog") y asignarla en Ajustes → Lectura → "Página de entradas". Mientras no se configure, el enlace "Blog" lleva a la sección "Recursos" de la portada como alternativa provisional, y aparece un aviso en el escritorio de WordPress recordando el paso pendiente.
- Nuevo: la plantilla de listado de entradas (usada por esa página de blog, y también por los resultados de búsqueda) se rediseña por completo para usar las mismas tarjetas que "Recursos" (fecha, tiempo de lectura estimado, extracto, "Leer artículo →") en vez del listado simple anterior, con paginación con el mismo estilo del resto del tema.

## 1.10.3
- Corregido: el contenido de las entradas del blog y de las páginas (`the_content()`) no tenía separación entre párrafos — el reset global `p{margin:0}` del tema (correcto para el resto de textos sueltos del sitio) dejaba los artículos largos con todo el texto pegado. Nueva clase `.entry-content` con tipografía de artículo completa: espacio entre párrafos, más espacio antes de cada `<h2>`/`<h3>`, listas, citas (`<blockquote>`) y enlaces con su propio estilo. Se aplica automáticamente a `single.php` y `page.php`, sin que el autor tenga que hacer nada al escribir.

## 1.10.2
- Corregido: la calculadora de fuga operativa mostraba el "coste anual estimado" con separador de miles (18.800 €) pero el "recuperable con IAEMPOWER" sin él (6580 € en vez de 6.580 €) para ciertos valores. Era un comportamiento real (y poco conocido) de `toLocaleString()` en JavaScript: por defecto, el agrupamiento de miles ("auto") no siempre se aplica igual según la cantidad de dígitos. Se fuerza el agrupamiento explícitamente para que sea siempre consistente.
- Cambiado: los botones (CTA) pasan de esquinas rectas a totalmente redondeados (forma de píldora), en toda la web.

## 1.10.1
- Cambiado: el tema pasa a llamarse "IAEMPOWER Pro" en Apariencia → Temas, con Luis Melgarejo (IAEMPOWER Founder & IA Consultant) como autor en los metadatos. Cambio solo de etiqueta/metadatos — la carpeta y el Text Domain siguen siendo `iaempower`, así que ningún ajuste guardado se pierde ni se reinicia.
- Nuevo: crédito "Diseñado por IAEmpower Agency" en la línea de copyright del pie de página, enlazado a la sección de contacto. Editable desde Personalizar → Contacto → etiquetas y pie de página.

## 1.10.0
- Rediseñado: paleta de acento — el degradado azul/violeta/naranja pasa a ser un degradado morado monocromo (violeta oscuro → violeta → lavanda), aplicado en botones, textos con énfasis, bordes, iconos y el botón de WhatsApp. Cambio puramente visual, ningún ajuste ni contenido guardado se ve afectado.
- Nuevo: tarjeta "sistema en vivo" junto al titular del hero — panel con indicador de estado, gráfico de barras y dos métricas configurables, reorganizando el hero en dos columnas en pantallas grandes (se apila en móvil). Titulares del hero también más grandes.
- Nuevo: sección "Arquitectura" (cómo lo hacemos) — diagrama de cuatro nodos conectados (Personas / Datos / IA / Resultado), con título, texto y botón editables desde Personalizar → "Arquitectura (cómo lo hacemos)".
- Nuevo: calculadora de fuga operativa — sección interactiva con tres controles deslizantes (horas manuales/semana, máx. 40; personas implicadas, hasta 10; coste hora medio, entre 10 € y 500 €) que calculan en vivo el coste anual estimado, lo recuperable trabajando con IAEMPOWER y el payback en meses. Todas las asunciones de cálculo (semanas laborables/año, % de recuperación, inversión media de referencia) y los textos son editables desde Personalizar → "Calculadora de fuga operativa", con interruptor para mostrarla u ocultarla. No incluye descarga de PDF ni validación automática — el botón lleva directamente al formulario de contacto real, para no repetir el error de un elemento que aparenta hacer algo que no hace.

## 1.9.3
- Nuevo: los títulos de las entradas del blog y de las páginas estáticas heredan ahora el mismo acento visual (degradado de color) que el titular de la portada — automático, sin tocar nada al escribir: si el título lleva ":" (ej. "Tema: subtítulo"), la parte después del último ":" se resalta con el degradado; si no lleva ":", el título se muestra exactamente igual que antes (así páginas como "Aviso Legal" no se ven afectadas).

## 1.9.2
- Corregido: la ilustración "SIERRA DE GUADARRAMA" de la sección de contacto era puramente decorativa — parecía un pin de mapa pero no llevaba a ningún sitio al pincharla. Ahora abre Google Maps con la dirección configurada en Personalizar → Contacto → Dirección.

## 1.9.1
- Corregido: la casilla de aceptación del formulario de contacto mencionaba "la política de privacidad" como texto plano, sin ningún enlace real — no cumplía con el requisito de la LOPD/RGPD de dar acceso efectivo a la política completa en el punto de recogida de datos. Ahora "Política de privacidad" es un enlace real a la URL configurada en Personalizar → Páginas legales.
- Nuevo: aviso de primera capa (RGPD) encima de la casilla — responsable, finalidad, legitimación y remisión a la política completa —, editable desde Personalizar → Formulario de contacto → "Aviso de primera capa". El texto por defecto es una plantilla razonable, no asesoría legal validada; revísalo con un abogado antes de confiar en él para producción.

## 1.9.0
- Corregido: los enlaces de ancla del menú (Servicios, Casos, Equipo, Precios, Recursos, Contacto), en la cabecera y en el pie de página, dejaban de funcionar en cuanto se entraba a cualquier página que no fuera la portada (por ejemplo, Aviso Legal) — al ser anclas relativas (`#servicios`), solo funcionan si esas secciones existen en la página actual. Ahora, fuera de la portada, esos mismos enlaces llevan primero de vuelta al inicio y luego saltan a la sección correcta.
- Nuevo: navegador flotante de subir/bajar entre secciones, en el borde derecho de la pantalla (solo en la portada, y solo en escritorio/tablet grande — se oculta en móvil, donde ya está el menú). Las flechas se atenúan automáticamente al llegar al principio o al final.

## 1.8.1
- Corregido: las páginas estáticas de WordPress (Aviso Legal, Política de Privacidad, Cookies, y cualquier página creada con el editor nativo) solo mostraban un extracto de ~30 palabras en vez del contenido completo. El tema nunca había tenido una plantilla `page.php` — sin ella, WordPress usaba `index.php` (pensada para listados/archivos) como último recurso también para páginas sueltas. Se añade `page.php`, dedicada a páginas estáticas, que muestra el título y el contenido completo. No afecta a las entradas del blog (Recursos), que ya usaban `single.php` correctamente.

## 1.8.0
- Nuevo: botón flotante de WhatsApp, visible en todas las páginas (esquina inferior derecha), que abre un chat con el número configurado y un mensaje predefinido editable por el visitante. Ícono en el tono marfil del tema (`--text`) sobre el degradado de acento y el halo pulsante propios del tema, en vez del verde/negro genérico de WhatsApp.
- Nuevo: panel "Botón flotante de WhatsApp" en el Personalizador (Apariencia → Personalizar) — número, mensaje predefinido, texto accesible y un interruptor para mostrarlo/ocultarlo, sin tocar código.
- Nuevo: nota de transparencia sobre el uso de IA en el pie de página, en cumplimiento del Reglamento (UE) 2024/1689 de Inteligencia Artificial (RIA) — texto e interruptor de visibilidad editables desde Apariencia → Personalizar → "Transparencia y cumplimiento IA (RIA)".
- Nuevo: soporte para controles de tipo casilla (`checkbox`) en el sistema de campos del Personalizador, reutilizable para futuros ajustes de sí/no.

## 1.7.0
- Nuevo: SEO técnico — descripción meta, Open Graph y Twitter Card, con una imagen para compartir en redes editable desde el Personalizador (Apariencia → Personalizar → SEO y compartir en redes). Estas etiquetas solo se generan si Rank Math SEO no está activo, para no duplicar lo que Rank Math ya gestiona en producción.
- Nuevo: datos estructurados (schema.org / JSON-LD) en la página de inicio — Servicios (desde "Servicios"), Preguntas frecuentes (desde "FAQ", para el resultado enriquecido de Google) y Equipo (desde "Equipo"). En las entradas del blog se añade la ruta de navegación (BreadcrumbList). El nodo de Organización solo se genera si Rank Math SEO no está activo — como en producción sí lo está y su panel "SEO local" ya publica su propia Organización, generar la nuestra además habría creado dos entidades "Organización" distintas en la misma página, que es peor para el SEO que tener solo una.
- Nuevo: enlaces reales a "Aviso legal", "Política de privacidad" y "Cookies" en el pie de página, con su URL editable desde Apariencia → Personalizar → Páginas legales. Por defecto apuntan a "#" hasta que se creen esas páginas con el editor nativo de WordPress; en ese momento basta con pegar su enlace ahí, sin tocar código.
- Corregido: el schema de cada Servicio marcaba `areaServed: "ES"` (solo España). La sede está en Madrid, pero la agencia atiende clientes en todo el mundo — se cambia a `"Worldwide"`.
- Corregido: en las entradas del blog (`single.php`) el script `main.js` podía fallar porque intentaba usar el formulario de contacto y la animación del hero, que solo existen en la página de inicio — rompía el menú móvil y el resto de interacciones en esas páginas. Ahora comprueba que existan antes de usarlos.
- Auditoría de compatibilidad: se confirma que el slug de la carpeta del tema, el Text Domain, los 5 tipos de contenido, los menús (`primary`/`footer`), los nombres de acción/nonce de AJAX y formulario, y los handles de encolado no cambian respecto a la v1.6.0 — ninguna instalación existente pierde datos ni configuración al actualizar.

## 1.6.0
- Nuevo: reordenar arrastrando y soltando (drag & drop) en las listas de Equipo, Servicios, Casos de éxito, Planes de precio y Preguntas frecuentes — sin plugin, con el icono ⋮⋮ a la izquierda de cada fila. El orden se guarda al instante y es el mismo que se usa en la página.
- Nuevo: `screenshot.png` del tema, para que Apariencia → Temas ya no muestre la miniatura vacía.

## 1.5.1
- Los avatares de fotos reales del equipo pasan a tamaño fluido (`clamp()`), en vez de un tamaño fijo, para escalar mejor en pantallas muy pequeñas.

## 1.5.0
- Corregido: la foto de cada miembro del equipo salía recortada de forma poco profesional. Ahora se muestra en un avatar circular con el encuadre favoreciendo la parte superior de la imagen (donde suele estar la cara).

## 1.4.0
- Nuevo: selector de foto para cada miembro del equipo directamente en el mismo cuadro de edición donde está el campo "Cargo" (usa la biblioteca de medios de WordPress). Antes solo existía la "Imagen destacada" nativa, en un panel aparte y fácil de no encontrar.
- Corregido: el header y el footer ahora usan exactamente el mismo logo (icono + wordmark real), antes el header mostraba el nombre del sitio en texto.
- Corregido: los archivos CSS/JS se versionan automáticamente según su fecha de modificación, para que el navegador y plugins de caché (WP Rocket) no sirvan una versión antigua tras cada actualización del tema.

## 1.3.0
- Nuevo: "clic para editar" en el Personalizador (Selective Refresh de WordPress). Al pasar el ratón por casi cualquier texto de la vista previa aparece un lápiz; al hacer clic, el panel salta directo al campo correspondiente. Cubre 86 de los 91 ajustes de texto.
- Corregido: tamaños de letra del cuerpo de texto (tarjetas, formulario, pie de página) demasiado pequeños en móvil — reforzados para no bajar de ~16px en pantallas pequeñas.

## 1.2.0
- Nuevo: todos los textos que quedaban fijos en el código (marquee de sectores, títulos "Sin/Con IAEMPOWER", etiquetas "Reto/Solución", "Leer artículo", etiquetas de contacto, aviso legal del pie de página, y absolutamente todo el formulario de contacto) pasan a ser editables desde Apariencia → Personalizar.
- Nuevo: los títulos y textos de introducción de cada sección (Pilares, Antes/Después, Servicios, Casos, Equipo, Precios, FAQ, Recursos, Contacto) pasan a ser editables desde el Personalizador.

## 1.1.0
- Corregido: bug de origen — los textos editables (titular, botones, estadísticas) se guardaban vacíos hasta que alguien abría el Personalizador y guardaba manualmente una vez; provocaba titulares en blanco y "NaN" en las estadísticas en una instalación nueva.
- Corregido: los campos personalizados (Cargo, Sector, precio, etc.) quedaban escondidos al final de la pantalla de edición en el editor de bloques. Los 5 tipos de contenido (Servicios, Casos, Equipo, Precios, FAQ) pasan al editor clásico, donde esos campos aparecen arriba, junto al título.
- Corregido: HTML inválido (párrafo duplicado dentro de otro párrafo) en las descripciones de Servicios y las bios de Equipo.
- Corregido: títulos duplicados en los planes de precio del contenido de ejemplo.

## 1.0.0
- Primera versión: tema completo de una sola página (hero animado, pilares, antes/después, servicios, casos de éxito, equipo, precios, FAQ, blog y contacto), con 5 tipos de contenido personalizados, Personalizador básico, formulario de contacto funcional vía `wp_mail()`, y contenido de ejemplo sembrado al activar.
