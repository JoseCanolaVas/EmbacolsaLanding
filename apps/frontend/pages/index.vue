<template>
  <v-app class="storefront" :style="variablesMarca">
    <store-header :logo-src="logoActual" active-section="inicio" />

    <main>
      <section id="inicio" class="hero-section">
        <v-container class="hero-inner">
          <div class="hero-copy">
            <h1>{{ 'SITIO WEB DESACTIVADO  ' }}</h1>
            <p>{{ 'contacta a softnova para poder activar tu servicio ' }}</p>

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
            <img :src="bannerActual" alt="Banner Embacolsa">
            <div class="hero-floating-card">
              <v-icon color="#1e88e5">mdi-package-check</v-icon>
              <div>
                <strong>Catálogo industrial</strong>
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
              <p class="section-lead">Productos reales del panel, organizados para vender sin enredos.</p>
            </div>
            <v-chip color="primary" outlined>
              {{ productosIndex.length }} visibles
            </v-chip>
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
                    Ver en catálogo
                    <v-icon right small>mdi-arrow-right</v-icon>
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
            <v-btn x-large rounded color="white" class="primary--text" :href="whatsappUrl" target="_blank">
              <v-icon left>mdi-whatsapp</v-icon>
              WhatsApp
            </v-btn>
            <v-btn x-large rounded outlined color="white" :href="correoUrl">
              Correo
            </v-btn>
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
        { value: '+100', label: 'Soluciones de empaque' },
        { value: 'B2B', label: 'Atención comercial' },
      ],
    }
  },

  computed: {
    logoActual() {
      return this.obtenerImagenPorTipo('logo') || '/images/embacolsa-optimized.webp'
    },

    bannerActual() {
      return this.obtenerImagenPorTipo('banner') ||
        this.obtenerImagenPorTipo('principal')
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
      const telefono = this.configuracionSitio.telefono_whatsapp || '573218720375'
      const mensaje = `Hola, quiero cotizar productos de ${this.configuracionSitio.nombre_sitio || 'Embacolsa'}`

      return `https://wa.me/${telefono}?text=${encodeURIComponent(mensaje)}`
    },

    correoUrl() {
      return `mailto:${this.configuracionSitio.correo_contacto || 'comercial@embacolsa.com.co'}`
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

      if (nombre.includes('cinta')) {
        return 'mdi-tape-measure'
      }

      if (nombre.includes('caja') || nombre.includes('cartón') || nombre.includes('carton')) {
        return 'mdi-package-variant-closed'
      }

      if (nombre.includes('zuncho') || nombre.includes('suncho')) {
        return 'mdi-link-variant'
      }

      if (nombre.includes('seguridad')) {
        return 'mdi-shield-check-outline'
      }

      if (nombre.includes('stretch') || nombre.includes('film')) {
        return 'mdi-rollup'
      }

      return 'mdi-package-variant'
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
  color: #102b5c;
  font-family: Arial, sans-serif;
}

.hero-section {
  background:
    linear-gradient(rgba(255, 255, 255, .045) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255, 255, 255, .045) 1px, transparent 1px),
    radial-gradient(circle at 78% 14%, rgba(114, 237, 240, .25), transparent 28%),
    radial-gradient(circle at 18% 68%, rgba(30, 136, 229, .23), transparent 32%),
    linear-gradient(110deg, #061d43 0%, var(--brand-primary) 48%, var(--brand-secondary) 100%);
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
  color: #edf3f3;
  display: inline-block;
  font-size: 12px;
  font-weight: 900;
  letter-spacing: 1px;
}

.eyebrow.blue {
  color: #fbffff;
}

.hero-copy h1 {
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
  background: linear-gradient(135deg, var(--brand-accent), #25a8e0) !important;
  box-shadow: 0 18px 38px rgba(30, 136, 229, .32) !important;
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
  background: rgba(255, 255, 255, .92);
  border: 1px solid rgba(255, 255, 255, .68);
  border-radius: 18px;
  bottom: 18px;
  box-shadow: 0 24px 52px rgba(0, 25, 60, .22);
  color: #12305e;
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
  font-size: 14px;
  font-weight: 950;
}

.hero-floating-card span {
  color: #72839a;
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
  color: #112e64;
  font-size: 42px;
  font-weight: 900;
  letter-spacing: 0;
  line-height: 1.08;
  margin: 8px 0 0;
}

.section-lead {
  color: #6b7c92;
  font-size: 16px;
  line-height: 1.7;
  margin: 10px 0 0;
}

.product-card {
  border-color: #dfe9f2 !important;
  border-radius: 22px !important;
  box-shadow: 0 8px 24px rgba(16, 43, 92, .055);
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
}

.product-card:hover {
  box-shadow: 0 24px 52px rgba(16, 43, 92, .16);
  transform: translateY(-7px);
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
  background:
    radial-gradient(circle at 10% 20%, rgba(13, 120, 128, .08), transparent 24%),
    #fff;
}

.benefit-grid {
  display: grid;
  gap: 14px;
  grid-template-columns: repeat(2, 1fr);
}

.benefit-item {
  align-items: flex-start;
  background: linear-gradient(145deg, #ffffff, #f4f8fb);
  border: 1px solid #e2ebf3;
  border-radius: 18px;
  box-shadow: 0 12px 28px rgba(16, 43, 92, .06);
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
  background:
    radial-gradient(circle at 80% 20%, rgba(255, 255, 255, .22), transparent 26%),
    linear-gradient(105deg, var(--brand-primary), var(--brand-secondary));
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
}
</style>
