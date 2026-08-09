<template>
  <v-app class="catalog-page">
    <header class="catalog-header">
      <v-container class="header-inner">
        <a href="/" class="brand-link">
          <img :src="logoActual" alt="Embacolsa" class="brand-logo">
        </a>

        <nav class="header-links d-none d-md-flex">
          <a href="/">Inicio</a>
          <a href="/#productos">Productos</a>
          <a href="/#beneficios">Beneficios</a>
          <a href="/#contacto">Contacto</a>
          <a href="/login">Panel</a>
        </nav>

        <v-btn icon color="primary" class="d-md-none" @click="drawer = true">
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
      <section class="catalog-hero">
        <v-container>
          <v-row align="center">
            <v-col cols="12" md="7">
              <span class="eyebrow">CATÁLOGO COMPLETO</span>
              <h1>Todos los productos en un solo panel.</h1>
              <!-- <p>
                Busca por nombre, categoría o marca. Esta vista queda separada del inicio
                para que la landing sea limpia y el catálogo sí sea potente.
              </p> -->
            </v-col>

            <v-col cols="12" md="5">
              <v-card class="catalog-count-card" elevation="0">
                <v-icon color="primary" size="42">
                  mdi-store-search-outline
                </v-icon>
                <strong>{{ productosCatalogo.length }}</strong>
                <span>productos encontrados</span>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </section>

      <section class="catalog-content">
        <v-container>
          <v-card outlined class="catalog-search-panel">
            <v-row dense align="center">
              <v-col cols="12" md="5">
                <v-text-field v-model="catalogoBusqueda" label="Nombre o descripción" outlined dense rounded clearable
                  hide-details prepend-inner-icon="mdi-magnify" />
              </v-col>

              <v-col cols="12" md="3">
                <v-select v-model="catalogoCategoria" :items="opcionesCategorias" item-text="name" item-value="id"
                  label="Categoría" outlined dense rounded clearable hide-details prepend-inner-icon="mdi-filter-outline" />
              </v-col>

              <v-col cols="12" md="3">
                <v-select v-model="catalogoMarca" :items="opcionesMarcas" item-text="nombre" item-value="id"
                  label="Marca" outlined dense rounded clearable hide-details prepend-inner-icon="mdi-tag-outline" />
              </v-col>

              <v-col cols="12" md="1" class="text-md-right">
                <v-btn v-if="hayFiltrosCatalogo" icon color="error" @click="limpiarPanelCatalogo">
                  <v-icon>mdi-broom</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card>

          <v-row class="mt-5">
            <v-col v-for="product in productosCatalogo" :key="`catalogo-${product.id || product.title}`" cols="12" md="6">
              <v-card outlined class="catalog-product-row">
                <v-avatar tile size="104" class="catalog-product-image">
                  <v-img v-if="product.image" :src="product.image" cover />
                  <v-icon v-else color="primary" size="46">
                    {{ product.icon }}
                  </v-icon>
                </v-avatar>

                <div class="catalog-product-info">
                  <div class="catalog-product-meta">
                    <v-chip x-small color="primary" outlined>
                      {{ product.type }}
                    </v-chip>
                    <v-chip v-if="product.brand" x-small color="secondary" outlined>
                      {{ product.brand }}
                    </v-chip>
                  </div>

                  <h3>{{ product.title }}</h3>
                  <p>{{ product.text }}</p>

                  <div class="catalog-product-bottom">
                    <span>{{ product.unit || 'Unidad según referencia' }}</span>
                    <strong>{{ product.price || 'Cotizar' }}</strong>
                  </div>
                </div>
              </v-card>
            </v-col>

            <v-col v-if="!productosCatalogo.length" cols="12">
              <div class="empty-products">
                <v-icon size="64" color="grey lighten-1">
                  mdi-database-search-outline
                </v-icon>
                <h3>No encontramos productos</h3>
                <p>Cambia el nombre, la categoría o la marca para ver más resultados.</p>
              </div>
            </v-col>
          </v-row>
        </v-container>
      </section>
    </main>
  </v-app>
</template>

<script>
export default {
  name: 'CatalogoPage',

  data() {
    return {
      drawer: false,
      catalogoBusqueda: '',
      catalogoCategoria: null,
      catalogoMarca: null,
      links: [
        { label: 'Inicio', href: '/', icon: 'mdi-home-outline' },
        { label: 'Productos', href: '/#productos', icon: 'mdi-package-variant-closed' },
        { label: 'Beneficios', href: '/#beneficios', icon: 'mdi-shield-check-outline' },
        { label: 'Contacto', href: '/#contacto', icon: 'mdi-phone-outline' },
        { label: 'Panel', href: '/login', icon: 'mdi-view-dashboard-outline' },
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
          text: 'Cierre fuerte, presentación limpia y medidas para distintos usos.',
          icon: 'mdi-tape-measure',
          class: 'visual-cyan',
        },
        {
          categoryId: 'stretch',
          type: 'Protección',
          title: 'Stretch film',
          text: 'Estabilidad para carga, inventario y despachos de alto movimiento.',
          icon: 'mdi-rollup',
          class: 'visual-blue',
        },
        {
          categoryId: 'zunchos',
          type: 'Carga',
          title: 'Sunchos y zunchos',
          text: 'Sujeción firme para cajas, paquetes y procesos industriales.',
          icon: 'mdi-link-variant',
          class: 'visual-gold',
        },
        {
          categoryId: 'cajas',
          type: 'Empaque',
          title: 'Cajas de cartón',
          text: 'Opciones resistentes para proteger lo que vendes y despachas.',
          icon: 'mdi-package-variant-closed',
          class: 'visual-green',
        },
      ],
      categoriasParametrizadas: [],
      productosParametrizados: [],
      imagenesParametrizadas: [],
      marcasParametrizadas: [],
    }
  },

  computed: {
    logoActual() {
      return this.obtenerImagenPorTipo('logo') || '/images/embacolsa-optimized.webp'
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

    opcionesMarcas() {
      return this.marcasParametrizadas
        .filter(marca => this.estaActivo(marca.estado))
        .map(marca => ({
          id: marca.id,
          nombre: marca.nombre,
        }))
    },

    productosNormalizados() {
      if (!this.productosParametrizados.length) {
        return this.products.map(producto => ({
          ...producto,
          brandId: null,
          brand: null,
          image: null,
          price: null,
          unit: null,
        }))
      }

      return this.productosParametrizados
        .filter(producto => this.estaActivo(producto.estado))
        .map((producto, index) => ({
          id: producto.id,
          categoryId: producto.categoria_id || producto.categoria?.id || null,
          brandId: producto.marca_id || producto.marca?.id || null,
          brand: producto.marca?.nombre || producto.marca_nombre || null,
          type: producto.categoria?.nombre || 'Producto',
          title: producto.nombre,
          text: producto.descripcion || 'Sin descripción por ahora',
          icon: this.products[index % this.products.length].icon,
          class: this.products[index % this.products.length].class,
          image: this.resolverImagen(producto.ruta_imagen),
          unit: producto.unidad_medida,
          price: this.formatearPrecio(producto.precio),
        }))
    },

    productosCatalogo() {
      const busqueda = (this.catalogoBusqueda || '').toLowerCase().trim()

      return this.productosNormalizados
        .filter((producto) => {
          if (!this.catalogoCategoria) {
            return true
          }

          return producto.categoryId === this.catalogoCategoria
        })
        .filter((producto) => {
          if (!this.catalogoMarca) {
            return true
          }

          return producto.brandId === this.catalogoMarca
        })
        .filter((producto) => {
          if (!busqueda) {
            return true
          }

          return `${producto.title} ${producto.text} ${producto.type} ${producto.brand || ''}`
            .toLowerCase()
            .includes(busqueda)
        })
    },

    hayFiltrosCatalogo() {
      return Boolean(this.catalogoBusqueda || this.catalogoCategoria || this.catalogoMarca)
    },
  },

  mounted() {
    this.catalogoBusqueda = this.$route.query.buscar || ''
    this.cargarParametrizacion()
  },

  methods: {
    async cargarParametrizacion() {
      await Promise.all([
        this.listarCategorias(),
        this.listarProductos(),
        this.listarImagenes(),
        this.listarMarcas(),
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

    async listarMarcas() {
      try {
        const response = await this.$axios.get('/marcas/listar')
        this.marcasParametrizadas = response.data || []
      } catch (error) {
        this.marcasParametrizadas = []
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

    formatearPrecio(precio) {
      if (precio === null || precio === undefined || precio === '') {
        return null
      }

      const valor = Number(precio)

      if (Number.isNaN(valor)) {
        return precio
      }

      return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0,
      }).format(valor)
    },

    limpiarPanelCatalogo() {
      this.catalogoBusqueda = ''
      this.catalogoCategoria = null
      this.catalogoMarca = null
    },
  },
}
</script>

<style scoped>
.catalog-page {
  background: #f4f8fb;
  color: #102b5c;
  font-family: Arial, sans-serif;
}

.catalog-header {
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

.brand-logo,
.drawer-logo {
  display: block;
  height: 100%;
  object-fit: cover;
  object-position: center;
  width: 100%;
}

.drawer-logo {
  height: 72px;
  width: 220px;
}

.header-links {
  align-items: center;
  display: flex;
  gap: 24px;
  margin-left: auto;
}

.header-links a {
  color: #17365d;
  font-size: 14px;
  font-weight: 800;
  text-decoration: none;
}

.catalog-hero {
  background:
    radial-gradient(circle at 85% 15%, rgba(114, 237, 240, .22), transparent 28%),
    linear-gradient(110deg, #061d43 0%, #073b60 48%, #0d7880 100%);
  color: #fff;
  padding: 74px 0;
}

.eyebrow {
  color: #72edf0;
  display: inline-block;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 1px;
}

.catalog-hero h1 {
  color: #fff;
  font-size: 54px;
  font-weight: 900;
  line-height: 1;
  margin: 14px 0;
}

.catalog-hero p {
  color: #e5f4fb;
  font-size: 18px;
  line-height: 1.7;
  max-width: 640px;
}

.catalog-count-card {
  align-items: center;
  background: rgba(255, 255, 255, .94) !important;
  border: 1px solid rgba(255, 255, 255, .5);
  display: flex;
  flex-direction: column;
  gap: 8px;
  padding: 34px;
}

.catalog-count-card strong {
  color: #0f8e9a;
  font-size: 54px;
  line-height: 1;
}

.catalog-count-card span {
  color: #65758d;
  font-weight: 800;
  text-transform: uppercase;
}

.catalog-content {
  padding: 54px 0 78px;
}

.catalog-search-panel,
.catalog-product-row {
  border-color: #dfe9f2 !important;
  border-radius: 8px;
}

.catalog-search-panel {
  padding: 18px;
}

.catalog-product-row {
  align-items: stretch;
  display: flex;
  gap: 16px;
  height: 100%;
  padding: 14px;
}

.catalog-product-image {
  background: #f4f8fb;
  border: 1px solid #e1e8f0;
  border-radius: 8px;
  flex: 0 0 auto;
}

.catalog-product-info {
  display: flex;
  flex: 1;
  flex-direction: column;
  min-width: 0;
}

.catalog-product-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 7px;
}

.catalog-product-info h3 {
  color: #17365d;
  font-size: 18px;
  font-weight: 900;
  line-height: 1.2;
  margin: 0 0 6px;
  overflow-wrap: anywhere;
}

.catalog-product-info p,
.empty-products p {
  color: #65758d;
  line-height: 1.45;
  margin: 0;
}

.catalog-product-bottom {
  align-items: center;
  display: flex;
  gap: 12px;
  justify-content: space-between;
  margin-top: auto;
  padding-top: 12px;
}

.catalog-product-bottom span {
  color: #6b7a90;
  font-size: 12px;
  font-weight: 700;
}

.catalog-product-bottom strong {
  color: #0f8e9a;
  font-size: 16px;
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

@media (max-width: 960px) {
  .catalog-hero h1 {
    font-size: 42px;
  }

  .catalog-product-row {
    flex-direction: column;
  }
}

@media (max-width: 600px) {
  .brand-link {
    height: 56px;
    width: 190px;
  }

  .catalog-hero {
    padding: 48px 0;
  }

  .catalog-hero h1 {
    font-size: 35px;
  }
}
</style>
