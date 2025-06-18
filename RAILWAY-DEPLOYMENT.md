# 🚄 Guía Completa de Deployment en Railway

Esta guía te llevará paso a paso para deployar el Portal de Servicios IT en Railway.

## 📋 Prerrequisitos

1. Cuenta en [GitHub](https://github.com)
2. Cuenta en [Railway](https://railway.app) (gratuita)
3. Los archivos del proyecto en un repositorio GitHub

## 🚀 Pasos de Deployment

### Paso 1: Preparar el Repositorio

1. **Subir el proyecto a GitHub**:
   ```bash
   git init
   git add .
   git commit -m "Portal IT MSX International"
   git remote add origin https://github.com/tu-usuario/portal-it-msx.git
   git push -u origin main
   ```

### Paso 2: Crear Proyecto en Railway

1. **Ir a Railway**: [railway.app](https://railway.app)
2. **Hacer login** con tu cuenta GitHub
3. **Crear nuevo proyecto**: "New Project"
4. **Seleccionar**: "Deploy from GitHub repo"
5. **Elegir tu repositorio** del portal IT

### Paso 3: Configurar la Base de Datos

1. **En tu proyecto Railway**, haz clic en "Add Service"
2. **Selecciona**: "Database" → "MySQL"
3. **Railway creará automáticamente**:
   - Base de datos MySQL
   - Variables de entorno (`DATABASE_URL`)
   - Conexión segura

### Paso 4: Inicializar la Base de Datos

1. **Ir a la pestaña "MySQL"** en tu proyecto
2. **Hacer clic en "Query"**
3. **Copiar y pegar** todo el contenido del archivo `railway-setup.sql`
4. **Ejecutar el script** (Run Query)

### Paso 5: Verificar el Deployment

1. **Ir a la pestaña principal** de tu aplicación
2. **Ver los logs** para confirmar que el deployment fue exitoso
3. **Hacer clic en la URL** generada por Railway
4. **Verificar que el portal carga correctamente**

## 🔧 Configuración Avanzada

### Variables de Entorno Disponibles

Railway configurará automáticamente:

```bash
# Base de datos (automáticas)
DATABASE_URL=mysql://username:password@host:port/database
MYSQL_HOST=host
MYSQL_PORT=3306
MYSQL_USER=username
MYSQL_PASSWORD=password
MYSQL_DATABASE=database

# Aplicación (automáticas)
RAILWAY_ENVIRONMENT=production
RAILWAY_STATIC_URL=https://tu-app.up.railway.app
PORT=3000
```

### Configuraciones Personalizadas

Si necesitas personalizar algo, puedes agregar variables:

1. **En Railway**: Ve a "Variables"
2. **Agregar variables**:
   ```
   SITE_NAME=Tu Empresa IT Portal
   DEBUG_MODE=false
   ```

## 🎯 Acceso al Portal

### URLs del Portal

- **Portal Principal**: `https://tu-app.up.railway.app/`
- **Panel Admin**: `https://tu-app.up.railway.app/admin.php`
- **Login**: `https://tu-app.up.railway.app/login.php`

### Credenciales por Defecto

**Usuario Administrador:**
- **Usuario**: `admin`
- **Contraseña**: `password`

## 🔍 Troubleshooting

### Error de Conexión a Base de Datos

1. **Verificar** que el servicio MySQL esté corriendo
2. **Revisar logs** en la pestaña "Deployments"
3. **Confirmar** que el script SQL se ejecutó correctamente

### Error 500 - Internal Server Error

1. **Ver logs detallados** en Railway
2. **Verificar** la configuración PHP
3. **Comprobar** permisos de archivos

### Base de Datos Vacía

1. **Ir a MySQL Query**
2. **Re-ejecutar** el script `railway-setup.sql`
3. **Verificar** que las tablas se crearon:
   ```sql
   SHOW TABLES;
   ```

## 📊 Monitoreo

### Railway Dashboard

- **Metrics**: CPU, memoria, tráfico
- **Logs**: Logs en tiempo real
- **Deployments**: Historial de deployments

### Logs Útiles

```bash
# Ver logs en tiempo real
railway logs

# Ver logs específicos
railway logs --tail 100
```

## 🔄 Actualizaciones

### Deployment Automático

Railway desplegará automáticamente cuando hagas push a tu repositorio:

```bash
git add .
git commit -m "Actualización del portal"
git push origin main
```

### Rollback

Si algo sale mal:

1. **En Railway**: "Deployments"
2. **Seleccionar deployment anterior**
3. **"Redeploy"**

## 💡 Consejos Pro

### 1. Dominio Personalizado

1. **En Railway**: "Settings" → "Domains"
2. **Agregar tu dominio**
3. **Configurar DNS** según las instrucciones

### 2. Variables de Entorno por Ambiente

```bash
# Desarrollo
DEBUG_MODE=true
LOG_LEVEL=debug

# Producción
DEBUG_MODE=false
LOG_LEVEL=error
```

### 3. Backup de Base de Datos

```sql
-- Desde Railway MySQL Query
SELECT * INTO OUTFILE '/tmp/backup.sql' FROM users;
```

### 4. SSL/HTTPS

Railway proporciona HTTPS automáticamente. ¡No necesitas configurar nada!

## 🎉 ¡Listo!

Tu Portal de Servicios IT ya está funcionando en Railway con:

- ✅ **HTTPS automático**
- ✅ **Base de datos MySQL**
- ✅ **Deployment automático**
- ✅ **Monitoreo integrado**
- ✅ **Escalabilidad automática**

## 📞 Soporte

Si tienes problemas:

1. **Revisar logs** en Railway
2. **Consultar documentación** de Railway
3. **Crear issue** en el repositorio del proyecto

---

**¡Felicidades! Tu portal está en línea y listo para usar.** 🎊 