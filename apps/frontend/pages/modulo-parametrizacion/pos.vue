<template>
    <v-card flat class="pos-page">
        <div class="pos-topbar">
            <v-toolbar flat color="transparent">
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="warning" class="mr-3" size="30">
                            mdi-cash-register
                        </v-icon>
                        <span>Modulo de ventas | SOFTNOVA</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
                <v-spacer />
                <v-btn color="primary" rounded outlined to="/modulo-parametrizacion">
                    <v-icon left>mdi-arrow-left</v-icon>
                    Panel
                </v-btn>
                <v-btn color="success" rounded depressed class="ml-2" to="/modulo-parametrizacion/ventas">
                    <v-icon left>mdi-chart-line</v-icon>
                    Ver ventas
                </v-btn>
                <v-btn color="primary" rounded outlined class="ml-2" :loading="loading.productos" @click="listarProductos">
                    <v-icon left>mdi-refresh</v-icon>
                    Productos
                </v-btn>
            </v-toolbar>
        </div>

        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3>
                <b>
                    <v-icon>mdi-information-slab-circle-outline</v-icon>
                    Registra ventas rápidas, controla cantidades y descuenta stock automáticamente.
                </b>
            </h3>
        </v-card-subtitle>

        <v-card-text>
            <v-row>
                <v-col cols="12" lg="7">
                    <v-card outlined class="pos-panel">
                        <div class="pos-panel-header">
                            <div>
                                <span>Catálogo POS</span>
                                <h3>Selecciona productos disponibles</h3>
                            </div>
                            <v-chip color="primary" outlined>
                                {{ productosFiltrados.length }} productos
                            </v-chip>
                        </div>

                        <v-row dense>
                            <v-col cols="12" md="7">
                                <v-text-field
                                    v-model="buscarProducto"
                                    label="Buscar producto"
                                    prepend-inner-icon="mdi-magnify"
                                    outlined
                                    rounded
                                    dense
                                    clearable
                                    hide-details
                                />
                            </v-col>

                            <v-col cols="12" md="5">
                                <v-select
                                    v-model="filtroStock"
                                    :items="opcionesStock"
                                    label="Inventario"
                                    prepend-inner-icon="mdi-package-variant-closed"
                                    outlined
                                    rounded
                                    dense
                                    hide-details
                                />
                            </v-col>
                        </v-row>

                        <div v-if="loading.productos" class="text-center py-12">
                            <v-progress-circular indeterminate color="primary" />
                            <div class="mt-3 grey--text">Cargando productos...</div>
                        </div>

                        <v-row v-else class="mt-4">
                            <v-col v-for="producto in productosPaginados" :key="`pos-producto-${producto.id}`" cols="12" md="6">
                                <v-card outlined class="pos-product-card" :class="{ agotado: productoAgotado(producto) }">
                                    <div class="pos-product-image">
                                        <v-img v-if="resolverImagen(producto.ruta_imagen)" :src="resolverImagen(producto.ruta_imagen)" height="96" width="96" cover />
                                        <v-icon v-else color="primary" size="46">mdi-package-variant-closed</v-icon>
                                    </div>

                                    <div class="pos-product-copy">
                                        <span>{{ producto.categoria?.nombre || 'Sin categoría' }}</span>
                                        <h4>{{ producto.nombre }}</h4>
                                        <small>
                                            {{ producto.marca?.nombre || 'Sin marca' }}
                                            <template v-if="producto.bodega?.nombre">
                                                · {{ producto.bodega.nombre }}
                                            </template>
                                        </small>

                                        <div class="pos-product-bottom">
                                            <strong>{{ formatearPrecio(producto.precio) }}</strong>
                                            <v-chip x-small :color="productoAgotado(producto) ? 'error' : 'success'" text-color="white">
                                                {{ productoAgotado(producto) ? 'Agotado' : `${producto.stock} und` }}
                                            </v-chip>
                                        </div>
                                    </div>

                                    <v-btn
                                        fab
                                        small
                                        depressed
                                        color="primary"
                                        class="pos-add-btn"
                                        :disabled="productoAgotado(producto)"
                                        @click="agregarProducto(producto)"
                                    >
                                        <v-icon>{{ productoAgotado(producto) ? 'mdi-cart-off' : 'mdi-plus' }}</v-icon>
                                    </v-btn>
                                </v-card>
                            </v-col>

                            <v-col v-if="!productosFiltrados.length" cols="12">
                                <v-sheet outlined rounded="lg" class="pa-10 text-center">
                                    <v-icon size="58" color="grey lighten-1">mdi-database-search-outline</v-icon>
                                    <h3 class="mt-3">No hay productos para vender</h3>
                                    <p class="grey--text mb-0">Revisa el stock o cambia el filtro.</p>
                                </v-sheet>
                            </v-col>
                        </v-row>

                        <div v-if="totalPaginasProductos > 1" class="pos-pagination">
                            <span>Mostrando {{ productosPaginados.length }} de {{ productosFiltrados.length }}</span>
                            <v-pagination v-model="paginaProductos" :length="totalPaginasProductos" :total-visible="5" circle color="primary" />
                        </div>
                    </v-card>
                </v-col>

                <v-col cols="12" lg="5">
                    <v-card outlined class="pos-panel pos-sale-panel">
                        <div class="pos-panel-header">
                            <div>
                                <span>Venta actual</span>
                                <h3>Carrito POS</h3>
                            </div>
                            <v-chip color="success" text-color="white">
                                {{ totalItems }} item(s)
                            </v-chip>
                        </div>

                        <v-row dense>
                            <v-col cols="12" md="6">
                                <v-text-field v-model.trim="venta.cliente_nombre" label="Cliente" outlined rounded dense clearable />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model.trim="venta.cliente_documento" label="Documento" outlined rounded dense clearable />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field v-model.trim="venta.cliente_telefono" label="Teléfono" outlined rounded dense clearable />
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-select v-model="venta.metodo_pago" :items="metodosPago" label="Método de pago" outlined rounded dense />
                            </v-col>
                        </v-row>

                        <v-divider class="my-4" />

                        <div v-if="!carrito.length" class="pos-empty-cart">
                            <v-icon size="64" color="grey lighten-1">mdi-cart-outline</v-icon>
                            <h3>Venta vacía</h3>
                            <p>Agrega productos desde el catálogo POS.</p>
                        </div>

                        <div v-else class="pos-cart-list">
                            <div v-for="item in carrito" :key="`venta-item-${item.producto_id}`" class="pos-cart-item">
                                <div>
                                    <strong>{{ item.nombre }}</strong>
                                    <span>{{ item.precio_formateado }} · stock {{ item.stock }}</span>
                                </div>

                                <div class="pos-cart-actions">
                                    <v-btn x-small fab depressed color="#eef5ff" @click="disminuirCantidad(item)">
                                        <v-icon small>mdi-minus</v-icon>
                                    </v-btn>
                                    <b>{{ item.cantidad }}</b>
                                    <v-btn x-small fab depressed color="#eef5ff" :disabled="item.cantidad >= item.stock" @click="incrementarCantidad(item)">
                                        <v-icon small>mdi-plus</v-icon>
                                    </v-btn>
                                    <v-btn x-small icon color="error" @click="quitarProducto(item)">
                                        <v-icon small>mdi-trash-can-outline</v-icon>
                                    </v-btn>
                                </div>
                            </div>
                        </div>

                        <v-divider class="my-4" />

                        <v-row dense>
                            <v-col cols="12" md="6">
                                <v-text-field v-model.number="venta.descuento" label="Descuento" type="number" min="0" outlined rounded dense />
                            </v-col>
                            <v-col cols="12">
                                <v-textarea v-model.trim="venta.observaciones" label="Observaciones" rows="2" auto-grow outlined rounded dense clearable />
                            </v-col>
                        </v-row>

                        <div class="pos-totals">
                            <div>
                                <span>Subtotal</span>
                                <strong>{{ formatearPrecio(subtotal) }}</strong>
                            </div>
                            <div>
                                <span>Descuento</span>
                                <strong>{{ formatearPrecio(descuentoSeguro) }}</strong>
                            </div>
                            <div class="total">
                                <span>Total</span>
                                <strong>{{ formatearPrecio(total) }}</strong>
                            </div>
                        </div>

                        <v-btn
                            block
                            x-large
                            rounded
                            depressed
                            color="success"
                            class="mt-5"
                            :disabled="!carrito.length || !$can('ventas.crear')"
                            :loading="loading.guardando"
                            @click="registrarVenta"
                        >
                            <v-icon left>mdi-content-save-check</v-icon>
                            Registrar venta
                        </v-btn>
                    </v-card>
                </v-col>
            </v-row>

        </v-card-text>
    </v-card>
</template>

<script>
export default {
    layout: 'pos',

    data() {
        return {
            productos: [],
            carrito: [],
            buscarProducto: '',
            filtroStock: 'con_stock',
            paginaProductos: 1,
            productosPorPagina: 8,
            venta: {
                cliente_nombre: null,
                cliente_documento: null,
                cliente_telefono: null,
                metodo_pago: 'efectivo',
                descuento: 0,
                observaciones: null,
            },
            loading: {
                productos: false,
                guardando: false,
            },
            opcionesStock: [
                { text: 'Solo disponibles', value: 'con_stock' },
                { text: 'Todos', value: 'todos' },
                { text: 'Agotados', value: 'sin_stock' },
            ],
            metodosPago: [
                { text: 'Efectivo', value: 'efectivo' },
                { text: 'Transferencia', value: 'transferencia' },
                { text: 'Tarjeta', value: 'tarjeta' },
                { text: 'Mixto', value: 'mixto' },
                { text: 'Otro', value: 'otro' },
            ],
        }
    },

    computed: {
        productosFiltrados() {
            const termino = String(this.buscarProducto || '').toLowerCase().trim()

            return this.productos
                .filter(producto => {
                    if (this.filtroStock === 'con_stock') {
                        return Number(producto.stock || 0) > 0
                    }

                    if (this.filtroStock === 'sin_stock') {
                        return Number(producto.stock || 0) <= 0
                    }

                    return true
                })
                .filter(producto => {
                    if (!termino) {
                        return true
                    }

                    return [
                        producto.nombre,
                        producto.descripcion,
                        producto.unidad_medida,
                        producto.categoria?.nombre,
                        producto.marca?.nombre,
                        producto.bodega?.nombre,
                    ].some(valor => String(valor || '').toLowerCase().includes(termino))
                })
        },

        productosPaginados() {
            const inicio = (this.paginaProductos - 1) * this.productosPorPagina
            return this.productosFiltrados.slice(inicio, inicio + this.productosPorPagina)
        },

        totalPaginasProductos() {
            return Math.ceil(this.productosFiltrados.length / this.productosPorPagina)
        },

        subtotal() {
            return this.carrito.reduce((total, item) => total + (Number(item.precio_unitario || 0) * Number(item.cantidad || 0)), 0)
        },

        descuentoSeguro() {
            return Math.min(Number(this.venta.descuento || 0), this.subtotal)
        },

        total() {
            return Math.max(this.subtotal - this.descuentoSeguro, 0)
        },

        totalItems() {
            return this.carrito.reduce((total, item) => total + Number(item.cantidad || 0), 0)
        },
    },

    watch: {
        buscarProducto() {
            this.paginaProductos = 1
        },

        filtroStock() {
            this.paginaProductos = 1
        },
    },

    mounted() {
        this.listarProductos()
    },

    methods: {
        async listarProductos() {
            try {
                this.loading.productos = true
                const response = await this.$axios.post('/productos/listar', {
                    estado: true,
                })

                this.productos = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar los productos.')
            } finally {
                this.loading.productos = false
            }
        },

        productoAgotado(producto) {
            return !producto || Number(producto.stock || 0) <= 0
        },

        agregarProducto(producto) {
            if (this.productoAgotado(producto)) {
                this.$toast.warning('Este producto está agotado.')
                return
            }

            const item = this.carrito.find(productoCarrito => String(productoCarrito.producto_id) === String(producto.id))

            if (item) {
                if (item.cantidad >= Number(producto.stock || 0)) {
                    this.$toast.warning('Ya agregaste todo el stock disponible de este producto.')
                    return
                }

                item.cantidad += 1
                return
            }

            this.carrito.push({
                producto_id: producto.id,
                nombre: producto.nombre,
                precio_unitario: Number(producto.precio || 0),
                precio_formateado: this.formatearPrecio(producto.precio),
                cantidad: 1,
                stock: Number(producto.stock || 0),
            })
        },

        incrementarCantidad(item) {
            if (item.cantidad >= item.stock) {
                this.$toast.warning('No hay más stock disponible.')
                return
            }

            item.cantidad += 1
        },

        disminuirCantidad(item) {
            if (item.cantidad <= 1) {
                this.quitarProducto(item)
                return
            }

            item.cantidad -= 1
        },

        quitarProducto(item) {
            this.carrito = this.carrito.filter(productoCarrito => String(productoCarrito.producto_id) !== String(item.producto_id))
        },

        async registrarVenta() {
            if (!this.carrito.length) {
                return
            }

            try {
                this.loading.guardando = true

                await this.$axios.post('/ventas/registrar', {
                    ...this.venta,
                    descuento: this.descuentoSeguro,
                    productos: this.carrito.map(item => ({
                        producto_id: item.producto_id,
                        cantidad: item.cantidad,
                    })),
                })

                this.$toast.success('Venta registrada correctamente.')
                this.limpiarVenta()
                await this.listarProductos()
            } catch (error) {
                const mensaje = error.response?.data?.errors?.productos?.[0] ||
                    error.response?.data?.error ||
                    error.response?.data?.message ||
                    'No se pudo registrar la venta.'

                this.$toast.error(mensaje)
            } finally {
                this.loading.guardando = false
            }
        },

        limpiarVenta() {
            this.carrito = []
            this.venta = {
                cliente_nombre: null,
                cliente_documento: null,
                cliente_telefono: null,
                metodo_pago: 'efectivo',
                descuento: 0,
                observaciones: null,
            }
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

        formatearPrecio(precio) {
            const valor = Number(precio || 0)

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
.pos-page {
    background:
        radial-gradient(circle at 8% 8%, rgba(30, 136, 229, .12), transparent 28%),
        radial-gradient(circle at 94% 14%, rgba(25, 196, 99, .11), transparent 30%),
        #f4f8fb;
    min-height: 100vh;
    padding-bottom: 22px;
}

.pos-topbar {
    backdrop-filter: blur(18px);
    background: rgba(255, 255, 255, .92);
    border-bottom: 1px solid #dce8f5;
    box-shadow: 0 12px 28px rgba(6, 29, 67, .08);
    position: sticky;
    top: 0;
    z-index: 20;
}

.pos-page ::v-deep .v-card__subtitle {
    background:
        linear-gradient(110deg, #0f2c61 0%, #155f56 58%, #1e88e5 100%);
    color: #fff;
    margin: 22px 24px 0 !important;
    padding: 20px !important;
    border-radius: 20px;
}

.pos-page ::v-deep .v-card__subtitle .v-icon {
    color: #72edf0;
}

.pos-page ::v-deep .v-card__text {
    padding: 24px !important;
}

.pos-panel {
    background: rgba(255, 255, 255, .96) !important;
    border: 1px solid #dce8f5 !important;
    border-radius: 22px !important;
    box-shadow: 0 16px 38px rgba(6, 29, 67, .06) !important;
    padding: 22px;
}

.pos-panel-header {
    align-items: center;
    display: flex;
    gap: 16px;
    justify-content: space-between;
    margin-bottom: 18px;
}

.pos-panel-header span {
    color: #0d7880;
    font-size: 12px;
    font-weight: 950;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.pos-panel-header h3 {
    color: #17365d;
    font-size: 26px;
    font-weight: 950;
    margin: 3px 0 0;
}

.pos-product-card {
    align-items: center;
    border-radius: 18px !important;
    display: flex;
    gap: 14px;
    min-height: 136px;
    padding: 14px;
    position: relative;
}

.pos-product-card.agotado {
    background: #fff7f7;
}

.pos-product-image {
    align-items: center;
    background: #f4f9ff;
    border: 1px solid #dce8f5;
    border-radius: 18px;
    display: flex;
    flex: 0 0 96px;
    height: 96px;
    justify-content: center;
    overflow: hidden;
}

.pos-product-copy {
    flex: 1;
    min-width: 0;
    padding-right: 42px;
}

.pos-product-copy span,
.pos-product-copy small {
    color: #7487a3;
    display: block;
    font-size: 11px;
    font-weight: 900;
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: uppercase;
    white-space: nowrap;
}

.pos-product-copy h4 {
    color: #10213f;
    font-size: 16px;
    font-weight: 950;
    line-height: 1.2;
    margin: 6px 0;
}

.pos-product-bottom {
    align-items: center;
    display: flex;
    gap: 8px;
    justify-content: space-between;
    margin-top: 10px;
}

.pos-product-bottom strong {
    color: #0b5ed7;
    font-size: 16px;
}

.pos-add-btn {
    position: absolute;
    right: 12px;
    top: 12px;
}

.pos-add-btn.v-btn--disabled {
    background: #fff1f1 !important;
    color: #d32f2f !important;
    opacity: 1 !important;
}

.pos-sale-panel {
    position: sticky;
    top: 102px;
}

.pos-empty-cart {
    color: #7487a3;
    padding: 34px 12px;
    text-align: center;
}

.pos-empty-cart h3 {
    color: #17365d;
    margin: 12px 0 4px;
}

.pos-cart-list {
    display: grid;
    gap: 12px;
    max-height: 320px;
    overflow-y: auto;
    padding-right: 4px;
}

.pos-cart-item {
    align-items: center;
    background: linear-gradient(135deg, #f7fbff, #f2f7ff);
    border: 1px solid #dce8f5;
    border-radius: 16px;
    display: flex;
    gap: 12px;
    justify-content: space-between;
    padding: 12px;
}

.pos-cart-item strong,
.pos-cart-item span {
    display: block;
}

.pos-cart-item strong {
    color: #10213f;
}

.pos-cart-item span {
    color: #7487a3;
    font-size: 12px;
}

.pos-cart-actions {
    align-items: center;
    display: flex;
    gap: 8px;
}

.pos-totals {
    background: #f7fbff;
    border: 1px solid #dce8f5;
    border-radius: 18px;
    display: grid;
    gap: 10px;
    padding: 16px;
}

.pos-totals div {
    align-items: center;
    display: flex;
    justify-content: space-between;
}

.pos-totals span {
    color: #7487a3;
    font-weight: 800;
}

.pos-totals strong {
    color: #17365d;
    font-size: 18px;
}

.pos-totals .total {
    border-top: 1px solid #dce8f5;
    padding-top: 10px;
}

.pos-totals .total strong {
    color: #0b5ed7;
    font-size: 28px;
    font-weight: 950;
}

.pos-pagination {
    align-items: center;
    display: flex;
    gap: 16px;
    justify-content: space-between;
    margin-top: 18px;
}

@media (max-width: 960px) {
    .pos-sale-panel {
        position: static;
    }

    .pos-panel-header,
    .pos-cart-item,
    .pos-pagination {
        align-items: stretch;
        flex-direction: column;
    }

    .pos-page ::v-deep .v-card__subtitle,
    .pos-page ::v-deep .v-card__text {
        margin-left: 12px !important;
        margin-right: 12px !important;
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
}
</style>
