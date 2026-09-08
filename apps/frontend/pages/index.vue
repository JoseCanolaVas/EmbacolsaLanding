<template>
  <v-app class="storefront" :style="variablesMarca">
    <store-header
      :logo-src="logoActual"
      active-section="inicio"
      :cart-count="totalItemsCarrito"
      @open-cart="abrirCarrito"
    />

    <main>
      <section id="inicio" class="hero-section">
        <v-container class="hero-inner">
          <div class="hero-copy">
            <span class="eyebrow">{{ configuracionSitio.eyebrow }}</span>
            <h1>{{ configuracionSitio.titulo ?? '¡ SITIO WEB DESACTIVADO ! ' }}</h1>
            <p>{{ configuracionSitio.descripcion ?? 'contacta a softnova para poder activar tu servicio '}}</p>

            <div class="hero-actions">
              <v-btn x-large rounded depressed class="brand-primary-btn" :to="{ path: '/', hash: '#productos' }">
                Ver productos
                <v-icon right>mdi-arrow-right</v-icon>
              </v-btn>
              <v-btn x-large rounded outlined color="white" :to="{ path: '/catalogo' }">
                Ver catálogo completo
              </v-btn>
              <v-btn x-large rounded outlined color="white" :href="whatsappUrl" target="_blank">
                Cotizar por WhatsApp
              </v-btn>
            </div>

            <div class="hero-trust-strip">
              <div v-for="metric in metricasHero" :key="metric.label">
                <strong>{{ metric.value }}</strong>
                <span>{{ metric.label }}</span>
              </div>
            </div>
          </div>

          <div v-if="bannerActual" class="hero-products-figure">
            <img :src="bannerActual" alt="Banner NovaCell">
            <div class="hero-floating-card">
              <v-icon color="#1e88e5">mdi-cellphone-check</v-icon>
              <div>
                <strong>Catálogo tecnológico</strong>
                <span>Listo para cotizar</span>
              </div>
            </div>
          </div>
        </v-container>
      </section>

      <section id="productos" class="products-section">
        <v-container>
          <div class="section-heading products-heading">
            <div>
              <span class="eyebrow blue">{{ configuracionSitio.subtitulo_productos }}</span>
              <h2>{{ tituloProductos }}</h2>
              <p class="section-lead">Productos reales del panel, organizados para vender tecnología sin enredos.</p>
            </div>
            <v-chip color="primary" outlined>
              {{ productosIndex.length }} visibles
            </v-chip>
          </div>

          <v-row>
            <v-col v-for="product in productosIndexPaginados" :key="product.title" cols="12" sm="6" lg="3">
              <v-card class="product-card" outlined height="100%">
                <div class="product-visual" :class="product.class">
                  <v-img v-if="product.image" :src="product.image" height="175" cover />
                  <v-icon v-else>{{ product.icon }}</v-icon>
                </div>
                <v-card-text>
                  <div class="product-type">{{ product.type }}</div>
                  <h3>{{ product.title }}</h3>
                  <p>{{ product.text }}</p>
                  <v-chip x-small :color="productoAgotado(product) ? 'error' : 'success'" text-color="white">
                    <v-icon left x-small>
                      {{ productoAgotado(product) ? 'mdi-cart-off' : 'mdi-package-check' }}
                    </v-icon>
                    {{ productoAgotado(product) ? 'Agotado' : `${product.stock} disponibles` }}
                  </v-chip>
                </v-card-text>
                <v-card-actions class="product-card-actions">
                  <v-btn text color="primary" @click="seleccionarProducto(product)">
                    Ver en catálogo
                    <v-icon right small>mdi-arrow-right</v-icon>
                  </v-btn>

                  <v-btn
                    rounded
                    depressed
                    class="cart-add-mini"
                    :disabled="productoAgotado(product)"
                    @click.stop="agregarAlCarrito(product)"
                  >
                    <v-icon left small>{{ productoAgotado(product) ? 'mdi-cart-off' : 'mdi-cart-plus' }}</v-icon>
                    {{ productoAgotado(product) ? 'Agotado' : 'Añadir' }}
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

          <div v-if="totalPaginasIndex > 1" class="home-pagination">
            <span>
              Mostrando {{ productosIndexPaginados.length }} de {{ productosIndex.length }} destacados
            </span>

            <v-pagination
              v-model="paginaIndex"
              :length="totalPaginasIndex"
              :total-visible="5"
              circle
              color="primary"
            />
          </div>
        </v-container>
      </section>

      <section id="beneficios" class="operation-section">
        <v-container>
          <v-row align="center">
            <v-col cols="12" md="5">
              <span>OPERACIÓN SIN ENREDOS</span>
              <h2>{{ configuracionSitio.titulo_beneficios }}</h2>
              <p>{{ configuracionSitio.descripcion_beneficios }}</p>
            </v-col>
            <v-col cols="12" md="7">
              <div class="benefit-grid">
                <div v-for="benefit in benefitsActuales" :key="benefit.title" class="benefit-item">
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
            <h2>{{ configuracionSitio.titulo_contacto }}</h2>
            <p>{{ configuracionSitio.descripcion_contacto }}</p>
          </div>
          <div class="contact-actions">
            <v-btn x-large rounded color="white" class="primary--text" :href="whatsappUrl"
              target="_blank">
              <v-icon left>mdi-whatsapp</v-icon>
              WhatsApp
            </v-btn>
            <!-- <v-btn x-large rounded outlined color="white" :href="correoUrl">
              Correo
            </v-btn> -->
          </div>
        </v-container>
      </section>
    </main>

    <footer class="store-footer">
      <v-container class="footer-inner">
        <span>© {{ new Date().getFullYear() }} {{ configuracionSitio.nombre_sitio }}</span>
        <span>Desarrollado por SOFTNOVA SOLUTIONS</span>
      </v-container>
    </footer>

  </v-app>
</template>

<script>
export default {
  name: 'IndexPage',

  data() {
    return {
      categoriasParametrizadas: [],
      productosParametrizados: [],
      imagenesParametrizadas: [],
      carrito: [],
      paginaIndex: 1,
      productosPorPaginaIndex: 4,
      configuracionSitio: {
        nombre_sitio: '',
        eyebrow: '',
        titulo: '',
        descripcion: '',
        titulo_productos: '',
        subtitulo_productos: '',
        titulo_beneficios: '',
        descripcion_beneficios: '',
        titulo_contacto: '',
        descripcion_contacto: '',
        telefono_whatsapp: '',
        correo_contacto: '',
        beneficios: [],
        colores: {
          primario: '#0f2c61',
          secundario: '#0d7880',
          acento: '#1e88e5',
          fondo: '#f4f8fb',
        },
      },
      metricasHero: [
        { value: '24/7', label: 'Catálogo disponible' },
        { value: '+50', label: 'Accesorios útiles' },
        { value: 'Tech', label: 'Asesoría compatible' },
      ],
    }
  },

  computed: {
    logoActual() {
      return this.obtenerImagenPorTipo('logo') || '/images/novacell.png'
    },

    bannerActual() {
      return this.obtenerImagenPorTipo('banner') ||
        this.obtenerImagenPorTipo('principal') ||
        '/images/novacell-hero-tech.png'
    },

    categoriasIndex() {
      const categoriasActivas = this.categoriasParametrizadas
        .filter(categoria => this.estaActivo(categoria.estado))
        .map(categoria => ({
          id: categoria.id,
          name: categoria.nombre,
          icon: 'mdi-format-list-bulleted-type',
        }))

      return categoriasActivas
    },

    opcionesCategorias() {
      return this.categoriasIndex
    },

    productosIndex() {
      const categoriasVistas = new Set()
      const productosActivos = this.productosParametrizados
        .filter(producto => this.estaActivo(producto.estado))
        .filter((producto) => {
          const categoriaId = producto.categoria_id || producto.categoria?.id || `sin-${producto.id}`

          if (categoriasVistas.has(categoriaId)) {
            return false
          }

          categoriasVistas.add(categoriaId)
          return true
        })
        .map((producto, index) => ({
          id: producto.id,
          categoryId: producto.categoria_id || producto.categoria?.id,
          type: producto.categoria?.nombre || 'Producto',
          title: producto.nombre,
          text: producto.descripcion || 'Sin descripción registrada.',
          icon: this.obtenerIconoProducto(producto),
          class: this.obtenerClaseVisual(index),
          image: this.resolverImagen(producto.ruta_imagen),
          brandId: producto.marca_id || producto.marca?.id,
          brand: producto.marca?.nombre || producto.marca_nombre || null,
          unit: producto.unidad_medida || null,
          price: this.formatearPrecio(producto.precio),
          precioOriginal: producto.precio,
          stock: Number(producto.stock || 0),
          bodega: producto.bodega?.nombre || producto.bodega_nombre || null,
        }))

      return productosActivos
    },

    tituloProductos() {
      return this.configuracionSitio.titulo_productos
    },

    benefitsActuales() {
      return this.configuracionSitio.beneficios || []
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

    whatsappUrl() {
      const telefono = this.configuracionSitio.telefono_whatsapp || '573188158107'
      const mensaje = `Hola, quiero cotizar productos de ${this.configuracionSitio.nombre_sitio || 'NovaCell'}`

      return `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`
    },

    correoUrl() {
      return `mailto:${this.configuracionSitio.correo_contacto || 'comercial@embacolsa.com.co'}`
    },

    totalItemsCarrito() {
      return this.carrito.reduce((total, item) => total + Number(item.cantidad || 0), 0)
    },

    totalPaginasIndex() {
      return Math.ceil(this.productosIndex.length / this.productosPorPaginaIndex)
    },

    productosIndexPaginados() {
      const inicio = (this.paginaIndex - 1) * this.productosPorPaginaIndex
      const fin = inicio + this.productosPorPaginaIndex

      return this.productosIndex.slice(inicio, fin)
    },
  },

  mounted() {
    this.cargarCarrito()
    this.cargarParametrizacion()
  },

  watch: {
    productosIndex() {
      if (this.paginaIndex > this.totalPaginasIndex) {
        this.paginaIndex = this.totalPaginasIndex || 1
      }
    },
  },

  methods: {
    productoAgotado(producto) {
      return !producto || Number(producto.stock || 0) <= 0
    },

    async cargarParametrizacion() {
      await Promise.all([
        this.listarCategorias(),
        this.listarProductos(),
        this.listarImagenes(),
        this.obtenerConfiguracionSitio(),
      ])
    },

    configuracionPorDefecto() {
      return {
        nombre_sitio: '',
        eyebrow: '',
        titulo: '',
        descripcion: '',
        titulo_productos: '',
        subtitulo_productos: '',
        titulo_beneficios: '',
        descripcion_beneficios: '',
        titulo_contacto: '',
        descripcion_contacto: '',
        telefono_whatsapp: '',
        correo_contacto: '',
        beneficios: [],
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
          beneficios: data.beneficios || [],
        }
      } catch (error) {
        this.configuracionSitio = this.configuracionPorDefecto()
      }
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

    obtenerIconoProducto(producto) {
      const categoria = producto.categoria?.nombre || producto.categoria_nombre || ''
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

    obtenerClaseVisual(index) {
      return ['visual-cyan', 'visual-blue', 'visual-gold', 'visual-green'][index % 4]
    },

    seleccionarProducto(product) {
      this.$router.push({
        path: '/catalogo',
        query: {
          buscar: product.title,
        },
      })
    },

    abrirCarrito() {
      this.$router.push({
        path: '/catalogo',
        query: {
          carrito: '1',
        },
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

          return
        }

        item.cantidad += 1
      } else {
        this.carrito.push(this.normalizarProductoCarrito(producto))
      }

      this.guardarCarrito()

      if (this.$toast) {
        this.$toast.success('Producto agregado al carrito.')
      }
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

  },
}
</script>
<style scoped>
.storefront {
  background: var(--brand-bg);
  color: #0b2a55;
  font-family: Arial, sans-serif;
}

.hero-section {
  background:
    linear-gradient(rgba(255, 255, 255, .045) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, .045) 1px, transparent 1px),
    radial-gradient(circle at 78% 14%, rgba(114, 237, 240, .25), transparent 28%),
    radial-gradient(circle at 18% 68%, rgba(30, 136, 229, .23), transparent 32%),
    linear-gradient(110deg, #061d43 0%, #0b5ed7 48%, #8b2cf5 100%);
  background-size: 42px 42px, 42px 42px, auto, auto, auto;
  color: #fff;
  overflow: hidden;
  position: relative;
}

.hero-section::before {
  background:
    radial-gradient(circle, rgba(255, 255, 255, .18) 0 1px, transparent 1px),
    linear-gradient(100deg, rgba(255, 255, 255, .12), transparent 42%);
  background-size: 24px 24px, auto;
  content: '';
  inset: 0;
  opacity: .32;
  pointer-events: none;
  position: absolute;
}

.hero-inner {
  align-items: center;
  display: grid;
  gap: 44px;
  grid-template-columns: minmax(0, .92fr) minmax(360px, 1.08fr);
  min-height: 610px;
  padding-bottom: 72px;
  padding-top: 96px;
  position: relative;
}

.hero-copy {
  max-width: 610px;
  position: relative;
  z-index: 2;
}

.eyebrow {
  color: #72edf0;
  display: inline-block;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.eyebrow.blue {
  color: #00c8ff;
}

.hero-copy h1 {
  color: #fff;
  font-size: clamp(54px, 8vw, 96px);
  font-weight: 950;
  letter-spacing: -3px;
  line-height: .95;
  margin: 16px 0;
  text-shadow: 0 20px 50px rgba(0, 0, 0, .24);
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

.brand-primary-btn {
  background:
    linear-gradient(
      135deg,
      #0b5ed7,
      #00c8ff
    ) !important;

  box-shadow:
    0 18px 38px rgba(11, 94, 215, .32) !important;

  color: #fff !important;
  font-weight: 950;
}

.hero-trust-strip {
  backdrop-filter: blur(14px);
  background: rgba(255, 255, 255, .1);
  border: 1px solid rgba(255, 255, 255, .16);
  border-radius: 22px;
  display: flex;
  flex-wrap: wrap;
  margin-top: 34px;
  overflow: hidden;
  width: fit-content;
}

.hero-trust-strip div {
  border-right: 1px solid rgba(255, 255, 255, .15);
  min-width: 138px;
  padding: 14px 18px;
}

.hero-trust-strip div:last-child {
  border-right: 0;
}

.hero-trust-strip strong,
.hero-trust-strip span {
  display: block;
}

.hero-trust-strip strong {
  color: #fff;
  font-size: 22px;
  font-weight: 950;
}

.hero-trust-strip span {
  color: rgba(255, 255, 255, .76);
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .5px;
  text-transform: uppercase;
}

.hero-products-figure {
  justify-self: end;
  position: relative;
  width: 100%;
  z-index: 1;
}

.hero-products-figure img {
  border: 1px solid rgba(255, 255, 255, .18);
  border-radius: 30px;
  box-shadow: 0 30px 70px rgba(0, 0, 0, .28);
  display: block;
  height: min(48vw, 470px);
  object-fit: cover;
  width: 100%;
}

.hero-floating-card {
  align-items: center;
  backdrop-filter: blur(16px);

  background:
    linear-gradient(
      145deg,
      rgba(255, 255, 255, .97),
      rgba(242, 247, 255, .94)
    );

  border: 1px solid rgba(255, 255, 255, .68);
  border-radius: 18px;
  bottom: 18px;
  box-shadow: 0 24px 52px rgba(6, 29, 67, .22);
  color: #061d43;
  display: flex;
  gap: 12px;
  padding: 14px 16px;
  position: absolute;
  right: 130px;
}

.hero-floating-card strong,
.hero-floating-card span {
  display: block;
}

.hero-floating-card strong {
  color: #061d43;
  font-size: 14px;
  font-weight: 950;
}

.hero-floating-card span {
  color: #7487a3;
  font-size: 12px;
  font-weight: 800;
}

.products-section,
.full-catalog-section,
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
  color: #061d43;
  font-size: 42px;
  font-weight: 900;
  letter-spacing: 0;
  line-height: 1.08;
  margin: 8px 0 0;
}

.section-lead {
  color: #6d809e;
  font-size: 16px;
  line-height: 1.7;
  margin: 10px 0 0;
}

/* =========================================
   PRODUCT CARDS
   MISMO ESTILO DEL CATÁLOGO
========================================= */

.product-card {
  background: #fff !important;
  border: 1px solid #dce8f5 !important;
  border-radius: 24px !important;
  box-shadow: 0 14px 34px rgba(6, 29, 67, .08) !important;
  overflow: hidden;
  transition:
    transform .2s ease,
    box-shadow .2s ease;
}

.product-card:hover {
  box-shadow: 0 28px 58px rgba(6, 29, 67, .16) !important;
  transform: translateY(-6px);
}

.product-visual {
  align-items: center;

  background:
    radial-gradient(
      circle at 30% 20%,
      rgba(0, 200, 255, .16),
      transparent 28%
    ),
    radial-gradient(
      circle at 85% 80%,
      rgba(139, 44, 245, .10),
      transparent 30%
    ),
    linear-gradient(
      145deg,
      #f8fbff,
      #e8f1fb
    );

  display: flex;
  height: 245px;
  justify-content: center;
  overflow: hidden;
  padding: 12px;
  position: relative;
}

.product-visual .v-image {
  border-radius: 20px;
  height: 100%;
  width: 100%;
}

.product-visual img {
  border-radius: 20px;
  height: 100%;
  object-fit: cover;
  width: 100%;
}

.product-visual .v-icon {
  color: #0b5ed7;
  font-size: 82px;
}

/*
  Conservamos estas clases para no tocar el template,
  pero todas usan el mismo fondo.
*/
.visual-cyan,
.visual-blue,
.visual-gold,
.visual-green {
  background:
    radial-gradient(
      circle at 30% 20%,
      rgba(0, 200, 255, .16),
      transparent 28%
    ),
    radial-gradient(
      circle at 85% 80%,
      rgba(139, 44, 245, .10),
      transparent 30%
    ),
    linear-gradient(
      145deg,
      #f8fbff,
      #e8f1fb
    );
}

.product-type {
  color: #0b5ed7;
  font-size: 11px;
  font-weight: 900;
  letter-spacing: .5px;
  text-transform: uppercase;
}

.product-card h3 {
  color: #061d43;
  font-size: 23px;
  font-weight: 950;
  letter-spacing: -.4px;
  line-height: 1.12;
  margin: 12px 0 10px;
}

.product-card p {
  color: #667b99;
  font-size: 14px;
  line-height: 1.65;
  margin: 0 0 18px;
}

.product-card .v-card__text {
  color: #667b99;
  padding: 22px;
}

.product-card .v-card__actions {
  padding: 0 22px 22px;
}

.product-card-actions {
  align-items: center;
  gap: 10px;
  justify-content: space-between;
}

.cart-add-mini {
  background: #f4f9ff !important;
  border: 1px solid #cfe2f3 !important;
  box-shadow: none !important;
  color: #0f2c61 !important;
  font-size: 12px;
  font-weight: 950;
  letter-spacing: .2px;
}

.cart-add-mini:hover {
  background: #eaf5ff !important;
  box-shadow: 0 10px 24px rgba(15, 44, 97, .10) !important;
}

.cart-add-mini.v-btn--disabled {
  background: #fff1f1 !important;
  border-color: #ffd0d0 !important;
  color: #d32f2f !important;
  opacity: 1 !important;
}

.home-pagination {
  align-items: center;
  background: #fff;
  border: 1px solid #dce8f5;
  border-radius: 22px;
  box-shadow: 0 14px 34px rgba(6, 29, 67, .07);
  display: flex;
  gap: 18px;
  justify-content: space-between;
  margin-top: 30px;
  padding: 14px 18px;
}

.home-pagination span {
  color: #667b99;
  font-size: 13px;
  font-weight: 850;
}

/* =========================================
   EMPTY PRODUCTS
========================================= */

.empty-products {
  align-items: center;

  background:
    radial-gradient(
      circle at 30% 20%,
      rgba(11, 94, 215, .10),
      transparent 28%
    ),
    radial-gradient(
      circle at 80% 80%,
      rgba(139, 44, 245, .07),
      transparent 28%
    ),
    linear-gradient(
      145deg,
      #ffffff,
      #f5f9ff
    );

  border: 1px dashed #b6cde5;
  border-radius: 24px;
  color: #061d43;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 260px;
  padding: 32px;
  text-align: center;
}

.empty-products h3 {
  color: #061d43;
  font-size: 22px;
  font-weight: 900;
  margin: 12px 0 4px;
}

.empty-products p {
  color: #667b99;
  line-height: 1.6;
  margin: 0;
}

/* =========================================
   OPERATION / BENEFITS
========================================= */

.operation-section {
  background:
    radial-gradient(
      circle at 10% 20%,
      rgba(11, 94, 215, .09),
      transparent 24%
    ),
    radial-gradient(
      circle at 92% 75%,
      rgba(139, 44, 245, .07),
      transparent 26%
    ),
    #fff;
}

.operation-section p,
.benefit-item p {
  color: #667b99;
  line-height: 1.6;
  margin: 0;
}

.benefit-grid {
  display: grid;
  gap: 14px;
  grid-template-columns: repeat(2, 1fr);
}

.benefit-item {
  align-items: flex-start;

  background:
    linear-gradient(
      145deg,
      #ffffff,
      #f4f8ff
    );

  border: 1px solid #dce8f5;
  border-radius: 18px;
  box-shadow: 0 12px 28px rgba(6, 29, 67, .06);
  display: flex;
  gap: 14px;
  min-height: 132px;
  padding: 22px;
}

.benefit-item strong {
  color: #061d43;
  display: block;
  font-size: 17px;
  margin-bottom: 6px;
}

/* =========================================
   CONTACT
========================================= */

.contact-section {
  background:
    radial-gradient(
      circle at 80% 20%,
      rgba(255, 255, 255, .22),
      transparent 26%
    ),
    radial-gradient(
      circle at 15% 80%,
      rgba(114, 237, 240, .18),
      transparent 28%
    ),
    linear-gradient(
      105deg,
      #061d43,
      #0b5ed7 52%,
      #8b2cf5
    );

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

/* =========================================
   FOOTER
========================================= */

.store-footer {
  background:
    linear-gradient(
      105deg,
      #04142f,
      #061d43
    );

  color: #c7d5e8;
  font-size: 13px;
  padding: 20px 0;
}

/* =========================================
   RESPONSIVE
========================================= */

@media (max-width: 960px) {
  .hero-copy h1 {
    font-size: 58px;
  }

  .benefit-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .hero-inner {
    display: block;
  }

  .hero-products-figure {
    margin: 28px 0 0;
    width: 100%;
  }

  .hero-products-figure img {
    height: auto;
    max-height: 420px;
  }

  .hero-floating-card {
    bottom: 18px;
    right: 18px;
  }

  .products-heading {
    align-items: stretch;
    flex-direction: column;
  }

  .contact-inner,
  .footer-inner {
    align-items: flex-start;
    flex-direction: column;
  }
}

@media (max-width: 600px) {
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

  .hero-products-figure {
    margin-top: 22px;
    width: 100%;
  }

  .hero-products-figure img {
    border-radius: 22px;
  }

  .hero-floating-card {
    font-size: 12px;
    padding: 9px 11px;
    right: 26px;
  }

  .benefit-grid {
    grid-template-columns: 1fr;
  }

  .section-heading h2,
  .operation-section h2,
  .contact-section h2 {
    font-size: 31px;
  }

  .product-visual {
    height: 220px;
  }

  .product-card-actions {
    align-items: stretch;
    flex-direction: column;
  }

  .product-card-actions .v-btn {
    width: 100%;
  }

  .home-pagination {
    align-items: stretch;
    flex-direction: column;
    text-align: center;
  }
}
</style>
