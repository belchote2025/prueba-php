# Filá Mariscales Web - Versión 2.0.0

Sitio web moderno y optimizado para la **Filá Mariscales de Caballeros Templarios de Elche**.

## 🚀 Características

- **Frontend Moderno**: HTML5, CSS3, JavaScript ES6+ con Bootstrap 5
- **Backend Optimizado**: PHP 8+ con arquitectura MVC
- **Base de Datos**: MySQL con estructura optimizada
- **API REST**: Comunicación frontend-backend
- **Panel de Administración**: Moderno y responsive
- **Responsive Design**: Compatible con todos los dispositivos
- **SEO Optimizado**: Meta tags y estructura semántica

## 📁 Estructura del Proyecto

```
fila-mariscales-web/
├── index.html              # Página principal
├── login.html              # Página de login
├── assets/                 # Recursos estáticos
│   ├── css/               # Estilos CSS
│   ├── js/                # JavaScript
│   └── images/            # Imágenes
├── api/                   # API Backend
│   ├── config/           # Configuración
│   ├── noticias.php      # API de noticias
│   ├── eventos.php       # API de eventos
│   ├── galeria.php       # API de galería
│   ├── productos.php     # API de productos
│   └── contacto.php      # API de contacto
├── admin/                # Panel de administración
│   └── dashboard.html    # Dashboard principal
├── database/             # Base de datos
│   └── schema.sql        # Esquema de la BD
└── uploads/              # Archivos subidos
```

## 🛠️ Instalación

### Requisitos
- PHP 8.0+
- MySQL 8.0+
- Servidor web (Apache/Nginx)
- XAMPP/WAMP/LAMP

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
git clone https://github.com/tu-usuario/fila-mariscales-web.git
cd fila-mariscales-web
   ```

2. **Configurar la base de datos**
```bash
# Importar el esquema
mysql -u root -p < database/schema.sql
```

3. **Configurar la aplicación**
```bash
# Editar la configuración de la base de datos
nano api/config/database.php
```

4. **Configurar permisos**
```bash
chmod 755 uploads/
chmod 755 uploads/images/
chmod 755 uploads/gallery/
chmod 755 uploads/news/
```

5. **Acceder al sitio**
- Frontend: `http://localhost/fila-mariscales-web/`
- Admin: `http://localhost/fila-mariscales-web/admin/dashboard.html`

## 🔧 Configuración

### Base de Datos
Editar `api/config/database.php`:
```php
define('DB_CONFIG', [
    'host' => 'localhost',
    'dbname' => 'fila_mariscales_web',
    'username' => 'root',
    'password' => 'tu_password'
]);
```

### Email
Configurar en `api/config/database.php`:
```php
define('EMAIL_CONFIG', [
    'smtp_host' => 'smtp.gmail.com',
    'smtp_port' => 587,
    'smtp_username' => 'tu_email@gmail.com',
    'smtp_password' => 'tu_password'
]);
```

## 📱 Funcionalidades

### Frontend
- ✅ Página principal con hero section
- ✅ Sección de noticias dinámicas
- ✅ Calendario de eventos
- ✅ Galería de imágenes
- ✅ Tienda online
- ✅ Formulario de contacto
- ✅ Sistema de login/registro
- ✅ Diseño responsive

### Panel de Administración
- ✅ Dashboard con estadísticas
- ✅ Gestión de noticias
- ✅ Gestión de eventos
- ✅ Gestión de galería
- ✅ Gestión de productos
- ✅ Gestión de pedidos
- ✅ Gestión de mensajes
- ✅ Gestión de usuarios
- ✅ Configuración del sistema

### API REST
- ✅ Endpoints para todas las funcionalidades
- ✅ Autenticación JWT
- ✅ Validación de datos
- ✅ Manejo de errores
- ✅ Documentación automática

## 🎨 Personalización

### Colores
Los colores principales se definen en `assets/css/style.css`:
```css
:root {
    --primary-color: #8B4513;
    --secondary-color: #D2691E;
    --accent-color: #CD853F;
    --dark-color: #2C1810;
}
```

### Fuentes
```css
font-family: 'Cinzel', serif;  /* Títulos */
font-family: 'Open Sans', sans-serif;  /* Texto */
```

## 🔒 Seguridad

- ✅ Validación de entrada
- ✅ Sanitización de datos
- ✅ Protección CSRF
- ✅ Autenticación segura
- ✅ Headers de seguridad
- ✅ Límites de rate limiting

## 📊 Rendimiento

- ✅ Optimización de imágenes
- ✅ Minificación de CSS/JS
- ✅ Caché de consultas
- ✅ Compresión gzip
- ✅ CDN para recursos estáticos

## 🚀 Despliegue

### Producción
1. Configurar servidor web
2. Configurar SSL/HTTPS
3. Optimizar base de datos
4. Configurar backup automático
5. Monitoreo de rendimiento

### Docker (Opcional)
```bash
docker-compose up -d
```

## 📝 Changelog

### v2.0.0 (2024-01-06)
- ✨ Migración completa a arquitectura moderna
- ✨ Nuevo panel de administración
- ✨ API REST completa
- ✨ Diseño responsive mejorado
- ✨ Optimización de rendimiento
- ✨ Mejoras de seguridad

## 🤝 Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver `LICENSE` para más detalles.

## 📞 Contacto

- **Email**: info@filamariscales.com
- **Teléfono**: +34 965 123 456
- **Web**: https://filamariscales.com

## 🙏 Agradecimientos

- Bootstrap 5 por el framework CSS
- Font Awesome por los iconos
- Chart.js por los gráficos
- Google Fonts por las tipografías

---

**Desarrollado con ❤️ para la Filá Mariscales de Caballeros Templarios de Elche**