<template>
  <v-app class="catalog-page" :style="variablesMarca">
    <store-header
      :logo-src="logoActual"
      active-section="catalogo"
      :cart-count="totalItemsCarrito"
      @open-cart="drawerCarrito = true"
    />

    <main>

      <section class="catalog-hero">
        <v-container>
          <v-row align="center" class="catalog-hero-row">

            <v-col cols="12" md="7">
              <div class="catalog-eyebrow">
                {{ configuracionSitio.etiqueta_catalogo }}
              </div>

              <h1 class="catalog-title">
                {{ configuracionSitio.titulo_catalogo }}
              </h1>

              <p class="catalog-lead">
                {{ configuracionSitio.descripcion_catalogo }}
              </p>

            </v-col>

            <v-col cols="12" md="5">
              <v-card flat class="catalog-counter-card pa-7 text-center">
                <v-icon color="primary" size="44">
                  mdi-store-search-outline
                </v-icon>

                <div class="text-h2 font-weight-black primary--text mt-2">
                  {{ productosCatalogo.length }}
                </div>

                <div class="text-caption font-weight-bold text-uppercase grey--text">
                  productos encontrados
                </div>

                <div class="catalog-counter-grid">
                  <div>
                    <strong>{{ opcionesCategorias.length }}</strong>
                    <span>categorías</span>
                  </div>
                  <div>
                    <strong>{{ opcionesMarcas.length }}</strong>
                    <span>marcas</span>
                  </div>
                  <div>
                    <strong>B2B</strong>
                    <span>cotización</span>
                  </div>
                </div>
              </v-card>
            </v-col>

          </v-row>
        </v-container>
      </section>

      <!-- CONTENIDO -->
      <section class="py-12">
        <v-container>

          <!-- FILTROS -->
          <v-card outlined class="catalog-filter-card pa-4">
            <div class="filter-title">
              <div>
                <span>Encuentra rápido</span>
                <strong>Filtra el portafolio disponible</strong>
              </div>

              <v-btn v-if="hayFiltrosCatalogo" text rounded color="error" @click="limpiarPanelCatalogo">
                <v-icon left small>mdi-broom</v-icon>
                Limpiar
              </v-btn>
            </div>

            <v-row dense align="center">

              <v-col cols="12" md="5">
                <v-text-field v-model="catalogoBusqueda" label="Nombre o descripción" outlined dense rounded clearable
                  hide-details prepend-inner-icon="mdi-magnify" />
              </v-col>



              <v-col cols="12" md="3">
                <v-select v-model="catalogoMarca" :items="opcionesMarcas" item-text="nombre" item-value="id"
                  label="Marca" outlined dense rounded clearable hide-details prepend-inner-icon="mdi-tag-outline" />
              </v-col>

              <v-col cols="12" md="1" class="text-center">
                <v-tooltip bottom v-if="hayFiltrosCatalogo">
                  <template #activator="{ on, attrs }">
                    <v-btn icon color="error" v-bind="attrs" v-on="on" @click="limpiarPanelCatalogo">
                      <v-icon>mdi-broom</v-icon>
                    </v-btn>
                  </template>

                  <span>Limpiar filtros</span>
                </v-tooltip>
              </v-col>

            </v-row>
          </v-card>

          <div v-if="opcionesCategorias.length" class="category-rail">
            <button type="button" :class="{ active: !catalogoCategoria }" @click="catalogoCategoria = null">
              <v-icon small>mdi-view-grid-outline</v-icon>
              Todo
            </button>

            <button v-for="categoria in opcionesCategorias" :key="`rail-${categoria.id}`" type="button"
              :class="{ active: String(catalogoCategoria) === String(categoria.id) }"
              @click="catalogoCategoria = categoria.id">
              <v-icon small>mdi-package-variant-closed</v-icon>
              {{ categoria.name }}
            </button>
          </div>

          <!-- LOADER -->
          <div v-if="cargandoProductos" class="text-center py-16">
            <v-progress-circular indeterminate color="primary" size="54" width="5" />

            <div class="mt-4 grey--text text--darken-1">
              Cargando productos...
            </div>
          </div>

          <!-- PRODUCTOS -->
          <v-row v-else class="mt-5">
            <v-col v-for="product in productosCatalogoPaginados" :key="`catalogo-${product.id}`" cols="12" md="6" lg="4">
              <v-card outlined hover height="100%" class="catalog-product-card">
                <div class="catalog-product-visual">
                  <v-img v-if="product.image" :src="product.image" height="245" cover>
                    <template #placeholder>
                      <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="primary" />
                      </v-row>
                    </template>
                  </v-img>

                  <div v-else class="catalog-product-empty">
                    <v-icon color="primary" size="72">
                      {{ product.icon }}
                    </v-icon>
                  </div>

                  <div class="product-category-pill">
                    {{ product.type }}
                  </div>
                </div>

                <div class="catalog-product-body">
                  <div class="product-meta-row">
                    <span>{{ product.unit || 'Unidad por definir' }}</span>
                    <strong v-if="product.brand">{{ product.brand }}</strong>
                    <strong v-else>Disponible</strong>
                  </div>

                  <h2>{{ product.title }}</h2>

                  <p>
                    {{ limitarTexto(product.text, 136) }}
                  </p>

                  <div class="product-price-row">
                    <div>
                      <span>Precio</span>
                      <strong>{{ product.price || 'A cotizar' }}</strong>
                    </div>

                    <v-chip small :color="productoAgotado(product) ? 'error' : 'success'" text-color="white">
                      {{ productoAgotado(product) ? 'Agotado' : `${product.stock} und` }}
                    </v-chip>
                  </div>

                  <div class="product-actions">
                    <v-btn rounded depressed color="primary" @click="verDetalle(product)">
                      Ver detalle
                    </v-btn>

                    <v-btn rounded outlined color="success" @click="cotizarProducto(product)">
                      <v-icon left small>
                        mdi-whatsapp
                      </v-icon>
                      Cotizar
                    </v-btn>

                    <v-btn
                      rounded
                      depressed
                      class="cart-add-btn"
                      :disabled="productoAgotado(product)"
                      @click="agregarAlCarrito(product)"
                    >
                      <v-icon left small>
                        {{ productoAgotado(product) ? 'mdi-cart-off' : 'mdi-cart-plus' }}
                      </v-icon>
                      {{ productoAgotado(product) ? 'Agotado' : 'Añadir al carrito' }}
                    </v-btn>
                  </div>
                </div>
              </v-card>
            </v-col>

            <!-- SIN RESULTADOS -->
            <v-col v-if="!productosCatalogo.length" cols="12">
              <v-sheet outlined rounded="lg" class="pa-12 text-center">
                <v-icon size="72" color="grey lighten-1">
                  mdi-database-search-outline
                </v-icon>

                <div class="text-h5 font-weight-black mt-4" style="color: #17365d;">
                  No encontramos productos
                </div>

                <div class="grey--text mt-2">
                  Cambia el nombre, la categoría o la marca para ver más resultados.
                </div>

                <v-btn v-if="hayFiltrosCatalogo" color="primary" outlined rounded class="mt-5"
                  @click="limpiarPanelCatalogo">
                  Limpiar filtros
                </v-btn>
              </v-sheet>
            </v-col>

          </v-row>

          <div v-if="totalPaginasCatalogo > 1" class="catalog-pagination">
            <span>
              Mostrando {{ productosCatalogoPaginados.length }} de {{ productosCatalogo.length }} productos
            </span>

            <v-pagination
              v-model="paginaCatalogo"
              :length="totalPaginasCatalogo"
              :total-visible="7"
              circle
              color="primary"
            />
          </div>

        </v-container>
      </section>

    </main>

    <!-- MODAL DETALLE -->
    <v-dialog v-model="modalDetalle" max-width="900" scrollable>
      <v-card v-if="productoSeleccionado" class="rounded-lg">
        <v-card-title class="d-flex align-center">
          <v-icon color="primary" class="mr-2">
            mdi-package-variant-closed
          </v-icon>

          <span class="font-weight-black">
            Detalle del producto
          </span>

          <v-spacer />

          <v-btn icon @click="modalDetalle = false">
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-divider />

        <v-card-text class="pa-0">
          <v-row no-gutters>

            <!-- IMAGEN GRANDE -->
            <v-col cols="12" md="5" class="grey lighten-5">
              <div class="pa-6 d-flex align-center justify-center" style="min-height: 390px;">
                <v-img v-if="productoSeleccionado.image" :src="productoSeleccionado.image" contain max-height="350" />

                <v-icon v-else color="primary" size="120">
                  {{ productoSeleccionado.icon }}
                </v-icon>
              </div>
            </v-col>

            <!-- DETALLE -->
            <v-col cols="12" md="7">
              <div class="pa-7">

                <div class="mb-3">
                  <v-chip color="primary" outlined small class="mr-2">
                    {{ productoSeleccionado.type }}
                  </v-chip>

                  <v-chip v-if="productoSeleccionado.brand" color="secondary" outlined small>
                    {{ productoSeleccionado.brand }}
                  </v-chip>
                </div>

                <h2 class="text-h4 font-weight-black mb-4" style="color: #17365d;">
                  {{ productoSeleccionado.title }}
                </h2>

                <div class="text-body-1 grey--text text--darken-2 mb-6" style="line-height: 1.7;">
                  {{ productoSeleccionado.text }}
                </div>

                <v-divider />

                <v-list class="transparent">

                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="primary">
                        mdi-ruler
                      </v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-subtitle>
                        Unidad de medida
                      </v-list-item-subtitle>

                      <v-list-item-title class="font-weight-bold">
                        {{ productoSeleccionado.unit || 'No especificada' }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="primary">
                        mdi-cash
                      </v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-subtitle>
                        Precio
                      </v-list-item-subtitle>

                      <v-list-item-title class="font-weight-black primary--text">
                        {{ productoSeleccionado.price || 'Solicitar cotización' }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item>
                    <v-list-item-icon>
                      <v-icon color="primary">
                        mdi-package-check
                      </v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-subtitle>
                        Stock disponible
                      </v-list-item-subtitle>

                      <v-list-item-title class="font-weight-bold">
                        {{ productoAgotado(productoSeleccionado) ? 'Agotado' : `${productoSeleccionado.stock} unidades` }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item v-if="productoSeleccionado.bodega">
                    <v-list-item-icon>
                      <v-icon color="primary">
                        mdi-warehouse
                      </v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-subtitle>
                        Bodega
                      </v-list-item-subtitle>

                      <v-list-item-title class="font-weight-bold">
                        {{ productoSeleccionado.bodega }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>

                  <v-list-item v-if="productoSeleccionado.brand">
                    <v-list-item-icon>
                      <v-icon color="primary">
                        mdi-tag-outline
                      </v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-subtitle>
                        Marca
                      </v-list-item-subtitle>

                      <v-list-item-title class="font-weight-bold">
                        {{ productoSeleccionado.brand }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>

                </v-list>

              </div>
            </v-col>

          </v-row>
        </v-card-text>

        <v-divider />

        <v-card-actions class="pa-4">
          <v-spacer />

          <v-btn text rounded @click="modalDetalle = false">
            Cerrar
          </v-btn>

          <v-btn rounded color="success" dark @click="cotizarProducto(productoSeleccionado)">
            <v-icon left>
              mdi-whatsapp
            </v-icon>

            Solicitar cotización
          </v-btn>

          <v-btn
            rounded
            depressed
            class="cart-add-btn"
            :disabled="productoAgotado(productoSeleccionado)"
            @click="agregarAlCarrito(productoSeleccionado)"
          >
            <v-icon left>
              {{ productoAgotado(productoSeleccionado) ? 'mdi-cart-off' : 'mdi-cart-plus' }}
            </v-icon>

            {{ productoAgotado(productoSeleccionado) ? 'Agotado' : 'Agregar al carrito' }}
          </v-btn>
        </v-card-actions>

      </v-card>
    </v-dialog>

    <v-navigation-drawer v-model="drawerCarrito" fixed temporary right width="440" class="cart-drawer">
      <div class="cart-header">
        <div class="cart-header-icon">
          <v-icon color="white">
            mdi-cart-heart
          </v-icon>
        </div>

        <div class="cart-header-copy">
          <span>Pedido rápido</span>
          <h3>Carrito</h3>
          <p>{{ totalItemsCarrito }} producto(s) listos para consultar</p>
        </div>

        <v-btn icon dark @click="drawerCarrito = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </div>

      <div v-if="!carrito.length" class="cart-empty">
        <v-icon size="72" color="primary">
          mdi-cart-outline
        </v-icon>

        <h4>Tu carrito está vacío</h4>
        <p>Agrega productos del catálogo y cotízalos todos en un solo mensaje.</p>

        <v-btn rounded depressed color="primary" class="mt-5" @click="drawerCarrito = false">
          Explorar productos
        </v-btn>
      </div>

      <div v-else class="cart-content">
        <div v-for="item in carrito" :key="`cart-${item.id}`" class="cart-item">
          <div class="cart-item-visual">
            <v-img v-if="item.image" :src="item.image" height="72" width="72" cover />
            <v-icon v-else color="primary">
              {{ item.icon }}
            </v-icon>
          </div>

          <div class="cart-item-body">
            <div class="cart-item-top">
              <span>{{ item.type }}</span>

              <v-btn x-small icon color="error" @click="quitarDelCarrito(item)">
                <v-icon small>mdi-trash-can-outline</v-icon>
              </v-btn>
            </div>

            <strong>{{ item.title }}</strong>

            <small>
              {{ item.brand ? `${item.brand} · ` : '' }}{{ item.price || 'Precio a cotizar' }}
            </small>
            <small>{{ productoAgotado(item) ? 'Agotado' : `${item.stock} und disponibles` }}</small>

            <div class="cart-qty">
              <v-btn x-small fab depressed color="#eef5ff" @click="disminuirCantidad(item)">
                <v-icon small>mdi-minus</v-icon>
              </v-btn>

              <b>{{ item.cantidad }}</b>

              <v-btn x-small fab depressed color="#eef5ff" :disabled="productoAgotado(item)" @click="incrementarCantidad(item)">
                <v-icon small>mdi-plus</v-icon>
              </v-btn>
            </div>
          </div>
        </div>

        <div class="cart-summary">
          <span>Total estimado</span>
          <strong>{{ carritoFormateado || 'A cotizar' }}</strong>
        </div>

        <v-btn block rounded x-large class="cart-whatsapp-btn" @click="cotizarCarrito">
          <v-icon left>mdi-whatsapp</v-icon>
          Cotizar por WhatsApp
        </v-btn>

        <v-btn block rounded text color="error" class="mt-2" @click="vaciarCarrito">
          Vaciar carrito
        </v-btn>
      </div>
    </v-navigation-drawer>

  </v-app>
</template>

<script>
export default {
  name: 'CatalogoPage',

  data() {
    return {
      cargandoProductos: false,

      catalogoBusqueda: '',
      catalogoCategoria: null,
      catalogoMarca: null,
      paginaCatalogo: 1,
      productosPorPagina: 9,

      modalDetalle: false,
      productoSeleccionado: null,
      drawerCarrito: false,
      carrito: [],

      categoriasParametrizadas: [],
      productosParametrizados: [],
      imagenesParametrizadas: [],
      marcasParametrizadas: [],
      configuracionSitio: this.configuracionPorDefecto(),
    }
  },

  computed: {

    logoActual() {
      return (
        this.obtenerImagenPorTipo('logo') ||
        '/images/novacell.png'
      )
    },

    coloresMarca() {
      return {
        primario: '#0f2c61',
        secundario: '#0d7880',
        acento: '#1e88e5',
        fondo: '#f4f8fb',
        ...(this.configuracionSitio.colores || {}),
      }
    },

    variablesMarca() {
      return {
        '--brand-primary': this.coloresMarca.primario,
        '--brand-secondary': this.coloresMarca.secundario,
        '--brand-accent': this.coloresMarca.acento,
        '--brand-bg': this.coloresMarca.fondo,
      }
    },

    opcionesCategorias() {
      return this.categoriasParametrizadas
        .filter(categoria => this.estaActivo(categoria.estado))
        .map(categoria => ({
          id: categoria.id,
          name: categoria.nombre,
        }))
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
      return this.productosParametrizados
        .filter(producto => this.estaActivo(producto.estado))
        .map(producto => ({
          id: producto.id,

          categoryId:
            producto.categoria_id ||
            (producto.categoria ? producto.categoria.id : null),

          brandId:
            producto.marca_id ||
            (producto.marca ? producto.marca.id : null),

          brand:
            (producto.marca ? producto.marca.nombre : null) ||
            producto.marca_nombre ||
            null,

          type:
            (producto.categoria ? producto.categoria.nombre : null) ||
            producto.categoria_nombre ||
            'Producto',

          title: producto.nombre,

          text:
            producto.descripcion ||
            'Este producto no tiene una descripción registrada.',

          icon: this.obtenerIconoProducto(producto),

          image: this.resolverImagen(producto.ruta_imagen),

          unit: producto.unidad_medida || null,

          price: this.formatearPrecio(producto.precio),

          precioOriginal: producto.precio,

          stock: Number(producto.stock || 0),

          bodega:
            (producto.bodega ? producto.bodega.nombre : null) ||
            producto.bodega_nombre ||
            null,
        }))
    },

    productosCatalogo() {
      const busqueda = String(this.catalogoBusqueda || '')
        .toLowerCase()
        .trim()

      return this.productosNormalizados
        .filter((producto) => {
          if (this.catalogoCategoria === null || this.catalogoCategoria === '') {
            return true
          }

          return (String(producto.categoryId) === String(this.catalogoCategoria))
        })

        .filter((producto) => {
          if (
            this.catalogoMarca === null ||
            this.catalogoMarca === ''
          ) {
            return true
          }

          return (
            String(producto.brandId) ===
            String(this.catalogoMarca)
          )
        })

        .filter((producto) => {
          if (!busqueda) {
            return true
          }

          const contenido = `
            ${producto.title}
            ${producto.text}
            ${producto.type}
            ${producto.brand || ''}
          `
            .toLowerCase()

          return contenido.includes(busqueda)
        })
    },

    hayFiltrosCatalogo() {
      return Boolean(this.catalogoBusqueda || this.catalogoCategoria || this.catalogoMarca)
    },

    totalPaginasCatalogo() {
      return Math.ceil(this.productosCatalogo.length / this.productosPorPagina)
    },

    productosCatalogoPaginados() {
      const inicio = (this.paginaCatalogo - 1) * this.productosPorPagina
      const fin = inicio + this.productosPorPagina

      return this.productosCatalogo.slice(inicio, fin)
    },

    totalItemsCarrito() {
      return this.carrito.reduce((total, item) => total + Number(item.cantidad || 0), 0)
    },

    totalCarrito() {
      return this.carrito.reduce((total, item) => {
        const precio = Number(item.precioOriginal || 0)
        return total + (Number.isNaN(precio) ? 0 : precio * Number(item.cantidad || 0))
      }, 0)
    },

    carritoFormateado() {
      return this.totalCarrito
        ? this.formatearPrecio(this.totalCarrito)
        : null
    },
  },

  mounted() {
    this.catalogoBusqueda = this.$route.query.buscar || ''
    this.cargarCarrito()
    this.drawerCarrito = this.$route.query.carrito === '1'

    this.cargarParametrizacion()
  },

  watch: {
    catalogoBusqueda() {
      this.paginaCatalogo = 1
    },

    catalogoCategoria() {
      this.paginaCatalogo = 1
    },

    catalogoMarca() {
      this.paginaCatalogo = 1
    },

    productosCatalogo() {
      if (this.paginaCatalogo > this.totalPaginasCatalogo) {
        this.paginaCatalogo = this.totalPaginasCatalogo || 1
      }
    },
  },

  methods: {
    productoAgotado(producto) {
      return !producto || Number(producto.stock || 0) <= 0
    },

    async cargarParametrizacion() {
      this.cargandoProductos = true

      try {
        await Promise.all([
          this.listarCategorias(),
          this.listarProductos(),
          this.listarImagenes(),
          this.listarMarcas(),
          this.obtenerConfiguracionSitio(),
        ])
      } finally {
        this.cargandoProductos = false
      }
    },

    configuracionPorDefecto() {
      return {
        nombre_sitio: '',
        etiqueta_catalogo: '',
        titulo_catalogo: '',
        descripcion_catalogo: '',
        telefono_whatsapp: '',
        colores: {
          primario: '#0f2c61',
          secundario: '#0d7880',
          acento: '#1e88e5',
          fondo: '#f4f8fb',
        },
      }
    },

    async obtenerConfiguracionSitio() {
      try {
        const response = await this.$axios.get('/sitio-publico/configuracion')
        const data = response.data || {}
        const base = this.configuracionPorDefecto()

        this.configuracionSitio = {
          ...base,
          ...data,
          colores: {
            ...base.colores,
            ...(data.colores || {}),
          },
        }
      } catch (error) {
        this.configuracionSitio = this.configuracionPorDefecto()
      }
    },

    async listarCategorias() {
      try {
        const response = await this.$axios.get('/categorias/listar')
        this.categoriasParametrizadas = this.extraerLista(response.data)
      } catch (error) {
        this.$toast.error('Ha ocurrido un error al cargar las categorías.');
      }
    },

    async listarProductos() {
      try {
        const response = await this.$axios.post(
          '/productos/listar',
          {
            paginacion: null,
          }
        )

        this.productosParametrizados =
          this.extraerLista(response.data)
      } catch (error) {
        console.error(
          'Error cargando productos:',
          error
        )

        this.productosParametrizados = []
      }
    },

    async listarImagenes() {
      try {
        const response = await this.$axios.get(
          '/imagenes/listar'
        )

        this.imagenesParametrizadas =
          this.extraerLista(response.data)
      } catch (error) {
        console.error(
          'Error cargando imágenes:',
          error
        )

        this.imagenesParametrizadas = []
      }
    },

    async listarMarcas() {
      try {
        const response = await this.$axios.get(
          '/marcas/listar'
        )

        this.marcasParametrizadas =
          this.extraerLista(response.data)
      } catch (error) {
        console.error(
          'Error cargando marcas:',
          error
        )

        this.marcasParametrizadas = []
      }
    },

    /**
     * Soporta:
     *
     * response.data = []
     *
     * response.data = {
     *   data: []
     * }
     *
     * response.data = {
     *   data: {
     *     data: []
     *   }
     * }
     */
    extraerLista(data) {
      if (Array.isArray(data)) {
        return data
      }

      if (data && Array.isArray(data.data)) {
        return data.data
      }

      if (
        data &&
        data.data &&
        Array.isArray(data.data.data)
      ) {
        return data.data.data
      }

      return []
    },

    verDetalle(producto) {
      this.productoSeleccionado = producto
      this.modalDetalle = true
    },

    cotizarProducto(producto) {
      if (!producto) {
        return
      }

      const mensaje = [
        `Hola ${this.configuracionSitio.nombre_sitio || 'NovaCell'}, estoy interesado en cotizar el siguiente producto:`,
        '',
        `Producto: ${producto.title}`,
        `Categoría: ${producto.type}`,
        producto.brand
          ? `Marca: ${producto.brand}`
          : null,
        producto.unit
          ? `Unidad: ${producto.unit}`
          : null,
        producto.price
          ? `Precio publicado: ${producto.price}`
          : 'Precio: Solicitar cotización',
        '',
        '¿Me pueden brindar más información?',
      ]
        .filter(Boolean)
        .join('\n')

      const numero = this.configuracionSitio.telefono_whatsapp || ''

      if (numero) {
        const numeroLimpio = String(numero)
          .replace(/\D/g, '')

        const url =
          `https://wa.me/${numeroLimpio}` +
          `?text=${encodeURIComponent(mensaje)}`

        window.open(
          url,
          '_blank',
          'noopener,noreferrer'
        )

        return
      }

      /**
       * Si todavía no has configurado WhatsApp,
       * enviamos al usuario al contacto.
       */
      this.$router.push({
        path: '/',
        query: {
          producto: producto.title,
        },
        hash: '#contacto',
      })
    },

    normalizarProductoCarrito(producto) {
      return {
        id: producto.id,
        title: producto.title,
        text: producto.text,
        type: producto.type,
        brand: producto.brand,
        unit: producto.unit,
        price: producto.price,
        precioOriginal: producto.precioOriginal,
        stock: producto.stock,
        bodega: producto.bodega,
        image: producto.image,
        icon: producto.icon,
        cantidad: 1,
      }
    },

    agregarAlCarrito(producto) {
      if (!producto) {
        return
      }

      if (this.productoAgotado(producto)) {
        if (this.$toast) {
          this.$toast.warning('Este producto está agotado y no se puede agregar al carrito.')
        }

        return
      }

      const item = this.carrito.find(productoCarrito => String(productoCarrito.id) === String(producto.id))

      if (item) {
        if (Number(item.stock || 0) > 0 && item.cantidad >= Number(item.stock)) {
          if (this.$toast) {
            this.$toast.warning('Ya agregaste todo el stock disponible de este producto.')
          }

          this.drawerCarrito = true
          return
        }

        item.cantidad += 1
      } else {
        this.carrito.push(this.normalizarProductoCarrito(producto))
      }

      this.guardarCarrito()
      this.drawerCarrito = true

      if (this.$toast) {
        this.$toast.success('Producto agregado al carrito.')
      }
    },

    incrementarCantidad(item) {
      if (this.productoAgotado(item)) {
        if (this.$toast) {
          this.$toast.warning('Este producto está agotado.')
        }

        return
      }

      if (Number(item.stock || 0) > 0 && item.cantidad >= Number(item.stock)) {
        if (this.$toast) {
          this.$toast.warning('No hay más stock disponible para este producto.')
        }

        return
      }

      item.cantidad += 1
      this.guardarCarrito()
    },

    disminuirCantidad(item) {
      if (item.cantidad <= 1) {
        this.quitarDelCarrito(item)
        return
      }

      item.cantidad -= 1
      this.guardarCarrito()
    },

    quitarDelCarrito(item) {
      this.carrito = this.carrito.filter(productoCarrito => String(productoCarrito.id) !== String(item.id))
      this.guardarCarrito()
    },

    vaciarCarrito() {
      this.carrito = []
      this.guardarCarrito()
    },

    guardarCarrito() {
      if (!process.client) {
        return
      }

      localStorage.setItem('novacell_cart', JSON.stringify(this.carrito))
    },

    cargarCarrito() {
      if (!process.client) {
        return
      }

      try {
        this.carrito = JSON.parse(localStorage.getItem('novacell_cart') || '[]')
      } catch (error) {
        this.carrito = []
      }
    },

    cotizarCarrito() {
      if (!this.carrito.length) {
        return
      }

      const productos = this.carrito
        .map((item, index) => [
          `${index + 1}. ${item.title} x${item.cantidad}`,
          item.type ? `   Categoría: ${item.type}` : null,
          item.brand ? `   Marca: ${item.brand}` : null,
          item.unit ? `   Unidad: ${item.unit}` : null,
          item.price ? `   Precio publicado: ${item.price}` : '   Precio: Solicitar cotización',
          item.stock > 0 ? `   Stock visto en web: ${item.stock} unidades` : null,
        ].filter(Boolean).join('\n'))
        .join('\n\n')

      const mensaje = [
        `Hola ${this.configuracionSitio.nombre_sitio || 'NovaCell'}, quiero cotizar estos productos:`,
        '',
        productos,
        '',
        this.carritoFormateado
          ? `Total estimado: ${this.carritoFormateado}`
          : null,
        '¿Me pueden confirmar disponibilidad y tiempos de entrega?',
      ]
        .filter(Boolean)
        .join('\n')

      const numero = this.configuracionSitio.telefono_whatsapp || ''

      if (numero) {
        const numeroLimpio = String(numero).replace(/\D/g, '')
        const url = `https://wa.me/${numeroLimpio}?text=${encodeURIComponent(mensaje)}`

        window.open(url, '_blank', 'noopener,noreferrer')
        return
      }

      this.$router.push({
        path: '/',
        query: {
          pedido: 'carrito',
        },
        hash: '#contacto',
      })
    },

    obtenerImagenPorTipo(tipoBuscado) {
      const imagen =
        this.imagenesParametrizadas.find((item) => {
          const nombreTipo =
            (item.tipo_imagen
              ? item.tipo_imagen.nombre
              : '') ||
            (item.tipoImagen
              ? item.tipoImagen.nombre
              : '') ||
            ''

          return nombreTipo
            .toLowerCase()
            .includes(
              String(tipoBuscado).toLowerCase()
            )
        })

      return imagen
        ? this.resolverImagen(imagen.ruta)
        : null
    },

    resolverImagen(rutaImagen) {
      if (!rutaImagen) {
        return null
      }

      if (
        rutaImagen.startsWith('http://') ||
        rutaImagen.startsWith('https://') ||
        rutaImagen.startsWith('blob:')
      ) {
        return rutaImagen
      }

      const apiUrl =
        (this.$axios && this.$axios.defaults &&
          this.$axios.defaults.baseURL
        ) ||
        (
          this.$config &&
          this.$config.API_URL
        ) ||
        'http://localhost:8000/api'

      const backendUrl = apiUrl
        .replace(/\/api\/?$/, '')
        .replace(/\/$/, '')

      const ruta = rutaImagen.startsWith('/')
        ? rutaImagen
        : `/${rutaImagen}`

      return `${backendUrl}${ruta}`
    },

    obtenerIconoProducto(producto) {
      const categoria =
        (
          producto.categoria &&
          producto.categoria.nombre
        ) ||
        producto.categoria_nombre ||
        ''

      const nombre = categoria.toLowerCase()

      if (nombre.includes('forro') || nombre.includes('funda') || nombre.includes('case')) {
        return 'mdi-shield-phone-outline'
      }

      if (nombre.includes('cable') || nombre.includes('adaptador')) {
        return 'mdi-usb-port'
      }

      if (nombre.includes('audífono') || nombre.includes('audifono') || nombre.includes('audio')) {
        return 'mdi-headphones'
      }

      if (nombre.includes('vidrio') || nombre.includes('templado')) {
        return 'mdi-cellphone-screenshot'
      }

      if (nombre.includes('soporte') || nombre.includes('carro')) {
        return 'mdi-car-connected'
      }

      if (nombre.includes('cargador') || nombre.includes('carga')) {
        return 'mdi-battery-charging-high'
      }

      return 'mdi-cellphone-link'
    },

    estaActivo(estado) {
      return (
        estado === true ||
        estado === 1 ||
        estado === '1'
      )
    },

    formatearPrecio(precio) {
      if (precio === null || precio === undefined || precio === '') {
        return null
      }

      const valor = Number(precio)

      if (Number.isNaN(valor)) {
        return precio
      }

      return new Intl.NumberFormat(
        'es-CO',
        {
          style: 'currency',
          currency: 'COP',
          minimumFractionDigits: 0,
        }
      ).format(valor)
    },

    limitarTexto(texto, limite = 120) {
      if (!texto) {
        return ''
      }

      if (texto.length <= limite) {
        return texto
      }

      return `${texto.substring(0, limite)}...`
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
  color: #0b2a55;
}

.catalog-hero {
  background:
    linear-gradient(rgba(255, 255, 255, .045) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, .045) 1px, transparent 1px),
    radial-gradient(circle at 78% 14%, rgba(114, 237, 240, .25), transparent 28%),
    radial-gradient(circle at 18% 68%, rgba(30, 136, 229, .23), transparent 32%),
    linear-gradient(110deg, #071f42 0%, #0f4f7c 56%, #0d7880 100%);

  background-size: 42px 42px, 42px 42px, auto, auto, auto;
  color: #fff;
  overflow: hidden;
  position: relative;
}

.catalog-hero::after {
  background:
    radial-gradient(
      circle,
      rgba(255, 255, 255, .18) 0 1px,
      transparent 1px
    );

  background-size: 22px 22px;
  content: '';
  inset: 0;
  opacity: .24;
  pointer-events: none;
  position: absolute;
}

.catalog-hero-row {
  position: relative;
  z-index: 2;
}

.catalog-eyebrow {
  color: #72edf0;
  font-size: 12px;
  font-weight: 950;
  letter-spacing: 1.4px;
  text-transform: uppercase;
}

.catalog-title {
  color: #fff;
  font-size: clamp(44px, 6vw, 72px);
  font-weight: 950;
  line-height: 1.05;
  margin-top: 12px;
  max-width: 700px;
}

.catalog-lead {
  color: rgba(255, 255, 255, .82);
  font-size: 18px;
  line-height: 1.7;
  margin-top: 18px;
  max-width: 640px;
}

.catalog-hero-actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 30px;
}

.catalog-main-action {
  background: linear-gradient(
    135deg,
    #0b5ed7,
    #72edf0
  ) !important;

  box-shadow:
    0 18px 36px rgba(11, 94, 215, .32) !important;

  color: #fff !important;
  font-weight: 950;
}

.catalog-counter-card,
.catalog-filter-card,
.catalog-product-card {
  margin-top: 24px;
  border-radius: 24px !important;
}

.catalog-counter-card {
  backdrop-filter: blur(18px);

  background:
    linear-gradient(
      145deg,
      rgba(255, 255, 255, .98),
      rgba(240, 247, 255, .94)
    ) !important;

  border: 1px solid rgba(255, 255, 255, .62) !important;

  box-shadow:
    0 28px 70px rgba(6, 29, 67, .22) !important;

  overflow: hidden;
  position: relative;
}

.catalog-counter-card::before {
  background:
    linear-gradient(
      135deg,
      #071f42,
      #0d7880
    );

  content: '';
  height: 6px;
  left: 0;
  position: absolute;
  right: 0;
  top: 0;
}

.catalog-counter-grid {
  border-top: 1px solid #dce8f5;
  display: grid;
  gap: 0;
  grid-template-columns: repeat(3, 1fr);
  margin-top: 22px;
  padding-top: 18px;
}

.catalog-counter-grid div {
  border-right: 1px solid #dce8f5;
  padding: 4px 10px;
}

.catalog-counter-grid div:last-child {
  border-right: 0;
}

.catalog-counter-grid strong,
.catalog-counter-grid span {
  display: block;
}

.catalog-counter-grid strong {
  color: #061d43;
  font-size: 22px;
  font-weight: 950;
}

.catalog-counter-grid span {
  color: #7487a3;
  font-size: 11px;
  font-weight: 900;
  text-transform: uppercase;
}

.catalog-filter-card {
  background: rgba(255, 255, 255, .97) !important;
  border-color: #dce8f5 !important;

  box-shadow:
    0 24px 58px rgba(6, 29, 67, .12);

  margin-top: -68px;
  position: relative;
  z-index: 2;
}

.filter-title {
  align-items: center;
  display: flex;
  gap: 16px;
  justify-content: space-between;
  margin-bottom: 14px;
}

.filter-title span,
.filter-title strong {
  display: block;
}

.filter-title span {
  color: #0b5ed7;
  font-size: 11px;
  font-weight: 950;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.filter-title strong {
  color: #061d43;
  font-size: 20px;
  font-weight: 950;
}

.category-rail {
  display: flex;
  gap: 10px;
  margin: 22px 0 6px;
  overflow-x: auto;
  padding-bottom: 6px;
}

.category-rail button {
  align-items: center;
  background: #fff;
  border: 1px solid #dce8f5;
  border-radius: 999px;
  color: #061d43;
  cursor: pointer;
  display: inline-flex;
  flex: 0 0 auto;
  font-size: 13px;
  font-weight: 900;
  gap: 8px;
  min-height: 42px;
  padding: 0 16px;

  transition:
    transform .18s ease,
    box-shadow .18s ease,
    color .18s ease,
    background .18s ease;
}

.category-rail button:hover,
.category-rail button.active {
  background:
    linear-gradient(
      135deg,
      #071f42,
      #0d7880
    );

  box-shadow:
    0 12px 26px rgba(11, 94, 215, .22);

  color: #fff;
  transform: translateY(-2px);
}

.category-rail button.active .v-icon,
.category-rail button:hover .v-icon {
  color: #fff !important;
}

.catalog-product-card {
  background: #fff !important;
  border-color: #dce8f5 !important;

  box-shadow:
    0 14px 34px rgba(6, 29, 67, .08);

  overflow: hidden;

  transition:
    transform .2s ease,
    box-shadow .2s ease;
}

.catalog-product-card:hover {
  box-shadow:
    0 28px 58px rgba(6, 29, 67, .16);

  transform: translateY(-6px);
}

.catalog-product-visual {
  background:
    radial-gradient(
      circle at 30% 20%,
      rgba(0, 200, 255, .16),
      transparent 28%
    ),
    radial-gradient(
      circle at 85% 80%,
      rgba(13, 120, 128, .10),
      transparent 30%
    ),
    linear-gradient(
      145deg,
      #f8fbff,
      #e8f1fb
    );

  min-height: 245px;
  padding: 12px;
  position: relative;
}

.catalog-product-visual .v-image {
  border-radius: 20px;
}

.catalog-product-empty {
  align-items: center;

  background:
    radial-gradient(
      circle at 30% 20%,
      rgba(11, 94, 215, .14),
      transparent 28%
    ),
    radial-gradient(
      circle at 80% 80%,
      rgba(13, 120, 128, .10),
      transparent 28%
    ),
    linear-gradient(
      145deg,
      #f8fbff,
      #e8f1fb
    );

  border: 1px dashed #a9c7e7;
  border-radius: 20px;
  display: flex;
  height: 221px;
  justify-content: center;
}

.product-category-pill {
  backdrop-filter: blur(14px);

  background:
    rgba(255, 255, 255, .94);

  border:
    1px solid rgba(255, 255, 255, .74);

  border-radius: 999px;
  bottom: 24px;

  box-shadow:
    0 12px 28px rgba(6, 29, 67, .16);

  color: #061d43;
  font-size: 12px;
  font-weight: 950;
  left: 24px;
  max-width: calc(100% - 48px);
  overflow: hidden;
  padding: 8px 13px;
  position: absolute;
  text-overflow: ellipsis;
  text-transform: uppercase;
  white-space: nowrap;
}

.catalog-product-body {
  display: flex;
  flex-direction: column;
  min-height: 278px;
  padding: 22px;
}

.eyebrowes {
  background:
    linear-gradient(
      90deg,
      #72edf0,
      #0f4f7c,
      #0d7880
    );

  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;

  display: inline-block;
  font-size: 20px;
  font-weight: 900;
  letter-spacing: 1px;
}

.product-meta-row,
.product-price-row,
.product-actions {
  align-items: center;
  display: flex;
  gap: 12px;
  justify-content: space-between;
}

.product-meta-row {
  color: #7487a3;
  font-size: 12px;
  font-weight: 850;
  text-transform: uppercase;
}

.product-meta-row strong {
  color: #0d7880;
}

.catalog-product-body h2 {
  color: #061d43;
  font-size: 23px;
  font-weight: 950;
  letter-spacing: -.4px;
  line-height: 1.12;
  margin: 12px 0 10px;
}

.catalog-product-body p {
  color: #667b99;
  font-size: 14px;
  line-height: 1.65;
  margin: 0 0 18px;
}

.product-price-row {
  background:
    linear-gradient(
      135deg,
      #f7fbff,
      #f2f4ff
    );

  border: 1px solid #dce8f5;
  border-radius: 18px;
  margin-top: auto;
  padding: 14px;
}

.product-price-row span,
.product-price-row strong {
  display: block;
}

.product-price-row span {
  color: #7487a3;
  font-size: 11px;
  font-weight: 950;
  text-transform: uppercase;
}

.product-price-row strong {
  color: #0b5ed7;
  font-size: 18px;
  font-weight: 950;
}

.product-actions {
  align-items: stretch;
  display: grid;
  gap: 10px;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  justify-content: initial;
  margin-top: 16px;
}

.product-actions .v-btn {
  font-size: 12px;
  font-weight: 950;
  letter-spacing: .4px;
}

.product-actions .cart-add-btn {
  grid-column: 1 / -1;
}

.cart-add-btn {
  background: linear-gradient(135deg, #071f42, #0f4f7c 58%, #0d7880) !important;
  border: 0 !important;
  box-shadow: 0 16px 34px rgba(7, 31, 66, .26) !important;
  color: #fff !important;
  font-weight: 950;
  letter-spacing: .3px;
}

.cart-add-btn:hover {
  background: linear-gradient(135deg, #08244c, #12608f 58%, #11939a) !important;
  box-shadow: 0 20px 42px rgba(7, 31, 66, .34) !important;
}

.cart-add-btn.v-btn--disabled {
  background: #fff1f1 !important;
  border-color: #ffd0d0 !important;
  color: #d32f2f !important;
  opacity: 1 !important;
}

.cart-drawer {
  background:
    linear-gradient(
      180deg,
      #f7fbff 0%,
      #ffffff 38%,
      #f4f8ff 100%
    ) !important;
  border-radius: 0;
  height: 100vh !important;
  max-height: 100vh !important;
  overflow: hidden;
  top: 0 !important;
  z-index: 1000 !important;
}

.cart-header {
  align-items: center;
  background:
    radial-gradient(circle at 82% 18%, rgba(72, 211, 214, .20), transparent 34%),
    linear-gradient(135deg, #071f42, #0f4f7c 58%, #0d7880);
  color: #fff;
  display: flex;
  gap: 14px;
  justify-content: space-between;
  padding: 24px 22px;
  position: relative;
}

.cart-header::after {
  background:
    radial-gradient(circle, rgba(255, 255, 255, .22) 0 1px, transparent 1px);
  background-size: 18px 18px;
  content: '';
  inset: 0;
  opacity: .24;
  pointer-events: none;
  position: absolute;
}

.cart-header > * {
  position: relative;
  z-index: 1;
}

.cart-header-icon {
  align-items: center;
  background: rgba(255, 255, 255, .16);
  border: 1px solid rgba(255, 255, 255, .24);
  border-radius: 18px;
  display: flex;
  flex: 0 0 54px;
  height: 54px;
  justify-content: center;
}

.cart-header-copy {
  flex: 1;
  min-width: 0;
}

.cart-header span {
  color: #8ff4f0;
  display: block;
  font-size: 11px;
  font-weight: 950;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.cart-header h3 {
  color: #fff;
  font-size: 24px;
  font-weight: 950;
  margin: 4px 0 0;
}

.cart-header p {
  color: rgba(255, 255, 255, .78);
  font-size: 13px;
  font-weight: 750;
  margin: 4px 0 0;
}

.cart-content {
  display: flex;
  flex-direction: column;
  gap: 13px;
  max-height: calc(100vh - 128px);
  overflow-y: auto;
  padding: 18px 18px 24px;
}

.cart-empty {
  align-items: center;
  color: #667b99;
  display: flex;
  flex-direction: column;
  min-height: 430px;
  justify-content: center;
  padding: 28px;
  text-align: center;
}

.cart-empty h4 {
  color: #061d43;
  font-size: 22px;
  font-weight: 950;
  margin: 14px 0 6px;
}

.cart-empty p {
  line-height: 1.6;
  margin: 0;
}

.cart-item {
  background:
    linear-gradient(
      145deg,
      rgba(255, 255, 255, .98),
      rgba(246, 250, 255, .94)
    );
  border: 1px solid rgba(180, 205, 235, .72);
  border-radius: 22px;
  box-shadow: 0 14px 32px rgba(6, 29, 67, .08);
  display: flex;
  gap: 13px;
  padding: 13px;
}

.cart-item-visual {
  align-items: center;
  background:
    radial-gradient(circle at 30% 20%, rgba(0, 200, 255, .18), transparent 34%),
    linear-gradient(145deg, #f8fbff, #e8f1fb);
  border: 1px solid #dce8f5;
  border-radius: 18px;
  display: flex;
  flex: 0 0 78px;
  height: 78px;
  justify-content: center;
  overflow: hidden;
}

.cart-item-visual .v-image {
  border-radius: 18px;
}

.cart-item-body {
  min-width: 0;
  width: 100%;
}

.cart-item-top {
  align-items: center;
  display: flex;
  justify-content: space-between;
}

.cart-item-body span,
.cart-item-body small {
  color: #7487a3;
  display: block;
  font-size: 11px;
  font-weight: 850;
  text-transform: uppercase;
}

.cart-item-body strong {
  color: #061d43;
  display: block;
  font-size: 16px;
  font-weight: 950;
  line-height: 1.2;
  margin: 2px 0 4px;
}

.cart-qty {
  align-items: center;
  background: #f0f6ff;
  border: 1px solid #dce8f5;
  border-radius: 999px;
  display: inline-flex;
  gap: 8px;
  margin-top: 10px;
  padding: 4px;
}

.cart-qty b {
  color: #061d43;
  min-width: 28px;
  text-align: center;
}

.cart-summary {
  align-items: center;
  background:
    radial-gradient(circle at 80% 20%, rgba(0, 200, 255, .13), transparent 32%),
    linear-gradient(135deg, #ffffff, #eef5ff);
  border: 1px solid #cfe0f3;
  border-radius: 22px;
  display: flex;
  justify-content: space-between;
  margin-top: 6px;
  padding: 18px;
}

.cart-summary span {
  color: #7487a3;
  font-size: 12px;
  font-weight: 950;
  text-transform: uppercase;
}

.cart-summary strong {
  color: #0b5ed7;
  font-size: 20px;
  font-weight: 950;
}

.cart-whatsapp-btn {
  background:
    linear-gradient(
      135deg,
      #10943a,
      #25d366
    ) !important;

  box-shadow: 0 16px 34px rgba(37, 211, 102, .28) !important;
  color: #fff !important;
  font-weight: 950;
}

.catalog-pagination {
  align-items: center;
  background: rgba(255, 255, 255, .92);
  border: 1px solid #dce8f5;
  border-radius: 22px;
  box-shadow: 0 14px 34px rgba(6, 29, 67, .07);
  display: flex;
  gap: 18px;
  justify-content: space-between;
  margin-top: 30px;
  padding: 14px 18px;
}

.catalog-pagination span {
  color: #667b99;
  font-size: 13px;
  font-weight: 850;
}

@media (max-width: 960px) {
  .catalog-title {
    font-size: 42px;
  }

  .catalog-filter-card {
    margin-top: -24px;
  }
}

@media (max-width: 600px) {
  .cart-drawer {
    width: min(92vw, 440px) !important;
  }

  .catalog-hero {
    padding: 54px 0 78px;
  }

  .catalog-title {
    font-size: 34px;
  }

  .catalog-hero-actions .v-btn,
  .product-actions .v-btn {
    width: 100%;
  }

  .product-actions {
    grid-template-columns: 1fr;
  }

  .catalog-filter-card {
    margin-top: -46px;
  }

  .filter-title {
    align-items: flex-start;
    flex-direction: column;
  }

  .catalog-product-visual {
    min-height: 220px;
  }

  .catalog-product-empty {
    height: 196px;
  }

  .catalog-product-body {
    min-height: auto;
  }

  .catalog-pagination {
    align-items: stretch;
    flex-direction: column;
    text-align: center;
  }
}
</style>
