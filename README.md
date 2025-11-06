# 🛡️ Filá Mariscales - Caballeros Templarios

Sitio web oficial de la Filá Mariscales de Caballeros Templarios de Elche.

## 📋 Descripción

Aplicación web desarrollada en PHP con arquitectura MVC para la gestión de la Filá Mariscales. Incluye sistema de noticias, eventos, galería, tienda online, panel de administración y más.

## 🚀 Características

- ✅ Sistema de noticias y publicaciones
- ✅ Gestión de eventos y calendario
- ✅ Galería de imágenes
- ✅ Tienda online con carrito de compras
- ✅ Sistema de contacto y newsletter
- ✅ Panel de administración completo
- ✅ Sistema de usuarios y autenticación
- ✅ Estadísticas y analytics

## 🛠️ Tecnologías

- **Backend**: PHP 8.4+
- **Base de datos**: MySQL/MariaDB
- **Frontend**: Bootstrap 5.3, JavaScript, HTML5, CSS3
- **Servidor**: Apache con mod_rewrite

## 📦 Instalación

### Requisitos

- PHP 8.0 o superior
- MySQL 5.7+ o MariaDB 10.2+
- Apache con mod_rewrite
- Extensiones PHP: PDO, mysqli, mbstring, gd

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/belchote2025/prueba-php.git
   cd prueba-php
   ```

2. **Configurar base de datos**
   - Importar el archivo `database/schema.sql` en tu base de datos
   - O ejecutar `public/create-missing-tables.php` para crear tablas faltantes

3. **Configurar variables de entorno**
   - Copiar `.env.example` a `.env` (si existe)
   - O editar `src/config/config.php` directamente
   - Configurar credenciales de base de datos:
     ```php
     DB_HOST=localhost
     DB_NAME=nombre_base_datos
     DB_USER=usuario
     DB_PASS=contraseña
     ```

4. **Configurar Document Root**
   - En local: Apuntar a `public/` o usar `http://localhost/prueba-php/public/`
   - En hosting: Configurar Document Root a `public_html/public/` o similar

5. **Permisos**
   - Asegurar permisos de escritura en `uploads/` (755)
   - Archivos: 644, Directorios: 755

## 🌐 Configuración de Hosting

### Hostinger / cPanel

1. Subir todos los archivos al servidor
2. Configurar Document Root a `public_html/public/`
3. Verificar que `.htaccess` esté habilitado
4. Configurar credenciales de base de datos en `.env` o `config.php`

### Verificación

Después de la instalación, verifica que todo funcione:
- Base de datos: `https://tudominio.com/public/check-db.php`
- Página principal: `https://tudominio.com/`

## 📁 Estructura del Proyecto

```
prueba-php/
├── public/                 # Punto de entrada (Document Root)
│   ├── index.php          # Router principal
│   ├── assets/            # CSS, JS, imágenes
│   └── .htaccess          # Configuración Apache
├── src/
│   ├── config/            # Configuración
│   ├── controllers/       # Controladores MVC
│   ├── models/            # Modelos de datos
│   ├── views/             # Vistas
│   └── helpers/           # Funciones auxiliares
├── database/
│   └── schema.sql         # Esquema de base de datos
├── uploads/               # Archivos subidos
└── .env                   # Variables de entorno (no subir)
```

## 🔒 Seguridad

⚠️ **IMPORTANTE**: Antes de subir a producción:

1. Eliminar archivos de test/debug:
   - `public/test-*.php`
   - `public/debug-*.php`
   - `public/check-db.php`
   - `public/create-missing-tables.php`

2. Proteger archivos sensibles:
   - `.env` está en `.gitignore`
   - No subir credenciales al repositorio

3. Configurar permisos correctos
4. Habilitar HTTPS

## 📝 Scripts Útiles

- `public/check-db.php` - Verificar estado de base de datos
- `public/create-missing-tables.php` - Crear tablas faltantes
- `database/create-missing-tables.sql` - SQL para crear tablas

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es privado y propiedad de la Filá Mariscales de Caballeros Templarios.

## 👥 Contacto

Para más información, contacta con la administración de la Filá Mariscales.

---

**Desarrollado para la Filá Mariscales de Caballeros Templarios de Elche** 🛡️
