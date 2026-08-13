<template>
  <header class="store-header">
    <v-container class="header-inner">
      <a :href="homeHref" class="brand-link" aria-label="Ir al inicio">
        <img :src="logoSrc" alt="Embacolsa" class="brand-logo">
      </a>

      <nav class="header-links d-none d-lg-flex" aria-label="Navegación principal">
        <a v-for="link in navigationLinks" :key="link.label" :href="link.href" :class="{ active: link.active }">
          {{ link.label }}
        </a>
      </nav>

      <v-btn
        fab
        small
        depressed
        color="primary"
        class="menu-button d-lg-none"
        aria-label="Abrir menú de navegación"
        @click="drawer = true"
      >
        <v-icon>mdi-menu</v-icon>
      </v-btn>
    </v-container>

    <v-navigation-drawer
      v-model="drawer"
      temporary
      right
      fixed
      width="292"
      class="mobile-drawer"
    >
      <div class="drawer-brand">
        <img :src="logoSrc" alt="Embacolsa" class="drawer-logo">

        <v-btn icon color="primary" aria-label="Cerrar menú" @click="drawer = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </div>

      <v-divider />

      <v-list nav class="mt-2">
        <v-list-item v-for="link in drawerLinks" :key="link.label" :href="link.href" @click="drawer = false">
          <v-list-item-icon>
            <v-icon color="primary">
              {{ link.icon }}
            </v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title class="font-weight-bold">
              {{ link.label }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </header>
</template>

<script>
export default {
  name: 'StoreHeader',

  props: {
    logoSrc: {
      type: String,
      default: '/images/embacolsa-optimized.webp',
    },

    activeSection: {
      type: String,
      default: 'inicio',
    },
  },

  data() {
    return {
      drawer: false,
    }
  },

  computed: {
    isHome() {
      return this.activeSection === 'inicio'
    },

    homeHref() {
      return this.isHome ? '#inicio' : '/'
    },

    navigationLinks() {
      return [
        {
          label: 'Inicio',
          href: this.isHome ? '#inicio' : '/',
          active: this.activeSection === 'inicio',
        },
        {
          label: 'Productos',
          href: this.isHome ? '#productos' : '/#productos',
          active: this.activeSection === 'productos',
        },
        {
          label: 'Catálogo',
          href: '/catalogo',
          active: this.activeSection === 'catalogo',
        },
        {
          label: 'Beneficios',
          href: this.isHome ? '#beneficios' : '/#beneficios',
          active: this.activeSection === 'beneficios',
        },
        {
          label: 'Contacto',
          href: this.isHome ? '#contacto' : '/#contacto',
          active: this.activeSection === 'contacto',
        },
      ]
    },

    drawerLinks() {
      return [
        {
          label: 'Inicio',
          href: this.isHome ? '#inicio' : '/',
          icon: 'mdi-home-outline',
        },
        {
          label: 'Productos',
          href: this.isHome ? '#productos' : '/#productos',
          icon: 'mdi-package-variant-closed',
        },
        {
          label: 'Catálogo',
          href: '/catalogo',
          icon: 'mdi-storefront-outline',
        },
        {
          label: 'Beneficios',
          href: this.isHome ? '#beneficios' : '/#beneficios',
          icon: 'mdi-shield-check-outline',
        },
        {
          label: 'Contacto',
          href: this.isHome ? '#contacto' : '/#contacto',
          icon: 'mdi-phone-outline',
        },

      ]
    },
  },

  watch: {
    $route() {
      this.drawer = false
    },
  },
}
</script>

<style scoped>
.store-header {
  backdrop-filter: blur(16px);
  background: rgba(255, 255, 255, .96);
  border-bottom: 1px solid rgba(210, 224, 238, .9);
  box-shadow: 0 12px 35px rgba(9, 35, 73, .06);
  position: sticky;
  top: 0;
  z-index: 80;
}

.header-inner {
  align-items: center;
  display: flex;
  gap: 24px;
  min-height: 88px;
}

.brand-link {
  align-items: center;
  display: flex;
  flex: 0 0 auto;
  height: 66px;
  overflow: hidden;
  width: 244px;
}

.brand-logo {
  display: block;
  height: 100%;
  object-fit: contain;
  object-position: left center;
  width: 100%;
}

.header-links {
  align-items: center;
  gap: 8px;
  margin-left: auto;
}

.header-links a {
  border-radius: 999px;
  color: #17365d;
  font-size: 14px;
  font-weight: 900;
  letter-spacing: .2px;
  padding: 11px 15px;
  position: relative;
  text-decoration: none;
  transition: background .2s ease, color .2s ease, transform .2s ease;
}

.header-links a:hover,
.header-links a.active {
  background: #eef7ff;
  color: #1e88e5;
  transform: translateY(-1px);
}


.menu-button {
  margin-left: auto;
  min-width: 40px;
  position: relative;
  z-index: 90;
}

.mobile-drawer {
  z-index: 220 !important;
}

.drawer-brand {
  align-items: center;
  display: flex;
  justify-content: space-between;
  padding: 22px 20px 14px;
}

.drawer-logo {
  display: block;
  height: 72px;
  object-fit: contain;
  object-position: left center;
  width: 220px;
}

@media (max-width: 600px) {
  .header-inner {
    min-height: 76px;
  }

  .brand-link {
    height: 56px;
    width: 184px;
  }
}
</style>
