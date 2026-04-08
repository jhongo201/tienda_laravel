# Tienda — E-commerce Laravel 11

Aplicación de comercio electrónico completa construida con **Laravel 11**, **Livewire 3 + Volt** y **TailwindCSS**, con integración de pagos a través de **Wompi** (Colombia).

---

## Tabla de contenidos

1. [Stack tecnológico](#stack-tecnológico)
2. [Requisitos previos](#requisitos-previos)
3. [Instalación](#instalación)
4. [Configuración del entorno (.env)](#configuración-del-entorno-env)
5. [Base de datos y seeders](#base-de-datos-y-seeders)
6. [Iniciar el servidor de desarrollo](#iniciar-el-servidor-de-desarrollo)
7. [Compilar assets con Vite](#compilar-assets-con-vite)
8. [Configurar ngrok (webhooks Wompi)](#configurar-ngrok-webhooks-wompi)
9. [Credenciales de prueba](#credenciales-de-prueba)
10. [Arquitectura del proyecto](#arquitectura-del-proyecto)
11. [Rutas principales](#rutas-principales)
12. [Panel de administración](#panel-de-administración)
13. [Ejecutar pruebas](#ejecutar-pruebas)

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11 · PHP 8.2 |
| Frontend reactivo | Livewire 3 · Volt |
| Estilos | TailwindCSS 3 · Vite 5 |
| Autenticación | Laravel Breeze |
| Base de datos | PostgreSQL (recomendado) · SQLite (desarrollo rápido) |
| Roles y permisos | Spatie Laravel Permission |
| Media | Spatie Laravel Medialibrary |
| Backups | Spatie Laravel Backup |
| Pagos | Wompi (Colombia, COP) |
| Debug | Laravel Telescope |
| Tests | Pest |

---

## Requisitos previos

Asegúrate de tener instalado lo siguiente antes de clonar el proyecto:

| Herramienta | Versión mínima | Instalación |
|---|---|---|
| PHP | 8.2 | [php.net](https://www.php.net/downloads) / [Laragon](https://laragon.org) |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org/download/) |
| Node.js | 18.x LTS | [nodejs.org](https://nodejs.org) |
| npm | 9.x | Incluido con Node.js |
| PostgreSQL | 14+ | [postgresql.org](https://www.postgresql.org/download/) |
| Git | cualquier | [git-scm.com](https://git-scm.com) |

> **Tip:** Si usas [Laragon](https://laragon.org) en Windows, incluye PHP 8.2, Composer, PostgreSQL y Node.js en un solo instalador.

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/tienda.git
cd tienda
```

### 2. Instalar dependencias PHP

```bash
composer install
```

### 3. Instalar dependencias JavaScript

```bash
npm install
```

### 4. Copiar el archivo de entorno

```bash
cp .env.example .env
```

### 5. Generar la clave de la aplicación

```bash
php artisan key:generate
```

---

## Configuración del entorno (.env)

Abre el archivo `.env` y configura las siguientes variables:

### Aplicación

```env
APP_NAME="Mi Tienda"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=es
APP_TIMEZONE=America/Bogota
```

### Base de datos (PostgreSQL)

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tienda
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña
```

> Para desarrollo rápido con SQLite, usa `DB_CONNECTION=sqlite` y crea el archivo:
> ```bash
> touch database/database.sqlite
> ```

### Cola de trabajos y caché

```env
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```

### Wompi — Pasarela de pagos

Crea una cuenta en [sandbox.wompi.co](https://sandbox.wompi.co) y obtén tus credenciales de prueba:

```env
WOMPI_PUBLIC_KEY=pub_test_XXXXXXXXXXXXXXXXXXXXXXXX
WOMPI_PRIVATE_KEY=prv_test_XXXXXXXXXXXXXXXXXXXXXXXX
WOMPI_INTEGRITY_KEY=test_integrity_XXXXXXXXXXXXXXXX
WOMPI_EVENTS_KEY=test_events_XXXXXXXXXXXXXXXXXX
WOMPI_SANDBOX=true
WOMPI_REDIRECT_URL="${APP_URL}/checkout/retorno"
```

Agrega al archivo `config/services.php` si no existe:

```php
'wompi' => [
    'public_key'    => env('WOMPI_PUBLIC_KEY'),
    'private_key'   => env('WOMPI_PRIVATE_KEY'),
    'integrity_key' => env('WOMPI_INTEGRITY_KEY'),
    'events_key'    => env('WOMPI_EVENTS_KEY'),
    'sandbox'       => env('WOMPI_SANDBOX', true),
    'redirect_url'  => env('WOMPI_REDIRECT_URL'),
],
```

### Mail (opcional — por defecto usa log)

```env
MAIL_MAILER=log
```

---

## Base de datos y seeders

### Crear la base de datos (PostgreSQL)

```sql
CREATE DATABASE tienda;
```

### Ejecutar migraciones

```bash
php artisan migrate
```

### Poblar con datos de prueba

```bash
php artisan db:seed
```

Esto ejecuta:
- **`RoleSeeder`** — Crea los roles `admin` y `cliente`
- **`AdminSeeder`** — Crea los usuarios de prueba (ver [Credenciales de prueba](#credenciales-de-prueba))
- **`ProductSeeder`** — Genera categorías y productos de ejemplo

### Migración + seed en un solo comando

```bash
php artisan migrate:fresh --seed
```

> ⚠️ `migrate:fresh` **elimina todas las tablas**. Úsalo solo en desarrollo.

---

## Iniciar el servidor de desarrollo

Necesitas **dos terminales** corriendo simultáneamente:

### Terminal 1 — Servidor PHP

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

### Terminal 2 — Cola de trabajos (necesario para webhooks Wompi)

```bash
php artisan queue:work
```

> Si no procesas pagos en esta sesión, puedes omitir este comando.

---

## Compilar assets con Vite

### Modo desarrollo (con hot-reload)

```bash
npm run dev
```

Vite iniciará en `http://localhost:5173` e inyectará los assets automáticamente. **Debe estar corriendo mientras desarrollas.**

### Modo producción (build optimizado)

```bash
npm run build
```

Genera los archivos compilados en `public/build/`. Úsalo antes de desplegar.

> **Importante:** Si el servidor Laravel está corriendo pero Vite no, verás la página sin estilos. Siempre ejecuta `npm run dev` junto con `php artisan serve`.

---

## Configurar ngrok (webhooks Wompi)

Wompi necesita una URL pública HTTPS para enviar notificaciones de pago. En desarrollo local usamos **ngrok** para exponer el servidor.

### 1. Instalar ngrok

Descarga desde [ngrok.com/download](https://ngrok.com/download) y sigue las instrucciones para tu SO.

En Windows con chocolatey:
```bash
choco install ngrok
```

En macOS con Homebrew:
```bash
brew install ngrok/ngrok/ngrok
```

### 2. Autenticar ngrok (solo la primera vez)

Crea una cuenta gratuita en [dashboard.ngrok.com](https://dashboard.ngrok.com) y obtén tu token:

```bash
ngrok config add-authtoken TU_TOKEN_AQUI
```

### 3. Exponer el servidor local

Con el servidor Laravel corriendo en el puerto 8000:

```bash
ngrok http 8000
```

Obtendrás una URL como:
```
Forwarding  https://abc123-xyz.ngrok-free.app -> http://localhost:8000
```

### 4. Actualizar el .env con la URL de ngrok

```env
APP_URL=https://abc123-xyz.ngrok-free.app
WOMPI_REDIRECT_URL=https://abc123-xyz.ngrok-free.app/checkout/retorno
```

Luego limpia la caché de configuración:

```bash
php artisan config:clear
```

### 5. Registrar el webhook en Wompi

En el [dashboard de Wompi sandbox](https://sandbox.wompi.co) → **Configuración → Webhooks**, agrega:

```
https://abc123-xyz.ngrok-free.app/webhooks/wompi
```

> La ruta `/webhooks/wompi` está excluida de la protección CSRF para permitir las llamadas de Wompi.

### Resumen — orden de inicio completo

```
Terminal 1:  php artisan serve
Terminal 2:  npm run dev
Terminal 3:  php artisan queue:work
Terminal 4:  ngrok http 8000
```

---

## Credenciales de prueba

Después de ejecutar `php artisan db:seed`:

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | `admin@mitienda.com` | `password123` |
| Cliente | `cliente@mitienda.com` | `password123` |

### Tarjeta de prueba Wompi (sandbox)

| Campo | Valor |
|---|---|
| Número | `4242 4242 4242 4242` |
| Fecha exp. | Cualquier fecha futura |
| CVV | `123` |
| Nombre | Cualquier nombre |

---

## Arquitectura del proyecto

El proyecto sigue una arquitectura **Domain-Driven Design (DDD)** bajo `app/Domains/`:

```
app/
├── Domains/
│   ├── Cart/
│   │   ├── Models/          Cart, CartItem
│   │   └── Services/        CartService
│   ├── Product/
│   │   ├── Models/          Product, Category, ProductVariant
│   │   └── Services/        ProductService, StockService
│   ├── Order/
│   │   ├── Models/          Order, OrderItem, Address
│   │   ├── Enums/           OrderStatus
│   │   └── Services/        OrderService
│   ├── Payment/
│   │   ├── Models/          Payment, WebhookLog
│   │   ├── Services/        WompiService
│   │   └── Jobs/            ProcessWompiWebhook
│   ├── Coupon/
│   │   ├── Models/          Coupon
│   │   └── Services/        CouponValidator
│   └── Shipping/            (zona de envío — costo fijo $15.000 COP)
│
├── Http/Controllers/
│   ├── Admin/               DashboardController, ProductController,
│   │                        CategoryController, OrderAdminController
│   ├── HomeController
│   ├── ShopController
│   ├── CartController
│   ├── CheckoutController
│   ├── OrderController
│   ├── DashboardController  (cliente)
│   └── WompiController
│
└── Livewire/
    ├── Admin/               ProductTable, ProductForm,
    │                        CategoryForm, OrderTable
    ├── CartDrawer
    ├── ProductCatalog
    └── CheckoutForm
```

---

## Rutas principales

| Método | URL | Descripción | Acceso |
|---|---|---|---|
| GET | `/` | Landing page comercial | Público |
| GET | `/productos` | Catálogo con filtros | Público |
| GET | `/productos/{slug}` | Detalle de producto | Público |
| GET | `/categoria/{slug}` | Filtro por categoría | Público |
| GET | `/carrito` | Vista del carrito | Público |
| POST | `/carrito/agregar` | Agregar ítem | Público |
| GET | `/checkout` | Formulario de pago | Auth |
| GET | `/checkout/retorno` | Retorno desde Wompi | Auth |
| GET | `/dashboard` | Dashboard del cliente | Auth |
| GET | `/mis-ordenes` | Lista de pedidos | Auth |
| GET | `/mis-ordenes/{ref}` | Detalle de pedido | Auth |
| POST | `/webhooks/wompi` | Webhook de pago | Sin CSRF |
| GET | `/admin` | Panel admin | Admin |
| GET | `/admin/productos` | CRUD productos | Admin |
| GET | `/admin/categorias` | CRUD categorías | Admin |
| GET | `/admin/ordenes` | Gestión de pedidos | Admin |

---

## Panel de administración

Accede con el usuario admin en `/admin`.

| Sección | URL | Funcionalidades |
|---|---|---|
| Dashboard | `/admin` | KPIs: ventas, órdenes, productos, usuarios |
| Productos | `/admin/productos` | Listar, crear, editar, activar/desactivar, soft-delete |
| Categorías | `/admin/categorias` | CRUD inline con Livewire |
| Órdenes | `/admin/ordenes` | Listar con filtro de estado, cambiar estado |

---

## Ejecutar pruebas

```bash
php artisan test
```

O usando Pest directamente:

```bash
./vendor/bin/pest
```

Con reporte de cobertura:

```bash
./vendor/bin/pest --coverage
```

---

## Variables de entorno — resumen

| Variable | Descripción | Requerida |
|---|---|---|
| `APP_KEY` | Clave de la app (generada con `artisan key:generate`) | ✅ |
| `APP_URL` | URL base de la app (ngrok en desarrollo) | ✅ |
| `DB_*` | Credenciales de PostgreSQL | ✅ |
| `WOMPI_PUBLIC_KEY` | Llave pública Wompi | ✅ para pagos |
| `WOMPI_PRIVATE_KEY` | Llave privada Wompi | ✅ para pagos |
| `WOMPI_INTEGRITY_KEY` | Llave de integridad Wompi | ✅ para pagos |
| `WOMPI_EVENTS_KEY` | Llave de eventos/webhooks Wompi | ✅ para webhooks |
| `WOMPI_SANDBOX` | `true` en desarrollo, `false` en producción | ✅ |
| `WOMPI_REDIRECT_URL` | URL de retorno tras el pago | ✅ para pagos |
| `QUEUE_CONNECTION` | Driver de cola (`database` recomendado) | ✅ |

---

## Licencia

MIT 2025
