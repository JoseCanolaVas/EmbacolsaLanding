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

    <transition name="mobile-menu-fade">
      <div v-if="drawer" class="mobile-menu-shell d-lg-none">
        <button class="mobile-menu-backdrop" type="button" aria-label="Cerrar menú" @click="drawer = false" />

        <aside class="mobile-menu-panel" aria-label="Menú de navegación móvil">
          <div class="drawer-brand">
            <img :src="logoSrc" alt="Embacolsa" class="drawer-logo">

            <v-btn icon color="primary" aria-label="Cerrar menú" @click="drawer = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </div>

          <nav class="mobile-menu-links">
            <a
              v-for="link in drawerLinks"
              :key="link.label"
              :href="link.href"
              class="mobile-menu-link"
              @click="drawer = false"
            >
              <span class="mobile-menu-icon">
                <v-icon color="primary">{{ link.icon }}</v-icon>
              </span>
              <span>{{ link.label }}</span>
              <v-icon small color="#8aa0b8">mdi-chevron-right</v-icon>
            </a>
          </nav>

          <div class="mobile-menu-cta">
            <v-btn block rounded color="primary" href="https://wa.me/573218720375" target="_blank">
              <v-icon left>mdi-whatsapp</v-icon>
              Cotizar por WhatsApp
            </v-btn>
          </div>
        </aside>
      </div>
    </transition>
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

.mobile-menu-shell {
  bottom: 0;
  left: 0;
  position: fixed;
  right: 0;
  top: 0;
  z-index: 500;
}

.mobile-menu-backdrop {
  background: rgba(6, 24, 48, .56);
  border: 0;
  bottom: 0;
  cursor: pointer;
  left: 0;
  padding: 0;
  position: absolute;
  right: 0;
  top: 0;
  width: 100%;
}

.mobile-menu-panel {
  background:
    radial-gradient(circle at 10% 0%, rgba(112, 221, 224, .24), transparent 34%),
    #fff;
  bottom: 0;
  box-shadow: -24px 0 50px rgba(5, 31, 62, .28);
  display: flex;
  flex-direction: column;
  max-width: 340px;
  overflow-y: auto;
  position: absolute;
  right: 0;
  top: 0;
  width: min(86vw, 340px);
}

.drawer-brand {
  align-items: center;
  display: flex;
  justify-content: space-between;
  padding: 18px 18px 14px;
}

.drawer-logo {
  display: block;
  height: 62px;
  object-fit: contain;
  object-position: left center;
  width: 190px;
}

.mobile-menu-links {
  border-top: 1px solid #e5edf5;
  display: grid;
  gap: 10px;
  padding: 16px;
}

.mobile-menu-link {
  align-items: center;
  background: #f6f9fc;
  border: 1px solid #e2ebf3;
  border-radius: 18px;
  color: #17365d;
  display: grid;
  font-size: 15px;
  font-weight: 900;
  gap: 12px;
  grid-template-columns: 42px 1fr auto;
  min-height: 58px;
  padding: 8px 12px 8px 8px;
  text-decoration: none;
}

.mobile-menu-icon {
  align-items: center;
  background: #eaf5ff;
  border-radius: 14px;
  display: flex;
  height: 42px;
  justify-content: center;
  width: 42px;
}

.mobile-menu-cta {
  margin-top: auto;
  padding: 16px;
}

.mobile-menu-fade-enter-active,
.mobile-menu-fade-leave-active {
  transition: opacity .18s ease;
}

.mobile-menu-fade-enter-active .mobile-menu-panel,
.mobile-menu-fade-leave-active .mobile-menu-panel {
  transition: transform .22s ease;
}

.mobile-menu-fade-enter,
.mobile-menu-fade-leave-to {
  opacity: 0;
}

.mobile-menu-fade-enter .mobile-menu-panel,
.mobile-menu-fade-leave-to .mobile-menu-panel {
  transform: translateX(100%);
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
