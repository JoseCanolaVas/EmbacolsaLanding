# Embacolsa - Aplicación Web y Landing Page

Proyecto desacoplado en dos servidores independientes: **Frontend (Nuxt.js / Vue.js)** y **Backend (Laravel / PHP)**.

---

## 🏛️ Arquitectura del Sistema

```
[ Cliente / Navegador ]
       │
       ├───► [ Servidor Frontend ] (Nuxt.js / Nginx) ───► https://embacolsa.com.co
       │
       └───► [ Servidor Backend ]  (Laravel / PHP-FPM) ─► https://api.embacolsa.com.co
                   │
                   └───► [ Base de Datos ] (PostgreSQL / MySQL)
```

---

## 📁 Estructura del Repositorio

```
EmbacolsaLanding/
├── apps/
│   ├── backend/     # API REST en Laravel 10 (PHP 8.2+)
│   └── frontend/    # Aplicación SPA / Nuxt 2 (Vue 2 / Vuetify)
└── README.md
```

---

## 🚀 Guías de Despliegue en Servidores Independientes

### 1. [Servidor de Backend (Laravel API)](apps/backend/README.md)
Guía detallada paso a paso para configurar el servidor de Backend con:
- PHP 8.2+ y extensiones requeridas.
- Base de datos (PostgreSQL o MySQL).
- Composer, Laravel Passport y almacenamiento de archivos.
- Configuración de Servidor Web (Nginx o Apache).
- Certificados SSL con Let's Encrypt (Certbot).

👉 **[Ver Guía del Servidor Backend](apps/backend/README.md)**

---

### 2. [Servidor de Frontend (Nuxt.js SPA / SSR)](apps/frontend/README.md)
Guía detallada paso a paso para configurar el servidor de Frontend con:
- Node.js 18 o 20 LTS.
- Configuración de variable de entorno `API_URL` apuntando al servidor backend.
- **Opción A (Recomendada - SPA Estática)**: Generación con `npm run generate` y servido ultra-rápido con Nginx.
- **Opción B (Servidor Node con PM2)**: Ejecución con `npm run build` y `npm run start`.
- Configuración de Servidor Web Nginx y SSL.

👉 **[Ver Guía del Servidor Frontend](apps/frontend/README.md)**

---

## 💻 Desarrollo Local Rápido

### Backend:
```bash
cd apps/backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan passport:keys
php artisan storage:link
php artisan serve --port=8000
```

### Frontend:
```bash
cd apps/frontend
cp .env.example .env
npm install
npm run dev
```
La aplicación web estará disponible en `http://localhost:3000`.
