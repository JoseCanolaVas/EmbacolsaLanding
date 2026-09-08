# Servidor Backend - Embacolsa (Laravel API)

API REST construida con **Laravel 10**, autenticación mediante **Laravel Passport** y persistencia en base de datos (**PostgreSQL** o **MySQL**).

---

## 📋 Requisitos del Servidor

- **Sistema Operativo**: Linux (Ubuntu 22.04 LTS / 24.04 LTS recomendado, Debian, CentOS, etc.)
- **PHP**: 8.1 o superior (8.2 recomendado)
- **Extensiones PHP**: `php-fpm`, `php-pgsql` (o `php-mysql`), `php-mbstring`, `php-xml`, `php-curl`, `php-intl`, `php-zip`, `php-bcmath`, `php-tokenizer`, `php-fileinfo`
- **Gestor de paquetes**: Composer 2.x
- **Base de Datos**: PostgreSQL 14+ o MySQL 8+
- **Servidor Web**: Nginx o Apache2

---

## 🛠️ Paso 1: Instalación de Dependencias del Sistema (Ubuntu / Debian)

```bash
sudo apt update && sudo apt upgrade -y

# Instalar PHP 8.2 y extensiones requeridas
sudo apt install -y php8.2 php8.2-fpm php8.2-pgsql php8.2-mysql php8.2-mbstring \
    php8.2-xml php8.2-curl php8.2-intl php8.2-zip php8.2-bcmath php8.2-tokenizer \
    php8.2-fileinfo unzip git curl nginx

# Instalar Composer
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

---

## ⚙️ Paso 2: Configuración del Proyecto

1. **Clonar o ubicar el proyecto**:
   ```bash
   cd /var/www/embacolsa-backend # o la ruta de tu preferencia
   ```

2. **Instalar dependencias de PHP**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. **Configurar el archivo de entorno (`.env`)**:
   ```bash
   cp .env.example .env
   nano .env
   ```
   Ajusta las siguientes variables esenciales:
   ```env
   APP_NAME=Embacolsa
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://api.embacolsa.com.co

   # Conexión por URL de Base de Datos (PostgreSQL o MySQL)
   DATABASE_URL=postgres://embacolsa_user:tu_password_seguro@127.0.0.1:5432/embacolsa_db
   ```

4. **Generar claves y configurar la aplicación**:
   ```bash
   # Generar clave de cifrado de la aplicación
   php artisan key:generate

   # Ejecutar migraciones y seeders
   php artisan migrate --force
   php artisan db:seed --force

   # Generar claves de Laravel Passport para autenticación
   php artisan passport:keys --force

   # Crear enlace simbólico para imágenes y archivos públicos
   php artisan storage:link

   # Optimizar configuración y rutas para producción
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Ajustar permisos de almacenamiento y caché**:
   ```bash
   sudo chown -R www-data:www-data /var/www/embacolsa-backend/storage /var/www/embacolsa-backend/bootstrap/cache
   sudo chmod -R 775 /var/www/embacolsa-backend/storage /var/www/embacolsa-backend/bootstrap/cache
   ```

---

## 🌐 Paso 3: Configuración del Servidor Web

### Opción A: Configuración con Nginx (Recomendada)

Crea el archivo `/etc/nginx/sites-available/embacolsa-backend`:

```nginx
server {
    listen 80;
    server_name api.embacolsa.com.co; # Cambia por tu dominio o IP pública
    root /var/www/embacolsa-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;
    charset utf-8;

    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Habilitar el sitio y reiniciar Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/embacolsa-backend /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

### Opción B: Configuración con Apache2

Si utilizas Apache, crea `/etc/apache2/sites-available/embacolsa-backend.conf`:

```apache
<VirtualHost *:80>
    ServerName api.embacolsa.com.co
    DocumentRoot /var/www/embacolsa-backend/public

    <Directory /var/www/embacolsa-backend/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/embacolsa_backend_error.log
    CustomLog ${APACHE_LOG_DIR}/embacolsa_backend_access.log combined
</VirtualHost>
```

Habilitar módulos y sitio:
```bash
sudo a2enmod rewrite headers
sudo a2ensite embacolsa-backend.conf
sudo systemctl reload apache2
```

---

## 🔒 Paso 4: Configuración de Certificado SSL (HTTPS)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d api.embacolsa.com.co
```

---

## 🔄 Comandos Útiles de Mantenimiento

```bash
# Limpiar caché tras actualizar código
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar nuevas migraciones
php artisan migrate --force
```
