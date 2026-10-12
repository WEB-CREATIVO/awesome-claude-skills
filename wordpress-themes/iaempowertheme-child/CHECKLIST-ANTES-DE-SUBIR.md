# ✅ CHECKLIST: ANTES DE SUBIR EL CHILD THEME

**Estado:** LISTO PARA PRODUCCIÓN  
**Fecha Creación:** Octubre 2026  
**Última Revisión:** [HOY]  

---

## 🔍 VERIFICACIÓN PRE-SUBIDA

Completa ANTES de subir nada a producción:

### **1. BACKUP (CRÍTICO)**
```
☐ Backup completo de base de datos descargado
☐ Carpeta /wp-content/themes/ descargada
☐ Archivo backup guardado en lugar seguro (Google Drive, OneDrive, etc.)
☐ Fecha de backup anotada: _____________

COMANDO FTP (alternativa):
wget -r ftp://usuario:contraseña@ftp.tudominio.com/public_html/wp-content/
```

### **2. ARCHIVOS PREPARADOS**
```
☐ Carpeta iaempowertheme-child/ existe en scratchpad
☐ Contiene style.css
☐ Contiene functions.php
☐ Contiene README.md
☐ Permisos: 644 (archivos), 755 (carpetas)
```

### **3. INFORMACIÓN DEL SITIO**
```
☐ URL del sitio: https://iaempoweragency.com
☐ Versión WordPress: 6.0+
☐ Theme padre: iaempowertheme 1.12.3
☐ Plugins activos (cuenta): ______
☐ Email admin de WordPress: webcreativo2@gmail.com
```

### **4. VERIFICACIÓN FTP**
```
☐ Credenciales FTP disponibles:
   Servidor: _______________________
   Usuario: ________________________
   Contraseña: ______________________
   
☐ Acceso a /wp-content/themes/ confirmado
☐ Puedo crear carpetas nuevas
☐ Puedo subir archivos
```

---

## 📤 PROCESO DE SUBIDA

### **PASO 1: Conectar FTP**
```
☐ Cliente FTP abierto (FileZilla recomendado)
☐ Conectado a servidor
☐ Navegué a /public_html/wp-content/themes/
☐ Veo carpeta iaempowertheme (tema padre)
```

### **PASO 2: Crear Carpeta Child Theme**
```
☐ Creé carpeta: iaempowertheme-child
   Ubicación: /public_html/wp-content/themes/iaempowertheme-child/
☐ Permisos set a 755
```

### **PASO 3: Subir Archivos**
```
☐ Subí style.css a iaempowertheme-child/
☐ Subí functions.php a iaempowertheme-child/
☐ Subí README.md a iaempowertheme-child/
☐ Verifiqué que los 3 archivos están presentes

Tamaño esperado:
- style.css: ~500 bytes
- functions.php: ~8 KB
- README.md: ~2 KB
```

### **PASO 4: Permisos**
```
☐ style.css: 644 (rw-r--r--)
☐ functions.php: 644 (rw-r--r--)
☐ README.md: 644 (rw-r--r--)
☐ Carpeta iaempowertheme-child: 755 (rwxr-xr-x)

COMANDO (si usas SSH):
chmod 755 /public_html/wp-content/themes/iaempowertheme-child
chmod 644 /public_html/wp-content/themes/iaempowertheme-child/*
```

---

## 🔌 ACTIVACIÓN EN WORDPRESS

### **PASO 1: Verificar en Panel**
```
☐ Accedí a Panel WordPress (Apariencia)
☐ Voy a Apariencia → Temas
☐ Se ve "IAEMPOWER Child" con thumbnail
☐ Dice "Basado en: IAEMPOWER"
☐ No hay errores de PHP (rojo) en el theme
```

### **PASO 2: Activación**
```
☐ Hago PREVIEW primero (botón "Preview")
☐ Se ve idéntico al tema padre (IMPORTANTE)
☐ Cierro preview
☐ Hago clic en "Activar"
☐ Espero 5 segundos a que cargue
```

### **PASO 3: Verificación Inicial**
```
☐ Panel cargó correctamente
☐ No hay mensaje de error en la parte superior
☐ Puedo navegar el panel sin problemas
```

---

## ✔️ VERIFICACIÓN POST-ACTIVACIÓN

**Abre tu sitio en navegador (sin cache):**
```
SHORTCUT: Ctrl+Shift+Delete (borra cache antes)
Luego: Abre https://iaempoweragency.com
```

### **Frontend Checks**
```
☐ Página principal carga
☐ Logo visible y clickeable
☐ Menú principal visible y funciona
☐ Links de menú no tienen parámetros _gl= (_ga=...)
☐ Calculadora de pérdida de horas funciona
☐ Formulario de contacto presente
☐ Footer visible

Timing: Página debe cargar en <3 segundos
```

### **Blog Checks**
```
☐ Apariencia → Personalizar → Lectura
☐ Veo la configuración normal
☐ Blog posts siguen en su lugar
☐ Puedo hacer clic en un post existente
☐ El post se abre sin problemas
☐ Comments sigue funcionando (si están habilitados)
```

### **Páginas Existentes**
```
☐ Cada página existente carga
☐ Menú principal funciona desde cada página
☐ Estilos CSS se ven normales
☐ No hay textos raros o codes visibles
```

### **Admin Checks**
```
☐ Panel WordPress funciona
☐ Aparece "Servicios" en menú lateral izquierdo
☐ Puedo hacer clic en Servicios
☐ Veo "Agregar Nuevo Servicio"
☐ Puedo escribir en formulario de servicio
```

### **SEO Checks**
```
☐ Google Analytics sigue registrando (si usas)
☐ Eventos GA4 se registran (botones, links)
☐ URLs no tienen parámetros GA4 visibles en la barra
```

---

## 🚨 ROLLBACK DE EMERGENCIA

**Si algo se ve muy mal:**

```
OPCIÓN 1: Desde Panel WordPress (5 segundos)
1. Apariencia → Temas
2. Busca "IAEMPOWER" (tema padre)
3. Haz clic en la imagen
4. Haz clic "Activar"
5. Listo - vuelves al padre

OPCIÓN 2: Desde FTP (1 minuto)
1. Accede FTP
2. Navega a /wp-content/themes/
3. Renombra carpeta iaempowertheme-child → iaempowertheme-child-backup
4. WordPress detecciona cambio y vuelve al padre
5. Espera 10 segundos
6. Refresca el sitio

OPCIÓN 3: Contactar Soporte
Email: webcreativo2@gmail.com
Info a proporcionar:
- URL del sitio
- Screenshot del error
- Qué pasó exactamente
```

---

## ✅ VERIFICACIÓN FINAL

**Antes de considerar "completado":**

### **Funcionalidad Completa**
```
☐ Sitio se ve idéntico al anterior
☐ Blog funciona normalmente
☐ Páginas cargan
☐ Admin usa nuevo theme sin errores
☐ Panel de WordPress funciona
☐ Servicios aparece en admin
```

### **SEO Intacto**
```
☐ URLs siguen sin parámetros GA4
☐ Meta titles/descriptions mantienen
☐ Imágenes destacadas se ven
☐ Internal links funcionan
```

### **Performance OK**
```
☐ Página carga en <3 segundos
☐ No hay 404 errors
☐ No hay warnings en consola (F12)
```

---

## 📝 PRÓXIMO PASO: CREAR SERVICIOS

**Una vez TODA la verificación esté ✅:**

```
1. Panel → Servicios → Agregar Nuevo
2. Título: "Consultoría ChatGPT"
3. Contenido: [Tu descripción]
4. Categoría: Selecciona o crea una
5. Imagen destacada: Sube imagen
6. Publicar

RESULTADO: https://iaempoweragency.com/servicios/consultoria-chatgpt/
```

---

## 🎯 Resumen

| Paso | Tarea | Tiempo | Estado |
|------|-------|--------|--------|
| 1 | Backup | 10 min | ☐ |
| 2 | Verificar FTP | 5 min | ☐ |
| 3 | Crear carpeta child | 2 min | ☐ |
| 4 | Subir archivos | 2 min | ☐ |
| 5 | Configurar permisos | 2 min | ☐ |
| 6 | Activar en WordPress | 2 min | ☐ |
| 7 | Verificar frontend | 10 min | ☐ |
| 8 | Verificar admin | 5 min | ☐ |
| 9 | Verificar blog/posts | 5 min | ☐ |
| 10 | Crear servicio de prueba | 5 min | ☐ |

**TOTAL: ~50 minutos**

---

## 📞 Soporte

Si algo falla, proporciona:
1. ¿Qué pasó exactamente?
2. Screenshot del error
3. Error en consola (F12 → Console)
4. URL del sitio
5. Versión WordPress

---

## ✨ ¡LISTO!

Una vez completes TODOS los ☐, tu child theme está:
✅ Instalado  
✅ Activado  
✅ Funcionando  
✅ Listo para crear servicios  
✅ Protegido contra actualizaciones futuras  

**Fecha de completación:** _______________  
**Quién lo hizo:** _______________  
**Problemas encontrados:** Ninguno ☐ / Describe: _________  

---

Documento creado por: Claude Haiku 4.5  
Última actualización: Octubre 2026
