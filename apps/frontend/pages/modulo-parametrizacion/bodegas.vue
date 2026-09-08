<template>
    <v-card flat class="mb-4">
        <v-card-title>
            <v-toolbar flat>
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="warning" class="mr-3" size="30">
                            mdi-warehouse
                        </v-icon>
                        <span>Bodegas</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </v-card-title>

        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3>
                <b class="justify-center">
                    <v-icon>mdi-information-slab-circle-outline</v-icon>
                    Administra las bodegas donde se ubica el stock de productos.
                </b>
            </h3>
        </v-card-subtitle>

        <v-card-text>
            <v-row align="center">
                <v-col cols="12" md="6">
                    <v-text-field v-model="buscar" label="Buscar bodega" outlined dense clearable rounded
                        prepend-inner-icon="mdi-magnify" />
                </v-col>

                <v-col cols="12" md="6" class="text-md-right">
                    <v-btn v-if="$can('bodegas.crear')" color="primary" rounded @click="abrirModalBodega()">
                        <v-icon left>mdi-plus</v-icon>
                        Crear bodega
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-text class="mt-5">
            <v-data-table :items="bodegas" :headers="headersBodegas" :loading="loading.bodegas" disable-pagination
                hide-default-footer :search="buscar">
                <template v-slot:item.estado="{ item }">
                    <v-chip :color="item.estado ? 'green' : 'red'" dark small>
                        {{ item.estado ? 'Activo' : 'Inactivo' }}
                    </v-chip>
                </template>

                <template v-slot:item.acciones="{ item }">
                    <v-icon v-if="$can('bodegas.editar')" color="teal" @click="abrirModalBodega(item)">
                        mdi-pencil
                    </v-icon>
                </template>
            </v-data-table>
        </v-card-text>

        <v-dialog v-model="modalBodega" max-width="560px">
            <ModalBodegas :bodegaSeleccionada="bodegaSeleccionada" @cerrarModal="modalBodega = false"
                @recargar="listarBodegas" />
        </v-dialog>
    </v-card>
</template>

<script>
import ModalBodegas from '~/components/modulo-parametrizacion/bodegas/modalBodegas.vue'

export default {
    layout: 'parametrizacion',

    components: {
        ModalBodegas,
    },

    data() {
        return {
            buscar: '',
            bodegas: [],
            bodegaSeleccionada: {},
            modalBodega: false,
            loading: {
                bodegas: false,
            },
            headersBodegas: [
                { text: 'Nombre', value: 'nombre', align: 'center' },
                { text: 'Código', value: 'codigo', align: 'center' },
                { text: 'Ubicación', value: 'ubicacion', align: 'center' },
                { text: 'Descripción', value: 'descripcion', align: 'center' },
                { text: 'Estado', value: 'estado', align: 'center' },
                { text: 'Acciones', value: 'acciones', sortable: false, align: 'center' },
            ],
        }
    },

    mounted() {
        this.listarBodegas()
    },

    methods: {
        async listarBodegas() {
            try {
                this.loading.bodegas = true
                const response = await this.$axios.get('/bodegas/listar')
                this.bodegas = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar las bodegas.')
            } finally {
                this.loading.bodegas = false
            }
        },

        abrirModalBodega(item = {}) {
            this.bodegaSeleccionada = { ...item }
            this.modalBodega = true
        },
    },
}
</script>
