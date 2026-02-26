# 📚 Centro G2 – Gestión de Recursos

Backend desarrollado con **Laravel**, **Docker (Sail)** y **MySQL**

Este proyecto implementa el backend de una aplicación web para la gestión y reserva de recursos e instalaciones del Centro G2.

---

## 🧩 Requisitos previos

### 🔹 Comunes (Windows y macOS)

Es necesario tener instalado:

- **Docker Desktop**
- **Git**
- **Navegador web** (Chrome, Firefox, etc.)

> ⚠️ No es necesario instalar PHP ni Composer en el sistema anfitrión.  
> Laravel Sail gestiona todo el entorno mediante Docker.

---

### 🍎 macOS

- macOS 12 o superior
- Docker Desktop para macOS (Intel o Apple Silicon según el equipo)

### 1️⃣ Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd <nombre-del-proyecto>
```

## Si tienes macOS, después de clonar el repositorio continua en el paso 2.

## 🪟 Windows (WSL2 + Docker Desktop)

### Preparación (solo la primera vez)

1. Instala **WSL2** y una distribución Linux (recomendado: Ubuntu).
2. Instala **Docker Desktop** y actívalo con:
    - **Settings → General**: ✅ _Use the WSL 2 based engine_
    - **Settings → Resources → WSL Integration**: ✅ _Enable integration with my default WSL distro_

3. Abre una terminal **WSL** (Ubuntu).

> ⚠️ MUY IMPORTANTE (para evitar errores con Composer y Docker):
>
> - **No trabajes en la ruta** `/mnt/c/...`
> - Trabaja siempre en el filesystem Linux: **`/home/<usuario>/...`** 
---

### 1️⃣ Clonar el repositorio en el filesystem de WSL

En la terminal WSL (Ubuntu):

```bash
mkdir -p ~/projects
cd ~/projects
git clone <URL_DEL_REPOSITORIO>
cd <NOMBRE_DEL_REPO>
```

Para abrir el proyecto en el editor de código con la ruta del filesystem de Linux hay que abrir un terminal de Ubuntu, cambiar a la ruta del proyecto con `cd ~/projects/<NOMBRE_DEL_REPO>` y lanzar el comando `code .` (si tu editor de código es Visual Studio Code) o `cursor .` (si tu editor es Cursor).

---

## 🚀 Puesta en marcha del proyecto

### 2️⃣ Configurar variables de entorno

Copiar el archivo de ejemplo:

```bash
cp .env.example .env
```

Editar el archivo .env y comprobar que la configuración de base de datos es la siguiente:

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=g2
DB_USERNAME=sail
DB_PASSWORD=password
```

Si no está configurada con estos valores, modifícalos por estos.

## 3️⃣ Instalar dependencias dentro de Docker

Esto generará la carpeta `vendor/` y `vendor/bin/sail`.

```bash
docker run --rm \
  -v "$(pwd):/app" \
  -w /app \
  php:8.4-cli \
  sh -lc "apt-get update \
    && apt-get install -y git unzip zip \
    && git config --global --add safe.directory /app \
    && php -r \"copy('https://getcomposer.org/installer','/tmp/composer-setup.php');\" \
    && php /tmp/composer-setup.php --install-dir=/tmp --filename=composer \
    && /tmp/composer install --no-interaction --prefer-dist"
```

Después de instalar las dependencias verifica que sail existe con `ls -la vendor/bin/sail`.

## 4️⃣ Levantar el entorno con Laravel Sail

Desde la raíz del proyecto:

```bash
./vendor/bin/sail up

```

Este comando levanta varios servicios usando Docker. Los importantes que usaremos son los siguientes:

- Servidor Laravel
- MySQL (base de datos)
- phpMyAdmin (interfaz visual de las tablas de la base de datos)

⏳ La primera ejecución puede tardar varios minutos.

## Crear la base de datos y las tablas

Una vez que haya terminado la creación de los contenedores, en el mismo terminal quedarán ejecutándose los logs del contenedor. En otro terminal a parte, ejecutar las migraciones:

```bash
./vendor/bin/sail artisan migrate
```

Si se desea reiniciar completamente la base de datos:

```bash
./vendor/bin/sail artisan migrate:fresh
```

Para eliminar todas las tablas, vistas y tipos de la base de datos actual definida en .env, dejándola vacía.

```bash
./vendor/bin/sail artisan db:wipe
```

## Población inicial de roles con Seeder

Para que la API funcione correctamente al registrar usuarios, es necesario que existan ciertos roles en la base de datos. Esto se hace con un seeder de Laravel.

```bash
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

## 🗄️ Acceso a la base de datos

### phpMyAdmin

Acceder desde el navegador:

```bash
http://localhost:8080
```

Las credenciales para acceder son:

- Servidor: mysql

- Usuario: sail

- Contraseña: password

La base de datos debería aparecer en el panel lateral izquierdo con el nombre `g2`.

## 🌐 Acceso a la aplicación

API Laravel:

```bash
http://localhost
```

## Detener el entorno

Este comando detiene el entorno sin borrar la base de datos:

```bash
./vendor/bin/sail down
```

Este detiene el entorno y elimina los volúmenes, incluyendo la base de datos:

```bash
./vendor/bin/sail down -b
```

## 📁 Estructura de directorios importante

A continuación se describen los directorios y archivos más relevantes del proyecto y su función dentro de la arquitectura MVC (API REST).

---

### `routes/`

Contiene la definición de las rutas del proyecto.

- **`api.php`**  
  Define los endpoints de la API REST.  
  Todas las rutas aquí definidas están prefijadas por `/api` y utilizan el middleware `api`.  
  Es el archivo principal para exponer los servicios que consumirá el frontend.

- **`web.php`**  
  Incluye rutas web tradicionales (con sesiones y vistas).  
  En este proyecto apenas se utiliza, ya que el backend está diseñado como API.

- **`console.php`**  
  Define comandos personalizados ejecutables desde la consola.

---

### `app/Models/`

Contiene los **modelos Eloquent**, uno por cada entidad del dominio.

Cada modelo representa una tabla de la base de datos y encapsula:

- El nombre de la tabla
- La clave primaria
- Las relaciones con otras entidades
- La lógica asociada al acceso a datos

Ejemplos:

- `Rol`
- `Usuario`
- `Recurso`
- `Reserva`
- `Incidencia`

---

### `app/Http/Controllers/`

Contiene los **controladores**, responsables de gestionar las peticiones HTTP.

- Cada controlador implementa la lógica de los endpoints (CRUD).
- Reciben las peticiones, validan los datos y devuelven respuestas JSON.

Ejemplos:

- `RolController`
- `UsuarioController`
- `ReservaController`

---

### `app/Http/Responses/`

Contiene clases encargadas de **estandarizar las respuestas de la API**.

- **`ResultResponse`**  
  Centraliza el formato de las respuestas JSON, incluyendo:
    - Código de estado
    - Mensaje
    - Datos
    - Errores

Esto garantiza consistencia en todas las respuestas del backend.

---

### `database/migrations/`

Contiene las **migraciones de base de datos**.

- Cada migración define la estructura de una tabla.
- Permiten crear, modificar o eliminar tablas de forma versionada.
- Reflejan fielmente el diseño lógico de la base de datos.

---

### `bootstrap/`

Incluye la configuración inicial del framework.

- **`app.php`**  
  Define cómo se cargan las rutas (`web.php`, `api.php`, `console.php`) en Laravel 12.

---

### `compose.yaml`

Archivo de configuración de Docker Compose utilizado por Laravel Sail.

Define los servicios del entorno de desarrollo:

- Servidor de la aplicación
- MySQL
- phpMyAdmin

---

### `.env`

Archivo de configuración de entorno.

Contiene variables sensibles como:

- Credenciales de base de datos
- Configuración de la aplicación
- Entorno de ejecución (`local`, `production`, etc.)

Este archivo **no debe subirse al repositorio**.

---
