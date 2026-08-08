<template>
  <v-app class="admin-shell">
    <v-navigation-drawer app permanent width="286" class="admin-drawer" dark>
      <div class="drawer-brand">
        <div class="brand-frame">
          <img src="/images/softnova.png" alt="Softnova">
        </div>
        <span>Centro Softnova</span>
      </div>

      <Sidebar />

      <div class="drawer-footer">
        <v-icon color="#63e5e9">
          mdi-shield-check-outline
        </v-icon>
        <div>
          <strong>Modo administrador</strong>
          <small>Contenido visible en Embacolsa</small>
        </div>
      </div>
    </v-navigation-drawer>

    <v-app-bar app flat height="76" class="admin-topbar">
      <div>
        <div class="topbar-eyebrow">
          Softnova
        </div>
        <h1>Parametrizacion del contenido</h1>
      </div>

      <v-spacer />

      <v-text-field class="topbar-search d-none d-md-flex" dense hide-details outlined rounded
        prepend-inner-icon="mdi-magnify" placeholder="Buscar en el panel" />

      <v-btn icon class="ml-2" color="primary" to="/">
        <v-icon>mdi-web</v-icon>
      </v-btn>
    </v-app-bar>

    <v-main class="admin-main">
      <v-container fluid class="admin-container">
        <div class="admin-hero">
          <div>
            <span>Gestion central</span>
            <h2>Lo que parametrices aqui alimenta el index.</h2>
            <p>Activa categorias, productos, logos y banners para controlar lo que ve el cliente.</p>
          </div>
          <div class="hero-actions">
            <v-btn rounded depressed color="primary" to="/modulo-parametrizacion/productos">
              Productos
              <v-icon right>mdi-package-variant-closed</v-icon>
            </v-btn>
            <v-btn rounded outlined color="primary" to="/modulo-parametrizacion/imagenes">
              Imagenes
              <v-icon right>mdi-image-outline</v-icon>
            </v-btn>
          </div>
        </div>

        <Nuxt />
      </v-container>
    </v-main>

    <AppToast />
    <AppPreload />
  </v-app>
</template>

<script>
import Sidebar from '~/components/modulo-parametrizacion/Sidebar.vue'
import AppToast from '~/components/AppToast.vue'
import AppPreload from '../components/AppPreload.vue'

export default {
  components: {
    Sidebar,
    AppToast,
    AppPreload,
  },
}
</script>

<style scoped>
.admin-shell {
  background: #eef4f8;
  color: #17365d;
}

.admin-drawer {
  background: linear-gradient(180deg, #071b42 0%, #0f3565 52%, #196b5f 100%) !important;
}

.drawer-brand {
  padding: 22px 18px 16px;
}

.brand-frame {
  background: rgba(255, 255, 255, .96);
  height: 76px;
  overflow: hidden;
  padding: 8px;
  width: 238px;
}

.brand-frame img {
  display: block;
  height: 100%;
  object-fit: contain;
  object-position: center;
  width: 100%;
}

.drawer-brand span {
  color: #d9f4f7;
  display: block;
  font-size: 12px;
  font-weight: 800;
  letter-spacing: .7px;
  margin-top: 12px;
  text-transform: uppercase;
}

.drawer-footer {
  align-items: center;
  border: 1px solid rgba(255, 255, 255, .14);
  bottom: 18px;
  display: flex;
  gap: 12px;
  left: 18px;
  padding: 14px;
  position: absolute;
  right: 18px;
}

.drawer-footer strong,
.drawer-footer small {
  display: block;
}

.drawer-footer strong {
  color: #fff;
  font-size: 13px;
}

.drawer-footer small {
  color: #b7d5dd;
  font-size: 11px;
}

.admin-topbar {
  background: rgba(255, 255, 255, .94) !important;
  border-bottom: 1px solid #dfe8f0;
}

.topbar-eyebrow {
  color: #16a0ad;
  font-size: 11px;
  font-weight: 900;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.admin-topbar h1 {
  color: #112e64;
  font-size: 22px;
  font-weight: 900;
  line-height: 1.2;
  margin: 2px 0 0;
}

.topbar-search {
  max-width: 360px;
}

.admin-main {
  background:
    linear-gradient(180deg, rgba(15, 165, 177, .08), rgba(255, 255, 255, 0) 260px),
    #eef4f8;
}

.admin-container {
  padding: 28px;
}

.admin-hero {
  align-items: center;
  background:
    linear-gradient(110deg, rgba(7, 27, 66, .95), rgba(15, 117, 128, .82)),
    url('/images/login.png') center/cover;
  color: #fff;
  display: flex;
  gap: 18px;
  justify-content: space-between;
  margin-bottom: 24px;
  min-height: 178px;
  padding: 28px;
}

.admin-hero span {
  color: #78edf0;
  display: block;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.admin-hero h2 {
  font-size: 30px;
  font-weight: 900;
  line-height: 1.12;
  margin: 7px 0;
}

.admin-hero p {
  color: #dcecf4;
  margin: 0;
  max-width: 610px;
}

.hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

@media (max-width: 960px) {
  .admin-hero {
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>
