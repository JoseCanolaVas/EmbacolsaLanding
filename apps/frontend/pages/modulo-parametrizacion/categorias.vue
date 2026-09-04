<template>
    <v-card flat class="categories-page">
        <div class="page-heading">
            <div>
                <span>Catálogo</span>
                <h2>Categorías</h2>
                <p>
                    Organiza las líneas visibles del catálogo y controla qué grupos aparecen en el sitio público.
                </p>
            </div>

            <v-btn v-if="$can('categorias.crear')" rounded depressed color="primary" @click="abrirModalCrearCategoria()">
                <v-icon left>mdi-folder-plus-outline</v-icon>
                Crear categoría
            </v-btn>
        </div>

        <v-card outlined class="panel-card table-card">
            <v-card-title class="panel-card-title">
                <div class="panel-title-icon">
                    <v-icon color="primary">mdi-format-list-bulleted-type</v-icon>
                </div>
                <div>
                    <strong>Categorías configuradas</strong>
                    <p>Busca, revisa estado y edita la información que alimenta productos.</p>
                </div>

                <v-spacer />

                <v-text-field v-model="buscar" dense outlined rounded clearable hide-details
                    prepend-inner-icon="mdi-magnify" label="Buscar categoría" class="panel-search" />
            </v-card-title>

            <v-data-table :items="categorias" :headers="headersCategorias" :loading="loading.categorias"
                disable-pagination hide-default-footer :search="buscar" class="panel-table">
                <template v-slot:[`item.estado`]="{ item }">
                    <v-chip small :color="item.estado === true ? 'green' : 'grey'" dark>
                        {{ item.estado === true ? 'Activo' : 'Inactivo' }}
                    </v-chip>
                </template>

                <template v-slot:[`item.acciones`]="{ item }">
                    <v-btn v-if="$can('categorias.editar')" icon color="primary" @click="abrirModalCrearCategoria(item)">
                        <v-icon>mdi-pencil-outline</v-icon>
                    </v-btn>
                </template>
            </v-data-table>
        </v-card>

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
                { text: 'Nombre', value: 'nombre' },
                { text: 'Descripción', value: 'descripcion' },
                { text: 'Estado', value: 'estado', align: 'center' },
                { text: 'Acciones', value: 'acciones', sortable: false, align: 'center' },
            ],
            categoriaSeleccionada: {},
        }
    },

    mounted() {
        this.listarCategorias()
    },

    methods: {
        abrirModalCrearCategoria(item = {}) {
            this.categoriaSeleccionada = { ...item }
            this.modalCategoria = true
        },

        async listarCategorias() {
            try {
                this.loading.categorias = true
                const response = await this.$axios.get('/categorias/listar')
                this.categorias = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar las categorías. Por favor, inténtelo de nuevo.')
            } finally {
                this.loading.categorias = false
            }
        },
    },
}
</script>

<style scoped>
.categories-page {
    background: transparent;
}

.page-heading,
.panel-card {
    border-radius: 16px !important;
}

.page-heading {
    align-items: center;
    background: #fff;
    border: 1px solid #dfe8f0;
    display: flex;
    gap: 18px;
    justify-content: space-between;
    margin-bottom: 18px;
    padding: 24px;
}

.page-heading span {
    color: #0d7880;
    font-size: 11px;
    font-weight: 950;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.page-heading h2 {
    color: #14325f;
    font-size: 28px;
    font-weight: 950;
    margin: 4px 0;
}

.page-heading p {
    color: #65758d;
    line-height: 1.55;
    margin: 0;
}

.panel-card {
    border-color: #dfe8f0 !important;
    overflow: hidden;
}

.panel-card-title {
    align-items: center;
    gap: 14px;
    padding: 22px 24px;
}

.panel-title-icon {
    align-items: center;
    background: #eef6ff;
    border-radius: 14px;
    display: flex;
    height: 46px;
    justify-content: center;
    width: 46px;
}

.panel-card-title strong {
    color: #243b53;
    display: block;
    font-size: 19px;
}

.panel-card-title p {
    color: #65758d;
    font-size: 13px;
    margin: 2px 0 0;
}

.panel-search {
    max-width: 360px;
}

.panel-table {
    border-top: 1px solid #e6edf5;
}

@media (max-width: 760px) {
    .page-heading,
    .panel-card-title {
        align-items: flex-start;
        flex-direction: column;
    }

    .panel-search {
        max-width: 100%;
        width: 100%;
    }
}
</style>
