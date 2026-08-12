<template>
  <v-app class="catalog-page">
    <store-header :logo-src="logoActual" active-section="catalogo" />

    <main>

      <!-- HERO -->
      <section class="catalog-hero">
        <v-container>
          <v-row align="center">

            <v-col cols="12" md="7">
              <div class="text-overline font-weight-bold cyan--text text--lighten-2">
                CATÁLOGO COMPLETO
              </div>

              <h1 class="catalog-title">
                Todos los productos en un solo panel.
              </h1>
            </v-col>

            <v-col cols="12" md="5">
              <v-card
                flat
                class="pa-7 text-center rounded-lg"
              >
                <v-icon
                  color="primary"
                  size="44"
                >
                  mdi-store-search-outline
                </v-icon>

                <div class="text-h2 font-weight-black primary--text mt-2">
                  {{ productosCatalogo.length }}
                </div>

                <div class="text-caption font-weight-bold text-uppercase grey--text">
                  productos encontrados
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
          <v-card
            outlined
            class="pa-4 rounded-lg"
          >
            <v-row dense align="center">

              <v-col cols="12" md="5">
                <v-text-field
                  v-model="catalogoBusqueda"
                  label="Nombre o descripción"
                  outlined
                  dense
                  rounded
                  clearable
                  hide-details
                  prepend-inner-icon="mdi-magnify"
                />
              </v-col>

              <v-col cols="12" md="3">
                <v-select
                  v-model="catalogoCategoria"
                  :items="opcionesCategorias"
                  item-text="name"
                  item-value="id"
                  label="Categoría"
                  outlined
                  dense
                  rounded
                  clearable
                  hide-details
                  prepend-inner-icon="mdi-filter-outline"
                />
              </v-col>

              <v-col cols="12" md="3">
                <v-select
                  v-model="catalogoMarca"
                  :items="opcionesMarcas"
                  item-text="nombre"
                  item-value="id"
                  label="Marca"
                  outlined
                  dense
                  rounded
                  clearable
                  hide-details
                  prepend-inner-icon="mdi-tag-outline"
                />
              </v-col>

              <v-col
                cols="12"
                md="1"
                class="text-center"
              >
                <v-tooltip bottom v-if="hayFiltrosCatalogo">
                  <template #activator="{ on, attrs }">
                    <v-btn
                      icon
                      color="error"
                      v-bind="attrs"
                      v-on="on"
                      @click="limpiarPanelCatalogo"
                    >
                      <v-icon>mdi-broom</v-icon>
                    </v-btn>
                  </template>

                  <span>Limpiar filtros</span>
                </v-tooltip>
              </v-col>

            </v-row>
          </v-card>

          <!-- LOADER -->
          <div
            v-if="cargandoProductos"
            class="text-center py-16"
          >
            <v-progress-circular
              indeterminate
              color="primary"
              size="54"
              width="5"
            />

            <div class="mt-4 grey--text text--darken-1">
              Cargando productos...
            </div>
          </div>

          <!-- PRODUCTOS -->
          <v-row
            v-else
            class="mt-5"
          >
            <v-col
              v-for="product in productosCatalogo"
              :key="`catalogo-${product.id}`"
              cols="12"
              md="6"
            >
              <v-card
                outlined
                hover
                height="100%"
                class="rounded-lg overflow-hidden"
              >
                <v-row
                  no-gutters
                  class="fill-height"
                >
                  <!-- IMAGEN -->
                  <v-col
                    cols="12"
                    sm="4"
                    class="grey lighten-5"
                  >
                    <v-responsive
                      :aspect-ratio="1"
                      class="fill-height"
                    >
                      <v-img
                        v-if="product.image"
                        :src="product.image"
                        height="100%"
                        contain
                        class="pa-3"
                      >
                        <template #placeholder>
                          <v-row
                            class="fill-height ma-0"
                            align="center"
                            justify="center"
                          >
                            <v-progress-circular
                              indeterminate
                              color="primary"
                            />
                          </v-row>
                        </template>
                      </v-img>

                      <div
                        v-else
                        class="fill-height d-flex align-center justify-center"
                      >
                        <v-icon
                          color="primary"
                          size="64"
                        >
                          {{ product.icon }}
                        </v-icon>
                      </div>
                    </v-responsive>
                  </v-col>

                  <!-- INFO -->
                  <v-col cols="12" sm="8">
                    <div class="pa-5 d-flex flex-column fill-height">

                      <div class="mb-2">
                        <v-chip
                          small
                          color="primary"
                          outlined
                          class="mr-1 mb-1"
                        >
                          {{ product.type }}
                        </v-chip>

                        <v-chip
                          v-if="product.brand"
                          small
                          color="secondary"
                          outlined
                          class="mb-1"
                        >
                          {{ product.brand }}
                        </v-chip>
                      </div>

                      <div
                        class="text-h6 font-weight-black mb-2"
                        style="color: #17365d;"
                      >
                        {{ product.title }}
                      </div>

                      <div
                        class="text-body-2 grey--text text--darken-1 mb-4"
                      >
                        {{ limitarTexto(product.text, 120) }}
                      </div>

                      <v-spacer />

                      <div class="d-flex align-center justify-space-between mb-4">
                        <span class="text-caption grey--text text--darken-1">
                          {{ product.unit || 'Unidad según referencia' }}
                        </span>

                        <span
                          v-if="product.price"
                          class="text-subtitle-1 font-weight-black primary--text"
                        >
                          {{ product.price }}
                        </span>

                        <v-chip
                          v-else
                          small
                          color="orange"
                          text-color="white"
                        >
                          Precio a cotizar
                        </v-chip>
                      </div>

                      <v-divider class="mb-4" />

                      <!-- ACCIONES -->
                      <div class="d-flex flex-wrap">
                        <v-btn
                          outlined
                          rounded
                          color="primary"
                          class="mr-2 mb-2"
                          @click="verDetalle(product)"
                        >
                          <v-icon left small>
                            mdi-eye-outline
                          </v-icon>

                          Ver detalle
                        </v-btn>

                        <v-btn
                          rounded
                          color="success"
                          dark
                          class="mb-2"
                          @click="cotizarProducto(product)"
                        >
                          <v-icon left>
                            mdi-whatsapp
                          </v-icon>

                          Cotizar
                        </v-btn>
                      </div>

                    </div>
                  </v-col>
                </v-row>
              </v-card>
            </v-col>

            <!-- SIN RESULTADOS -->
            <v-col
              v-if="!productosCatalogo.length"
              cols="12"
            >
              <v-sheet
                outlined
                rounded="lg"
                class="pa-12 text-center"
              >
                <v-icon
                  size="72"
                  color="grey lighten-1"
                >
                  mdi-database-search-outline
                </v-icon>

                <div
                  class="text-h5 font-weight-black mt-4"
                  style="color: #17365d;"
                >
                  No encontramos productos
                </div>

                <div class="grey--text mt-2">
                  Cambia el nombre, la categoría o la marca para ver más resultados.
                </div>

                <v-btn
                  v-if="hayFiltrosCatalogo"
                  color="primary"
                  outlined
                  rounded
                  class="mt-5"
                  @click="limpiarPanelCatalogo"
                >
                  Limpiar filtros
                </v-btn>
              </v-sheet>
            </v-col>

          </v-row>

        </v-container>
      </section>

    </main>

    <!-- MODAL DETALLE -->
    <v-dialog
      v-model="modalDetalle"
      max-width="900"
      scrollable
    >
      <v-card
        v-if="productoSeleccionado"
        class="rounded-lg"
      >
        <v-card-title class="d-flex align-center">
          <v-icon
            color="primary"
            class="mr-2"
          >
            mdi-package-variant-closed
          </v-icon>

          <span class="font-weight-black">
            Detalle del producto
          </span>

          <v-spacer />

          <v-btn
            icon
            @click="modalDetalle = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-card-title>

        <v-divider />

        <v-card-text class="pa-0">
          <v-row no-gutters>

            <!-- IMAGEN GRANDE -->
            <v-col
              cols="12"
              md="5"
              class="grey lighten-5"
            >
              <div
                class="pa-6 d-flex align-center justify-center"
                style="min-height: 390px;"
              >
                <v-img
                  v-if="productoSeleccionado.image"
                  :src="productoSeleccionado.image"
                  contain
                  max-height="350"
                />

                <v-icon
                  v-else
                  color="primary"
                  size="120"
                >
                  {{ productoSeleccionado.icon }}
                </v-icon>
              </div>
            </v-col>

            <!-- DETALLE -->
            <v-col cols="12" md="7">
              <div class="pa-7">

                <div class="mb-3">
                  <v-chip
                    color="primary"
                    outlined
                    small
                    class="mr-2"
                  >
                    {{ productoSeleccionado.type }}
                  </v-chip>

                  <v-chip
                    v-if="productoSeleccionado.brand"
                    color="secondary"
                    outlined
                    small
                  >
                    {{ productoSeleccionado.brand }}
                  </v-chip>
                </div>

                <h2
                  class="text-h4 font-weight-black mb-4"
                  style="color: #17365d;"
                >
                  {{ productoSeleccionado.title }}
                </h2>

                <div
                  class="text-body-1 grey--text text--darken-2 mb-6"
                  style="line-height: 1.7;"
                >
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

                      <v-list-item-title
                        class="font-weight-black primary--text"
                      >
                        {{ productoSeleccionado.price || 'Solicitar cotización' }}
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

          <v-btn
            text
            rounded
            @click="modalDetalle = false"
          >
            Cerrar
          </v-btn>

          <v-btn
            rounded
            color="success"
            dark
            @click="cotizarProducto(productoSeleccionado)"
          >
            <v-icon left>
              mdi-whatsapp
            </v-icon>

            Solicitar cotización
          </v-btn>
        </v-card-actions>

      </v-card>
    </v-dialog>

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

      modalDetalle: false,
      productoSeleccionado: null,

      categoriasParametrizadas: [],
      productosParametrizados: [],
      imagenesParametrizadas: [],
      marcasParametrizadas: [],
    }
  },

  computed: {

    logoActual() {
      return (
        this.obtenerImagenPorTipo('logo') ||
        '/images/embacolsa-optimized.webp'
      )
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
        }))
    },

    productosCatalogo() {
      const busqueda = String(this.catalogoBusqueda || '')
        .toLowerCase()
        .trim()

      return this.productosNormalizados
        .filter((producto) => {
          if (
            this.catalogoCategoria === null ||
            this.catalogoCategoria === ''
          ) {
            return true
          }

          return (
            String(producto.categoryId) ===
            String(this.catalogoCategoria)
          )
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
      return Boolean(
        this.catalogoBusqueda ||
        this.catalogoCategoria ||
        this.catalogoMarca
      )
    },
  },

  mounted() {
    this.catalogoBusqueda =
      this.$route.query.buscar || ''

    this.cargarParametrizacion()
  },

  methods: {

    async cargarParametrizacion() {
      this.cargandoProductos = true

      try {
        await Promise.all([
          this.listarCategorias(),
          this.listarProductos(),
          this.listarImagenes(),
          this.listarMarcas(),
        ])
      } finally {
        this.cargandoProductos = false
      }
    },

    async listarCategorias() {
      try {
        const response = await this.$axios.get(
          '/categorias/listar'
        )

        this.categoriasParametrizadas =
          this.extraerLista(response.data)
      } catch (error) {
        console.error(
          'Error cargando categorías:',
          error
        )

        this.categoriasParametrizadas = []
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
        'Hola Embacolsa, estoy interesado en cotizar el siguiente producto:',
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

      /**
       * Puedes configurar:
       *
       * WHATSAPP_NUMBER=573001234567
       *
       * Sin +, espacios ni guiones.
       */
      const numero =
        (
          this.$config &&
          this.$config.WHATSAPP_NUMBER
        ) ||
        process.env.WHATSAPP_NUMBER ||
        ''

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
      const productoQuery =
        encodeURIComponent(producto.title)

      window.location.href =
        `/?producto=${productoQuery}#contacto`
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
        (this.$axios &&this.$axios.defaults &&
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

      if (nombre.includes('cinta')) {
        return 'mdi-tape-measure'
      }

      if (
        nombre.includes('caja') ||
        nombre.includes('cartón') ||
        nombre.includes('carton')
      ) {
        return 'mdi-package-variant-closed'
      }

      if (
        nombre.includes('zuncho') ||
        nombre.includes('suncho')
      ) {
        return 'mdi-link-variant'
      }

      if (
        nombre.includes('seguridad')
      ) {
        return 'mdi-shield-check-outline'
      }

      if (
        nombre.includes('stretch') ||
        nombre.includes('film')
      ) {
        return 'mdi-rollup'
      }

      return 'mdi-package-variant'
    },

    estaActivo(estado) {
      return (
        estado === true ||
        estado === 1 ||
        estado === '1'
      )
    },

    formatearPrecio(precio) {
      if (
        precio === null ||
        precio === undefined ||
        precio === ''
      ) {
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
  background: #f4f8fb;
  color: #102b5c;
}

.catalog-hero {
  background:
    radial-gradient(
      circle at 85% 15%,
      rgba(114, 237, 240, .22),
      transparent 28%
    ),
    linear-gradient(
      110deg,
      #061d43 0%,
      #073b60 48%,
      #0d7880 100%
    );
  color: white;
  padding: 74px 0;
}

.catalog-title {
  color: white;
  font-size: 54px;
  font-weight: 900;
  line-height: 1.05;
  margin-top: 12px;
  max-width: 700px;
}

@media (max-width: 960px) {
  .catalog-title {
    font-size: 42px;
  }
}

@media (max-width: 600px) {
  .catalog-hero {
    padding: 48px 0;
  }

  .catalog-title {
    font-size: 34px;
  }
}
</style>
