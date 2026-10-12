# IAEMPOWER Pro — Tema de WordPress

Tema a medida para [IAEMPOWER](https://iaempoweragency.com), consultoría e ingeniería de IA en Collado Villalba, Madrid. Página única (one-page) con animaciones, tipografía propia y contenido totalmente editable (servicios, casos de éxito, equipo, precios, FAQ y blog) desde el panel de WordPress — sin tocar código.

**Versión actual:** 1.12.2 · **Requiere:** WordPress 5.9+ y PHP 7.4+

## Características

- Página de inicio de una sola página: hero animado, manifiesto, pilares, antes/después, servicios, casos de éxito, equipo, precios, FAQ, recursos y contacto.
- 5 tipos de contenido personalizados (Servicios, Casos de éxito, Equipo, Precios, FAQ), cada uno reordenable por arrastrar y soltar desde el admin.
- Personalizador de WordPress (Apariencia → Personalizar) con edición de casi todos los textos del sitio, "clic para editar" con vista previa en vivo (Selective Refresh), y soporte de selector de imagen.
- Botón flotante de WhatsApp (número, mensaje predefinido y on/off editables desde el Personalizador), con el degradado de acento del tema en vez del verde genérico de WhatsApp.
- Tarjeta "sistema en vivo" en el hero, sección "Arquitectura" (diagrama de nodos) y calculadora interactiva de fuga operativa (horas/semana, personas, coste/hora → coste anual, recuperable y payback), todas editables desde el Personalizador.
- Formulario de contacto funcional vía `wp_mail()`, sin plugin.
- SEO técnico: meta description, Open Graph, Twitter Card y datos estructurados JSON-LD (Servicios, FAQ, Equipo, breadcrumbs en el blog) — se desactiva automáticamente si Rank Math SEO está activo, para no duplicar datos.
- Contenido de ejemplo sembrado automáticamente al activar el tema, para no arrancar con el sitio vacío.
- Pestaña "Blog" en el menú (cabecera y pie) que lleva a una página real con todas las entradas publicadas, paginada — requiere asignar una "Página de entradas" en Ajustes → Lectura (aviso automático en el escritorio si falta).
- Iconos de redes sociales (LinkedIn, Instagram, TikTok) en el pie de página, editables desde el Personalizador y reflejados también en el schema de Organización (`sameAs`).

## Estructura del proyecto

```
iaempower/
├── style.css              Cabecera del tema + estilos principales
├── functions.php          Setup del tema, enqueue de assets, carga de inc/
├── header.php / footer.php / front-page.php / single.php / page.php / index.php
├── inc/
│   ├── cpts.php            Tipos de contenido personalizados y metaboxes
│   ├── customizer.php      Opciones del Personalizador
│   ├── contact-form.php    Formulario de contacto (AJAX + wp_mail)
│   ├── seed-content.php    Contenido de ejemplo al activar
│   ├── reorder.php         Drag & drop de orden en el admin
│   ├── seo.php             Meta tags, Open Graph, JSON-LD
│   └── whatsapp.php        Botón flotante de WhatsApp
├── assets/
│   ├── js/main.js
│   ├── fonts/               Fraunces, Public Sans, JetBrains Mono (woff2)
│   └── images/               Icono y wordmark del logo
├── dist/
│   └── iaempower-theme-1.12.2.zip   Paquete listo para instalar
└── CHANGELOG.md
```

## Instalación

**Opción rápida (zip ya empaquetado):**

1. Descarga [`dist/iaempower-theme-1.12.2.zip`](dist/iaempower-theme-1.12.2.zip) o, mejor, el zip adjunto a la última [Release](../../releases/latest).
2. En WordPress: Apariencia → Temas → Añadir nuevo → Subir tema → selecciona el zip → Instalar → Activar.

**Desde el código fuente:**

1. Clona o descarga este repositorio.
2. Copia la carpeta a `wp-content/themes/iaempower` en tu instalación de WordPress.
3. Actívalo desde Apariencia → Temas.

Al activarlo por primera vez se siembra contenido de ejemplo automáticamente (servicios, casos, equipo, precios y FAQ de muestra) para que el sitio no arranque vacío.

## Historial de versiones

Ver [CHANGELOG.md](CHANGELOG.md) para el detalle completo de cada versión, desde la 1.0.0 hasta la 1.12.2.

## Nota técnica: publicar cada versión como GitHub Release (referencia)

Cada versión se commitea como `.zip` dentro de `dist/`, pero lo correcto además es publicarla como [GitHub Release](https://docs.github.com/es/repositories/releasing-projects-on-github) con ese mismo `.zip` adjunto — así queda en `/releases`, versionada con su propio tag, sin depender de navegar el código para encontrar el paquete. Esto **no se puede automatizar desde una sesión de Claude Code** trabajando en este repo, por dos limitaciones puntuales del entorno (no son un problema de esta cuenta de GitHub ni de la plataforma):

1. El conjunto de herramientas de GitHub conectado en esas sesiones no incluye `create_release` / `upload_release_asset` — solo permite *leer* releases existentes, no crearlas.
2. El proxy de git de esas sesiones devuelve `403` al intentar `git push origin <tag>` (push de un tag), aunque el push de la propia rama `main` sí funciona — parece limitado a la rama designada, no a refs de tags.

La release de la **v1.7.0** ya se publicó así, manualmente, y funcionó sin problema — sirve de referencia para las siguientes:

1. Ve a la pestaña **Releases** de este repositorio → **Draft a new release**.
2. En **Tag**, escribe `v1.12.2` (o la versión que corresponda) y selecciona el commit deseado (normalmente el último de `main`).
3. En **Title**, pon `v1.12.2`, y pega la sección correspondiente de `CHANGELOG.md` en la descripción.
4. Descarga `dist/iaempower-theme-1.12.2.zip` de este repo y arrástralo al área de "Attach binaries".
5. En **Release label** deja **"None"** (no es un pre-release) y pulsa **Publish release**.

Repite este mismo proceso en cada versión futura, o inténtalo de nuevo desde una sesión con push a tags habilitado / con la herramienta `create_release` disponible, si en algún momento deja de ser una limitación.
