# ✅ Cómo Verificar que la Base de Datos Funciona Correctamente

## 🎯 Método Rápido

### Opción 1: Acceso desde el Navegador (Recomendado)

1. **Abre tu navegador** y accede a:
   ```
   https://tudominio.com/public/check-db.php
   ```
   O si estás en local:
   ```
   http://localhost/prueba-php/public/check-db.php
   ```

2. **Para ver en formato HTML** (más fácil de leer):
   ```
   https://tudominio.com/public/check-db.php?html=1
   ```

### Opción 2: Desde la Línea de Comandos

Si tienes acceso SSH o estás en local:
```bash
php public/check-db.php
```

## 📊 Qué Muestra el Script

El script `check-db.php` verifica y muestra:

### ✅ Información del Entorno
- Versión de PHP
- Servidor actual
- Fecha y hora

### ✅ Configuración de Base de Datos
- Host, nombre de base de datos, usuario
- Estado del archivo `.env`

### ✅ Estado de la Conexión
- ✅ **Conexión exitosa**: Todo funciona correctamente
- ❌ **Error de conexión**: Muestra el error específico y sugerencias

### ✅ Información del Servidor MySQL
- Versión de MySQL/MariaDB
- Hora del servidor
- Base de datos actual

### ✅ Lista de Tablas
- **Tablas principales** que deberían existir:
  - `usuarios` / `users` - Usuarios del sistema
  - `noticias` - Noticias/publicaciones
  - `eventos` - Eventos de la filá
  - `galeria` - Imágenes de la galería
  - `productos` - Productos de la tienda
  - `pedidos` - Pedidos realizados
  - `contactos` - Formularios de contacto
  - `newsletter` / `newsletter_subscriptions` - Suscripciones
  - `documentos` - Documentos subidos
  - `visitas` - Estadísticas de visitas
  - `configuracion` - Configuración del sistema

- **Para cada tabla** muestra:
  - Nombre de la tabla
  - Número de registros
  - Estado (OK o Error)

### ✅ Pruebas de Funcionalidad
- Prueba de consultas SELECT
- Prueba de transacciones

## 🔍 Interpretación de Resultados

### ✅ Todo Funciona Correctamente

Si ves:
```
✅ CONEXIÓN EXITOSA
✅ ESTADO GENERAL: BASE DE DATOS FUNCIONANDO CORRECTAMENTE
```

**Significado**: La base de datos está funcionando perfectamente.

### ⚠️ Tablas Faltantes

Si ves:
```
⚠️ TABLAS FALTANTES (opcionales):
  - Noticias (noticias)
```

**Significado**: Algunas tablas no existen. Esto puede ser normal si:
- Es una instalación nueva
- No has importado todas las tablas
- Algunas funcionalidades no están en uso

**Solución**: Importa el archivo `database/schema.sql` si necesitas todas las tablas.

### ❌ Error de Conexión

Si ves:
```
❌ ERROR DE CONEXIÓN
Mensaje: Access denied for user...
```

**Posibles causas**:
1. **Credenciales incorrectas**: Verifica `.env` o `config.php`
2. **Base de datos no existe**: Crea la base de datos primero
3. **Usuario sin permisos**: Verifica permisos del usuario MySQL
4. **Servidor MySQL no está corriendo**: Verifica que MySQL esté activo

**Solución**:
1. Verifica las credenciales en `.env` o `src/config/config.php`
2. Verifica que la base de datos exista
3. Verifica que el usuario tenga permisos
4. En local, verifica que XAMPP/MySQL esté corriendo

## 🛠️ Solución de Problemas

### Problema: "No se encontraron tablas"

**Solución**: Importa el archivo `database/schema.sql`:
```sql
-- En phpMyAdmin o MySQL CLI:
SOURCE database/schema.sql;
```

### Problema: "Access denied"

**Solución**:
1. Verifica que las credenciales en `.env` sean correctas
2. En producción, verifica que el usuario tenga permisos en la base de datos
3. Verifica que el host sea correcto (puede ser `localhost` o una IP específica)

### Problema: "Base de datos no existe"

**Solución**: Crea la base de datos primero:
```sql
CREATE DATABASE nombre_de_tu_base_de_datos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 📝 Verificación Regular

Es recomendable ejecutar este script:
- ✅ Después de subir el proyecto al hosting
- ✅ Después de cambios en la configuración de base de datos
- ✅ Si hay errores en la aplicación
- ✅ Periódicamente para verificar que todo funciona

## 🔒 Seguridad

⚠️ **IMPORTANTE**: Este script muestra información sensible. 

**En producción**:
- Elimina o protege el archivo `check-db.php` después de usarlo
- O agrega autenticación básica:
  ```php
  // Al inicio del archivo
  if (!isset($_SERVER['PHP_AUTH_USER']) || 
      $_SERVER['PHP_AUTH_USER'] !== 'admin' || 
      $_SERVER['PHP_AUTH_PW'] !== 'tu_password') {
      header('WWW-Authenticate: Basic realm="Database Check"');
      header('HTTP/1.0 401 Unauthorized');
      die('Acceso denegado');
  }
  ```

## ✅ Resumen

El script `check-db.php` es tu herramienta principal para verificar que la base de datos funciona correctamente. Úsalo siempre que necesites diagnosticar problemas o verificar el estado de tu base de datos.

**Acceso rápido**:
```
https://tudominio.com/public/check-db.php?html=1
```

