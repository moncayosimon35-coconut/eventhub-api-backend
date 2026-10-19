# EventHub API 🚀

[Español](#español) | [English](#english)

---

## Español

API RESTful desarrollada en **Laravel 11** para la gestión y control de eventos. Cuenta con un sistema sólido de autenticación mediante **Laravel Sanctum**, control de accesos basado en roles a través de **Policies** (para la edición de eventos propios) y **Gates** (para la visualización de métricas administrativas).

### 🛠️ Requisitos del Sistema
- PHP 8.2 o superior
- Extensiones de PHP: `php-sqlite3`, `php-curl`
- Composer
- WSL2 (Ubuntu) / Entorno Linux o macOS

### 🔧 Instalación y Configuración Estricta (WSL2)

Si estás trabajando en WSL2 y experimentas problemas de red o resolución de DNS al descargar dependencias, sigue este orden exacto de comandos:

```bash
# 1. Clonar el proyecto e ingresar a la carpeta
git clone https://github.com/TU_USUARIO/eventhub-api.git
cd eventhub-api

# 2. Reparar DNS e Internet en WSL (Ejecutar si falla la conexión)
sudo rm /etc/resolv.conf
sudo bash -c 'echo -e "nameserver 8.8.8.8\nnameserver 1.1.1.1" > /etc/resolv.conf'

# 3. Instalar soporte para base de datos local SQLite
sudo apt update && sudo apt install -y php-sqlite3

# 4. Configurar variables de entorno (.env)
cp .env.example .env
# Nota: Edita tu .env para que contenga únicamente DB_CONNECTION=sqlite (borrando las demás líneas DB_*)

# 5. Inicializar dependencias y generar llave de cifrado
composer install
php artisan key:generate

# 6. Preparar rutas e instalar soporte API (Sanctum)
touch routes/api.php
php artisan install:api --no-interaction

# 7. Ejecutar migraciones junto con los datos de prueba (Seeders)
php artisan migrate:fresh --seed

# 8. Levantar el servidor de desarrollo expuesto a la red local de Windows
php artisan serve --host=0.0.0.0
```

### 👥 Usuarios de Prueba (Contraseña común: `password`)

| Rol | Email | Propósito de Prueba |
|---|---|---|
| **admin** | admin@eventhub.com | Acceso exclusivo a Métricas (Gate) |
| **organizador** | orga@eventhub.com | Dueño del Evento ID: 1 (Policy exitoso) |
| **organizador** | orgb@eventhub.com | No es dueño del Evento ID: 1 (Policy provoca 403) |
| **asistente** | asistente@eventhub.com | Usuario regular sin privilegios de edición o métricas |

### 🛣️ Endpoints de la API
Todas las peticiones deben incluir obligatoriamente las cabeceras `Accept: application/json` y `Content-Type: application/json`.

| Método | Ruta | Autenticación | Descripción / Capa de Seguridad |
|---|---|---|---|
| **POST** | `/api/login` | No | Retorna el Token Bearer de Sanctum |
| **GET** | `/api/eventos` | No | Lista global de eventos cargando la relación del organizador |
| **GET** | `/api/perfil` | Sí (Sanctum) | Retorna la información completa del usuario autenticado |
| **PUT** | `/api/eventos/{id}` | Sí (Solo el dueño) | Actualiza un evento específico bloqueado por **EventoPolicy** |
| **GET** | `/api/admin/metricas` | Sí (Solo administradores) | Retorna datos globales bloqueado por el Gate **ver-metricas** |

### ⚡ Pruebas Rápidas con la Extensión REST Client (VS Code)
Puedes crear un archivo llamado `pruebas.http` en la raíz del proyecto para testear los endpoints sin salir del editor:

```http
### Obtener Eventos (Público)
GET http://localhost:8000/api/eventos
Accept: application/json

### Iniciar Sesión (Login del Organizador)
# @name login
POST http://localhost:8000/api/login
Content-Type: application/json
Accept: application/json

{
    "email": "orga@eventhub.com",
    "password": "password"
}

### Guardar el Token obtenido en una variable de la extensión
@token = {{login.response.body.token}}

### Ver Perfil Autenticado (Requiere Token)
GET http://localhost:8000/api/perfil
Authorization: Bearer {{token}}
Accept: application/json
```

---

## English

A RESTful API built with **Laravel 11** for event planning and management. It features a secure authentication mechanism powered by **Laravel Sanctum**, role-based access control implemented via **Policies** (to protect events ownership) and **Gates** (to restrict administrative dashboard metrics).

### 🛠️ Prerequisites
- PHP 8.2 or higher
- Required PHP Extensions: `php-sqlite3`, `php-curl`
- Composer
- WSL2 (Ubuntu) / Linux environment or macOS

### 🔧 Installation and Strict Setup (WSL2)

If you are developing inside WSL2 and experience network dropouts or domain resolution bugs while installing packages, use this exact command sequence:

```bash
# 1. Clone the repository and enter the directory
git clone https://github.com/TU_USUARIO/eventhub-api.git
cd eventhub-api

# 2. Fix WSL DNS and Internet connection issues (Run if connection timeouts happen)
sudo rm /etc/resolv.conf
sudo bash -c 'echo -e "nameserver 8.8.8.8\nnameserver 1.1.1.1" > /etc/resolv.conf'

# 3. Install native local SQLite database drivers
sudo apt update && sudo apt install -y php-sqlite3

# 4. Set up environment variables (.env)
cp .env.example .env
# Note: Edit your .env file to only have DB_CONNECTION=sqlite (remove all other DB_* lines)

# 5. Install dependencies and generate application encryption key
composer install
php artisan key:generate

# 6. Initialize routes and install API backend infrastructure (Sanctum)
touch routes/api.php
php artisan install:api --no-interaction

# 7. Run fresh database schema migrations and seed dummy data
php artisan migrate:fresh --seed

# 8. Start the development server mapped to the Windows host network
php artisan serve --host=0.0.0.0
```

### 👥 Seeded Test Users (Default Password: `password`)

| Role | Email | Testing Purpose |
|---|---|---|
| **admin** | admin@eventhub.com | Exclusive access to Metrics dashboard (Gate) |
| **organizador** | orga@eventhub.com | Owner of Event ID: 1 (Policy permits update) |
| **organizador** | orgb@eventhub.com | Non-owner of Event ID: 1 (Policy throws 403 Forbidden) |
| **asistente** | asistente@eventhub.com | Regular client user without management privileges |

### 🛣️ API Endpoints
All HTTP requests must submit the headers `Accept: application/json` and `Content-Type: application/json`.

| Method | Route | Authentication | Security Layer & Description |
|---|---|---|---|
| **POST** | `/api/login` | None | Validates credentials and returns Sanctum Bearer Token |
| **GET** | `/api/eventos` | None | Public feed fetching all events with eager-loaded host information |
| **GET** | `/api/perfil` | Yes (Sanctum) | Yields authenticated user identity fields |
| **PUT** | `/api/eventos/{id}` | Yes (Owner Only) | Mutates an existing event record guarded by **EventoPolicy** |
| **GET** | `/api/admin/metricas` | Yes (Admin Only) | Serves data counters guarded by the **ver-metricas** Gate |

### 🧳 HTTP Status Codes Reference
- **200 OK** · Process completed successfully.
- **401 Unauthorized** · Missing authorization token or bad authentication credentials.
- **403 Forbidden** · Authenticated user lacks authorization clearance (Policy/Gate restriction).
- **422 Unprocessable Entity** · Data payload validation constraints failed.
