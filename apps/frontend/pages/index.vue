<template>
  <v-app class="storefront">

    <header class="main-header">
      <v-container class="d-flex align-center py-4">
        <a href="#inicio" class="brand">
          <img class="brand-logo" src="/images/embacolsa.png" alt="Embacolsa">
        </a>
        <v-spacer />
        <v-text-field v-model="search" class="search-box d-none d-sm-flex" dense hide-details outlined rounded
          placeholder="¿Qué producto necesitas?" prepend-inner-icon="mdi-magnify" @keyup.enter="showSearch = true">
          <template #append><v-btn color="primary" small depressed rounded
              @click="showSearch = true">Buscar</v-btn></template>
        </v-text-field>
        <v-btn icon class="d-sm-none" color="primary"
          @click="mobileSearch = !mobileSearch"><v-icon>mdi-magnify</v-icon></v-btn>
        <v-badge :content="cart" :value="cart" color="primary" overlap class="ml-3"><v-btn icon
            color="primary"><v-icon>mdi-cart-outline</v-icon></v-btn></v-badge>
      </v-container>
      <v-container v-if="mobileSearch" class="pt-0 d-sm-none"><v-text-field v-model="search" dense hide-details outlined
          rounded placeholder="Buscar productos" prepend-inner-icon="mdi-magnify" /></v-container>
    </header>

    <nav class="nav-bar">
      <v-container class="d-flex align-center py-0">
        <v-menu offset-y>
          <template #activator="{ on, attrs }"><v-btn class="category-button" text v-bind="attrs" v-on="on"><v-icon
                left>mdi-view-grid-outline</v-icon> Categorías <v-icon right
                small>mdi-chevron-down</v-icon></v-btn></template>
          <v-list dense><v-list-item v-for="category in categories" :key="category"><v-list-item-title>{{ category
          }}</v-list-item-title></v-list-item></v-list>
        </v-menu>
        <div class="nav-links d-none d-md-flex"><a v-for="link in links" :key="link.label" :href="link.href">{{
          link.label
            }}</a></div>
        <v-spacer /><v-btn text color="white" class="d-md-none" @click="drawer = true"><v-icon left>mdi-menu</v-icon>
          Menú</v-btn>
      </v-container>
    </nav>

    <v-navigation-drawer v-model="drawer" temporary right fixed><v-list nav><v-list-item v-for="link in links"
          :key="link.label" :href="link.href" @click="drawer = false"><v-list-item-title>{{ link.label
          }}</v-list-item-title></v-list-item></v-list></v-navigation-drawer>

    <main>
      <section id="inicio" class="hero-section">
        <v-container>
          <div class="hero-card">
            <div class="hero-orbit orbit-one" />
            <div class="hero-orbit orbit-two" />
            <v-row align="center">
              <v-col cols="12" md="7" class="hero-copy">
                <span class="eyebrow">EMBACOLSA · SOLUCIONES EMPRESARIALES</span>
                <h1>Suministros que mueven <em>tu operación.</em></h1>
                <p>Todo lo que tu empresa necesita para empacar, proteger y despachar con calidad, respaldo y entregas a
                  nivel nacional.</p>
                <div class="d-flex flex-wrap" style="gap: 12px"><v-btn color="primary" x-large rounded depressed
                    href="#productos">Explorar productos <v-icon right>mdi-arrow-right</v-icon></v-btn><v-btn outlined
                    color="white" x-large rounded href="#contacto">Cotizar ahora</v-btn></div>
              </v-col>
              <v-col cols="12" md="5" class="hero-visual">
                <div class="package stack-one"><v-icon>mdi-package-variant-closed</v-icon></div>
                <div class="roll roll-one" />
                <div class="roll roll-two" />
                <div class="tape"><span>EMBACOLSA</span></div>
                <div class="hero-label"><v-icon color="primary" small>mdi-truck-fast-outline</v-icon><span><b>Envíos
                      rápidos</b><small>A toda Colombia</small></span></div>
              </v-col>
            </v-row>
            <v-row class="trust-row"><v-col v-for="item in trust" :key="item.title" cols="6" sm="3">
                <div class="trust-item"><v-icon color="primary">{{ item.icon }}</v-icon><span><b>{{ item.title
                }}</b><small>{{ item.text }}</small></span></div>
              </v-col></v-row>
          </div>
        </v-container>
      </section>

      <section id="productos" class="products-section">
        <v-container>
          <div class="section-heading">
            <div><span>CATEGORÍAS DESTACADAS</span>
              <h2>Encuentra la solución ideal</h2>
            </div><v-btn text color="primary" href="#contacto">Ver catálogo completo <v-icon
                right>mdi-arrow-right</v-icon></v-btn>
          </div>
          <v-row>
            <v-col v-for="product in products" :key="product.title" cols="12" sm="6" lg="3"><v-card class="product-card"
                outlined height="100%">
                <div class="product-art" :class="product.class"><v-icon>{{ product.icon }}</v-icon><span>{{
                  product.figure
                    }}</span></div><v-card-text><span class="product-type">{{ product.type }}</span>
                  <h3>{{ product.title }}</h3>
                  <p>{{ product.text }}</p><v-btn text color="primary" class="px-0" @click="addToCart(product)">Agregar
                    a
                    cotización <v-icon right small>mdi-plus</v-icon></v-btn>
                </v-card-text>
              </v-card></v-col>
          </v-row>
        </v-container>
      </section>

      <section id="beneficios" class="value-section"><v-container><v-row align="center"><v-col cols="12" md="5"><span
                class="eyebrow">¿POR QUÉ EMBACOLSA?</span>
              <h2>Más que productos, respaldo para cada despacho.</h2>
              <p>Atendemos las necesidades reales de tu negocio con soluciones prácticas y una asesoría que no te deja
                esperando.</p><v-btn color="primary" rounded depressed href="#contacto">Hablar con un asesor</v-btn>
            </v-col><v-col cols="12" md="7"><v-row><v-col v-for="benefit in benefits" :key="benefit.title" cols="12"
                  sm="6">
                  <div class="benefit-card"><v-icon color="primary">{{ benefit.icon }}</v-icon>
                    <h3>{{ benefit.title }}</h3>
                    <p>{{ benefit.text }}</p>
                  </div>
                </v-col></v-row></v-col></v-row></v-container></section>

      <section id="contacto" class="contact-section"><v-container><v-row align="center"><v-col cols="12" md="7"><span
                class="eyebrow">COTIZA SIN COMPLICACIONES</span>
              <h2>¿Listo para abastecer tu operación?</h2>
              <p>Cuéntanos qué necesitas y te ayudamos a encontrar la mejor solución.</p>
            </v-col><v-col cols="12" md="5" class="text-md-right"><v-btn x-large rounded color="white"
                class="primary--text mr-2 mb-2" href="https://wa.me/573218720375" target="_blank"><v-icon
                  left>mdi-whatsapp</v-icon> Escribir por WhatsApp</v-btn><v-btn x-large rounded outlined color="white"
                class="mb-2" href="mailto:comercial@embacolsa.com.co">Enviar
                correo</v-btn></v-col></v-row></v-container>
      </section>
    </main>
    <footer><v-container class="d-flex flex-column flex-md-row justify-space-between align-md-center"><span>© {{ new
      Date().getFullYear() }} SOFTNOVA SOLUTIONS </span><span>Colombia · Atención
          nacional</span></v-container></footer>
    <v-snackbar v-model="showSearch" color="primary" rounded>Buscaremos “{{ search || 'todos los productos' }}” en el
      catálogo.<template #action="{ attrs }"><v-btn text v-bind="attrs"
          @click="showSearch = false">Cerrar</v-btn></template></v-snackbar>
  </v-app>
</template>

<script>
export default {
  name: 'IndexPage',
  data: () => (
    {
      search: '',
      cart: 0,
      drawer: false,
      mobileSearch: false,
      showSearch: false,
      links: [
        { label: 'Inicio', href: '#inicio' },
        { label: 'Beneficios', href: '#beneficios' },
        { label: 'Productos', href: '#productos' },
        { label: 'Nosotros', href: '#beneficios' },
        { label: 'Contacto', href: '#contacto' }
      ],
      categories: [
        'Cintas y adhesivos',
        'Stretch film',
        'Sunchos y zunchos',
        'Cajas de cartón',
        'Seguridad industrial'
      ],
      trust: [{
        icon: 'mdi-map-marker-radius-outline',
        title: 'Cobertura nacional',
        text: 'Llegamos a tu empresa'
      },
      { icon: 'mdi-shield-check-outline', title: 'Calidad garantizada', text: 'Productos confiables' }, { icon: 'mdi-account-tie-outline', title: 'Asesoría experta', text: 'Atención personalizada' }, { icon: 'mdi-clock-fast', title: 'Respuesta ágil', text: 'Cotiza sin esperar' }], products: [{ type: 'Empaque', title: 'Cintas adhesivas', text: 'Sellado seguro y resistente para cada tipo de empaque.', icon: 'mdi-tape-measure', figure: '48 mm', class: 'art-tape' }, { type: 'Protección', title: 'Stretch film', text: 'Estabiliza y protege tus cargas durante el transporte.', icon: 'mdi-rollup', figure: '1500 m', class: 'art-film' }, { type: 'Industrial', title: 'Sunchos y zunchos', text: 'Sujeción firme para mercancía y operaciones exigentes.', icon: 'mdi-package-variant', figure: 'Alta tensión', class: 'art-strap' }, { type: 'Almacenaje', title: 'Cajas de cartón', text: 'Empaques durables, listos para cuidar lo que envías.', icon: 'mdi-cube-outline', figure: 'A tu medida', class: 'art-box' }], benefits: [{ icon: 'mdi-truck-check-outline', title: 'Entrega confiable', text: 'Coordinamos pedidos para que tu operación no se detenga.' }, { icon: 'mdi-layers-triple-outline', title: 'Portafolio completo', text: 'Compra lo esencial desde un solo proveedor.' }, { icon: 'mdi-handshake-outline', title: 'Acompañamiento real', text: 'Recomendaciones según tu producto y volumen.' }, { icon: 'mdi-headset', title: 'Canales directos', text: 'Estamos listos para responder y cotizar rápido.' }]
    }), methods: { addToCart(product) { this.cart += 1; this.search = product.title; this.showSearch = true } }
}
</script>

<style scoped>
.storefront {
  font-family: Arial, sans-serif;
  color: #102b5c;
  background: #fff;
}

.top-strip {
  background: linear-gradient(90deg, #0b2458, #174f82);
  color: #fff;
  font-size: 13px;
}

.top-strip .v-container {
  min-height: 42px;
}

.top-message {
  font-weight: 700;
}

.top-message .v-icon {
  font-size: 10px;
  margin-right: 7px;
}

.contact-links {
  gap: 10px;
}

.contact-links a {
  color: #fff;
  text-decoration: none;
  border: 1px solid rgba(255, 255, 255, .16);
  padding: 5px 11px;
  border-radius: 18px;
}

.contact-links .v-icon {
  color: #76e6ee;
  margin-right: 4px;
}

.main-header {
  background: linear-gradient(125deg, #fff, #f5fbfd);
}

.brand {
  display: block;
  width: 280px;
  height: 76px;
  overflow: hidden;
}

.brand-logo {
  display: block;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.search-box {
  max-width: 570px;
}

.search-box::v-deep .v-input__slot {
  background: #f5f8fd !important;
  border-color: #d8e1ee !important;
}

.nav-bar {
  background: linear-gradient(90deg, #0b255a, #173772 58%, #102b5c);
  box-shadow: 0 8px 22px rgba(10, 38, 85, .15);
}

.category-button {
  height: 38px !important;
  border: 1px solid rgba(116, 232, 237, .42) !important;
  border-radius: 20px !important;
  color: #d9fbfd !important;
  font-weight: 700;
  letter-spacing: .1px;
}

.nav-links {
  height: 48px;
  gap: 42px;
  margin-left: 30px;
  align-items: center;
}

.nav-links a {
  color: #fff;
  text-decoration: none;
  font-size: 14px;
  font-weight: 700;
}

.hero-section {
  background: linear-gradient(135deg, #0a245a, #09234b);
  padding: 32px 0 0;
}

.hero-card {
  overflow: hidden;
  padding: 52px 42px 0;
  border: 1px solid rgba(117, 235, 240, .18);
  border-radius: 28px 28px 0 0;
  background: linear-gradient(116deg, rgba(7, 27, 68, .95) 0%, rgba(10, 48, 97, .82) 48%, rgba(9, 43, 91, .75)), url('/images/login.png') center/cover;
  color: #fff;
  position: relative;
}

.hero-card>.row {
  position: relative;
  z-index: 1;
}

.hero-orbit {
  position: absolute;
  border: 1px solid rgba(102, 233, 239, .23);
  border-radius: 50%;
  z-index: 0;
}

.orbit-one {
  width: 540px;
  height: 540px;
  right: -160px;
  top: -260px
}

.orbit-two {
  width: 280px;
  height: 280px;
  right: 215px;
  bottom: -190px
}

.eyebrow {
  display: inline-block;
  font-size: 11px;
  font-weight: 900;
  letter-spacing: 1.5px;
  color: #65e2ea;
}

.eyebrow.blue {
  color: #20aab8;
}

.hero-copy h1,
.value-section h2,
.contact-section h2 {
  font-size: clamp(35px, 4vw, 58px);
  letter-spacing: -2.5px;
  line-height: 1.03;
  margin: 15px 0;
  max-width: 620px;
}

.hero-copy h1 em {
  color: #58d8df;
  font-style: normal;
}

.hero-copy p {
  max-width: 580px;
  line-height: 1.7;
  color: #dbe5f5;
}

.hero-visual {
  min-height: 320px;
  position: relative;
}

.package {
  position: absolute;
  right: 72px;
  bottom: 49px;
  width: 195px;
  height: 140px;
  border-radius: 7px;
  background: linear-gradient(145deg, #e6b662, #a5632c);
  box-shadow: 20px 18px 30px rgba(0, 0, 0, .27);
  transform: skewY(-8deg);
}

.package .v-icon {
  font-size: 66px;
  color: rgba(255, 255, 255, .72);
  margin: 38px 64px;
}

.roll {
  position: absolute;
  border-radius: 50%;
  background: repeating-radial-gradient(circle at center, #18242b 0, #18242b 6px, #59636b 7px, #59636b 10px);
  box-shadow: 13px 14px 25px rgba(0, 0, 0, .3);
}

.roll-one {
  height: 156px;
  width: 156px;
  right: 7px;
  top: 34px;
}

.roll-two {
  height: 114px;
  width: 114px;
  right: 125px;
  top: 70px;
  background: repeating-radial-gradient(circle at center, #eff5f5 0, #eff5f5 7px, #b8cdcf 8px, #b8cdcf 10px);
}

.tape {
  position: absolute;
  width: 130px;
  height: 130px;
  border-radius: 50%;
  right: 14px;
  bottom: 10px;
  background: repeating-radial-gradient(circle at center, #725723 0, #725723 16px, #e5bd59 17px, #e5bd59 25px);
  display: flex;
  align-items: center;
  justify-content: center;
}

.tape span {
  background: #fff8db;
  color: #9d6a10;
  font-weight: 900;
  font-size: 10px;
  padding: 9px 5px;
  border-radius: 50%;
}

.hero-label {
  position: absolute;
  bottom: 10px;
  left: 35px;
  display: flex;
  align-items: center;
  gap: 9px;
  background: linear-gradient(135deg, #fff, #e6feff);
  color: #112e64;
  padding: 11px 16px;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, .18);
}

.hero-label span,
.trust-item span {
  display: flex;
  flex-direction: column;
}

.hero-label small,
.trust-item small {
  font-size: 11px;
  color: #71819f;
}

.trust-row {
  border-top: 1px solid rgba(255, 255, 255, .12);
  margin-top: 25px;
}

.trust-item {
  display: flex;
  gap: 10px;
  align-items: center;
  padding: 18px 0;
}

.trust-item b {
  font-size: 13px;
}

.products-section,
.value-section {
  padding: 88px 0;
}

.section-heading {
  display: flex;
  justify-content: space-between;
  align-items: end;
  margin-bottom: 28px;
}

.section-heading h2 {
  font-size: 36px;
  letter-spacing: -1.4px;
  margin: 8px 0 0;
}

.product-card {
  border-color: #e1e9f3 !important;
  border-radius: 15px;
  overflow: hidden;
  transition: transform .2s, box-shadow .2s;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 13px 30px rgba(12, 48, 91, .12);
}

.product-art {
  height: 177px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.product-art .v-icon {
  font-size: 88px;
  color: #fff;
  filter: drop-shadow(0 9px 7px rgba(0, 0, 0, .18));
}

.product-art span {
  position: absolute;
  right: 14px;
  bottom: 12px;
  color: #fff;
  font-weight: 800;
  font-size: 12px;
  opacity: .8;
}

.art-tape {
  background: linear-gradient(135deg, #1fbfc5, #1786aa)
}

.art-film {
  background: linear-gradient(135deg, #7187a8, #26385d)
}

.art-strap {
  background: linear-gradient(135deg, #daac3f, #a26d14)
}

.art-box {
  background: linear-gradient(135deg, #d9904a, #8f522c)
}

.product-type {
  color: #18a9b7;
  text-transform: uppercase;
  font-size: 10px;
  letter-spacing: 1.4px;
  font-weight: 900;
}

.product-card h3,
.benefit-card h3 {
  margin: 7px 0 8px;
  font-size: 20px;
}

.product-card p,
.benefit-card p,
.value-section p {
  color: #61708b;
  line-height: 1.55;
  margin: 0;
}

.value-section {
  background: #f3f7fb;
}

.value-section h2 {
  color: #112e64;
  font-size: 42px;
}

.benefit-card {
  height: 100%;
  background: #fff;
  border-radius: 13px;
  padding: 23px;
  border: 1px solid #e6edf5;
}

.benefit-card h3 {
  font-size: 17px;
}

.contact-section {
  padding: 55px 0;
  background: linear-gradient(110deg, #1bc0c8, #2582bc);
  color: #fff;
}

.contact-section .eyebrow {
  color: #d7ffff;
}

.contact-section h2 {
  font-size: 39px;
  margin-bottom: 6px;
}

.contact-section p {
  margin-bottom: 0;
  font-size: 17px;
  opacity: .9;
}

footer {
  padding: 22px 0;
  background: #0b2149;
  color: #c2cee2;
  font-size: 13px;
}

@media(max-width:600px) {
  .hero-card {
    padding: 35px 24px 0
  }

  .hero-copy h1 {
    letter-spacing: -1.8px
  }

  .hero-visual {
    min-height: 245px
  }

  .package {
    transform: scale(.75) skewY(-8deg);
    transform-origin: bottom right
  }

  .roll-one {
    transform: scale(.75);
    transform-origin: top right
  }

  .roll-two {
    transform: scale(.75);
    transform-origin: top right
  }

  .tape {
    transform: scale(.75);
    transform-origin: bottom right
  }

  .hero-label {
    left: 0;
    transform: scale(.85);
    transform-origin: bottom left
  }

  .section-heading {
    align-items: start;
    flex-direction: column;
    gap: 7px
  }

  .products-section,
  .value-section {
    padding: 58px 0
  }

  .contact-section {
    text-align: center
  }

  .brand {
    width: 190px;
    height: 58px
  }

  .trust-item {
    padding: 11px 0
  }

  .trust-item b {
    font-size: 11px
  }

  .trust-item small {
    font-size: 9px
  }
}
</style>
