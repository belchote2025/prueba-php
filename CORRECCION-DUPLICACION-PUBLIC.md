# 🔧 Corrección: Duplicación de /public en las URLs

## ❌ Problema

Al hacer clic en enlaces del menú (como "Historia"), la URL se duplica:
- **URL generada**: `https://goldenrod-finch-839887.hostingersite.com/public/public/index.php`
- **URL correcta**: `https://goldenrod-finch-839887.hostingersite.com/public/historia`

## ✅ Solución Aplicada

### 1. `.htaccess` de la raíz
- ✅ Agregada regla para NO redirigir si la URL ya contiene `/public/`
- ✅ Esto evita la duplicación cuando se accede directamente a rutas que ya tienen `/public/`

### 2. `src/config/config.php`
- ✅ Mejorada la detección de `URL_ROOT`
- ✅ Ahora detecta correctamente cuando estamos en `public/index.php`
- ✅ Genera `URL_ROOT` como `https://dominio.com/public` (sin duplicar)

## 📦 Archivos a Subir

**IMPORTANTE**: Sube estos archivos actualizados:

1. **`.htaccess`** (en la raíz `public_html`)
2. **`src/config/config.php`** (con la detección mejorada)

## 🔍 Verificación

Después de subir los archivos:

1. Accede a tu sitio
2. Haz clic en "Historia" en el menú
3. Verifica que la URL es: `https://dominio.com/public/historia`
4. NO debe aparecer `/public/public/`

## ⚠️ Nota sobre la carpeta `api/`

Si hay una carpeta `api/` en el hosting que no debería estar:
- Es una carpeta antigua que eliminamos localmente
- Puedes eliminarla del hosting también
- No afecta el funcionamiento, pero es mejor mantenerlo limpio

