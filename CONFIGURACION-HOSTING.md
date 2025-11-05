# 📋 Configuración para Hosting

## ⚠️ Problema Común: Error 403 Forbidden

Si después de subir el proyecto al hosting aparece un error **403 Forbidden**, sigue estos pasos:

## 🔧 Solución 1: Configurar Document Root (Recomendado)

La mejor solución es configurar el **Document Root** del hosting para que apunte directamente a la carpeta `public/`:

### En Hostinger:
1. Ve a **Panel de Control** → **Dominios**
2. Selecciona tu dominio
3. Busca **Configuración de Document Root**
4. Cambia el Document Root de `/public_html` a `/public_html/public`
5. Guarda los cambios

### En otros hostings (cPanel, Plesk, etc.):
- Busca la opción **Document Root** o **Public HTML**
- Configúrala para que apunte a la carpeta `public/` de tu proyecto

**Después de esto, elimina el archivo `.htaccess` de la raíz del proyecto** (no el de `public/`).

---

## 🔧 Solución 2: Si NO puedes cambiar el Document Root

Si tu hosting no permite cambiar el Document Root, el proyecto está configurado para funcionar automáticamente. Solo asegúrate de:

1. **Subir TODA la estructura del proyecto** (no solo la carpeta `public/`)
2. El archivo `.htaccess` en la raíz debe estar presente
3. Verificar permisos de archivos:
   ```bash
   chmod 755 para directorios
   chmod 644 para archivos
   ```

### Verificar permisos en el hosting:
- **Carpetas**: 755
- **Archivos PHP**: 644
- **.htaccess**: 644

---

## 🗄️ Configuración de Base de Datos

Edita el archivo `src/config/config.php` y actualiza las credenciales de la base de datos:

```php
define('DB_HOST', 'localhost'); // O la IP del servidor de BD
define('DB_NAME', 'nombre_de_tu_bd');
define('DB_USER', 'usuario_bd');
define('DB_PASS', 'contraseña_bd');
```

---

## 🔍 Verificar que Funciona

1. Accede a tu dominio: `https://tudominio.com`
2. Deberías ver la página principal
3. Si ves error 403, revisa los permisos y el `.htaccess`

---

## 📝 Notas Importantes

- El proyecto detecta automáticamente si está en local o hosting
- Las URLs se generan dinámicamente según el entorno
- No necesitas cambiar rutas hardcodeadas (ya están corregidas)

---

## 🆘 Si sigue sin funcionar

1. **Verifica que PHP está activo** (versión 7.4 o superior)
2. **Revisa los logs de error** del hosting
3. **Prueba con `.htaccess` simplificado**:
   - Si el `.htaccess` actual causa problemas, renombra `.htaccess` a `.htaccess.backup`
   - Renombra `.htaccess.simple` a `.htaccess`
   - Prueba de nuevo
4. **Accede directamente** a: `https://tudominio.com/public/index.php`
5. **Verifica permisos**:
   - Todos los archivos: 644
   - Todas las carpetas: 755
   - `.htaccess`: 644

## 📌 Solución Rápida para Error 403

Si el error 403 persiste:

1. **Elimina temporalmente el `.htaccess` de la raíz**
2. Accede directamente a: `https://tudominio.com/public/`
3. Si funciona, el problema está en el `.htaccess`
4. Usa el `.htaccess.simple` como alternativa

