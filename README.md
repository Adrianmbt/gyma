<div align="center">

# 🏋️ GYMA
### Sistema de Gestión de Gimnasio

![Laravel](https://img.shields.io/badge/Laravel-9.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Sistema completo de gestión para gimnasios con control de membresías, suscripciones, inventario, ventas y más.**

[Reportar Bug](https://github.com/Adrianmbt/gyma/issues) · [Solicitar Feature](https://github.com/Adrianmbt/gyma/issues)

</div>

---

## 📋 Tabla de Contenidos

- [Sobre el Proyecto](#-sobre-el-proyecto)
- [Características](#-características)
- [Tecnologías](#️-tecnologías)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación](#-instalación)
- [Base de Datos](#-base-de-datos)
- [Configuración](#️-configuración)
- [Uso](#-uso)
- [Comandos Útiles](#-comandos-útiles)
- [Solución de Problemas](#-solución-de-problemas)
- [Contribuir](#-contribuir)
- [Licencia](#-licencia)
- [Contacto](#-contacto)

---

## 📖 Sobre el Proyecto

GYMA es un sistema integral de gestión diseñado específicamente para gimnasios y centros de fitness. Desarrollado con Laravel 9, ofrece una solución completa para administrar miembros, suscripciones, inventario, ventas y operaciones diarias de tu gimnasio.

El sistema incluye características avanzadas como:
- Gestión completa de miembros con fichas detalladas
- Sistema de suscripciones con renovación automática
- Integración en tiempo real con la tasa BCV (Banco Central de Venezuela)
- Control de inventario y productos
- Gestión de entrenadores y áreas del gimnasio
- Reportes y estadísticas detalladas
- Interfaz moderna y responsive con Bootstrap 5

### ¿Por qué GYMA?

- ✅ **Fácil de usar**: Interfaz intuitiva diseñada para recepcionistas y administradores
- ✅ **Completo**: Todas las funcionalidades que necesitas en un solo lugar
- ✅ **Moderno**: Tecnologías actuales y mejores prácticas de desarrollo
- ✅ **Personalizable**: Código abierto y fácil de adaptar a tus necesidades
- ✅ **Soporte local**: Integración con sistemas de pago venezolanos

---

## ✨ Características

### 👥 Gestión de Miembros
- Registro completo de miembros con foto
- Fichas detalladas con información personal
- Historial de suscripciones
- Búsqueda rápida por cédula
- Estados de membresía (Activo, Vencido, Por Vencer, Vetado)
- Sistema de badges con colores según estatus

### 💳 Sistema de Suscripciones
- Múltiples planes de suscripción
- Renovación automática desde la ficha del miembro
- Cálculo automático de fechas de vencimiento
- Historial completo de pagos
- Métodos de pago flexibles (Efectivo, Transferencia, Pago Móvil, Tarjeta)
- Alertas de vencimiento

### 💰 Recepción y Ventas
- Punto de venta integrado
- Búsqueda rápida de miembros
- Registro de transacciones en tiempo real
- Tabla de registros recientes con auto-actualización
- Integración con tasa BCV actualizada
- Conversión automática Bs/USD

### 📊 Reportes y Estadísticas
- Dashboard con métricas clave
- Reportes de ingresos
- Estadísticas de membresías
- Análisis de ventas
- Gráficos interactivos

### 🏪 Control de Inventario
- Gestión de productos y equipos
- Control de stock
- Alertas de inventario bajo
- Registro de movimientos

### 👨‍🏫 Gestión de Entrenadores
- Registro de entrenadores
- Asignación de áreas
- Horarios y disponibilidad
- Información de contacto

### 🎯 Características Técnicas
- DataTables con búsqueda y paginación
- Interfaz responsive (móvil, tablet, desktop)
- Validaciones en tiempo real
- Manejo robusto de errores
- Cache inteligente para optimización
- Logs detallados para debugging

---

## 🛠️ Tecnologías

### Backend
- **Laravel 9.x** - Framework PHP moderno
- **PHP 8.0+** - Lenguaje de programación
- **MySQL 5.7+** - Base de datos relacional
- **Composer** - Gestor de dependencias PHP

### Frontend
- **Bootstrap 5** - Framework CSS
- **jQuery 3.x** - Librería JavaScript
- **DataTables** - Tablas interactivas
- **Chart.js** - Gráficos y estadísticas
- **Font Awesome** - Iconos

### Herramientas de Desarrollo
- **Vite** - Build tool moderno
- **NPM** - Gestor de paquetes JavaScript
- **Laravel Tinker** - REPL para Laravel
- **Laravel Pint** - Code style fixer

### APIs Externas
- **PyDolarVe API** - Tasa BCV principal
- **DolarAPI** - Tasa BCV alternativa (fallback)

---

## 📦 Requisitos del Sistema

Antes de instalar GYMA, asegúrate de tener:

### Requisitos Obligatorios
- **PHP** >= 8.0.2
- **Composer** >= 2.0
- **MySQL** >= 5.7 o **MariaDB** >= 10.3
- **Node.js** >= 14.x
- **NPM** >= 6.x

### Extensiones de PHP Requeridas
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- PDO_MySQL
- Tokenizer
- XML

### Recomendaciones
- **Servidor Web**: Apache 2.4+ o Nginx 1.18+
- **RAM**: Mínimo 512MB, recomendado 1GB+
- **Espacio en Disco**: Mínimo 500MB
- **Sistema Operativo**: Windows 10+, Linux, macOS

### Verificar Requisitos

Puedes verificar tu versión de PHP y extensiones con:

```bash
php -v
php -m
```

---

## 🚀 Instalación

Sigue estos pasos para instalar GYMA en tu computadora:

### 1️⃣ Clonar el Repositorio

```bash
git clone https://github.com/Adrianmbt/gyma.git
cd gyma
```

### 2️⃣ Instalar Dependencias de PHP

```bash
composer install
```

### 3️⃣ Instalar Dependencias de Node.js

```bash
npm install
```

### 4️⃣ Configurar el Archivo de Entorno

Copia el archivo de ejemplo y genera la clave de aplicación:

```bash
# Windows (CMD)
copy .env.example .env

# Windows (PowerShell) o Linux/Mac
cp .env.example .env
```

Genera la clave de aplicación:

```bash
php artisan key:generate
```

### 5️⃣ Configurar la Base de Datos

Edita el archivo `.env` y configura tus credenciales de MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gyma_db
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

Crea la base de datos en MySQL:

```sql
CREATE DATABASE gyma_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6️⃣ Ejecutar las Migraciones

```bash
php artisan migrate
```

Si deseas datos de prueba, ejecuta los seeders:

```bash
php artisan db:seed
```

### 7️⃣ Crear Enlace Simbólico para Storage

```bash
php artisan storage:link
```

### 8️⃣ Compilar Assets

Para desarrollo:
```bash
npm run dev
```

Para producción:
```bash
npm run build
```

### 9️⃣ Iniciar el Servidor

```bash
php artisan serve
```

El sistema estará disponible en: **http://localhost:8000**

### ✅ Verificación de Instalación

Después de completar la instalación, verifica que:

- [ ] El servidor inicia sin errores
- [ ] Puedes acceder a http://localhost:8000
- [ ] La página de login se muestra correctamente
- [ ] No hay errores en la consola del navegador (F12)

---

## 💾 Base de Datos

### Opción 1: Instalación Nueva (Migraciones)

Si estás instalando GYMA por primera vez, usa las migraciones como se indicó en la sección de instalación:

```bash
php artisan migrate
php artisan db:seed  # Opcional: datos de prueba
```

### Opción 2: Importar Base de Datos Existente

Si ya tienes una base de datos de GYMA y quieres importarla:

#### Método 1: phpMyAdmin

1. Abre phpMyAdmin en tu navegador
2. Crea una nueva base de datos llamada `gyma_db`
3. Selecciona la base de datos
4. Ve a la pestaña **"Importar"**
5. Haz clic en **"Seleccionar archivo"** y elige tu archivo `.sql`
6. Haz clic en **"Continuar"** al final de la página
7. Espera a que termine la importación

#### Método 2: Línea de Comandos

```bash
# Crear la base de datos
mysql -u root -p -e "CREATE DATABASE gyma_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar el archivo SQL
mysql -u root -p gyma_db < ruta/al/archivo/backup.sql
```

#### Método 3: MySQL Workbench

1. Abre MySQL Workbench
2. Conecta a tu servidor MySQL
3. Ve a **Server** → **Data Import**
4. Selecciona **"Import from Self-Contained File"**
5. Elige tu archivo `.sql`
6. Selecciona o crea el schema `gyma_db`
7. Haz clic en **"Start Import"**

### Configurar Credenciales

Después de importar, asegúrate de configurar las credenciales en tu archivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gyma_db
DB_USERNAME=root
DB_PASSWORD=tu_contraseña
```

### ⚠️ Importante

- Si importas una base de datos existente, **NO ejecutes** `php artisan migrate` ya que las tablas ya existen
- Asegúrate de que la versión de la base de datos sea compatible con esta versión del sistema
- Haz siempre un backup antes de importar o modificar la base de datos

---

## ⚙️ Configuración

### Variables de Entorno Importantes

Edita el archivo `.env` para configurar el sistema según tus necesidades:

#### Configuración de la Aplicación

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `APP_NAME` | Nombre de la aplicación | `"GYMA - Sistema de Gestión"` |
| `APP_ENV` | Entorno de ejecución | `local` o `production` |
| `APP_DEBUG` | Modo debug (solo desarrollo) | `true` o `false` |
| `APP_URL` | URL de la aplicación | `http://localhost:8000` |

#### Configuración de Base de Datos

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `DB_CONNECTION` | Tipo de base de datos | `mysql` |
| `DB_HOST` | Host del servidor | `127.0.0.1` |
| `DB_PORT` | Puerto de MySQL | `3306` |
| `DB_DATABASE` | Nombre de la base de datos | `gyma_db` |
| `DB_USERNAME` | Usuario de MySQL | `root` |
| `DB_PASSWORD` | Contraseña de MySQL | `tu_contraseña` |

#### Configuración de Cache

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `CACHE_DRIVER` | Driver de cache | `file` |
| `SESSION_DRIVER` | Driver de sesión | `file` |
| `SESSION_LIFETIME` | Duración de sesión (minutos) | `120` |

#### Configuración de Correo (Opcional)

Si deseas enviar correos electrónicos:

| Variable | Descripción | Ejemplo |
|----------|-------------|---------|
| `MAIL_MAILER` | Servicio de correo | `smtp` |
| `MAIL_HOST` | Host SMTP | `smtp.gmail.com` |
| `MAIL_PORT` | Puerto SMTP | `587` |
| `MAIL_USERNAME` | Usuario de correo | `tu_email@gmail.com` |
| `MAIL_PASSWORD` | Contraseña de correo | `tu_contraseña` |
| `MAIL_ENCRYPTION` | Tipo de encriptación | `tls` |
| `MAIL_FROM_ADDRESS` | Correo remitente | `noreply@gyma.com` |

### Configuraciones Específicas del Sistema

El sistema incluye integraciones especiales:

- **Tasa BCV**: Se actualiza automáticamente desde APIs externas
- **Cache**: La tasa BCV se cachea por 1 hora para optimizar rendimiento
- **Storage**: Las fotos de miembros se almacenan en `storage/app/public/fotos`

---

## 📱 Uso

### Acceso al Sistema

1. Inicia el servidor: `php artisan serve`
2. Abre tu navegador en: http://localhost:8000
3. Inicia sesión con tus credenciales

### Módulos Principales

#### 👥 Gestión de Miembros
- **Ruta**: `/miembros`
- Registra nuevos miembros con foto y datos personales
- Busca miembros por cédula o nombre
- Visualiza fichas completas con historial
- Gestiona estados de membresía

#### 💳 Recepción y Ventas
- **Ruta**: `/recepcion`
- Busca miembros rápidamente por cédula
- Visualiza estado de suscripción en tiempo real
- Renueva suscripciones desde la ficha
- Consulta tasa BCV actualizada
- Registra ventas y transacciones

#### 📊 Planes de Suscripción
- **Ruta**: `/planes`
- Crea y edita planes de suscripción
- Define precios en USD y Bs
- Configura duración de planes
- Gestiona promociones

#### 🏪 Inventario
- **Ruta**: `/inventario`
- Controla productos y equipos
- Registra entradas y salidas
- Alertas de stock bajo
- Historial de movimientos

#### 👨‍🏫 Entrenadores
- **Ruta**: `/entrenadores`
- Registra entrenadores y personal
- Asigna áreas y horarios
- Gestiona información de contacto

### Funcionalidades Especiales

#### Búsqueda Rápida de Miembros
En el módulo de Recepción, ingresa la cédula del miembro y presiona "Buscar" para ver:
- Información personal completa
- Estado de suscripción actual
- Días restantes de membresía
- Historial de pagos
- Botón de renovación (si aplica)

#### Renovación de Suscripciones
Desde la ficha del miembro:
1. Haz clic en "Renovar Suscripción"
2. Selecciona el plan deseado
3. El monto se completa automáticamente
4. Elige el método de pago
5. Ingresa referencia (opcional)
6. Confirma la renovación

#### Tasa BCV Automática
El sistema consulta automáticamente la tasa del BCV y la muestra en:
- Módulo de Recepción
- Formularios de pago
- Reportes de ventas

---

## 🔧 Comandos Útiles

### Limpieza de Cache

```bash
# Limpiar cache de aplicación
php artisan cache:clear

# Limpiar cache de configuración
php artisan config:clear

# Limpiar cache de vistas
php artisan view:clear

# Limpiar cache de rutas
php artisan route:clear

# Limpiar todo el cache
php artisan optimize:clear
```

### Optimización

```bash
# Optimizar aplicación para producción
php artisan optimize

# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache
```

### Base de Datos

```bash
# Ejecutar migraciones
php artisan migrate

# Revertir última migración
php artisan migrate:rollback

# Refrescar base de datos (¡CUIDADO! Borra todos los datos)
php artisan migrate:fresh

# Ejecutar seeders
php artisan db:seed

# Refrescar y ejecutar seeders
php artisan migrate:fresh --seed
```

### Diagnóstico

```bash
# Ver todas las rutas
php artisan route:list

# Ver configuración de base de datos
php artisan config:show database

# Verificar conexión a base de datos
php artisan tinker
>>> DB::connection()->getPdo();

# Ver logs en tiempo real (PowerShell)
Get-Content storage\logs\laravel.log -Wait -Tail 50

# Ver logs en tiempo real (Linux/Mac)
tail -f storage/logs/laravel.log
```

### Mantenimiento

```bash
# Crear enlace simbólico de storage
php artisan storage:link

# Generar nueva clave de aplicación
php artisan key:generate

# Listar comandos disponibles
php artisan list

# Ver ayuda de un comando específico
php artisan help migrate
```

### Desarrollo

```bash
# Iniciar servidor de desarrollo
php artisan serve

# Iniciar servidor en puerto específico
php artisan serve --port=8080

# Compilar assets en modo desarrollo
npm run dev

# Compilar assets en modo producción
npm run build

# Compilar assets con watch
npm run watch
```

---

## 🐛 Solución de Problemas

### Error: "No application encryption key has been specified"

**Causa**: No se ha generado la clave de aplicación.

**Solución**:
```bash
php artisan key:generate
```

### Error: "SQLSTATE[HY000] [1045] Access denied for user"

**Causa**: Credenciales de base de datos incorrectas.

**Solución**:
1. Verifica las credenciales en el archivo `.env`
2. Asegúrate de que MySQL esté corriendo
3. Verifica que el usuario tenga permisos en la base de datos

```bash
# Verificar si MySQL está corriendo (Windows)
sc query MySQL80

# Iniciar MySQL (Windows)
net start MySQL80
```

### Error: "Class 'Composer\...' not found"

**Causa**: Dependencias de Composer no instaladas o desactualizadas.

**Solución**:
```bash
composer install
# o
composer dump-autoload
```

### Error: "npm ERR! code ENOENT"

**Causa**: Dependencias de Node.js no instaladas.

**Solución**:
```bash
npm install
```

### Error: "The stream or file could not be opened"

**Causa**: Permisos incorrectos en directorios de storage.

**Solución (Windows)**:
```bash
# Asegúrate de tener permisos de escritura en:
# - storage/
# - bootstrap/cache/
```

**Solución (Linux/Mac)**:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### La Tasa BCV No Se Actualiza

**Causa**: Cache no limpiado o problema con las APIs.

**Solución**:
```bash
php artisan cache:clear
```

Si el problema persiste, verifica los logs:
```bash
# Ver últimas líneas del log
Get-Content storage\logs\laravel.log -Tail 50
```

### DataTables No Cargan o Muestran Error

**Causa**: Problema con las rutas o datos.

**Solución**:
1. Abre la consola del navegador (F12)
2. Ve a la pestaña "Console" para ver errores JavaScript
3. Ve a la pestaña "Network" para ver errores de peticiones
4. Verifica que las rutas estén correctas:
```bash
php artisan route:list | findstr miembros
```

### Error 500 en Producción

**Causa**: Múltiples causas posibles.

**Solución**:
1. Revisa los logs: `storage/logs/laravel.log`
2. Verifica permisos de archivos
3. Asegúrate de que `.env` esté configurado correctamente
4. Limpia el cache: `php artisan optimize:clear`
5. Verifica que todas las dependencias estén instaladas

### La Página Se Ve Sin Estilos

**Causa**: Assets no compilados o enlace simbólico faltante.

**Solución**:
```bash
# Crear enlace simbólico
php artisan storage:link

# Compilar assets
npm run build
```

### Checklist de Verificación Post-Instalación

Usa este checklist para verificar que todo funcione correctamente:

- [ ] El servidor inicia sin errores: `php artisan serve`
- [ ] Puedes acceder a http://localhost:8000
- [ ] La página de login se muestra correctamente con estilos
- [ ] No hay errores en la consola del navegador (F12)
- [ ] Puedes iniciar sesión
- [ ] El dashboard carga correctamente
- [ ] Las DataTables muestran información
- [ ] La tasa BCV se muestra actualizada
- [ ] Puedes buscar un miembro por cédula
- [ ] Los módulos principales son accesibles
- [ ] Las imágenes se cargan correctamente

---

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Si deseas contribuir a GYMA:

### Cómo Contribuir

1. **Fork** el proyecto
2. Crea una **rama** para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. Abre un **Pull Request**

### Reportar Bugs

Si encuentras un bug, por favor abre un [issue](https://github.com/Adrianmbt/gyma/issues) incluyendo:

- Descripción clara del problema
- Pasos para reproducir el error
- Comportamiento esperado vs comportamiento actual
- Capturas de pantalla (si aplica)
- Versión de PHP, Laravel y navegador
- Logs relevantes

### Solicitar Features

Para solicitar nuevas funcionalidades, abre un [issue](https://github.com/Adrianmbt/gyma/issues) con:

- Descripción detallada del feature
- Casos de uso
- Beneficios esperados
- Mockups o ejemplos (si aplica)

### Código de Conducta

- Sé respetuoso con otros colaboradores
- Usa un lenguaje inclusivo
- Acepta críticas constructivas
- Enfócate en lo mejor para la comunidad

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.

La Licencia MIT es una licencia permisiva que permite:
- ✅ Uso comercial
- ✅ Modificación
- ✅ Distribución
- ✅ Uso privado

Con las siguientes condiciones:
- 📋 Incluir el aviso de copyright
- 📋 Incluir la licencia MIT

---

## 👤 Contacto

**Adrian Montilla**

- GitHub: [@Adrianmbt](https://github.com/Adrianmbt)
- Proyecto: [https://github.com/Adrianmbt/gyma](https://github.com/Adrianmbt/gyma)

### Soporte

Si necesitas ayuda:

1. Revisa la sección de [Solución de Problemas](#-solución-de-problemas)
2. Busca en los [Issues](https://github.com/Adrianmbt/gyma/issues) existentes
3. Abre un nuevo [Issue](https://github.com/Adrianmbt/gyma/issues/new) si no encuentras solución

---

## 🙏 Agradecimientos

- [Laravel](https://laravel.com/) - El framework PHP más elegante
- [Bootstrap](https://getbootstrap.com/) - Framework CSS
- [DataTables](https://datatables.net/) - Tablas interactivas
- [Font Awesome](https://fontawesome.com/) - Iconos
- [PyDolarVe](https://pydolarve.org/) - API de tasa BCV
- Todos los contribuidores que hacen posible este proyecto

---

## 📚 Documentación Adicional

### Estructura del Proyecto

```
gyma/
├── app/                    # Código de la aplicación
│   ├── Http/
│   │   └── Controllers/   # Controladores
│   ├── Models/            # Modelos Eloquent
│   └── ...
├── bootstrap/             # Archivos de arranque
├── config/                # Archivos de configuración
├── database/
│   ├── migrations/        # Migraciones de BD
│   └── seeders/          # Seeders
├── public/                # Archivos públicos
│   ├── css/
│   ├── js/
│   └── fotos/            # Fotos de miembros
├── resources/
│   └── views/            # Vistas Blade
├── routes/
│   └── web.php           # Rutas web
├── storage/              # Archivos generados
│   ├── app/
│   └── logs/             # Logs de la aplicación
├── .env.example          # Plantilla de configuración
├── composer.json         # Dependencias PHP
├── package.json          # Dependencias Node.js
└── README.md            # Este archivo
```

### Tecnologías y Versiones

- **Laravel**: 9.x
- **PHP**: 8.0.2+
- **MySQL**: 5.7+
- **Bootstrap**: 5.x
- **jQuery**: 3.x
- **Node.js**: 14.x+

---

<div align="center">

### ⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub

**Hecho con ❤️ para la comunidad fitness**

[⬆ Volver arriba](#-gyma)

</div>
