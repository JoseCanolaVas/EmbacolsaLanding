<template>
    <v-card flat class="mb-4">
        <v-card-title>
            <v-toolbar flat>
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="warning" class="mr-3" size="30">
                            mdi-cart
                        </v-icon>
                        <span>Productos</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </v-card-title>

        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3><b class="justify-center"> <v-icon>mdi-information-slab-circle-outline</v-icon> Panel de parametrizacion
                    de
                    productos, desde aca se podran gestionar informacion de los productos
                    disponibles y su visibilidad dentro de la web principal.</b></h3>
        </v-card-subtitle>

        <v-card-text>
            <v-row align="center">

                <v-col cols="12" md="3">
                    <v-text-field dense outlined rounded label="Numero de Registro" type="Number"></v-text-field>
                </v-col>

                <v-col cols="12" md="3">
                    <v-text-field v-model="buscar" label="Buscar" prepend-inner-icon="mdi-magnify" outlined dense
                        rounded clearable />
                </v-col>

                <v-col cols="12" md="3">
                    <v-autocomplete v-model="filtros.categoria" :items="categorias" label="Categoría" outlined dense
                        item-text="nombre" item-value="id" clearable rounded />
                </v-col>

                <v-col cols="12" md="2">
                    <v-select v-model="filtros.estado" :items="estados" label="Estado" outlined dense rounded
                        clearable />
                </v-col>

                <!-- Botones -->

                <v-card-actions class="mb-4">
                    <v-spacer></v-spacer>
                    <v-btn @click="listarProductos()" rounded color="info">
                        filtrar <v-icon right>mdi-magnify</v-icon>
                    </v-btn>
                    <v-btn @click="limpiarFiltros()" color="error" rounded>
                        Limpiar filtros <v-icon right>mdi-broom</v-icon>
                    </v-btn>
                    <v-btn @click="abrirModalProducto()" rounded color="primary">
                        Crear <v-icon right>mdi-plus</v-icon>
                    </v-btn>
                </v-card-actions>

            </v-row>
        </v-card-text>

        <v-card-text class="mt-5">

            <v-data-table :headers="headersProductos" :items="productos" :loading="loading.productos"
                disable-pagination hide-default-footer>
                <template v-slot:item.ruta_imagen="{ item }">
                    <v-avatar tile size="52" class="producto-avatar">
                        <v-img v-if="resolverImagen(item.ruta_imagen)" :src="resolverImagen(item.ruta_imagen)" contain />
                        <v-icon v-else color="grey lighten-1">
                            mdi-image-off-outline
                        </v-icon>
                    </v-avatar>
                </template>

                <template v-slot:item.categoria="{ item }">
                    {{ item.categoria?.nombre || 'Sin categoria' }}
                </template>

                <template v-slot:item.precio="{ item }">
                    {{ formatearPrecio(item.precio) }}
                </template>

                <template v-slot:item.estado="{ item }">
                    <v-chip small :color="productoActivo(item.estado) ? 'success' : 'grey'" dark>
                        {{ productoActivo(item.estado) ? 'Activo' : 'Inactivo' }}
                    </v-chip>
                </template>

                <template v-slot:item.acciones="{ item }">
                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon color="info" v-bind="attrs" v-on="on" @click="abrirModalDetalleProducto(item)">
                                <v-icon>
                                    mdi-eye-outline
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Ver detalle</span>
                    </v-tooltip>

                    <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn icon color="primary" v-bind="attrs" v-on="on" @click="abrirModalProducto(item)">
                                <v-icon>
                                    mdi-pencil-outline
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>Editar producto</span>
                    </v-tooltip>
                </template>

            </v-data-table>

        </v-card-text>

        <v-card-actions>

            <v-row>
                <v-col cols="12" sm="11" md="11">
                    <v-pagination v-model="paginacion.pagina" :length="paginacion.total" :total-visible="9"
                        @input="listarProductos()">
                    </v-pagination>
                </v-col>

                <v-col cols="12" sm="1" md="1">
                    <v-select dense outlined v-model="paginacion.cantidadRegistros" :items="[5, 10, 20, 50, 100]"
                        label="Registros por Página" @change="listarProductos()"></v-select>
                </v-col>
            </v-row>

        </v-card-actions>

        <v-dialog v-model="modalProducto" max-width="600px">
            <ModalProductos :productoSeleccionado="productoSeleccionado" @cerrar="modalProducto = false"
                @producto-guardado="listarProductos" />
        </v-dialog>

        <v-dialog v-model="modalDetalleProducto" max-width="760px">
            <ModalDetalleProductos :productoSeleccionado="productoSeleccionado" @cerrar="modalDetalleProducto = false" />
        </v-dialog>

    </v-card>
</template>

<script>
import ModalProductos from '../../components/modulo-parametrizacion/categorias/modalProductos.vue';
import ModalDetalleProductos from '../../components/modulo-parametrizacion/categorias/modalDetalleProductos.vue';

export default {
    layout: 'parametrizacion',

    components: {
        ModalProductos,
        ModalDetalleProductos,
    },

    data() {
        return {
            buscar: null,
            modalProducto: false,
            modalDetalleProducto: false,
            productoSeleccionado: {},
            filtros: {
                categoria: null,
                estado: null
            },
            categorias: [],
            headersProductos: [
                { text: 'Imagen', value: 'ruta_imagen', sortable: false },
                { text: 'Nombre', value: 'nombre' },
                { text: 'Categoría', value: 'categoria' },
                { text: 'Precio', value: 'precio' },
                { text: 'Estado', value: 'estado' },
                { text: 'Acciones', value: 'acciones', sortable: false }
            ],
            loading: {
                productos: false,
                categorias: false
            },
            productos: [],
            estados: [
                { text: 'Activo', value: true },
                { text: 'Inactivo', value: false }
            ],
            paginacion: {
                pagina: 1,
                cantidadRegistros: 10,
                total: 0
            }
        };
    },

    mounted() {
        this.listarProductos();
        this.listarCategorias();
    },

    methods: {
        abrirModalCrearCategoria() {
            this.modalCategoria = true;
        },

        async listarProductos() {
            try {
                this.loading.productos = true;
                const response = await this.$axios.post('/productos/listar', {
                    nombre: this.buscar,
                    categoria: this.filtros.categoria,
                    estado: this.filtros.estado,
                    paginacion: this.paginacion
                });

                this.productos = response.data?.data ?? [];
                this.paginacion.total = response.data.last_page;
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar los productos. Por favor, inténtelo de nuevo.');
            } finally {
                this.loading.productos = false;
            }
        },

        async listarCategorias() {
            try {
                this.loading.categorias = true;
                const response = await this.$axios.get('/categorias/listar');
                this.categorias = response.data;
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar las categorías. Por favor, inténtelo de nuevo.');
            } finally {
                this.loading.categorias = false;
            }
        },

        abrirModalProducto(item = {}) {
            this.modalProducto = true;
            this.productoSeleccionado = { ...item };
        },

        abrirModalDetalleProducto(item) {
            this.productoSeleccionado = { ...item };
            this.modalDetalleProducto = true;
        },

        productoActivo(estado) {
            return estado === true || estado === 1 || estado === '1';
        },

        formatearPrecio(precio) {
            if (precio === null || precio === undefined || precio === '') {
                return 'Sin precio';
            }

            const valor = Number(precio);

            if (Number.isNaN(valor)) {
                return precio;
            }

            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            }).format(valor);
        },

        resolverImagen(rutaImagen) {
            if (!rutaImagen) {
                return null;
            }

            if (rutaImagen.startsWith('http') || rutaImagen.startsWith('blob:')) {
                return rutaImagen;
            }

            const apiUrl = this.$axios?.defaults?.baseURL ||
                this.$config?.API_URL ||
                'http://localhost:8000/api';
            const backendUrl = apiUrl.replace(/\/api\/?$/, '').replace(/\/$/, '');
            const ruta = rutaImagen.startsWith('/') ? rutaImagen : `/${rutaImagen}`;

            return `${backendUrl}${ruta}`;
        },

        limpiarFiltros() {
            this.buscar = null;
            this.filtros.categoria = null;
            this.filtros.estado = null;
            this.paginacion.pagina = 1;
            this.paginacion.cantidadRegistros = 10;
            this.listarProductos();
        }
    }
};
</script>

<style scoped>
.producto-avatar {
    background: #f5f7fb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}
</style>
