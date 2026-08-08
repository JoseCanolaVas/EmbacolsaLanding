<template>
    <v-card flat class="mb-4">
        <v-card-title>
            <v-toolbar flat>
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="warning" class="mr-3" size="30">
                            mdi-folder-outline
                        </v-icon>
                        <span>Categorías</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </v-card-title>


        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3><b class="justify-center"> <v-icon>mdi-information-slab-circle-outline</v-icon> Panel de parametrizacion
                    de
                    Categorias, desde aca se podran gestionar informacion de las categorias disponibles para los
                    productos .</b></h3>
        </v-card-subtitle>

        <v-card-text>
            <v-row>
                <v-col cols="6" md="6" sm="6">
                    <v-text-field v-model="buscar" label="Buscar" outlined dense clearable rounded></v-text-field>
                </v-col>

                <v-col cols="6" md="6" sm="6">
                    <v-btn color="primary" @click="abrirModalCrearCategoria()" rounded>
                        <v-icon left>mdi-plus</v-icon>
                        Crear Categoría
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-text class="mt-5">

            <v-data-table :items="categorias" :headers="headersCategorias" :loading="loading.categorias" disable-pagination hide-default-footer :search="buscar" >

                <template v-slot:[`item.estado`]="{ item }">
                    <v-chip :color="item.estado === true ? 'green' : 'red'" dark>
                        {{ item.estado === true ? 'Activo' : 'Inactivo' }}
                    </v-chip>
                </template>

                <template v-slot:[`item.acciones`]="{ item }">
                    <v-icon color="teal" @click="abrirModalCrearCategoria(item)">
                        mdi-pencil
                    </v-icon>
                </template>
            </v-data-table>

        </v-card-text>


        <v-dialog v-model="modalCategoria" max-width="500px">
            <modalCategorias :categoriaSeleccionada="categoriaSeleccionada" @cerrarModal="modalCategoria = false"
                @recargar="listarCategorias" />
        </v-dialog>

    </v-card>
</template>

<script>
import modalCategorias from '~/components/modulo-parametrizacion/categorias/modalCategorias.vue'
export default {
    layout: 'parametrizacion',

    components: {
        modalCategorias,
    },

    data() {
        return {
            buscar: '',
            modalCategoria: false,
            categorias: [],
            loading: {
                categorias: false,
            },
            headersCategorias: [
                { text: 'Nombre', value: 'nombre' , align: 'center'},
                { text: 'Descripción', value: 'descripcion' , align: 'center'},
                { text: 'Estado', value: 'estado' , align: 'center'},
                { text: 'Acciones', value: 'acciones', sortable: false , align: 'center'},
            ],
            categoriaSeleccionada: {},
        };
    },

    mounted() {
        this.listarCategorias();
    },

    methods: {
        abrirModalCrearCategoria(item = {}) {
            this.categoriaSeleccionada = { ...item };
            this.modalCategoria = true;
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
        }
    }
};
</script>