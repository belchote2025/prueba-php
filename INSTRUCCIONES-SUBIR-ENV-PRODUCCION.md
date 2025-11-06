# 📤 INSTRUCCIONES: Subir .env a Producción

## ⚠️ PROBLEMA ACTUAL
El error aparece porque el archivo `.env` no está en el servidor de producción o no tiene las credenciales correctas.

## ✅ SOLUCIÓN PASO A PASO

### Paso 1: Verificar el archivo .env local

Abre el archivo `.env` en la raíz de tu proyecto y verifica que tenga estas líneas **DESCOMENTADAS** (sin # al inicio):

```env
DB_HOST=localhost
DB_NAME=u600265163_HAggBlS0j_pruebaphp2
DB_USER=u600265163_HAggBlS0j_pruebaphp2
DB_PASS=Belchote1#
```

### Paso 2: Subir el archivo .env al servidor

**OPCIÓN A: Por FTP/FileZilla**
1. Conecta a tu servidor Hostinger por FTP
2. Navega a la carpeta raíz del proyecto (donde están las carpetas `src/`, `public/`, etc.)
3. Sube el archivo `.env` a esa ubicación
4. Asegúrate de que el archivo se llame exactamente `.env` (con el punto al inicio)

**OPCIÓN B: Por cPanel File Manager**
1. Accede a cPanel de Hostinger
2. Abre "File Manager"
3. Navega a la carpeta raíz de tu proyecto (normalmente `public_html/` o `public_html/prueba-php/`)
4. Haz clic en "Upload" o "Subir"
5. Selecciona tu archivo `.env`
6. Asegúrate de que el archivo se suba correctamente

**OPCIÓN C: Crear directamente en el servidor**
1. Accede a cPanel File Manager
2. Navega a la raíz del proyecto
3. Crea un nuevo archivo llamado `.env`
4. Copia y pega este contenido:

```env
# Configuración de Base de Datos - PRODUCCIÓN
DB_HOST=localhost
DB_NAME=u600265163_HAggBlS0j_pruebaphp2
DB_USER=u600265163_HAggBlS0j_pruebaphp2
DB_PASS=Belchote1#
```

### Paso 3: Verificar permisos del archivo

El archivo `.env` debe tener permisos **644** o **600** (más seguro):
- En File Manager: Click derecho → Cambiar permisos → 644 o 600

### Paso 4: Verificar ubicación

El archivo `.env` debe estar en la **misma carpeta** que:
- `src/`
- `public/`
- `database/`
- `.gitignore`

**Estructura correcta:**
```
proyecto/
├── .env          ← AQUÍ debe estar
├── .gitignore
├── src/
├── public/
├── database/
└── ...
```

### Paso 5: Verificar que no esté en .gitignore

El archivo `.env` NO debe subirse a Git (está en `.gitignore`), pero SÍ debe estar en el servidor.

## 🔍 VERIFICACIÓN

Después de subir el archivo:

1. **Recarga la página**: https://goldenrod-finch-839887.hostingersite.com/
2. **El error debería desaparecer**

Si sigue apareciendo el error:

1. Verifica que el archivo se llame exactamente `.env` (con punto, sin extensión)
2. Verifica que esté en la raíz del proyecto (mismo nivel que `src/` y `public/`)
3. Verifica que las credenciales sean correctas
4. Verifica que no haya espacios extra en los valores
5. Verifica los permisos del archivo (644 o 600)

## 📝 CONTENIDO COMPLETO DEL .env PARA PRODUCCIÓN

Copia este contenido exacto en tu archivo `.env` en el servidor:

```env
# ============================================
# CONFIGURACIÓN DE BASE DE DATOS - PRODUCCIÓN
# ============================================
DB_HOST=localhost
DB_NAME=u600265163_HAggBlS0j_pruebaphp2
DB_USER=u600265163_HAggBlS0j_pruebaphp2
DB_PASS=Belchote1#

# Si el proyecto está en subcarpeta, déjalo vacío si va en raíz
URL_BASE_PATH=
```

## ⚠️ IMPORTANTE

- ✅ El archivo `.env` debe estar en el servidor
- ✅ NO debe estar en Git (está en `.gitignore`)
- ✅ Debe tener las credenciales correctas de producción
- ✅ Debe estar en la raíz del proyecto (mismo nivel que `src/`)

## 🆘 SI SIGUE SIN FUNCIONAR

Si después de seguir estos pasos sigue sin funcionar:

1. Verifica la ruta del archivo en `config.php`:
   ```php
   $envFile = dirname(dirname(__DIR__)) . '/.env';
   ```
   Esta ruta busca el `.env` en la raíz del proyecto.

2. Crea un archivo de prueba `test-env.php` en `public/`:
   ```php
   <?php
   $envFile = dirname(__DIR__) . '/.env';
   echo "Buscando .env en: " . $envFile . "<br>";
   echo "¿Existe? " . (file_exists($envFile) ? 'SÍ' : 'NO') . "<br>";
   if (file_exists($envFile)) {
       echo "Contenido:<br><pre>" . htmlspecialchars(file_get_contents($envFile)) . "</pre>";
   }
   ?>
   ```
   Accede a: `https://goldenrod-finch-839887.hostingersite.com/test-env.php`
   Esto te dirá exactamente dónde está buscando el archivo y si existe.

---

**Después de subir el `.env`, el error debería desaparecer.** ✅

