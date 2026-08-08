<template>
    <v-card class="detalle-producto-card">
        <v-card-title class="detalle-producto-header">
            <div>
                <div class="caption text-uppercase font-weight-bold grey--text text--darken-1">
                    Detalle del producto
                </div>
                <div class="text-h6 font-weight-bold">
                    {{ producto.nombre || 'Producto sin nombre' }}
                </div>
            </div>

            <v-spacer />

            <v-btn icon small @click="cerrarModal">
                <v-icon>mdi-close</v-icon>
            </v-btn>
        </v-card-title>

        <v-divider />

        <v-card-text class="pa-0">
            <v-img v-if="imagenProducto" :src="imagenProducto" class="detalle-producto-imagen" height="280" contain>
                <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="primary" />
                    </v-row>
                </template>
            </v-img>

            <div v-else class="detalle-producto-sin-imagen">
                <v-icon size="74" color="grey lighten-1">
                    mdi-image-off-outline
                </v-icon>
                <div class="mt-2 grey--text">
                    Este producto no tiene imagen
                </div>
            </div>

            <v-divider />

            <div class="pa-5">
                <v-row dense>
                    <v-col cols="12" sm="6">
                        <div class="detalle-label">
                            Categoría
                        </div>
                        <div class="detalle-valor">
                            {{ categoriaNombre }}
                        </div>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <div class="detalle-label">
                            Nombre
                        </div>
                        <div class="detalle-valor">
                            {{ producto.nombre || 'Sin nombre' }}
                        </div>
                    </v-col>

                    <v-col cols="12">
                        <div class="detalle-label">
                            Descripción
                        </div>
                        <div class="detalle-valor detalle-descripcion">
                            {{ producto.descripcion || 'Sin descripcion por ahora' }}
                        </div>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <div class="detalle-label">
                            Unidad de medida
                        </div>
                        <div class="detalle-valor">
                            {{ producto.unidad_medida || 'Sin unidad' }}
                        </div>
                    </v-col>

                    <v-col cols="12" sm="6">
                        <div class="detalle-label">
                            Estado
                        </div>
                        <v-chip small :color="estadoActivo ? 'primary' : 'grey'" dark>
                            <v-icon left small>
                                {{ estadoActivo ? 'mdi-check-circle-outline' : 'mdi-close-circle-outline' }}
                            </v-icon>
                            {{ estadoTexto }}
                        </v-chip>
                    </v-col>

                    <v-col v-if="tienePrecio" cols="12">
                        <div class="detalle-label">
                            Precio
                        </div>
                        <div class="detalle-precio">
                            {{ precioTexto }}
                        </div>
                    </v-col>
                </v-row>
            </div>
        </v-card-text>

        <v-divider />

        <v-card-actions class="pa-4">
            <v-spacer />
            <v-btn rounded color="primary" outlined @click="cerrarModal">
                Cerrar
                <v-icon right>
                    mdi-close
                </v-icon>
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    props: {
        productoSeleccionado: {
            type: Object,
            default: () => ({}),
        },
    },

    computed: {
        producto() {
            return this.productoSeleccionado || {}
        },

        categoriaNombre() {
            return this.producto.categoria?.nombre || 'Sin categoria'
        },

        estadoActivo() {
            return this.producto.estado === true ||
                this.producto.estado === 1 ||
                this.producto.estado === '1'
        },

        estadoTexto() {
            return this.estadoActivo ? 'Activo' : 'Inactivo'
        },

        tienePrecio() {
            return this.producto.precio !== null &&
                this.producto.precio !== undefined &&
                this.producto.precio !== ''
        },

        precioTexto() {
            const precio = Number(this.producto.precio)

            if (Number.isNaN(precio)) {
                return this.producto.precio
            }

            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0,
            }).format(precio)
        },

        imagenProducto() {
            const rutaImagen = this.producto.ruta_imagen

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
    },

    methods: {
        cerrarModal() {
            this.$emit('cerrar')
        },
    },
}
</script>

<style scoped>
.detalle-producto-card {
    overflow: hidden;
}

.detalle-producto-header {
    gap: 12px;
}

.detalle-producto-imagen {
    background: #f5f7fb;
}

.detalle-producto-sin-imagen {
    align-items: center;
    background: #f5f7fb;
    display: flex;
    flex-direction: column;
    height: 260px;
    justify-content: center;
}

.detalle-label {
    color: #6b7280;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 4px;
    text-transform: uppercase;
}

.detalle-valor {
    color: #1f2937;
    font-size: 15px;
    font-weight: 600;
    line-height: 1.45;
    overflow-wrap: anywhere;
}

.detalle-descripcion {
    font-weight: 400;
    white-space: pre-line;
}

.detalle-precio {
    color: #0f766e;
    font-size: 24px;
    font-weight: 800;
}

@media (max-width: 600px) {

    .detalle-producto-imagen,
    .detalle-producto-sin-imagen {
        height: 220px !important;
    }
}
</style>
</script>
