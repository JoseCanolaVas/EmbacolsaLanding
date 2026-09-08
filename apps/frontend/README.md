# Servidor Frontend - Embacolsa (Nuxt.js / Vue.js)

Aplicación web frontend SPA / SSR construida con **Nuxt.js 2**, **Vue.js** y **Vuetify**, comunicándose vía API REST con el servidor backend.

---

## 📋 Requisitos del Servidor

- **Sistema Operativo**: Linux (Ubuntu 22.04 LTS / 24.04 LTS, Debian, etc.)
- **Node.js**: Versión 18.x o 20.x LTS
- **NPM**: Incluido con Node.js
- **Servidor Web**: Nginx
- *(Opcional para modo SSR / Node)*: **PM2** (`npm install -g pm2`)

---

## 🛠️ Paso 1: Instalación de Node.js y Nginx (Ubuntu / Debian)

```bash
# Instalar Node.js 20 LTS vía NodeSource
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs nginx git

# Verificar versiones
node -v
npm -v
```

---

## ⚙️ Paso 2: Configuración del Proyecto y Variables de Entorno

1. **Ubicar el proyecto**:
   ```bash
   cd /var/www/embacolsa-frontend # o la ruta de tu preferencia
   ```

2. **Crear archivo `.env`**:
   ```bash
   cp .env.example .env
   nano .env
   ```
   Configura la URL que apunta hacia tu servidor backend:
   ```env
   # Si tu backend tiene dominio con SSL:
   API_URL=https://api.embacolsa.com.co/api

   # O si usas IP y puerto directamente:
   # API_URL=http://IP_DEL_SERVIDOR_BACKEND:8000/api
   ```

3. **Instalar dependencias**:
   ```bash
   npm ci # o npm install
   ```

---

## 🚀 Paso 3: Despliegue en Producción

Puedes desplegar el frontend de dos formas según tu preferencia:

### 🌟 Opción A: Despliegue Estático con Nginx (Recomendada - Máximo Rendimiento)

Al ser una aplicación configurada con `ssr: false`, puedes compilar el sitio como una Single Page Application (SPA) ultra rápida:

1. **Compilar el proyecto**:
   ```bash
   API_URL=https://api.embacolsa.com.co/api npm run generate
   ```
   *(Los archivos optimizados se generarán en la carpeta `dist/`)*

2. **Configurar el Virtual Host en Nginx**:
   Crea `/etc/nginx/sites-available/embacolsa-frontend`:
   ```nginx
   server {
       listen 80;
       server_name embacolsa.com.co www.embacolsa.com.co; # Tu dominio o IP pública

       root /var/www/embacolsa-frontend/dist;
       index index.html;

       client_max_body_size 25M;

       # Manejo de rutas SPA (Vue Router)
       location / {
           try_files $uri $uri/ /index.html;
           add_header Cache-Control "no-store, no-cache, must-revalidate, proxy-revalidate, max-age=0" always;
       }

       # Caché para assets estáticos generados por Nuxt
       location /_nuxt/ {
           expires 1y;
           add_header Cache-Control "public, immutable";
       }

       location ~* \.(?:ico|css|js|gif|jpe?g|png|woff2?|eot|ttf|svg|webp)$ {
           expires 6M;
           access_log off;
           add_header Cache-Control "public";
       }
   }
   ```

3. **Habilitar sitio y recargar Nginx**:
   ```bash
   sudo ln -s /etc/nginx/sites-available/embacolsa-frontend /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl reload nginx
   ```

---

### 🟢 Opción B: Despliegue como Servidor Node.js con PM2

Si prefieres correr Nuxt con el servidor integrado de Node.js:

1. **Instalar PM2**:
   ```bash
   sudo npm install -g pm2
   ```

2. **Compilar la aplicación**:
   ```bash
   npm run build
   ```

3. **Iniciar el proceso con PM2**:
   ```bash
   pm2 start npm --name "embacolsa-frontend" -- start
   pm2 save
   pm2 startup
   ```
   *(El servidor correrá en `http://localhost:3000`)*

4. **Configurar Nginx como Reverse Proxy**:
   Crea `/etc/nginx/sites-available/embacolsa-frontend`:
   ```nginx
   server {
       listen 80;
       server_name embacolsa.com.co www.embacolsa.com.co;

       location / {
           proxy_pass http://127.0.0.1:3000;
           proxy_http_version 1.1;
           proxy_set_header Upgrade $http_upgrade;
           proxy_set_header Connection 'upgrade';
           proxy_set_header Host $host;
           proxy_cache_bypass $http_upgrade;
           proxy_set_header X-Real-IP $remote_addr;
           proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
           proxy_set_header X-Forwarded-Proto $scheme;
       }
   }
   ```

5. **Habilitar sitio y recargar Nginx**:
   ```bash
   sudo ln -s /etc/nginx/sites-available/embacolsa-frontend /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl reload nginx
   ```

---

## 🔒 Paso 4: Certificado SSL con Let's Encrypt (HTTPS)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d embacolsa.com.co -d www.embacolsa.com.co
```

---

## 🔄 Actualización de Código en Producción

Para desplegar cambios nuevos en el frontend:

```bash
cd /var/www/embacolsa-frontend
git pull origin main
npm ci

# Para Opción A (Estático con Nginx):
npm run generate

# Para Opción B (PM2):
npm run build
pm2 restart embacolsa-frontend
```
