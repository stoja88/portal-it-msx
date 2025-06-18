# Portal de Servicios IT - MSX International

Un portal web completo para gestión de servicios IT, desarrollado con PHP, MySQL, HTML5 y Bootstrap 5.

## 🌟 Características

- **Portal Principal**: Página de bienvenida con servicios disponibles
- **Gestión de Tickets**: Sistema completo de tickets de soporte técnico
- **Control de Accesos**: Solicitudes de acceso a sistemas corporativos
- **Panel de Administración**: Dashboard completo con estadísticas en tiempo real
- **Gestión de Inventario**: Control de equipos y recursos IT
- **Sistema de Usuarios**: Autenticación y roles de usuario
- **Diseño Responsivo**: Compatible con todos los dispositivos
- **Interfaz Moderna**: Diseño atractivo con Bootstrap 5 y FontAwesome

## 📋 Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensiones PHP: PDO, PDO_MySQL

## 🚀 Instalación

### 1. Clonar/Descargar el proyecto

```bash
git clone [repository-url]
cd php
```

### 2. Configurar la base de datos

1. Crear una base de datos MySQL:
```sql
CREATE DATABASE portal_it;
```

2. Importar el esquema de la base de datos:
```bash
mysql -u root -p portal_it < database.sql
```

### 3. Configurar la conexión

Edita el archivo `config.php` con tus credenciales de base de datos:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'portal_it');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### 4. Configurar el servidor web

#### Apache
Asegúrate de que el mod_rewrite esté habilitado y configura el DocumentRoot hacia la carpeta del proyecto.

#### Nginx
Configura tu bloque server para servir los archivos PHP.

### 5. Acceder al portal

- **Portal Principal**: `http://tu-dominio/index.php`
- **Panel Admin**: `http://tu-dominio/admin.php`
- **Login**: `http://tu-dominio/login.php`

## 👤 Credenciales de Demo

**Usuario Administrador:**
- Usuario: `admin`
- Contraseña: `password`

⚠️ **IMPORTANTE**: Cambia la contraseña por defecto después del primer login en producción.

## 📁 Estructura del Proyecto

```
portal-it-msx/
├── assets/
│   ├── css/
│   │   └── style.css          # Estilos personalizados
│   └── js/
│       └── script.js          # JavaScript funcional
├── config.php                 # Configuración de BD y sesiones
├── database.sql              # Esquema de base de datos (desarrollo local)
├── railway-setup.sql         # Script SQL optimizado para Railway
├── index.php                 # Página principal
├── admin.php                 # Panel de administración
├── login.php                 # Página de login
├── logout.php                # Cerrar sesión
├── create-ticket.php         # Crear tickets de soporte
├── request-access.php        # Solicitar accesos
├── railway.json              # Configuración de Railway
├── Procfile                  # Comando de inicio para Railway
├── nixpacks.toml             # Configuración de build
├── .gitignore                # Archivos a ignorar en Git
├── README.md                 # Este archivo
└── RAILWAY-DEPLOYMENT.md     # Guía detallada de deployment
```

## 🎨 Funcionalidades Principales

### Portal Principal
- Página de bienvenida con branding de MSX International
- Dos servicios principales: Soporte Técnico y Control de Accesos
- Estadísticas en tiempo real del sistema
- Diseño moderno y responsivo

### Sistema de Tickets
- Creación de tickets de soporte técnico
- Categorización por tipo (Hardware, Software, Red, etc.)
- Niveles de prioridad (Baja, Media, Alta, Crítica)
- Seguimiento de estado (Abierto, En Progreso, Resuelto, Cerrado)

### Control de Accesos
- Solicitudes de acceso a sistemas corporativos
- Tipos de acceso (Lectura, Escritura, Administrador)
- Justificación requerida para cada solicitud
- Proceso de aprobación por administradores

### Panel de Administración
- Dashboard con estadísticas en tiempo real
- Gestión de tickets, usuarios e inventario
- Actualizaciones recientes del sistema
- Navegación por pestañas para diferentes secciones

## 🔧 Personalización

### Cambiar el Branding
1. Edita los archivos PHP para cambiar "MSX International" por tu empresa
2. Modifica los colores en `assets/css/style.css` (variables CSS)
3. Cambia el logo circular por tu propio logo

### Agregar Nuevas Funcionalidades
1. Crea nuevas páginas PHP siguiendo la estructura existente
2. Agrega nuevas tablas en `database.sql` si es necesario
3. Actualiza la navegación en `admin.php`

## 🚀 Deployment en Railway

### ⭐ Railway - Recomendado

**Railway es la opción perfecta para este proyecto PHP** porque:

1. ✅ **Soporte completo para PHP y MySQL**
2. ✅ **Deployment automático desde Git**
3. ✅ **Base de datos MySQL integrada**
4. ✅ **Variables de entorno automáticas**
5. ✅ **HTTPS gratuito**
6. ✅ **Escalabilidad automática**

### Pasos para Deploy en Railway

1. **Crear cuenta en Railway**: [railway.app](https://railway.app)

2. **Conectar tu repositorio GitHub**:
   - Haz fork o push de este proyecto a tu GitHub
   - En Railway: "New Project" → "Deploy from GitHub repo"
   - Selecciona tu repositorio

3. **Agregar base de datos MySQL**:
   - En tu proyecto Railway: "Add Service" → "Database" → "MySQL"
   - Railway creará automáticamente las variables de entorno

4. **Configurar variables de entorno** (opcional, Railway las crea automáticamente):
   ```
   DATABASE_URL=mysql://user:password@host:port/database
   RAILWAY_ENVIRONMENT=production
   ```

5. **Ejecutar el script de base de datos**:
   - En Railway: Ve a "Database" → "Query"
   - Copia y pega el contenido de `railway-setup.sql`
   - Ejecuta el script

6. **¡Listo!** Tu portal estará disponible en la URL de Railway

### Configuración Automática

El proyecto incluye:
- `railway.json` - Configuración de Railway
- `Procfile` - Comando de inicio
- `nixpacks.toml` - Configuración del build
- `railway-setup.sql` - Script de base de datos optimizado

### Otras Opciones de Deployment

1. **Heroku**: Soporte completo para PHP y MySQL
2. **DigitalOcean App Platform**: Fácil deployment con PHP
3. **AWS Elastic Beanstalk**: Para aplicaciones PHP escalables
4. **Hostings tradicionales**: cPanel, Plesk, etc.

## 🛠️ Desarrollo

### Agregar nuevas páginas
1. Copia la estructura de una página existente
2. Incluye `config.php` al inicio
3. Usa el mismo header/footer para consistencia
4. Agrega la navegación correspondiente

### Personalizar estilos
Los estilos están en `assets/css/style.css` usando variables CSS para fácil personalización:

```css
:root {
    --primary-color: #4F63D2;
    --secondary-color: #4CAF50;
    /* Cambia estos valores para personalizar */
}
```

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Puedes usarlo libremente para proyectos comerciales y personales.

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:
1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## 📞 Soporte

Para soporte técnico o preguntas sobre el código, por favor abre un issue en el repositorio.

---

**Desarrollado con ❤️ para MSX International Valencia HUB** 