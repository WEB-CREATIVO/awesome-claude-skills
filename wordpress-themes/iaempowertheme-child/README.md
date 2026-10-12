# 🎨 IAEMPOWER Child Theme

**Version:** 1.0.0  
**Compatible con:** WordPress 5.9+  
**Theme Padre:** iaempowertheme 1.12.3+  

---

## 📦 Contenido

```
iaempowertheme-child/
├── style.css           ← Encabezado theme + importa estilos padre
├── functions.php       ← CPT Servicios + Menú dinámico
└── README.md           ← Este archivo
```

---

## ✅ ¿Qué hace?

1. **Hereda todo** del theme padre (iaempowertheme)
2. **Registra Custom Post Type** "Servicios"
3. **Registra Taxonomía** "Categorías de Servicios"
4. **Provee menú dinámico** que incluye servicios
5. **Mantiene cambios seguros** ante actualizaciones del padre

---

## 🚀 Instalación Rápida

1. **Sube carpeta completa** a: `/wp-content/themes/iaempowertheme-child/`
2. **Panel WordPress** → Apariencia → Temas
3. **Busca** "IAEMPOWER Child"
4. **Activa**
5. **Verifica** que el sitio se ve igual

---

## 📝 Primeros Pasos

**Crear un servicio:**
1. Panel → Servicios → Agregar Nuevo
2. Título: Ej. "Consultoría ChatGPT"
3. Contenido: Tu descripción
4. Imagen destacada: (opcional)
5. Categoría: Elige o crea una
6. Publicar

**Resultado:** `/servicios/consultoria-chatgpt/`

---

## 🔗 Agregar al Menú

**Opción 1: Manual (Recomendado)**
1. Apariencia → Menús
2. Menú "Menú principal"
3. Agregar elementos → Servicios
4. Arrastra los que quieras
5. Guarda

**Opción 2: Automático**
Descomenta línea en functions.php:
```php
add_filter( 'wp_nav_menu_items', 'iaempower_child_custom_nav_menu', 10, 3 );
```

---

## ⚙️ Configuración

**Cambiar slug de servicios:**
En `functions.php`, línea ~58:
```php
'rewrite' => array( 'slug' => 'servicios' ),  // Cambia 'servicios' por lo que quieras
```

**Agregar campos custom:**
Usa plugin "Advanced Custom Fields" (opcional)

**Deshabilitar taxonomía:**
Comenta línea 131 en functions.php:
```php
// add_action( 'init', 'iaempower_child_register_servicio_category' );
```

---

## 🔒 Seguridad

✅ **No afecta posts existentes**  
✅ **No toca páginas estáticas**  
✅ **Blog sigue funcionando igual**  
✅ **Reversible en 5 segundos**  
✅ **Protegido de actualizaciones del padre**  

---

## 📊 Estructura BD

**CPT Creado:**
- Post Type: `servicio`
- Slug: `/servicios/`
- Soporte: título, editor, imagen, metadata

**Taxonomía Creada:**
- Taxonomy: `categoria_servicio`
- Slug: `/categoria-servicio/`
- Jerárquica: Sí (permite subcategorías)

---

## 🐛 Troubleshooting

**"No veo Servicios en el menú"**
→ Apariencia → Personalizar → Permalinks → Guardar

**"URLs de servicios no funcionan"**
→ Mismo paso arriba

**"Se rompió todo"**
→ Apariencia → Temas → Activa "IAEMPOWER" (padre)

---

## 📚 Documentación Completa

Ver archivo: `IMPLEMENTACION-THEME-CHILD.md`

---

**¿Preguntas?** Contacta: webcreativo2@gmail.com

Made with ❤️ by Claude + IAEMPOWER Team
