# 📋 Instrucciones para Crear las Tablas Faltantes

## 🎯 Método 1: Usando el Script PHP (Recomendado - Más Fácil)

### Paso 1: Acceder al Script
Abre tu navegador y accede a:
```
https://tudominio.com/public/create-missing-tables.php
```

### Paso 2: Ejecutar
1. Verás una página con información sobre las tablas que se crearán
2. Haz clic en **"✅ Ejecutar Creación de Tablas"**
3. El script creará las tablas automáticamente
4. Verás un resumen con el estado de cada tabla

### Paso 3: Verificar
Haz clic en **"🔍 Verificar Base de Datos"** para confirmar que todo está correcto.

## 🎯 Método 2: Usando phpMyAdmin

### Paso 1: Acceder a phpMyAdmin
1. Accede a tu panel de hosting (hPanel, cPanel, etc.)
2. Abre **phpMyAdmin**
3. Selecciona tu base de datos: `u600265163_HAggBlS0j_pruebaphp2`

### Paso 2: Ejecutar el SQL
1. Haz clic en la pestaña **"SQL"**
2. Copia el contenido del archivo `database/create-missing-tables.sql`
3. Pégalo en el área de texto
4. Haz clic en **"Continuar"** o **"Ejecutar"**

### Paso 3: Verificar
1. Ve a la pestaña **"Estructura"**
2. Verifica que las siguientes tablas existan:
   - ✅ `usuarios`
   - ✅ `contactos`
   - ✅ `newsletter`
   - ✅ `configuracion`

## 🎯 Método 3: Desde Línea de Comandos (SSH)

Si tienes acceso SSH:

```bash
# Conectarte a MySQL
mysql -u u600265163_HAggBlS0j_pruebaphp2 -p u600265163_HAggBlS0j_pruebaphp2

# O ejecutar el archivo SQL directamente
mysql -u u600265163_HAggBlS0j_pruebaphp2 -p u600265163_HAggBlS0j_pruebaphp2 < database/create-missing-tables.sql
```

## 📊 Tablas que se Crearán

### 1. `usuarios`
- **Propósito**: Usuarios del sistema (alternativa a `users`)
- **Nota**: Ya tienes `users`, esta es una tabla adicional para compatibilidad

### 2. `contactos`
- **Propósito**: Almacenar mensajes del formulario de contacto
- **Campos**: nombre, email, teléfono, asunto, mensaje, leído, fecha

### 3. `newsletter`
- **Propósito**: Suscripciones al newsletter (alternativa a `newsletter_subscriptions`)
- **Nota**: Ya tienes `newsletter_subscriptions`, esta es una tabla adicional para compatibilidad

### 4. `configuracion`
- **Propósito**: Configuración general del sitio
- **Datos iniciales**: Se insertan automáticamente:
  - Nombre del sitio
  - Descripción
  - Email de contacto
  - Teléfono
  - Dirección
  - Redes sociales
  - Modo mantenimiento

## ✅ Verificación Final

Después de crear las tablas, ejecuta el script de verificación:

```
https://tudominio.com/public/check-db.php?html=1
```

Deberías ver:
- ✅ `usuarios`: Existe
- ✅ `contactos`: Existe
- ✅ `newsletter`: Existe
- ✅ `configuracion`: Existe (con datos iniciales)

## 🔒 Seguridad

⚠️ **IMPORTANTE**: Después de crear las tablas:

1. **Elimina o protege** el archivo `public/create-missing-tables.php`
2. **Protege** el archivo `database/create-missing-tables.sql` si lo subes al servidor

## ❓ Problemas Comunes

### Error: "Table already exists"
- **Solución**: Es normal, significa que la tabla ya existe. El script usa `CREATE TABLE IF NOT EXISTS` para evitar errores.

### Error: "Access denied"
- **Solución**: Verifica que el usuario MySQL tenga permisos para crear tablas.

### Error: "Syntax error"
- **Solución**: Verifica que estés usando MySQL/MariaDB 5.7+ o 10.2+

## 📝 Notas

- Las tablas se crean con codificación `utf8mb4` para soportar emojis y caracteres especiales
- Se usan índices para optimizar las consultas
- Los datos iniciales en `configuracion` se insertan solo si no existen (usando `INSERT IGNORE`)

## ✅ Resultado Esperado

Después de ejecutar el script, tendrás:
- ✅ 4 tablas nuevas creadas
- ✅ Datos iniciales en `configuracion`
- ✅ Base de datos completa y lista para usar

¡Listo! Tu base de datos estará completamente configurada.

