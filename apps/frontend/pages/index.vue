<template>
  <v-app class="storefront">
    <header class="store-header">
      <v-container class="header-inner">
        <a href="#inicio" class="brand-link">
          <img :src="logoActual" alt="Embacolsa" class="brand-logo">
        </a>

        <div class="header-search d-none d-md-block">
          <v-text-field v-model="search" dense hide-details solo flat rounded background-color="#f4f8fb"
            placeholder="Buscar cintas, stretch, cajas..." prepend-inner-icon="mdi-magnify" @keyup.enter="buscarProducto">
            <template v-slot:append>
              <v-btn small rounded depressed color="primary" @click="buscarProducto">
                Buscar
              </v-btn>
            </template>
          </v-text-field>
        </div>

        <nav class="header-links d-none d-lg-flex">
          <a v-for="link in links" :key="link.label" :href="link.href">
            {{ link.label }}
          </a>
        </nav>

        <v-btn icon color="primary" class="d-lg-none" @click="drawer = true">
          <v-icon>mdi-menu</v-icon>
        </v-btn>
      </v-container>
    </header>

    <v-navigation-drawer v-model="drawer" temporary right fixed>
      <div class="pa-4">
        <img :src="logoActual" alt="Embacolsa" class="drawer-logo">
      </div>
      <v-list nav>
        <v-list-item v-for="link in links" :key="link.label" :href="link.href" @click="drawer = false">
          <v-list-item-icon>
            <v-icon>{{ link.icon }}</v-icon>
          </v-list-item-icon>
          <v-list-item-content>
            <v-list-item-title>{{ link.label }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <main>
      <section id="inicio" class="hero-section" :style="heroStyle">
        <v-container class="hero-inner">
          <div class="hero-copy">
            <span class="eyebrow">EMPAQUES, EMBALAJES Y SUMINISTROS</span>
            <h1>Embacolsa</h1>
            <p>
              Soluciones listas para empacar, proteger y despachar tu operacion con mejor imagen,
              menos vueltas y respuesta comercial rapida.
            </p>

            <div class="hero-actions">
              <v-btn x-large rounded depressed color="primary" href="#productos">
                Ver productos
                <v-icon right>mdi-arrow-right</v-icon>
              </v-btn>
              <v-btn x-large rounded outlined color="white" href="https://wa.me/573218720375" target="_blank">
                Cotizar por WhatsApp
              </v-btn>
            </div>
          </div>

          <div class="hero-panel catalog-panel">
            <div class="catalog-control">
              <span>Explorar catalogo</span>
              <v-select v-model="categoriaSeleccionada" :items="opcionesCategorias" item-text="name" item-value="id"
                solo flat dense hide-details rounded background-color="white" prepend-inner-icon="mdi-view-grid-outline"
                label="Todas las categorias" clearable />
            </div>

            <div class="catalog-summary">
              <div>
                <strong>{{ productosIndex.length }}</strong>
                <span>productos visibles</span>
              </div>
              <div>
                <strong>{{ categoriasIndex.length }}</strong>
                <span>categorias activas</span>
              </div>
            </div>
          </div>
        </v-container>
      </section>

      <section class="category-strip">
        <v-container>
          <div class="catalog-toolbar">
            <v-select v-model="categoriaSeleccionada" :items="opcionesCategorias" item-text="name" item-value="id"
              label="Categoria" outlined dense rounded clearable hide-details prepend-inner-icon="mdi-filter-outline" />

            <v-text-field v-model="search" label="Buscar producto" outlined dense rounded clearable hide-details
              prepend-inner-icon="mdi-magnify" />

            <v-btn rounded depressed color="primary" href="#productos">
              Ver resultados
              <v-icon right>mdi-arrow-down</v-icon>
            </v-btn>
          </div>
        </v-container>
      </section>

      <section id="productos" class="products-section">
        <v-container>
          <div class="section-heading products-heading">
            <div>
              <span class="eyebrow blue">CATALOGO PARAMETRIZADO</span>
              <h2>{{ tituloProductos }}</h2>
            </div>
            <v-btn v-if="categoriaSeleccionada || search" text color="primary" @click="limpiarCatalogo">
              Limpiar filtros
              <v-icon right small>mdi-close</v-icon>
            </v-btn>
          </div>

          <v-row>
            <v-col v-for="product in productosIndex" :key="product.title" cols="12" sm="6" lg="3">
              <v-card class="product-card" outlined height="100%">
                <div class="product-visual" :class="product.class">
                  <v-img v-if="product.image" :src="product.image" height="175" cover />
                  <v-icon v-else>{{ product.icon }}</v-icon>
                </div>
                <v-card-text>
                  <div class="product-type">{{ product.type }}</div>
                  <h3>{{ product.title }}</h3>
                  <p>{{ product.text }}</p>
                </v-card-text>
                <v-card-actions>
                  <v-btn text color="primary" @click="seleccionarProducto(product)">
                    Solicitar cotizacion
                    <v-icon right small>mdi-plus</v-icon>
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-col>

            <v-col v-if="!productosIndex.length" cols="12">
              <div class="empty-products">
                <v-icon size="64" color="grey lighten-1">
                  mdi-package-variant-remove
                </v-icon>
                <h3>No hay productos para este filtro</h3>
                <p>Prueba con otra categoria o limpia la busqueda.</p>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <section id="beneficios" class="operation-section">
        <v-container>
          <v-row align="center">
            <v-col cols="12" md="5">
              <span class="eyebrow blue">OPERACION SIN ENREDOS</span>
              <h2>Acompañamiento para comprar mejor, no solo comprar mas.</h2>
              <p>
                Te ayudamos a escoger materiales segun carga, rotacion, presupuesto y presentacion final.
              </p>
            </v-col>
            <v-col cols="12" md="7">
              <div class="benefit-grid">
                <div v-for="benefit in benefits" :key="benefit.title" class="benefit-item">
                  <v-icon color="primary">{{ benefit.icon }}</v-icon>
                  <div>
                    <strong>{{ benefit.title }}</strong>
                    <p>{{ benefit.text }}</p>
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <section id="contacto" class="contact-section">
        <v-container class="contact-inner">
          <div>
            <span class="eyebrow">HABLEMOS DE TU PEDIDO</span>
            <h2>Cuéntanos qué necesitas empacar.</h2>
            <p>Armamos una cotizacion clara para tu empresa.</p>
          </div>
          <div class="contact-actions">
            <v-btn x-large rounded color="white" class="primary--text" href="https://wa.me/573218720375" target="_blank">
              <v-icon left>mdi-whatsapp</v-icon>
              WhatsApp
            </v-btn>
            <v-btn x-large rounded outlined color="white" href="mailto:comercial@embacolsa.com.co">
              Correo
            </v-btn>
          </div>
        </v-container>
      </section>
    </main>

    <footer class="store-footer">
      <v-container class="footer-inner">
        <span>© {{ new Date().getFullYear() }} Embacolsa</span>
        <span>Desarrollado por SOFTNOVA SOLUTIONS</span>
      </v-container>
    </footer>

    <v-snackbar v-model="showSearch" color="primary" rounded>
      Listo, vamos a buscar "{{ search || 'todos los productos' }}" en el catalogo.
      <template v-slot:action="{ attrs }">
        <v-btn text v-bind="attrs" @click="showSearch = false">
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>
  </v-app>
</template>

<script>
export default {
  name: 'IndexPage',

  data() {
    return {
      search: '',
      categoriaSeleccionada: null,
      drawer: false,
      showSearch: false,
      links: [
        { label: 'Inicio', href: '#inicio', icon: 'mdi-home-outline' },
        { label: 'Productos', href: '#productos', icon: 'mdi-package-variant-closed' },
        { label: 'Beneficios', href: '#beneficios', icon: 'mdi-shield-check-outline' },
        { label: 'Contacto', href: '#contacto', icon: 'mdi-phone-outline' },
        { label: 'Panel', href: '/modulo-parametrizacion/productos', icon: 'mdi-view-dashboard-outline' },
      ],
      categories: [
        { id: 'cintas', name: 'Cintas', icon: 'mdi-tape-measure' },
        { id: 'stretch', name: 'Stretch film', icon: 'mdi-rollup' },
        { id: 'cajas', name: 'Cajas', icon: 'mdi-package-variant' },
        { id: 'zunchos', name: 'Zunchos', icon: 'mdi-link-variant' },
        { id: 'seguridad', name: 'Seguridad', icon: 'mdi-hard-hat' },
      ],
      products: [
        {
          categoryId: 'cintas',
          type: 'Sellado',
          title: 'Cintas adhesivas',
          text: 'Cierre fuerte, presentacion limpia y medidas para distintos usos.',
          icon: 'mdi-tape-measure',
          class: 'visual-cyan',
        },
        {
          categoryId: 'stretch',
          type: 'Proteccion',
          title: 'Stretch film',
          text: 'Estabilidad para carga, inventario y despachos de alto movimiento.',
          icon: 'mdi-rollup',
          class: 'visual-blue',
        },
        {
          categoryId: 'zunchos',
          type: 'Carga',
          title: 'Sunchos y zunchos',
          text: 'Sujecion firme para cajas, paquetes y procesos industriales.',
          icon: 'mdi-link-variant',
          class: 'visual-gold',
        },
        {
          categoryId: 'cajas',
          type: 'Empaque',
          title: 'Cajas de carton',
          text: 'Opciones resistentes para proteger lo que vendes y despachas.',
          icon: 'mdi-package-variant-closed',
          class: 'visual-green',
        },
      ],
      benefits: [
        { icon: 'mdi-truck-fast-outline', title: 'Despacho confiable', text: 'Coordinacion para que tu operacion no se frene.' },
        { icon: 'mdi-account-tie-outline', title: 'Asesoria real', text: 'Recomendaciones segun el producto y el volumen.' },
        { icon: 'mdi-layers-triple-outline', title: 'Portafolio completo', text: 'Compra lo esencial desde un solo proveedor.' },
        { icon: 'mdi-message-reply-text-outline', title: 'Atencion directa', text: 'Cotizaciones claras y respuesta sin tanta vuelta.' },
      ],
      categoriasParametrizadas: [],
      productosParametrizados: [],
      imagenesParametrizadas: [],
    }
  },

  computed: {
    logoActual() {
      return this.obtenerImagenPorTipo('logo') || '/images/embacolsa.png'
    },

    bannerActual() {
      return this.obtenerImagenPorTipo('banner') ||
        this.obtenerImagenPorTipo('principal') ||
        '/images/login.png'
    },

    heroStyle() {
      return {
        backgroundImage: `linear-gradient(110deg, rgba(7, 28, 66, .94), rgba(9, 71, 104, .78)), url('${this.bannerActual}')`,
      }
    },

    categoriasIndex() {
      const categoriasActivas = this.categoriasParametrizadas
        .filter(categoria => this.estaActivo(categoria.estado))
        .map(categoria => ({
          id: categoria.id,
          name: categoria.nombre,
          icon: 'mdi-format-list-bulleted-type',
        }))

      return categoriasActivas.length ? categoriasActivas : this.categories
    },

    opcionesCategorias() {
      return this.categoriasIndex
    },

    productosIndex() {
      const busqueda = (this.search || '').toLowerCase().trim()
      const productosActivos = this.productosParametrizados
        .filter(producto => this.estaActivo(producto.estado))
        .filter((producto) => {
          if (!this.categoriaSeleccionada) {
            return true
          }

          return producto.categoria_id === this.categoriaSeleccionada ||
            producto.categoria?.id === this.categoriaSeleccionada
        })
        .filter((producto) => {
          if (!busqueda) {
            return true
          }

          return `${producto.nombre || ''} ${producto.descripcion || ''} ${producto.categoria?.nombre || ''}`
            .toLowerCase()
            .includes(busqueda)
        })
        .slice(0, 8)
        .map((producto, index) => ({
          id: producto.id,
          categoryId: producto.categoria_id || producto.categoria?.id,
          type: producto.categoria?.nombre || 'Producto',
          title: producto.nombre,
          text: producto.descripcion || 'Sin descripcion por ahora',
          icon: this.products[index % this.products.length].icon,
          class: this.products[index % this.products.length].class,
          image: this.resolverImagen(producto.ruta_imagen),
        }))

      if (productosActivos.length || this.productosParametrizados.length) {
        return productosActivos
      }

      return this.products
        .filter((producto) => {
          if (!this.categoriaSeleccionada) {
            return true
          }

          return producto.categoryId === this.categoriaSeleccionada
        })
        .filter((producto) => {
          if (!busqueda) {
            return true
          }

          return `${producto.title} ${producto.text} ${producto.type}`
            .toLowerCase()
            .includes(busqueda)
        })
    },

    tituloProductos() {
      const categoria = this.categoriasIndex.find(item => item.id === this.categoriaSeleccionada)

      if (categoria) {
        return `Productos de ${categoria.name}`
      }

      return 'Productos que resuelven el dia a dia de tu bodega'
    },
  },

  mounted() {
    this.cargarParametrizacion()
  },

  methods: {
    async cargarParametrizacion() {
      await Promise.all([
        this.listarCategorias(),
        this.listarProductos(),
        this.listarImagenes(),
      ])
    },

    async listarCategorias() {
      try {
        const response = await this.$axios.get('/categorias/listar')
        this.categoriasParametrizadas = response.data || []
      } catch (error) {
        this.categoriasParametrizadas = []
      }
    },

    async listarProductos() {
      try {
        const response = await this.$axios.post('/productos/listar', {
          paginacion: null,
        })
        this.productosParametrizados = response.data || []
      } catch (error) {
        this.productosParametrizados = []
      }
    },

    async listarImagenes() {
      try {
        const response = await this.$axios.get('/imagenes/listar')
        this.imagenesParametrizadas = response.data || []
      } catch (error) {
        this.imagenesParametrizadas = []
      }
    },

    obtenerImagenPorTipo(tipoBuscado) {
      const imagen = this.imagenesParametrizadas.find((item) => {
        const nombreTipo = item.tipo_imagen?.nombre || item.tipoImagen?.nombre || ''

        return nombreTipo.toLowerCase().includes(tipoBuscado)
      })

      return imagen ? this.resolverImagen(imagen.ruta) : null
    },

    resolverImagen(rutaImagen) {
      if (!rutaImagen) {
        return null
      }

      if (rutaImagen.startsWith('http') || rutaImagen.startsWith('blob:')) {
        return rutaImagen
      }

      const apiUrl = this.$axios?.defaults?.baseURL ||
        this.$config?.API_URL ||
        'http://localhost:8000/api'
      const backendUrl = apiUrl.replace(/\/api\/?$/, '').replace(/\/$/, '')
      const ruta = rutaImagen.startsWith('/') ? rutaImagen : `/${rutaImagen}`

      return `${backendUrl}${ruta}`
    },

    estaActivo(estado) {
      return estado === true || estado === 1 || estado === '1'
    },

    buscarProducto() {
      this.showSearch = true
    },

    seleccionarProducto(product) {
      this.search = product.title
      this.showSearch = true
    },

    limpiarCatalogo() {
      this.search = ''
      this.categoriaSeleccionada = null
    },
  },
}
</script>

<style scoped>
.storefront {
  background: #f4f8fb;
  color: #102b5c;
  font-family: Arial, sans-serif;
}

.store-header {
  background: rgba(255, 255, 255, .96);
  border-bottom: 1px solid #e6edf5;
  position: sticky;
  top: 0;
  z-index: 10;
}

.header-inner {
  align-items: center;
  display: flex;
  gap: 22px;
  min-height: 84px;
}

.brand-link {
  display: block;
  flex: 0 0 auto;
  height: 64px;
  overflow: hidden;
  width: 235px;
}

.brand-logo {
  display: block;
  height: 100%;
  object-fit: cover;
  object-position: center;
  width: 100%;
}

.header-search {
  flex: 1;
  max-width: 520px;
}

.header-links {
  align-items: center;
  gap: 24px;
}

.header-links a {
  color: #17365d;
  font-size: 14px;
  font-weight: 800;
  text-decoration: none;
}

.drawer-logo {
  display: block;
  height: 72px;
  object-fit: cover;
  object-position: center;
  overflow: hidden;
  width: 220px;
}

.hero-section {
  background:
    linear-gradient(110deg, rgba(7, 28, 66, .94), rgba(9, 71, 104, .78)),
    url('/images/login.png') center/cover;
  color: #fff;
}

.hero-inner {
  min-height: 620px;
  padding-bottom: 36px;
  padding-top: 96px;
  position: relative;
}

.hero-copy {
  max-width: 680px;
}

.eyebrow {
  color: #72edf0;
  display: inline-block;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 1px;
}

.eyebrow.blue {
  color: #159eab;
}

.hero-copy h1 {
  font-size: 76px;
  font-weight: 900;
  letter-spacing: 0;
  line-height: .95;
  margin: 16px 0;
}

.hero-copy p {
  color: #e5f4fb;
  font-size: 20px;
  line-height: 1.6;
  max-width: 620px;
}

.hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 28px;
}

.hero-panel {
  bottom: 34px;
  left: 12px;
  max-width: 820px;
  position: absolute;
  right: 12px;
}

.catalog-panel {
  align-items: center;
  background: rgba(255, 255, 255, .94);
  border: 1px solid rgba(255, 255, 255, .5);
  color: #12305e;
  display: grid;
  gap: 18px;
  grid-template-columns: 1.5fr 1fr;
  padding: 18px;
}

.catalog-control span {
  color: #6b7a90;
  display: block;
  font-size: 12px;
  font-weight: 800;
  margin-bottom: 8px;
  text-transform: uppercase;
}

.catalog-summary {
  display: grid;
  gap: 10px;
  grid-template-columns: repeat(2, 1fr);
}

.catalog-summary div {
  background: #f4f8fb;
  border: 1px solid #dfe9f2;
  padding: 14px;
}

.catalog-summary strong,
.catalog-summary span {
  display: block;
}

.catalog-summary strong {
  color: #0f8e9a;
  font-size: 27px;
  line-height: 1;
}

.catalog-summary span {
  color: #6b7a90;
  font-size: 12px;
  font-weight: 800;
  margin-top: 5px;
  text-transform: uppercase;
}

.category-strip {
  background: #fff;
  border-bottom: 1px solid #e6edf5;
  border-top: 1px solid #e6edf5;
}

.catalog-toolbar {
  align-items: center;
  display: flex;
  gap: 14px;
  padding: 18px 0;
}

.catalog-toolbar .v-input {
  max-width: 360px;
}

.products-section,
.operation-section {
  padding: 78px 0;
}

.section-heading {
  margin-bottom: 30px;
  max-width: 680px;
}

.products-heading {
  align-items: flex-end;
  display: flex;
  justify-content: space-between;
  max-width: none;
}

.section-heading h2,
.operation-section h2,
.contact-section h2 {
  color: #112e64;
  font-size: 42px;
  font-weight: 900;
  letter-spacing: 0;
  line-height: 1.08;
  margin: 8px 0 0;
}

.product-card {
  border-color: #dfe9f2 !important;
  border-radius: 8px;
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
}

.product-card:hover {
  box-shadow: 0 18px 36px rgba(16, 43, 92, .12);
  transform: translateY(-4px);
}

.product-visual {
  align-items: center;
  display: flex;
  height: 175px;
  justify-content: center;
}

.product-visual .v-icon {
  color: #fff;
  font-size: 82px;
}

.visual-cyan {
  background: linear-gradient(135deg, #0fa5b1, #39d4d6);
}

.visual-blue {
  background: linear-gradient(135deg, #12305e, #2e7eb5);
}

.visual-gold {
  background: linear-gradient(135deg, #d49522, #f2c75c);
}

.visual-green {
  background: linear-gradient(135deg, #196b5f, #33b18e);
}

.product-type {
  color: #149daa;
  font-size: 11px;
  font-weight: 900;
  text-transform: uppercase;
}

.product-card h3 {
  color: #17365d;
  font-size: 21px;
  font-weight: 900;
  margin: 8px 0;
}

.product-card p,
.operation-section p,
.benefit-item p,
.contact-section p,
.empty-products p {
  color: #65758d;
  line-height: 1.6;
  margin: 0;
}

.empty-products {
  align-items: center;
  background: #fff;
  border: 1px dashed #cbd8e6;
  color: #17365d;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 260px;
  padding: 32px;
  text-align: center;
}

.empty-products h3 {
  font-size: 22px;
  font-weight: 900;
  margin: 12px 0 4px;
}

.operation-section {
  background: #fff;
}

.benefit-grid {
  display: grid;
  gap: 14px;
  grid-template-columns: repeat(2, 1fr);
}

.benefit-item {
  align-items: flex-start;
  background: #f6f9fc;
  border: 1px solid #e2ebf3;
  border-radius: 8px;
  display: flex;
  gap: 14px;
  min-height: 132px;
  padding: 22px;
}

.benefit-item strong {
  color: #17365d;
  display: block;
  font-size: 17px;
  margin-bottom: 6px;
}

.contact-section {
  background: linear-gradient(105deg, #0f2c61, #0fa5b1);
  color: #fff;
  padding: 54px 0;
}

.contact-section h2,
.contact-section p {
  color: #fff;
}

.contact-inner,
.footer-inner {
  align-items: center;
  display: flex;
  gap: 22px;
  justify-content: space-between;
}

.contact-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
}

.store-footer {
  background: #071b42;
  color: #c7d5e8;
  font-size: 13px;
  padding: 20px 0;
}

@media (max-width: 960px) {
  .hero-copy h1 {
    font-size: 58px;
  }

  .benefit-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .hero-panel {
    position: static;
    margin-top: 54px;
  }

  .catalog-panel,
  .catalog-summary {
    grid-template-columns: 1fr;
  }

  .catalog-toolbar,
  .products-heading {
    align-items: stretch;
    flex-direction: column;
  }

  .catalog-toolbar .v-input {
    max-width: none;
  }

  .contact-inner,
  .footer-inner {
    align-items: flex-start;
    flex-direction: column;
  }
}

@media (max-width: 600px) {
  .brand-link {
    height: 56px;
    width: 190px;
  }

  .hero-inner {
    min-height: auto;
    padding-top: 62px;
  }

  .hero-copy h1 {
    font-size: 46px;
  }

  .hero-copy p {
    font-size: 17px;
  }

  .hero-panel,
  .benefit-grid {
    grid-template-columns: 1fr;
  }

  .section-heading h2,
  .operation-section h2,
  .contact-section h2 {
    font-size: 31px;
  }
}
</style>
