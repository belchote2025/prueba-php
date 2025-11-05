# 🚨 Solución Urgente: Error 403 Forbidden

## Diagnóstico Rápido

Si ves **"Failed to load resource: the server responded with a status of 403"**, sigue estos pasos:

## ✅ Solución Paso a Paso

### Paso 1: Verificar Acceso Directo

1. **Accede directamente a:**
   ```
   https://tudominio.com/public/index.php
   ```

2. **Si funciona**: El problema está en el `.htaccess` de la raíz
3. **Si NO funciona**: El problema puede ser de permisos o configuración del servidor

---

### Paso 2: Usar Archivo de Prueba

1. **Sube el archivo `test-access.php`** a la raíz del hosting (`public_html`)
2. **Accede a:** `https://tudominio.com/test-access.php`
3. **Revisa los resultados** para identificar el problema exacto
4. **⚠️ IMPORTANTE: Elimina `test-access.php` después de usarlo**

---

### Paso 3: Simplificar .htaccess

Si el acceso directo a `public/index.php` funciona pero la raíz no:

#### Opción A: Eliminar .htaccess temporalmente
1. Renombra `.htaccess` a `.htaccess.backup`
2. Prueba acceder a: `https://tudominio.com/public/`
3. Si funciona, el problema está en el `.htaccess`

#### Opción B: Usar .htaccess simplificado
1. Copia el contenido de `.htaccess.hosting` a `.htaccess`
2. O usa este contenido mínimo:

```apache
RewriteEngine On
RewriteCond %{REQUEST_URI} ^/$
RewriteRule ^(.*)$ public/index.php [L]
```

---

### Paso 4: Verificar Permisos en Hostinger

En el **File Manager** de Hostinger:

1. **Carpeta `public/`**: Permisos `755` (drwxr-xr-x)
2. **Archivo `public/index.php`**: Permisos `644` (-rw-r--r--)
3. **Archivo `.htaccess`**: Permisos `644` (-rw-r--r--)
4. **Carpeta `src/`**: Permisos `755`
5. **Carpeta `public/assets/`**: Permisos `755`

**Para cambiar permisos:**
- Click derecho en el archivo/carpeta → **Change Permissions**
- Establece: `755` para carpetas, `644` para archivos

---

### Paso 5: Configurar Document Root (Solución Definitiva)

**La mejor solución es configurar el Document Root:**

1. En Hostinger: **Dominios** → **Gestionar** tu dominio
2. Busca **"Document Root"** o **"Cambiar Document Root"**
3. Cambia de: `/public_html`
4. A: `/public_html/public`
5. **Guarda** y espera unos minutos

**Después de esto:**
- ✅ Elimina el `.htaccess` de la raíz (`public_html`)
- ✅ Solo queda el `.htaccess` dentro de `public/`
- ✅ Accede directamente a: `https://tudominio.com`

---

## 🔍 Diagnóstico Avanzado

### Verificar Logs de Error

1. En Hostinger: **Logs** → **Error Logs**
2. Busca errores relacionados con:
   - `.htaccess`
   - Permisos
   - PHP

### Verificar Configuración de Apache

Algunos hostings bloquean ciertas directivas de `.htaccess`. Si el error persiste:
- Contacta al soporte de Hostinger
- Menciona que estás usando `.htaccess` para reescritura de URLs
- Pregunta si hay restricciones en tu plan

---

## 📋 Checklist Rápido

- [ ] ¿Accede a `https://tudominio.com/public/index.php`? → Sí/No
- [ ] ¿Los permisos son correctos (755/644)? → Sí/No
- [ ] ¿El `.htaccess` existe y tiene permisos 644? → Sí/No
- [ ] ¿PHP está activo en el hosting? → Sí/No
- [ ] ¿Probaste el archivo `test-access.php`? → Sí/No

---

## 🆘 Si Nada Funciona

1. **Elimina completamente el `.htaccess` de la raíz**
2. **Accede directamente a:** `https://tudominio.com/public/`
3. **Si funciona**: Configura el Document Root para que apunte a `public/`
4. **Si NO funciona**: Contacta al soporte de Hostinger con:
   - El error exacto (403 Forbidden)
   - La URL que estás intentando acceder
   - Que el acceso directo a `public/index.php` funciona (o no)

---

## ⚡ Solución Rápida Temporal

Si necesitas que funcione **YA**:

1. **Elimina el `.htaccess` de la raíz**
2. **Accede siempre a:** `https://tudominio.com/public/`
3. **Configura el Document Root** cuando tengas tiempo

Esto permitirá que el sitio funcione mientras resuelves el problema del `.htaccess`.

