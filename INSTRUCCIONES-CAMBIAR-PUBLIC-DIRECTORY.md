# 📋 Instrucciones para Cambiar Public Directory en Hostinger

## ✅ Pasos Exactos

1. **En la sección "Public directory"**, cambia el campo de:
   - `public_html/`
   
   A:
   - `public`

2. **Haz clic en "Save"** (Guardar)

3. **Espera unos minutos** para que se apliquen los cambios

## 📁 Estructura de Archivos

Después de cambiar, la estructura debería ser:

```
public_html/
├── public/          ← Este será el Document Root
│   ├── index.php
│   ├── assets/
│   └── .htaccess
├── src/
├── .env
├── .htaccess       ← Este ya no será necesario (puedes eliminarlo)
└── otros archivos...
```

## ✅ Después de Cambiar

Una vez que cambies el Public directory a `public/`:

1. **Las URLs serán más limpias**:
   - `https://goldenrod-finch-839887.hostingersite.com/historia`
   - Sin `/public/` en la URL

2. **El `URL_ROOT` se detectará automáticamente**:
   - Será solo el dominio: `https://goldenrod-finch-839887.hostingersite.com`
   - Sin `/public` al final

3. **Ya no necesitarás el `.htaccess` de la raíz**:
   - Puedes eliminarlo después de verificar que todo funciona

## 🔍 Verificación

Después de cambiar y esperar unos minutos:

1. Accede a: `https://goldenrod-finch-839887.hostingersite.com/`
2. Debería mostrar la página principal
3. Haz clic en "Historia" desde el menú
4. Deberías ir a: `https://goldenrod-finch-839887.hostingersite.com/historia`
5. **NO debería redirigir** a `/public/public/index.php`

## ⚠️ Importante

- Asegúrate de que todos los archivos estén en la estructura correcta
- El `index.php` debe estar en `public_html/public/index.php`
- Los assets (CSS, JS, imágenes) deben estar en `public_html/public/assets/`

¡Cambia el Public directory a `public` y guarda!

