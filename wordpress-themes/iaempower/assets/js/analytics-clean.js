/**
 * ANALYTICS CLEAN URLS v1.12.3
 * Rastrear TODOS los clicks en GA4 SIN parámetros en la URL
 * Safe: Solo actúa en enlaces internos con parámetros GA4
 * Mantiene: Links nuevos, descargas, eventos personalizados
 */

(function() {
  'use strict';

  // Solo ejecutar si GA4 está disponible
  if (typeof gtag === 'undefined') {
    return; // GA4 no cargado, no hacer nada
  }

  document.addEventListener('click', function(e) {
    // Obtener el enlace clickeado
    const link = e.target.closest('a');

    if (!link || !link.href) return;

    const href = link.href;
    const target = link.getAttribute('target');
    const isNewTab = target === '_blank' || e.ctrlKey || e.metaKey;

    // Si abre en nueva pestaña, dejar que funcione normalmente
    if (isNewTab) return;

    // Solo procesar enlaces internos
    if (!href.startsWith(window.location.origin)) return;

    // Solo si tiene parámetros GA4
    if (!href.includes('_gl=') && !href.includes('_ga')) return;

    // Prevenir navegación por defecto
    e.preventDefault();

    // Extraer URL limpia (sin parámetros GA4)
    const urlObj = new URL(href);
    const params = new URLSearchParams(urlObj.search);

    // Remover parámetros GA4
    params.delete('_gl');
    params.delete('_ga');
    params.delete('_ga_2NLNWF7QGB'); // Todas las variaciones de GA4

    // Reconstruir URL limpia
    const cleanUrl = urlObj.origin + urlObj.pathname +
                     (params.toString() ? '?' + params.toString() : '') +
                     urlObj.hash;

    // Registrar en GA4 y navegar
    gtag('event', 'internal_link_click', {
      'link_url': cleanUrl,
      'link_text': link.innerText || link.textContent,
      'event_callback': function() {
        window.location.href = cleanUrl;
      },
      'timeout': 500 // Max 500ms esperando GA4
    });

  }, false); // Usar capture=false para compatibilidad

})();
