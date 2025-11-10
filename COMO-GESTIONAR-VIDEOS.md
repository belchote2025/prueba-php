# 📹 Guía: Cómo Gestionar Videos en la Galería Multimedia

## 🎯 Acceso a la Galería Multimedia

### Para los Visitantes:
1. Ve al menú principal del sitio web
2. Haz clic en **"Utilidades"** (menú desplegable)
3. Selecciona **"Galería Multimedia"**
4. O accede directamente: `http://localhost/prueba-php/public/galeria-multimedia`

### URL Directa:
```
http://localhost/prueba-php/public/galeria-multimedia
```

---

## ➕ Cómo Añadir un Nuevo Video

### Paso 1: Acceder al Panel de Administración
1. Ve a: `http://localhost/prueba-php/public/admin/login`
2. Inicia sesión con tus credenciales de administrador

### Paso 2: Ir a la Gestión de Videos
1. En el menú superior del panel, haz clic en **"Videos"**
2. O accede directamente: `http://localhost/prueba-php/public/admin/videos`

### Paso 3: Crear un Nuevo Video
1. Haz clic en el botón **"Nuevo Video"** (esquina superior derecha)
2. Completa el formulario:
   - **Título**: Nombre del video
   - **Descripción**: Descripción del contenido
   - **URL del Video**: 
     - Para YouTube: `https://www.youtube.com/watch?v=VIDEO_ID`
     - Para Vimeo: `https://vimeo.com/VIDEO_ID`
     - Para video local: Sube el archivo de video
   - **Tipo**: Selecciona YouTube, Vimeo o Local
   - **Categoría**: Elige una categoría (desfiles, bandas, eventos, etc.)
   - **Duración**: Duración en segundos (opcional)
   - **Video activo**: ✅ **IMPORTANTE**: Marca esta casilla para que el video aparezca en la galería pública
3. Haz clic en **"Guardar Video"**

### Paso 4: Verificar
1. Ve a la galería multimedia pública
2. El video debería aparecer si está marcado como activo

---

## ✏️ Cómo Editar un Video Existente

### Paso 1: Acceder a la Lista de Videos
1. Ve a: `http://localhost/prueba-php/public/admin/videos`
2. Verás una tabla con todos los videos

### Paso 2: Editar el Video
1. Haz clic en el botón **"Editar"** (ícono de lápiz) del video que quieres modificar
2. Modifica los campos que necesites
3. **IMPORTANTE**: Asegúrate de que **"Video activo"** esté marcado si quieres que se muestre en la galería
4. Haz clic en **"Actualizar Video"**

---

## 🗑️ Cómo Eliminar un Video

1. Ve a: `http://localhost/prueba-php/public/admin/videos`
2. Haz clic en el botón **"Eliminar"** (ícono de papelera) del video
3. Confirma la eliminación

---

## ✅ Activar Videos Inactivos

Si tienes videos que no aparecen en la galería, pueden estar inactivos:

### Opción 1: Desde el Panel de Administración
1. Ve a: `http://localhost/prueba-php/public/admin/videos`
2. Edita el video y marca la casilla **"Video activo"**

### Opción 2: Usando el Script de Activación
1. Ve a: `http://localhost/prueba-php/public/activar-video.php`
2. Verás una lista de todos los videos y su estado
3. Haz clic en **"Activar"** para los videos inactivos

---

## 🔍 Tipos de Videos Soportados

### 1. YouTube
- **Formato de URL**: `https://www.youtube.com/watch?v=VIDEO_ID`
- **Ejemplo**: `https://www.youtube.com/watch?v=dQw4w9WgXcQ`
- El sistema generará automáticamente la miniatura

### 2. Vimeo
- **Formato de URL**: `https://vimeo.com/VIDEO_ID`
- **Ejemplo**: `https://vimeo.com/123456789`
- Puedes subir una miniatura personalizada

### 3. Video Local
- Sube el archivo de video desde tu computadora
- Formatos soportados: MP4, WebM, OGG
- También puedes subir una miniatura personalizada

---

## 📊 Estadísticas de Videos

En el panel de administración (`/admin/videos`) puedes ver:
- **Total de Videos**: Todos los videos en la base de datos
- **Videos Activos**: Videos visibles en la galería pública
- **Videos Inactivos**: Videos ocultos de la galería pública

---

## 🆘 Solución de Problemas

### El video no aparece en la galería
1. ✅ Verifica que el video esté marcado como **"Activo"**
2. ✅ Asegúrate de que la URL del video sea correcta
3. ✅ Revisa que el video no haya sido eliminado

### Error al subir video local
1. Verifica que el archivo no sea muy grande (máximo recomendado: 100MB)
2. Asegúrate de que el formato sea compatible (MP4, WebM, OGG)
3. Verifica los permisos de la carpeta `public/uploads/videos/`

### El video de YouTube no se reproduce
1. Verifica que la URL sea correcta
2. Asegúrate de que el video de YouTube no esté privado o restringido
3. Verifica que el ID del video sea correcto

---

## 📝 Notas Importantes

- **Solo los videos activos** se muestran en la galería pública
- Los videos inactivos **solo son visibles** en el panel de administración
- Puedes tener videos inactivos para guardarlos como borradores
- La miniatura se genera automáticamente para videos de YouTube
- Para videos locales y Vimeo, puedes subir una miniatura personalizada

---

## 🔗 Enlaces Útiles

- **Panel de Administración**: `http://localhost/prueba-php/public/admin/videos`
- **Galería Multimedia Pública**: `http://localhost/prueba-php/public/galeria-multimedia`
- **Activar Videos**: `http://localhost/prueba-php/public/activar-video.php`
- **Insertar Videos de Ejemplo**: `http://localhost/prueba-php/public/insertar-videos-ejemplo.php`
- **Diagnóstico de Videos**: `http://localhost/prueba-php/public/debug-video-especifico.php`

---

## 📞 ¿Necesitas Ayuda?

Si tienes problemas:
1. Revisa los logs de error en `error_log`
2. Ejecuta el script de diagnóstico: `/public/debug-video-especifico.php`
3. Verifica que la base de datos tenga la tabla `videos` creada
4. Asegúrate de tener permisos de administrador

