<template>
  <v-app class="admin-shell">
    <v-navigation-drawer
      v-model="drawer"
      app
      :permanent="$vuetify.breakpoint.mdAndUp"
      :temporary="$vuetify.breakpoint.smAndDown"
      width="245"
      class="admin-drawer"
    >
      <div class="drawer-mobile-close d-md-none">
        <v-btn icon color="white" aria-label="Cerrar menú" @click="drawer = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </div>

      <div class="drawer-brand">
        <img src="/images/softnova-optimized.webp" alt="Softnova">
      </div>

      <Sidebar />

    </v-navigation-drawer>

    <v-app-bar app color="white" light elevation="1" height="64">
      <v-btn
        fab
        small
        depressed
        color="primary"
        class="mr-3 d-md-none"
        aria-label="Abrir menú de parametrización"
        @click="drawer = true"
      >
        <v-icon>mdi-menu</v-icon>
      </v-btn>

      <div class="topbar-title">
        <span>Panel de parametrización</span>
        <small>Gestión de contenido y catálogo</small>
      </div>

      <v-spacer />

      <v-btn rounded outlined color="primary" small to="/">
        <v-icon left small>mdi-web</v-icon>
        Sitio público
      </v-btn>
    </v-app-bar>

    <v-main class="admin-main">
      <v-container fluid class="admin-container">
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

  data() {
    return {
      drawer: false,
    }
  },

  watch: {
    '$vuetify.breakpoint.mdAndUp': {
      immediate: true,
      handler(isDesktop) {
        this.drawer = isDesktop
      },
    },

    $route() {
      if (this.$vuetify.breakpoint.smAndDown) {
        this.drawer = false
      }
    },
  },
}
</script>

<style scoped>
.admin-shell {
  color: #243b53;
}

.admin-drawer {
  background:
    radial-gradient(circle at 18% 8%, rgba(114, 237, 240, .18), transparent 30%),
    linear-gradient(180deg, #0f2c61 0%, #155f56 42%, #0c5a4f 100%) !important;
  border-right: 1px solid rgba(255, 255, 255, .12) !important;
  box-shadow: 12px 0 34px rgba(8, 37, 63, .16);
  z-index: 210 !important;
}

.drawer-mobile-close {
  display: flex;
  justify-content: flex-end;
  padding: 10px 10px 0;
}

.drawer-brand {
  border-bottom: 1px solid rgba(255, 255, 255, .12);
  margin: 0 14px 10px;
  padding: 20px 10px 18px;
  text-align: center;
}

.drawer-brand img {
  height: 112px;
  margin: 0 auto;
  object-fit: contain;
  width: 172px;
}

.drawer-brand span {
  color: #fff;
  display: block;
  font-size: 15px;
  font-weight: 700;
  margin-top: 8px;
}

.drawer-brand small {
  color: rgba(255, 255, 255, .72);
  display: block;
  font-size: 11px;
  margin-top: 2px;
}

.drawer-bottom {
  bottom: 18px;
  left: 18px;
  position: absolute;
  right: 18px;
}

.topbar-title span,
.topbar-title small {
  display: block;
}

.topbar-title span {
  color: #1f2933;
  font-size: 18px;
  font-weight: 700;
}

.topbar-title small {
  color: #6b7280;
  font-size: 12px;
  margin-top: 1px;
}

.admin-main {
  background: #f4f6f8;
}

.admin-container {
  padding: 22px;
}

@media (max-width: 600px) {
  .topbar-title span {
    font-size: 15px;
  }

  .topbar-title small {
    display: none;
  }

  .admin-container {
    padding: 14px;
  }
}
</style>
