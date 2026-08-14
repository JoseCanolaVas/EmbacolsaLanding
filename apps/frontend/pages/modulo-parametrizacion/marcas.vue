<template>
    <v-card flat class="mb-4">
        <v-card-title>
            <v-toolbar flat>
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="warning" class="mr-3" size="30">
                            mdi-tag-multiple-outline
                        </v-icon>
                        <span>Marcas</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </v-card-title>

        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3>
                <b class="justify-center">
                    <v-icon>mdi-information-slab-circle-outline</v-icon>
                    Administra las marcas disponibles para el catálogo.
                </b>
            </h3>
        </v-card-subtitle>

        <v-card-text>
            <v-row align="center">
                <v-col cols="12" md="6">
                    <v-text-field v-model="buscar" label="Buscar" outlined dense clearable rounded
                        prepend-inner-icon="mdi-magnify" />
                </v-col>

                <v-col cols="12" md="6" class="text-md-right">
                    <v-btn v-if="$can('marcas.crear')" color="primary" rounded @click="abrirModalMarca()">
                        <v-icon left>mdi-plus</v-icon>
                        Crear marca
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-text class="mt-5">
            <v-data-table :items="marcas" :headers="headersMarcas" :loading="loading.marcas" disable-pagination
                hide-default-footer :search="buscar">
                <template v-slot:item.estado="{ item }">
                    <v-chip :color="item.estado ? 'green' : 'red'" dark small>
                        {{ item.estado ? 'Activo' : 'Inactivo' }}
                    </v-chip>
                </template>

                <template v-slot:item.acciones="{ item }">
                    <v-icon v-if="$can('marcas.editar')" color="teal" @click="abrirModalMarca(item)">
                        mdi-pencil
                    </v-icon>
                </template>
            </v-data-table>
        </v-card-text>

        <v-dialog v-model="modalMarca" max-width="500px">
            <ModalMarcas :marcaSeleccionada="marcaSeleccionada" @cerrarModal="modalMarca = false"
                @recargar="listarMarcas" />
        </v-dialog>
    </v-card>
</template>

<script>
import ModalMarcas from '~/components/modulo-parametrizacion/marcas/modalMarcas.vue'

export default {
    layout: 'parametrizacion',

    components: {
        ModalMarcas,
    },

    data() {
        return {
            buscar: '',
            marcas: [],
            marcaSeleccionada: {},
            modalMarca: false,
            loading: {
                marcas: false,
            },
            headersMarcas: [
                { text: 'Nombre', value: 'nombre', align: 'center' },
                { text: 'Descripción', value: 'descripcion', align: 'center' },
                { text: 'Estado', value: 'estado', align: 'center' },
                { text: 'Acciones', value: 'acciones', sortable: false, align: 'center' },
            ],
        }
    },

    mounted() {
        this.listarMarcas()
    },

    methods: {
        async listarMarcas() {
            try {
                this.loading.marcas = true
                const response = await this.$axios.get('/marcas/listar')
                this.marcas = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar las marcas.')
            } finally {
                this.loading.marcas = false
            }
        },

        abrirModalMarca(item = {}) {
            this.marcaSeleccionada = { ...item }
            this.modalMarca = true
        },
    },
}
</script>
