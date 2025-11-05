# 🗄️ Configuración de Base de Datos para Hosting

## ⚠️ Error Actual
```
Fatal error: Call to a member function prepare() on null
```

Este error indica que **la conexión a la base de datos está fallando**.

## ✅ Solución

### Opción 1: Configurar en archivo `.env` (Recomendado)

1. **Edita el archivo `.env` en la raíz del proyecto**
2. **Descomenta y configura las credenciales de producción**:

```ini
# Configuración para producción (Hostinger)
DB_HOST=localhost
DB_NAME=u600265163_HAggBlS0j_pruebaphp2
DB_USER=u600265163_HAggBlS0j_pruebaphp2
DB_PASS=Belchote1#
```

3. **Comenta las credenciales de desarrollo local**:
```ini
# Configuración para desarrollo local (XAMPP)
# DB_HOST=localhost
# DB_NAME=mariscales_db
# DB_USER=root
# DB_PASS=
```

4. **Sube el archivo `.env` actualizado al hosting**

---

### Opción 2: Configurar directamente en `src/config/config.php`

Si prefieres no usar `.env`, edita directamente `src/config/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'u600265163_HAggBlS0j_pruebaphp2');
define('DB_USER', 'u600265163_HAggBlS0j_pruebaphp2');
define('DB_PASS', 'Belchote1#');
```

---

## 🔍 Verificar Credenciales en Hostinger

1. **Accede a tu panel de Hostinger**
2. Ve a **Bases de Datos** → **MySQL Databases**
3. Verifica:
   - **Nombre de la base de datos**: `u600265163_HAggBlS0j_pruebaphp2`
   - **Usuario**: `u600265163_HAggBlS0j_pruebaphp2`
   - **Contraseña**: La que configuraste
   - **Host**: Generalmente `localhost` (puede ser diferente)

---

## 📝 Notas Importantes

- **Host**: En Hostinger generalmente es `localhost`, pero puede ser diferente
- **Nombre de BD**: En Hostinger suele tener el prefijo del usuario
- **Contraseña**: Asegúrate de usar la contraseña correcta (sensible a mayúsculas/minúsculas)

---

## 🧪 Verificar Conexión

Después de configurar, el sistema registrará automáticamente los errores de conexión en los logs si hay problemas.

Para verificar manualmente, puedes crear un archivo temporal `test-db.php`:

```php
<?php
require_once 'src/config/config.php';

try {
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    echo "✅ Conexión exitosa!";
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
```

**⚠️ IMPORTANTE: Elimina este archivo después de probar por seguridad.**

---

## 🔄 Si Cambias de Entorno

- **Para desarrollo local**: Comenta las credenciales de producción y descomenta las de local
- **Para producción**: Comenta las credenciales de local y descomenta las de producción

