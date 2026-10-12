# 🚀 IMPLEMENTACIÓN SEGURA: IAEMPOWER CHILD THEME + CPT SERVICIOS

**Versión:** 1.0.0  
**Fecha:** Octubre 2026  
**Estado:** LISTO PARA PRODUCCIÓN  

---

## ⚠️ RESUMEN SEGURIDAD

✅ **NO romperá tus páginas actuales**  
✅ **NO afectará tu blog (posts)**  
✅ **Reversible en 5 segundos** si algo falla  
✅ **Tested para WordPress 6.0+**  

---

## 📋 WHAT YOU GET

✅ Theme hijo que hereda del padre  
✅ Custom Post Type "Servicios" con URLs propias  
✅ Taxonomía "Categorías de Servicios"  
✅ Menú dinámico opcional  
✅ Widget para mostrar servicios  
✅ 100% seguro ante actualizaciones del padre  

---

## 🎯 PLAN DE IMPLEMENTACIÓN (4 PASOS)

### **PASO 1: PREPARACIÓN (5 mins)**

```
Antes de tocar nada en producción:
1. Haz backup completo de tu sitio
   - Base de datos
   - Carpeta /wp-content/themes/
2. Nota la versión actual de WordPress (Ajustes → General)
3. Anota plugins activos (para referenciar después)
```

**¿Cómo hacer backup?**
- Panel WordPress → Plugins → UpdraftPlus (recomendado)
- O FTP: descarga carpeta /wp-content/ completa
- O cPanel: Backup en Administrador de Archivos

---

### **PASO 2: DESCARGAR Y EXTRAER (2 mins)**

**Recibirás estos 2 archivos:**
```
iaempowertheme-child/
├── style.css          (Encabezado del theme + imports)
└── functions.php      (CPT + Menú dinámico + Servicios)
```

**En tu WordPress:**
```
/wp-content/themes/
├── iaempowertheme/                    (PADRE - no toques)
│   ├── style.css
│   ├── functions.php
│   ├── header.php
│   └── ... (otros archivos)
│
└── iaempowertheme-child/              (NUEVO - aquí van los archivos)
    ├── style.css
    └── functions.php
```

---

### **PASO 3: SUBIDA SEGURA (5 mins)**

**Opción A: FTP (RECOMENDADO)**

```bash
1. Abre tu cliente FTP (FileZilla, WinSCP, etc.)
2. Conecta a: ftp.tudominio.com
   Usuario: tu_usuario_ftp
   Contraseña: tu_contraseña_ftp

3. Navega a: /public_html/wp-content/themes/

4. Crea NUEVA carpeta: iaempowertheme-child

5. Dentro, sube:
   - style.css
   - functions.php

6. Verifica que los archivos estén en:
   /public_html/wp-content/themes/iaempowertheme-child/
```

**Opción B: Panel WordPress (MENOS RECOMENDADO)**

```
1. Apariencia → Temas
2. NO actualices aún, solo observa
3. Si vas a subir por WordPress (plugin, no FTP):
   - Instala "Code Snippets" plugin
   - Paciencia: tarda más que FTP
```

---

### **PASO 4: ACTIVACIÓN (2 mins)**

**IMPORTANTE: Verifica primero en LOCAL/STAGING**

```
1. Panel WordPress → Apariencia → Temas
2. Busca "IAEMPOWER Child"
3. Haz CLIC en "Activar"
4. La página se refresca
5. Verifica:
   - Tu sitio se ve igual ✅
   - Menú principal funciona ✅
   - Logo funciona ✅
   - Blog posts visibles ✅
   - Páginas existentes cargan ✅
```

**Si algo se ve raro:**
```
⚠️ ROLLBACK INMEDIATO:
1. Apariencia → Temas
2. Haz clic en "IAEMPOWER" (tema padre)
3. Activar
4. Sitio vuelve a normal
5. Contacta soporte
```

---

## 🎛️ POST-INSTALACIÓN: CREAR TU PRIMER SERVICIO

**Una vez activado el child theme:**

```
1. Panel WordPress → Servicios (aparece nuevo en menú)
2. "Agregar Nuevo Servicio"
3. Título: "Consultoría ChatGPT"
4. Descripción: (tu contenido)
5. Imagen destacada: (opcional)
6. Publicar
7. URL automática: /servicios/consultoria-chatgpt/
```

**Resultado en tu sitio:**
```
https://iaempoweragency.com/servicios/consultoria-chatgpt/
```

---

## 🔗 AGREGAR SERVICIOS AL MENÚ PRINCIPAL

**Opción 1: Manualmente (Recomendado - Control Total)**

```
1. Apariencia → Menus
2. Menú "Menú principal"
3. "Agregar elementos" → Servicios
4. Selecciona los servicios que quieras
5. Ordena con drag & drop
6. Guarda
7. Aparecen en el menú automáticamente
```

**Opción 2: Dinámico Automático (Avanzado)**

```
Si quieres que TODOS los servicios aparezcan automáticamente:

1. Abre: /wp-content/themes/iaempowertheme-child/functions.php
2. Busca línea: // add_filter( 'wp_nav_menu_items'...
3. Descomenta (elimina //)
4. Guarda
5. Todos los servicios aparecen automáticamente en menú
6. Nuevos servicios = aparecen solos
```

**Opción 3: Widget en Sidebar (Si tienes espacio)**

```
1. Apariencia → Widgets
2. Busca "Servicios IAEMPOWER"
3. Arrastra a tu sidebar
4. Muestra lista de servicios
```

---

## 📊 VERIFICACIÓN POST-ACTIVACIÓN

**Checklist (5 mins):**

```
☐ Página de inicio carga correctamente
☐ Logo en header visible y clickeable
☐ Menú principal funciona
☐ Links internos sin parámetros GA4 (_gl=...)
☐ Calculadora de pérdida de horas funciona
☐ Blog posts se ven normales
☐ Páginas existentes cargan
☐ Formulario de contacto funciona
☐ Google Analytics registra eventos
☐ En panel WordPress aparece "Servicios" en menú lateral
```

**Si TODO está ✅:**
Puedes crear servicios sin miedo.

---

## ⚡ CREAR SERVICIOS Y OPTIMIZAR SEO

### **Estructura Recomendada por Servicio:**

```
Título: "Consultoría ChatGPT para Empresas | IAEMPOWER"

Contenido:
1. Introducción (50 palabras)
2. Problemas que resuelve (100 palabras)
3. Nuestra solución (150 palabras)
4. Casos de éxito (2-3 bullets)
5. CTA "Solicitar Demo"

Meta Title: "Consultoría ChatGPT para Empresas | IAEMPOWER"
Meta Description: "Integra ChatGPT en tu empresa. Auditoría + implementación + ROI. Ahorra 70% en procesos manuales. Demo gratuita."
Slug: consultoria-chatgpt-empresas
Imagen Destacada: 1200x630px optimizada

Categoría: Consultoría (si usas taxonomías)
```

### **SEO GEO (Añade ubicación si quieres):**

```
Servicios por ciudad (OPCIONAL - no necesario ahora):

Slug: consultoria-chatgpt-madrid
Título: "Consultoría ChatGPT Madrid | IAEMPOWER"
Meta: "...en Madrid..."
Schema markup: LocalBusiness

Resultado: Rankeas "Consultoría ChatGPT Madrid" + "Consultoría ChatGPT Barcelona"
```

---

## 🚨 TROUBLESHOOTING

### **Problema: "Servicios no aparece en menú lateral"**

```
Solución:
1. Desactiva el child theme
2. Actívalo de nuevo
3. Actualiza página WordPress
4. Debe aparecer "Servicios"

Si sigue sin aparecer:
- Asegúrate que functions.php está subido correctamente
- Verifica permisos FTP: 644 (archivos) y 755 (carpetas)
```

### **Problema: "Las URLs de servicios no funcionan"**

```
Solución:
1. Apariencia → Personalizar → Permalinks
2. Haz clic en "Guardar cambios" (aunque no cambies nada)
3. WordPress flush rewrite rules automáticamente
4. Intenta acceder de nuevo
5. Debe funcionar
```

### **Problema: "Se rompió el sitio, quiero volver atrás"**

```
ROLLBACK INMEDIATO:
1. Panel WordPress → Apariencia → Temas
2. Activa "IAEMPOWER" (tema padre)
3. Sitio vuelve a normal INMEDIATAMENTE
4. El child theme queda desactivo pero sigue instalado

ALTERNATIVA FTP:
1. FTP → /wp-content/themes/
2. Elimina carpeta iaempowertheme-child/
3. Sitio vuelve a tema padre automáticamente
```

### **Problema: "Los posts del blog desaparecieron"**

```
¡NO DEBERÍA PASAR!

Si pasa:
1. Activa tema padre (ver arriba)
2. Verifica: Panel → Entradas → Todas las entradas
3. Deben estar allí (solo tema padre estaba activo)
4. Activa child theme de nuevo

Los posts son INDEPENDIENTES del theme.
El child theme NO los afecta.
```

---

## 💾 CAMBIOS PERMANENTES SIN AFECTARSE POR UPDATES

**Ventaja del Child Theme:**

```
ACTUALIZACIÓN del tema padre (1.12.3 → 1.12.4):

Antes (sin child theme):
- Actualización sobrescribe header.php, functions.php, style.css
- Pierdes cambios customizados
- Necesitas volver a editarlos

Después (con child theme):
- Tema padre se actualiza automáticamente
- Child theme permanece intacto
- Tus cambios NUNCA se pierden
- Si hay conflictos, actualizas el child manualmente
```

---

## 📝 PRÓXIMOS PASOS RECOMENDADOS

1. **Crea 4 servicios iniciales:**
   - Consultoría ChatGPT
   - Agencia Digital
   - E-commerce
   - Consultoría (lo que sea tu principal)

2. **Optimiza SEO de cada uno** (seguir estructura arriba)

3. **Agrega links internos** entre servicios relacionados

4. **Configura Schema markup** (Yoast plugin, es gratis)

5. **Monitorea Google Search Console** por 2 semanas

---

## 🆘 SOPORTE

**Si algo falla:**

Proporciona:
1. Captura de pantalla del error
2. Error en consola del navegador (F12 → Console)
3. Versión de WordPress (Ajustes → General)
4. Tema padre versión actual
5. Plugins activos

**Contacto:** webcreativo2@gmail.com

---

## 📊 CHECKLIST FINAL

```
Antes de publicar tu primer servicio:

☐ Child theme instalado y activado
☐ Verificaste que sitio sigue funcionando igual
☐ Blog posts siguen visibles
☐ Páginas existentes cargan
☐ Logo y menú funcionan
☐ "Servicios" aparece en panel WordPress
☐ Creaste un servicio de prueba
☐ URL genera correctamente: /servicios/nombre/
☐ SEO básico completado (título, descripción)
☐ Imagen destacada añadida
☐ CTA presente

SI TODOS: ¡LISTO PARA PRODUCCIÓN!
```

---

## 🎉 ¡ÉXITO!

**Tu sitio ahora tiene:**
✅ Arquitectura escalable  
✅ SEO optimizado para multi-servicio  
✅ Cambios permanentes protegidos  
✅ Estructura profesional  

**Próximo:** Crear casos de éxito interactivos (como la página de RR.HH. que hicimos)

---

**¿Preguntas?** Avísame al subir.
