<template>
    <v-card flat class="sales-page">
        <v-card-title>
            <v-toolbar flat>
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="success" class="mr-3" size="30">mdi-chart-line</v-icon>
                        <span>Ventas</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical />
                <v-spacer />
                <v-btn rounded color="primary" depressed to="/modulo-parametrizacion/pos">
                    <v-icon left>mdi-cash-register</v-icon>
                    Abrir POS
                </v-btn>
            </v-toolbar>
        </v-card-title>

        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3>
                <b>
                    <v-icon>mdi-information-slab-circle-outline</v-icon>
                    Consulta ventas, total vendido por mes, ventas del día y productos más vendidos.
                </b>
            </h3>
        </v-card-subtitle>

        <v-card-text>
            <v-card outlined class="sales-filter-card mb-6">
                <v-row dense align="center">
                    <v-col cols="12" md="3">
                        <v-text-field v-model="filtros.buscar" label="Buscar venta o cliente"
                            prepend-inner-icon="mdi-magnify" outlined dense rounded clearable hide-details
                            @keyup.enter="filtrar" @click:clear="filtrar" />
                    </v-col>

                    <v-col cols="12" md="2">
                        <v-select v-model="filtros.metodo_pago" :items="metodosPago" label="Método" outlined dense
                            rounded clearable hide-details @change="filtrar" />
                    </v-col>

                    <v-col cols="12" md="2">
                        <v-menu v-model="menus.fechaInicio" :close-on-content-click="false" offset-y min-width="auto">
                            <template #activator="{ on, attrs }">
                                <v-text-field v-model="filtros.fecha_inicio" label="Desde"
                                    prepend-inner-icon="mdi-calendar" outlined dense rounded readonly clearable
                                    hide-details v-bind="attrs" v-on="on" @click:clear="filtrar" />
                            </template>
                            <v-date-picker v-model="filtros.fecha_inicio"
                                @input="menus.fechaInicio = false; filtrar()" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="2">
                        <v-menu v-model="menus.fechaFin" :close-on-content-click="false" offset-y min-width="auto">
                            <template #activator="{ on, attrs }">
                                <v-text-field v-model="filtros.fecha_fin" label="Hasta"
                                    prepend-inner-icon="mdi-calendar-end" outlined dense rounded readonly clearable
                                    hide-details v-bind="attrs" v-on="on" @click:clear="filtrar" />
                            </template>
                            <v-date-picker v-model="filtros.fecha_fin" @input="menus.fechaFin = false; filtrar()" />
                        </v-menu>
                    </v-col>

                    <v-col cols="12" md="3" class="text-md-right">
                        <v-btn rounded outlined color="error" class="mr-2" @click="limpiarFiltros">
                            <v-icon left>mdi-broom</v-icon>
                            Limpiar
                        </v-btn>
                        <v-btn rounded color="primary" depressed :loading="loading.ventas || loading.resumen"
                            @click="cargarDatos">
                            <v-icon left>mdi-refresh</v-icon>
                            Consultar
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card>

            <v-row>
                <v-col v-for="card in tarjetasResumen" :key="card.title" cols="12" sm="6" lg="3">
                    <v-card outlined class="sales-stat-card">
                        <div class="sales-stat-icon" :class="card.class">
                            <v-icon color="white">{{ card.icon }}</v-icon>
                        </div>
                        <span>{{ card.title }}</span>
                        <strong>{{ card.value }}</strong>
                        <small>{{ card.help }}</small>
                    </v-card>
                </v-col>
            </v-row>

            <v-row class="mt-2">
                <v-col cols="12" lg="7">
                    <v-card outlined class="sales-panel">
                        <div class="sales-panel-header">
                            <div>
                                <span>Comportamiento</span>
                                <h3>Total vendido por mes</h3>
                            </div>
                        </div>

                        <div v-if="!resumen.por_mes.length" class="sales-empty">
                            <v-icon size="58" color="grey lighten-1">mdi-chart-bar</v-icon>
                            <p>No hay ventas para graficar todavía.</p>
                        </div>

                        <div v-else class="month-bars">
                            <div v-for="mes in resumen.por_mes" :key="mes.mes" class="month-bar-row">
                                <div class="month-label">
                                    <strong>{{ mes.mes }}</strong>
                                    <span>{{ mes.ventas }} venta(s)</span>
                                </div>
                                <div class="month-bar-track">
                                    <div class="month-bar-fill" :style="{ width: porcentajeMes(mes.total) + '%' }" />
                                </div>
                                <strong>{{ formatearPrecio(mes.total) }}</strong>
                            </div>
                        </div>
                    </v-card>
                </v-col>

                <v-col cols="12" lg="5">
                    <v-card outlined class="sales-panel">
                        <div class="sales-panel-header">
                            <div>
                                <span>Top</span>
                                <h3>Productos más vendidos</h3>
                            </div>
                        </div>

                        <div v-if="!resumen.productos_mas_vendidos.length" class="sales-empty">
                            <v-icon size="58" color="grey lighten-1">mdi-trophy-outline</v-icon>
                            <p>Aún no hay productos vendidos.</p>
                        </div>

                        <div v-else class="top-products">
                            <div v-for="(producto, index) in resumen.productos_mas_vendidos"
                                :key="producto.producto_id" class="top-product-item">
                                <div class="top-index">{{ index + 1 }}</div>
                                <div>
                                    <strong>{{ producto.producto_nombre }}</strong>
                                    <span>{{ producto.cantidad }} unidad(es)</span>
                                </div>
                                <b>{{ formatearPrecio(producto.total) }}</b>
                            </div>
                        </div>
                    </v-card>
                </v-col>
            </v-row>

            <v-card outlined class="sales-panel mt-6">
                <div class="sales-panel-header">
                    <div>
                        <span>Histórico</span>
                        <h3>Ventas registradas</h3>
                    </div>
                    <v-chip color="primary" outlined>{{ paginacion.totalRegistros }} registro(s)</v-chip>
                </div>

                <v-data-table :headers="headers" :items="ventas" :loading="loading.ventas" disable-pagination
                    hide-default-footer>
                    <template v-slot:item.cliente_nombre="{ item }">
                        <div>
                            <strong>{{ item.cliente_nombre || 'Consumidor final' }}</strong>
                            <small class="d-block grey--text">
                                {{ item.cliente_documento || item.cliente_telefono || 'Sin datos' }}
                            </small>
                        </div>
                    </template>

                    <template v-slot:item.detalles="{ item }">
                        <v-chip small color="primary" outlined>{{ item.detalles?.length || 0 }} producto(s)</v-chip>
                    </template>

                    <template v-slot:item.total="{ item }">
                        <strong class="primary--text">{{ formatearPrecio(item.total) }}</strong>
                    </template>

                    <template v-slot:item.created_at="{ item }">
                        {{ formatearFecha(item.created_at) }}
                    </template>

                    <template v-slot:item.acciones="{ item }">
                        <v-btn icon color="primary" @click="abrirDetalle(item)">
                            <v-icon>mdi-eye-outline</v-icon>
                        </v-btn>
                    </template>
                </v-data-table>

                <div v-if="paginacion.total > 1" class="sales-pagination">
                    <v-pagination v-model="paginacion.pagina" :length="paginacion.total" :total-visible="7" circle
                        color="primary" @input="listarVentas" />
                    <v-select v-model="paginacion.cantidadRegistros" :items="[10, 20, 50, 100]" label="Registros"
                        outlined dense rounded hide-details @change="filtrar" />
                </div>
            </v-card>
        </v-card-text>

        <v-dialog v-model="modalDetalle" max-width="760">
            <v-card v-if="ventaSeleccionada" class="rounded-lg">
                <v-card-title>
                    <v-icon color="primary" class="mr-2">mdi-receipt-text-outline</v-icon>
                    Venta {{ ventaSeleccionada.codigo }}
                    <v-spacer />
                    <v-btn icon @click="modalDetalle = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-divider />

                <v-card-text class="pt-5">
                    <v-row>
                        <v-col cols="12" md="6">
                            <strong>Cliente</strong>
                            <p>{{ ventaSeleccionada.cliente_nombre || 'Consumidor final' }}</p>
                        </v-col>
                        <v-col cols="12" md="3">
                            <strong>Método</strong>
                            <p>{{ ventaSeleccionada.metodo_pago }}</p>
                        </v-col>
                        <v-col cols="12" md="3">
                            <strong>Fecha</strong>
                            <p>{{ formatearFecha(ventaSeleccionada.created_at) }}</p>
                        </v-col>
                    </v-row>

                    <v-simple-table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="detalle in ventaSeleccionada.detalles || []" :key="detalle.id">
                                <td>{{ detalle.producto_nombre }}</td>
                                <td>{{ detalle.cantidad }}</td>
                                <td>{{ formatearPrecio(detalle.precio_unitario) }}</td>
                                <td>{{ formatearPrecio(detalle.subtotal) }}</td>
                            </tr>
                        </tbody>
                    </v-simple-table>

                    <div class="detail-total">
                        <span>Total</span>
                        <strong>{{ formatearPrecio(ventaSeleccionada.total) }}</strong>
                    </div>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
export default {
    layout: 'parametrizacion',

    data() {
        return {
            ventas: [],
            resumen: {
                total_vendido: 0,
                total_vendido_mes: 0,
                total_vendido_hoy: 0,
                cantidad_ventas: 0,
                cantidad_ventas_mes: 0,
                cantidad_ventas_hoy: 0,
                unidades_vendidas: 0,
                ticket_promedio: 0,
                por_mes: [],
                por_metodo_pago: [],
                productos_mas_vendidos: [],
            },
            filtros: {
                buscar: null,
                metodo_pago: null,
                fecha_inicio: null,
                fecha_fin: null,
            },
            menus: {
                fechaInicio: false,
                fechaFin: false,
            },
            loading: {
                ventas: false,
                resumen: false,
            },
            modalDetalle: false,
            ventaSeleccionada: null,
            metodosPago: [
                { text: 'Efectivo', value: 'efectivo' },
                { text: 'Transferencia', value: 'transferencia' },
                { text: 'Tarjeta', value: 'tarjeta' },
                { text: 'Mixto', value: 'mixto' },
                { text: 'Otro', value: 'otro' },
            ],
            headers: [
                { text: 'Código', value: 'codigo' },
                { text: 'Cliente', value: 'cliente_nombre' },
                { text: 'Productos', value: 'detalles', sortable: false },
                { text: 'Pago', value: 'metodo_pago' },
                { text: 'Total', value: 'total' },
                { text: 'Fecha', value: 'created_at' },
                { text: 'Acciones', value: 'acciones', sortable: false },
            ],
            paginacion: {
                pagina: 1,
                cantidadRegistros: 10,
                total: 0,
                totalRegistros: 0,
            },
        }
    },

    computed: {
        tarjetasResumen() {
            return [
                {
                    title: 'Vendido este mes',
                    value: this.formatearPrecio(this.resumen.total_vendido_mes),
                    help: `${this.resumen.cantidad_ventas_mes} venta(s) este mes`,
                    icon: 'mdi-calendar-month-outline',
                    class: 'blue',
                },
                {
                    title: 'Vendido hoy',
                    value: this.formatearPrecio(this.resumen.total_vendido_hoy),
                    help: `${this.resumen.cantidad_ventas_hoy} venta(s) hoy`,
                    icon: 'mdi-calendar-today-outline',
                    class: 'green',
                },
                {
                    title: 'Total vendido',
                    value: this.formatearPrecio(this.resumen.total_vendido),
                    help: `${this.resumen.cantidad_ventas} venta(s) registradas`,
                    icon: 'mdi-cash-multiple',
                    class: 'purple',
                },
                {
                    title: 'Ticket promedio',
                    value: this.formatearPrecio(this.resumen.ticket_promedio),
                    help: `${this.resumen.unidades_vendidas} unidad(es) vendidas`,
                    icon: 'mdi-receipt-text-check-outline',
                    class: 'orange',
                },
            ]
        },

        mayorVentaMes() {
            return Math.max(...this.resumen.por_mes.map(item => Number(item.total || 0)), 0)
        },
    },

    mounted() {
        this.cargarDatos()
    },

    methods: {
        async cargarDatos() {
            await Promise.all([
                this.listarVentas(),
                this.cargarResumen(),
            ])
        },

        async listarVentas() {
            try {
                this.loading.ventas = true
                const response = await this.$axios.post('/ventas/listar', {
                    ...this.filtros,
                    paginacion: this.paginacion,
                })

                this.ventas = response.data?.data ?? response.data ?? []
                this.paginacion.total = response.data?.last_page ?? 1
                this.paginacion.totalRegistros = response.data?.total ?? this.ventas.length
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar las ventas.')
            } finally {
                this.loading.ventas = false
            }
        },

        async cargarResumen() {
            try {
                this.loading.resumen = true
                const response = await this.$axios.post('/ventas/resumen', this.filtros)
                this.resumen = {
                    ...this.resumen,
                    ...(response.data || {}),
                }
            } catch (error) {
                this.$toast.error('Ocurrió un error al consultar el resumen de ventas.')
            } finally {
                this.loading.resumen = false
            }
        },

        filtrar() {
            this.paginacion.pagina = 1
            this.cargarDatos()
        },

        limpiarFiltros() {
            this.filtros = {
                buscar: null,
                metodo_pago: null,
                fecha_inicio: null,
                fecha_fin: null,
            }
            this.filtrar()
        },

        porcentajeMes(total) {
            if (!this.mayorVentaMes) {
                return 0
            }

            return Math.max((Number(total || 0) / this.mayorVentaMes) * 100, 5)
        },

        abrirDetalle(venta) {
            this.ventaSeleccionada = venta
            this.modalDetalle = true
        },

        formatearPrecio(precio) {
            const valor = Number(precio || 0)

            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
            }).format(valor)
        },

        formatearFecha(fecha) {
            if (!fecha) {
                return ''
            }

            return new Intl.DateTimeFormat('es-CO', {
                dateStyle: 'short',
                timeStyle: 'short',
            }).format(new Date(fecha))
        },
    },
}
</script>

<style scoped>
.sales-page {
    background: transparent;
}

.sales-filter-card,
.sales-panel,
.sales-stat-card {
    border: 1px solid #dce8f5 !important;
    border-radius: 22px !important;
    box-shadow: 0 16px 38px rgba(6, 29, 67, .06) !important;
}

.sales-filter-card,
.sales-panel {
    padding: 22px;
}

.sales-stat-card {
    min-height: 174px;
    padding: 22px;
}

.sales-stat-icon {
    align-items: center;
    border-radius: 18px;
    display: flex;
    height: 48px;
    justify-content: center;
    margin-bottom: 16px;
    width: 48px;
}

.sales-stat-icon.blue {
    background: linear-gradient(135deg, #0b5ed7, #2da8ff);
}

.sales-stat-icon.green {
    background: linear-gradient(135deg, #0c7a4d, #19c463);
}

.sales-stat-icon.purple {
    background: linear-gradient(135deg, #5528b5, #8d5cff);
}

.sales-stat-icon.orange {
    background: linear-gradient(135deg, #e67800, #ffb020);
}

.sales-stat-card span,
.sales-stat-card small {
    color: #7487a3;
    display: block;
    font-weight: 800;
}

.sales-stat-card strong {
    color: #17365d;
    display: block;
    font-size: 28px;
    font-weight: 950;
    line-height: 1.1;
    margin: 8px 0;
}

.sales-panel-header {
    align-items: center;
    display: flex;
    gap: 16px;
    justify-content: space-between;
    margin-bottom: 18px;
}

.sales-panel-header span {
    color: #0d7880;
    font-size: 12px;
    font-weight: 950;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.sales-panel-header h3 {
    color: #17365d;
    font-size: 24px;
    font-weight: 950;
    margin: 3px 0 0;
}

.sales-empty {
    color: #7487a3;
    padding: 46px 12px;
    text-align: center;
}

.month-bars,
.top-products {
    display: grid;
    gap: 14px;
}

.month-bar-row {
    align-items: center;
    display: grid;
    gap: 14px;
    grid-template-columns: 120px 1fr 140px;
}

.month-label strong,
.month-label span {
    display: block;
}

.month-label span,
.top-product-item span {
    color: #7487a3;
    font-size: 12px;
}

.month-bar-track {
    background: #eef5ff;
    border-radius: 999px;
    height: 14px;
    overflow: hidden;
}

.month-bar-fill {
    background: linear-gradient(90deg, #0f2c61, #1e88e5, #19c463);
    border-radius: inherit;
    height: 100%;
}

.top-product-item {
    align-items: center;
    background: linear-gradient(135deg, #f7fbff, #f2f7ff);
    border: 1px solid #dce8f5;
    border-radius: 16px;
    display: grid;
    gap: 12px;
    grid-template-columns: 38px 1fr auto;
    padding: 13px;
}

.top-index {
    align-items: center;
    background: #0b5ed7;
    border-radius: 14px;
    color: #fff;
    display: flex;
    font-weight: 950;
    height: 38px;
    justify-content: center;
    width: 38px;
}

.sales-pagination {
    align-items: center;
    display: flex;
    gap: 16px;
    justify-content: space-between;
    margin-top: 18px;
}

.sales-pagination .v-input {
    max-width: 160px;
}

.detail-total {
    align-items: center;
    background: #f7fbff;
    border: 1px solid #dce8f5;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    margin-top: 18px;
    padding: 16px;
}

.detail-total span {
    color: #7487a3;
    font-weight: 800;
}

.detail-total strong {
    color: #0b5ed7;
    font-size: 26px;
    font-weight: 950;
}

@media (max-width: 960px) {
    .month-bar-row {
        grid-template-columns: 1fr;
    }

    .sales-panel-header,
    .sales-pagination {
        align-items: stretch;
        flex-direction: column;
    }

    .sales-pagination .v-input {
        max-width: none;
    }
}
</style>
