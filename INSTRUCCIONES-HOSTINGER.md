# 📋 Instrucciones para Configurar en Hostinger

## 🎯 Solución Recomendada: Configurar Document Root en `public/`

El problema de duplicación de `/public/` se resolvería si configuras el **Document Root** directamente en la carpeta `public/` en lugar de la raíz del proyecto.

## 📝 Pasos en Hostinger

1. **Accede al panel de Hostinger** (hPanel)
2. **Ve a "Dominios"** o "Administrar dominio"
3. **Busca "Document Root"** o "Raíz del Documento"
4. **Cambia de**: `/` (raíz)
5. **A**: `/public` o `/public/`
6. **Guarda los cambios**

## ✅ Después de Configurar

Una vez que el Document Root esté en `public/`:

1. **Elimina el `.htaccess` de la raíz** (ya no es necesario)
2. **Actualiza `URL_ROOT`** en `src/config/config.php`:
   - Debe ser solo el dominio: `https://goldenrod-finch-839887.hostingersite.com`
   - Sin `/public` al final

3. **Actualiza todos los enlaces** en `src/views/layouts/main.php`:
   - Cambiar `URL_ROOT . '/historia'` a `URL_ROOT . '/historia'` (sin cambios)
   - Pero `URL_ROOT` ya no incluirá `/public`

## 🔍 Verificación

Después de configurar:
- Las URLs serán: `https://goldenrod-finch-839887.hostingersite.com/historia`
- Sin `/public/` en la URL
- El `.htaccess` de `public/` manejará todo
- No habrá duplicación

## ⚠️ Si No Puedes Cambiar el Document Root

Si Hostinger no permite cambiar el Document Root, contacta al soporte explicando:
- Tienes una aplicación PHP con estructura MVC
- Necesitas que el Document Root apunte a la carpeta `public/`
- Actualmente está en la raíz y causa problemas de routing

## 📞 Contacto con Soporte

Si necesitas ayuda, menciona:
- "Necesito configurar el Document Root en la subcarpeta `public/`"
- "Tengo una aplicación PHP con estructura MVC y el index.php está en `public/`"
- "Actualmente el Document Root está en la raíz y causa problemas de routing"

Esta es la solución más limpia y definitiva.

