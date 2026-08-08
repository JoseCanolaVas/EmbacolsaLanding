<template>
    <v-card flat class="imagenes-page">
        <div class="page-heading">
            <div>
                <span>Softnova CMS</span>
                <h2>Parametrizacion de imagenes</h2>
                <p>
                    Sube logos, banners y piezas visuales. El index toma las imagenes activas segun su tipo.
                </p>
            </div>

            <v-btn rounded depressed color="primary" @click="tab = 0">
                <v-icon left>mdi-cloud-upload-outline</v-icon>
                Subir imagen
            </v-btn>
        </div>

        <v-tabs v-model="tab" background-color="transparent" color="primary">
            <v-tab>
                <v-icon left>mdi-image-multiple-outline</v-icon>
                Imagenes
            </v-tab>
            <v-tab>
                <v-icon left>mdi-shape-outline</v-icon>
                Tipos de imagen
            </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab" class="transparent">
            <v-tab-item>
                <v-row class="mt-4">
                    <v-col cols="12" lg="4">
                        <v-card outlined class="upload-card">
                            <v-card-title>
                                Nueva imagen
                            </v-card-title>

                            <v-card-text>
                                <v-form ref="formularioImagen">
                                    <v-text-field v-model.trim="formImagen.nombre" label="Nombre" outlined dense rounded
                                        prepend-inner-icon="mdi-text-short" :rules="[rules.required]" />

                                    <v-select v-model="formImagen.tipo_imagen_id" :items="tiposImagenesActivos"
                                        item-text="nombre" item-value="id" label="Tipo de imagen" outlined dense rounded
                                        prepend-inner-icon="mdi-shape-outline" :rules="[rules.required]" />

                                    <v-file-input v-model="formImagen.imagen" label="Archivo" outlined dense rounded
                                        accept="image/png, image/jpeg, image/jpg, image/webp" prepend-icon=""
                                        prepend-inner-icon="mdi-camera-outline" show-size :rules="[rules.required]"
                                        @change="previsualizarImagen" />

                                    <div class="preview-box">
                                        <v-img v-if="imagenPreview" :src="imagenPreview" height="190" contain />
                                        <div v-else class="empty-preview">
                                            <v-icon size="58" color="grey lighten-1">
                                                mdi-image-plus-outline
                                            </v-icon>
                                            <span>Vista previa</span>
                                        </div>
                                    </div>
                                </v-form>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer />
                                <v-btn rounded outlined color="error" @click="limpiarFormularioImagen">
                                    Limpiar
                                </v-btn>
                                <v-btn rounded depressed color="primary" :loading="loading.guardandoImagen"
                                    @click="guardarImagen">
                                    Guardar
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>

                    <v-col cols="12" lg="8">
                        <v-card outlined>
                            <v-card-title class="table-title">
                                Banco de imagenes parametrizadas
                                <v-spacer />
                                <v-text-field v-model="buscarImagen" dense outlined rounded hide-details clearable
                                    prepend-inner-icon="mdi-magnify" label="Buscar" />
                            </v-card-title>

                            <v-data-table :items="imagenesFiltradas" :headers="headersImagenes"
                                :loading="loading.imagenes" disable-pagination hide-default-footer>
                                <template v-slot:item.ruta="{ item }">
                                    <v-avatar tile size="72" class="image-thumb">
                                        <v-img :src="resolverImagen(item.ruta)" contain />
                                    </v-avatar>
                                </template>

                                <template v-slot:item.tipo_imagen="{ item }">
                                    <v-chip small color="primary" outlined>
                                        {{ item.tipo_imagen?.nombre || 'Sin tipo' }}
                                    </v-chip>
                                </template>
                            </v-data-table>
                        </v-card>
                    </v-col>
                </v-row>
            </v-tab-item>

            <v-tab-item>
                <v-card outlined class="mt-4">
                    <v-card-title class="table-title">
                        Tipos que controlan el index
                        <v-spacer />
                        <v-btn color="primary" rounded depressed @click="abrirModalCrearTipoImagen()">
                            <v-icon left>mdi-plus</v-icon>
                            Crear tipo
                        </v-btn>
                    </v-card-title>

                    <v-card-text>
                        <v-alert text type="info">
                            Usa nombres claros como Logo, Banner principal, Banner secundario o Aliados. El index reconoce
                            especialmente Logo y Banner.
                        </v-alert>

                        <v-text-field v-model="buscarTipo" label="Buscar tipo" outlined dense clearable rounded
                            prepend-inner-icon="mdi-magnify" class="mb-4" />

                        <v-data-table :items="tiposImagenes" :loading="loading.tipos" :headers="headersTiposImagenes"
                            disable-pagination hide-default-footer :search="buscarTipo">
                            <template v-slot:item.estado="{ item }">
                                <v-chip :color="item.estado ? 'success' : 'grey'" dark small>
                                    {{ item.estado ? 'Activo' : 'Inactivo' }}
                                </v-chip>
                            </template>

                            <template v-slot:item.acciones="{ item }">
                                <v-btn icon color="primary" @click="abrirModalCrearTipoImagen(item)">
                                    <v-icon>mdi-pencil-outline</v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-tab-item>
        </v-tabs-items>

        <v-dialog v-model="modalTipoImagen" max-width="500px">
            <ModalTipoImagenes :tipoImagenSeleccionada="tipoImagenSeleccionada" @cerrarModal="modalTipoImagen = false"
                @recargar="listarTiposImagenes" />
        </v-dialog>
    </v-card>
</template>

<script>
import ModalTipoImagenes from '../../components/modulo-parametrizacion/categorias/modalTipoImagenes.vue'

export default {
    layout: 'parametrizacion',

    components: {
        ModalTipoImagenes,
    },

    data() {
        return {
            tab: 0,
            imagenes: [],
            tiposImagenes: [],
            buscarImagen: null,
            buscarTipo: null,
            imagenPreview: null,
            formImagen: {
                nombre: null,
                tipo_imagen_id: null,
                imagen: null,
            },
            loading: {
                imagenes: false,
                tipos: false,
                guardandoImagen: false,
            },
            headersImagenes: [
                { text: 'Imagen', value: 'ruta', sortable: false },
                { text: 'Nombre', value: 'nombre' },
                { text: 'Tipo', value: 'tipo_imagen' },
            ],
            headersTiposImagenes: [
                { text: 'Id', value: 'id', align: 'center' },
                { text: 'Nombre', value: 'nombre', align: 'center' },
                { text: 'Descripcion', value: 'descripcion', align: 'center' },
                { text: 'Estado', value: 'estado', align: 'center' },
                { text: 'Acciones', value: 'acciones', align: 'center', sortable: false },
            ],
            tipoImagenSeleccionada: {},
            modalTipoImagen: false,
            rules: {
                required: value => !!value || 'Este campo es obligatorio',
            },
        }
    },

    computed: {
        tiposImagenesActivos() {
            return this.tiposImagenes.filter(tipo => tipo.estado)
        },

        imagenesFiltradas() {
            if (!this.buscarImagen) {
                return this.imagenes
            }

            const busqueda = this.buscarImagen.toLowerCase()

            return this.imagenes.filter((imagen) => {
                const nombre = imagen.nombre || ''
                const tipo = imagen.tipo_imagen?.nombre || ''

                return `${nombre} ${tipo}`.toLowerCase().includes(busqueda)
            })
        },
    },

    mounted() {
        this.cargarDatos()
    },

    methods: {
        async cargarDatos() {
            await Promise.all([
                this.listarTiposImagenes(),
                this.listarImagenes(),
            ])
        },

        async listarImagenes() {
            try {
                this.loading.imagenes = true
                const response = await this.$axios.get('/imagenes/listar')
                this.imagenes = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrio un error al listar las imagenes.')
            } finally {
                this.loading.imagenes = false
            }
        },

        async listarTiposImagenes() {
            try {
                this.loading.tipos = true
                const response = await this.$axios.get('/tipo-imagenes/listar')
                this.tiposImagenes = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrio un error al listar los tipos de imagen.')
            } finally {
                this.loading.tipos = false
            }
        },

        previsualizarImagen(archivo) {
            if (this.imagenPreview && this.imagenPreview.startsWith('blob:')) {
                URL.revokeObjectURL(this.imagenPreview)
            }

            this.imagenPreview = archivo ? URL.createObjectURL(archivo) : null
        },

        async guardarImagen() {
            if (!this.$refs.formularioImagen.validate()) {
                return
            }

            try {
                this.loading.guardandoImagen = true

                const formData = new FormData()
                formData.append('nombre', this.formImagen.nombre)
                formData.append('tipo_imagen_id', this.formImagen.tipo_imagen_id)
                formData.append('imagen', this.formImagen.imagen)

                await this.$axios.post('/imagenes/crear', formData)

                this.$toast.success('Imagen parametrizada correctamente.')
                this.limpiarFormularioImagen()
                await this.listarImagenes()
            } catch (error) {
                this.$toast.error('No se pudo guardar la imagen.')
            } finally {
                this.loading.guardandoImagen = false
            }
        },

        limpiarFormularioImagen() {
            if (this.imagenPreview && this.imagenPreview.startsWith('blob:')) {
                URL.revokeObjectURL(this.imagenPreview)
            }

            this.formImagen = {
                nombre: null,
                tipo_imagen_id: null,
                imagen: null,
            }
            this.imagenPreview = null

            if (this.$refs.formularioImagen) {
                this.$refs.formularioImagen.resetValidation()
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

        abrirModalCrearTipoImagen(item = {}) {
            this.tipoImagenSeleccionada = { ...item }
            this.modalTipoImagen = true
        },
    },

    beforeDestroy() {
        if (this.imagenPreview && this.imagenPreview.startsWith('blob:')) {
            URL.revokeObjectURL(this.imagenPreview)
        }
    },
}
</script>

<style scoped>
.imagenes-page {
    background: transparent;
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
    color: #159eab;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.page-heading h2 {
    color: #112e64;
    font-size: 28px;
    font-weight: 900;
    margin: 4px 0;
}

.page-heading p {
    color: #65758d;
    margin: 0;
}

.upload-card,
.imagenes-page .v-card {
    border-radius: 8px;
}

.preview-box {
    align-items: center;
    background: #f4f8fb;
    border: 1px dashed #b9c9d8;
    border-radius: 8px;
    display: flex;
    min-height: 206px;
    justify-content: center;
    overflow: hidden;
}

.empty-preview {
    align-items: center;
    color: #7b8798;
    display: flex;
    flex-direction: column;
    font-weight: 700;
    gap: 8px;
}

.table-title {
    gap: 12px;
}

.image-thumb {
    background: #f4f8fb;
    border: 1px solid #e1e8f0;
    border-radius: 8px;
}

@media (max-width: 700px) {
    .page-heading {
        align-items: flex-start;
        flex-direction: column;
    }
}
</style>
