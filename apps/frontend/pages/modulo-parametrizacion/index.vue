<template>
    <v-card flat class="panel-home">
        <div class="home-hero">
            <div>
                <span>Centro de parametrización</span>
                <h1>Administra lo que se muestra en el sitio público.</h1>
                <p>
                    Desde acá controlas catálogo, marcas, categorías, imágenes y usuarios.
                    La idea es que el index público se alimente de esta configuración.
                </p>
            </div>

            <v-btn rounded depressed color="primary" to="/catalogo">
                Ver catálogo público
                <v-icon right>
                    mdi-open-in-new
                </v-icon>
            </v-btn>
        </div>

        <v-row class="mt-5">
            <v-col v-for="card in resumen" :key="card.title" cols="12" sm="6" lg="3">
                <v-card outlined class="summary-card" height="100%">
                    <v-icon :color="card.color" size="34">
                        {{ card.icon }}
                    </v-icon>
                    <strong>{{ card.value }}</strong>
                    <span>{{ card.title }}</span>
                </v-card>
            </v-col>
        </v-row>

        <v-row class="mt-5">
            <v-col cols="12" lg="8">
                <v-card outlined class="quick-card">
                    <v-card-title>
                        Accesos rápidos
                    </v-card-title>

                    <v-card-text>
                        <v-row dense>
                            <v-col v-for="item in accesosFiltrados" :key="item.title" cols="12" sm="6">
                                <v-card outlined class="access-card" :to="item.route">
                                    <v-icon :color="item.color">
                                        {{ item.icon }}
                                    </v-icon>
                                    <div>
                                        <strong>{{ item.title }}</strong>
                                        <p>{{ item.text }}</p>
                                    </div>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>

            <v-col cols="12" lg="4">
                <v-card outlined class="guide-card">
                    <v-card-title>
                        Flujo recomendado
                    </v-card-title>

                    <v-card-text>
                        <div v-for="(step, index) in pasos" :key="step.title" class="guide-step">
                            <div class="step-number">
                                {{ index + 1 }}
                            </div>
                            <div>
                                <strong>{{ step.title }}</strong>
                                <p>{{ step.text }}</p>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
export default {
    layout: 'parametrizacion',

    data() {
        return {
            categorias: [],
            productos: [],
            marcas: [],
            imagenes: [],
            usuarios: [],
            accesos: [
                {
                    title: 'Sitio público',
                    text: 'Edita textos, colores, WhatsApp, correo y beneficios del index.',
                    icon: 'mdi-palette-outline',
                    color: 'cyan darken-1',
                    route: '/modulo-parametrizacion/sitio-publico',
                    permiso: 'administrar-sitio',
                },
                {
                    title: 'Productos',
                    text: 'Crea productos con categoría, marca, imagen, precio y estado.',
                    icon: 'mdi-package-variant-closed',
                    color: 'primary',
                    route: '/modulo-parametrizacion/productos',
                    permiso: 'productos.ver',
                },
                {
                    title: 'Categorías',
                    text: 'Organiza el catálogo público y la muestra del inicio.',
                    icon: 'mdi-format-list-bulleted-type',
                    color: 'teal',
                    route: '/modulo-parametrizacion/categorias',
                    permiso: 'categorias.ver',
                },
                {
                    title: 'Marcas',
                    text: 'Parametriza marcas para filtrar y presentar productos.',
                    icon: 'mdi-tag-multiple-outline',
                    color: 'amber darken-2',
                    route: '/modulo-parametrizacion/marcas',
                    permiso: 'marcas.ver',
                },
                {
                    title: 'Roles y permisos',
                    text: 'Define perfiles reutilizables para usuarios y módulos.',
                    icon: 'mdi-shield-account-outline',
                    color: 'deep-purple',
                    route: '/modulo-parametrizacion/roles',
                    permiso: 'roles.ver',
                },
                {
                    title: 'Imágenes',
                    text: 'Controla logo, banner principal y demás piezas visuales.',
                    icon: 'mdi-image-multiple-outline',
                    color: 'indigo',
                    route: '/modulo-parametrizacion/imagenes',
                    permiso: 'imagenes.ver',
                },
            ],
            pasos: [
                {
                    title: 'Define marca y mensajes',
                    text: 'Configura textos, colores, WhatsApp, correo y beneficios del sitio público.',
                },
                {
                    title: 'Carga imágenes principales',
                    text: 'Define logo, banner y piezas visuales para darle personalidad al index.',
                },
                {
                    title: 'Crea categorías, marcas y productos',
                    text: 'Cada producto activo alimenta el inicio y el catálogo tipo ecommerce.',
                },
            ],
        }
    },

    computed: {
        resumen() {
            return [
                {
                    title: 'Productos activos',
                    value: this.productos.filter(item => this.estaActivo(item.estado)).length,
                    icon: 'mdi-package-variant-closed',
                    color: 'primary',
                },
                {
                    title: 'Categorías activas',
                    value: this.categorias.filter(item => this.estaActivo(item.estado)).length,
                    icon: 'mdi-format-list-bulleted-type',
                    color: 'teal',
                },
                {
                    title: 'Marcas activas',
                    value: this.marcas.filter(item => this.estaActivo(item.estado)).length,
                    icon: 'mdi-tag-multiple-outline',
                    color: 'amber darken-2',
                },
                {
                    title: 'Imágenes cargadas',
                    value: this.imagenes.length,
                    icon: 'mdi-image-multiple-outline',
                    color: 'indigo',
                },
            ]
        },

        accesosFiltrados() {
            return this.accesos.filter(item => !item.permiso || this.$can(item.permiso))
        },
    },

    mounted() {
        this.cargarDatos()
    },

    methods: {
        async cargarDatos() {
            await Promise.all([
                this.listarCategorias(),
                this.listarProductos(),
                this.listarMarcas(),
                this.listarImagenes(),
                this.listarUsuarios(),
            ])
        },

        async listarCategorias() {
            try {
                const response = await this.$axios.get('/categorias/listar')
                this.categorias = response.data || []
            } catch (error) {
                this.categorias = []
            }
        },

        async listarProductos() {
            try {
                const response = await this.$axios.post('/productos/listar', {
                    paginacion: null,
                })
                this.productos = response.data || []
            } catch (error) {
                this.productos = []
            }
        },

        async listarMarcas() {
            try {
                const response = await this.$axios.get('/marcas/listar')
                this.marcas = response.data || []
            } catch (error) {
                this.marcas = []
            }
        },

        async listarImagenes() {
            try {
                const response = await this.$axios.get('/imagenes/listar')
                this.imagenes = response.data || []
            } catch (error) {
                this.imagenes = []
            }
        },

        async listarUsuarios() {
            try {
                const response = await this.$axios.get('/usuarios/listar')
                this.usuarios = response.data || []
            } catch (error) {
                this.usuarios = []
            }
        },

        estaActivo(estado) {
            return estado === true || estado === 1 || estado === '1'
        },
    },
}
</script>

<style scoped>
.panel-home {
    background: transparent;
}

.home-hero {
    align-items: center;
    background:
        radial-gradient(circle at 85% 15%, rgba(114, 237, 240, .2), transparent 26%),
        linear-gradient(110deg, #0f2c61 0%, #155f56 100%);
    color: #fff;
    display: flex;
    gap: 24px;
    justify-content: space-between;
    padding: 34px;
}

.home-hero span {
    color: #72edf0;
    font-size: 12px;
    font-weight: 900;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.home-hero h1 {
    color: #fff;
    font-size: 34px;
    font-weight: 900;
    line-height: 1.1;
    margin: 8px 0 10px;
}

.home-hero p {
    color: rgba(255, 255, 255, .84);
    line-height: 1.65;
    margin: 0;
    max-width: 720px;
}

.summary-card {
    align-items: flex-start;
    border-color: #dfe8f0 !important;
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 22px;
}

.summary-card strong {
    color: #243b53;
    font-size: 34px;
    line-height: 1;
}

.summary-card span {
    color: #65758d;
    font-size: 12px;
    font-weight: 900;
    text-transform: uppercase;
}

.quick-card,
.guide-card,
.access-card {
    border-color: #dfe8f0 !important;
}

.access-card {
    align-items: flex-start;
    display: flex;
    gap: 14px;
    height: 100%;
    padding: 18px;
    text-decoration: none;
}

.access-card strong,
.guide-step strong {
    color: #243b53;
    display: block;
    font-size: 16px;
    margin-bottom: 4px;
}

.access-card p,
.guide-step p {
    color: #65758d;
    line-height: 1.5;
    margin: 0;
}

.guide-step {
    display: flex;
    gap: 14px;
    padding: 14px 0;
}

.guide-step + .guide-step {
    border-top: 1px solid #e6edf5;
}

.step-number {
    align-items: center;
    background: #e8f4f2;
    border-radius: 999px;
    color: #155f56;
    display: flex;
    flex: 0 0 auto;
    font-weight: 900;
    height: 32px;
    justify-content: center;
    width: 32px;
}

@media (max-width: 760px) {
    .home-hero {
        align-items: flex-start;
        flex-direction: column;
        padding: 24px;
    }

    .home-hero h1 {
        font-size: 27px;
    }
}
</style>
