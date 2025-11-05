# 🔧 Solución Error 403 en Hostinger

## Problema
Error 403 Forbidden al acceder al dominio después de subir el proyecto.

## ✅ Solución Rápida (Recomendada)

### Opción 1: Configurar Document Root en Hostinger

1. **Accede a tu panel de Hostinger**
2. Ve a **Dominios** → **Gestionar**
3. Busca **"Configuración de Document Root"** o **"Cambiar Document Root"**
4. Cambia de: `/public_html`
5. A: `/public_html/public`
6. **Guarda los cambios**

**Después de esto:**
- Elimina el archivo `.htaccess` de la raíz (`public_html`)
- El `.htaccess` de `public/` se encargará de todo

---

### Opción 2: Si NO puedes cambiar Document Root

Si tu plan de Hostinger no permite cambiar el Document Root, sigue estos pasos:

#### 1. Verificar permisos en el File Manager

En Hostinger File Manager, asegúrate de que:
- **Todas las carpetas**: Permisos `755` (drwxr-xr-x)
- **Todos los archivos PHP**: Permisos `644` (-rw-r--r--)
- **Archivo `.htaccess`**: Permisos `644`

#### 2. Probar acceso directo

Intenta acceder directamente a:
```
https://tudominio.com/public/index.php
```

Si esto funciona, el problema está en el `.htaccess` de la raíz.

#### 3. Usar `.htaccess` simplificado

Si el `.htaccess` actual causa problemas:

1. En el File Manager, renombra `.htaccess` a `.htaccess.backup`
2. Crea un nuevo `.htaccess` con este contenido mínimo:

```apache
RewriteEngine On
DirectoryIndex public/index.php index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ public/$1 [L]
```

---

## 🔍 Verificación

Después de aplicar los cambios:

1. Accede a: `https://tudominio.com`
2. Deberías ver la página principal
3. Si aún ves 403, prueba: `https://tudominio.com/public/`

---

## ⚠️ Problemas Comunes

### Error 403 persiste
- Verifica que PHP está activo (versión 7.4+)
- Revisa los logs de error en Hostinger
- Asegúrate de que `public/index.php` existe y tiene permisos correctos

### Página en blanco
- Verifica la configuración de base de datos en `src/config/config.php`
- Revisa los logs de PHP en Hostinger

### CSS/JS no cargan
- Verifica que las rutas en las vistas usan `URL_ROOT` (ya están configuradas)
- Limpia la caché del navegador

---

## 📞 Soporte

Si el problema persiste después de seguir estos pasos:
1. Revisa los logs de error en Hostinger
2. Verifica que todos los archivos se subieron correctamente
3. Contacta al soporte de Hostinger mencionando el error 403

