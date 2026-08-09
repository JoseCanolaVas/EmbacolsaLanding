<template>
    <v-card rounded="lg">
        <v-alert text type="info" class="mb-4">
            <div class="d-flex align-center">
                <b>
                    {{ productoSeleccionado?.id ? 'Editar producto' : 'Crear producto' }}
                </b>

                <v-spacer></v-spacer>

                <v-btn icon x-small @click="cerrarModal">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </div>
        </v-alert>

        <v-card-text class="pt-6">

            <v-form ref="formulario">
                <v-row dense>

                    <!-- NOMBRE -->
                    <v-col cols="12" md="6">
                        <v-text-field v-model.trim="form.nombre" label="Nombre"
                            prepend-inner-icon="mdi-package-variant-closed" outlined rounded dense clearable
                            :rules="[rules.requerido]" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field v-model.trim="form.unidad_medida" label="Unidad de medida"
                            prepend-inner-icon="mdi-ruler" outlined rounded dense clearable
                            :rules="[rules.requerido]" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-select v-model="form.categoria_id" :items="categorias" item-text="nombre" item-value="id" :loading="loading.categorias"
                            label="Categoría" prepend-inner-icon="mdi-format-list-bulleted-type" outlined rounded dense
                            :rules="[rules.requerido]" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-select v-model="form.marca_id" :items="marcas" item-text="nombre" item-value="id"
                            :loading="loading.marcas" label="Marca" prepend-inner-icon="mdi-tag-outline" outlined
                            rounded dense clearable />
                    </v-col>

                    <!-- PRECIO -->
                    <v-col cols="12" md="6">
                        <v-text-field v-model="form.precio" label="Precio" prepend-inner-icon="mdi-currency-usd"
                            type="number" outlined rounded dense clearable min="0" />
                    </v-col>

                    <!-- ESTADO -->
                    <v-col cols="12" md="6">
                        <v-select v-model="form.estado" :items="estados" label="Estado"
                            prepend-inner-icon="mdi-list-status" outlined rounded dense :rules="[rules.estado]" />
                    </v-col>

                    <!-- DESCRIPCIÓN -->
                    <v-col cols="12">
                        <v-textarea v-model.trim="form.descripcion" label="Descripción"
                            prepend-inner-icon="mdi-text-long" outlined rounded dense rows="3" auto-grow clearable />
                    </v-col>

                    <!-- IMAGEN -->
                    <v-col cols="12">
                        <v-file-input v-model="form.imagen" label="Imagen del producto"
                            accept="image/png, image/jpeg, image/jpg, image/webp" prepend-icon=""
                            prepend-inner-icon="mdi-camera" outlined rounded dense clearable show-size
                            truncate-length="40" @change="previsualizarImagen" />
                    </v-col>

                    <!-- PREVIEW -->
                    <v-col cols="12">

                        <v-card outlined rounded="lg" class="pa-4">
                            <div class="text-center mb-3">
                                <v-icon left color="primary">
                                    mdi-image-outline
                                </v-icon>

                                <b>
                                    Vista previa de la imagen
                                </b>
                            </div>

                            <v-divider class="mb-4" />

                            <!-- HAY IMAGEN -->
                            <div v-if="imagenPreview" class="d-flex justify-center">
                                <v-card outlined rounded="lg" width="400">
                                    <v-img :src="imagenPreview" max-height="300" height="250" contain>
                                        <template v-slot:placeholder>
                                            <v-row class="fill-height ma-0" align="center" justify="center">
                                                <v-progress-circular indeterminate color="primary" />
                                            </v-row>
                                        </template>
                                    </v-img>

                                    <v-divider />

                                    <v-card-actions class="justify-center">

                                        <v-btn text small color="error" @click="quitarImagen">
                                            <v-icon left>
                                                mdi-delete-outline
                                            </v-icon>

                                            Quitar imagen
                                        </v-btn>

                                    </v-card-actions>
                                </v-card>
                            </div>

                            <!-- NO HAY IMAGEN -->
                            <div v-else class="text-center py-8 grey--text">
                                <v-icon size="80" color="grey lighten-1">
                                    mdi-image-off-outline
                                </v-icon>

                                <div class="mt-3">
                                    No has seleccionado una imagen
                                </div>

                                <div class="caption mt-1">
                                    Selecciona una imagen para visualizarla aquí
                                </div>
                            </div>
                        </v-card>
                    </v-col>
                </v-row>
            </v-form>
        </v-card-text>

        <v-divider />
        <v-card-actions class="pa-4">
            <v-spacer />
            <v-btn rounded color="error" outlined @click="cerrarModal">
                Cancelar
                <v-icon right>mdi-close</v-icon>
            </v-btn>

            <v-btn rounded color="primary" :loading="guardando" :disabled="guardando" @click="guardarProducto">
                Guardar
                <v-icon right>mdi-content-save</v-icon>
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

    data() {
        return {
            guardando: false,
            imagenPreview: null,
            form: {
                nombre: null,
                descripcion: null,
                imagen: null,
                estado: true,
                unidad_medida: null,
                categoria_id: null,
                marca_id: null,
                precio: null,
            },
            estados: [
                {
                    text: 'Activo',
                    value: true,
                },
                {
                    text: 'Inactivo',
                    value: false,
                },
            ],
            rules: {
                requerido: value => !!value || 'Este campo es requerido',
                estado: value => value !== null && value !== undefined || 'Este campo es requerido',
            },
            categorias: [],
            marcas: [],
            loading: {
                categorias: false,
                marcas: false,
            },
        }
    },

    watch: {

        productoSeleccionado: {
            immediate: true,
            deep: true,

            handler(producto) {

                if (!producto || !producto.id) {
                    return
                }

                this.form.nombre = producto.nombre || null

                this.form.descripcion =
                    producto.descripcion || null

                this.form.estado =
                    producto.estado !== undefined
                        ? producto.estado
                        : true

                this.form.unidad_medida =
                    producto.unidad_medida || null

                this.form.precio =
                    producto.precio !== null &&
                        producto.precio !== undefined
                        ? producto.precio
                        : null

                this.form.categoria_id =
                    producto.categoria_id || producto.categoria?.id || null

                this.form.marca_id =
                    producto.marca_id || producto.marca?.id || null

                /*
                 * Si el backend devuelve una URL completa,
                 * puedes ponerla directamente.
                 */
                if (producto.ruta_imagen) {

                    this.imagenPreview =
                        this.resolverImagen(producto.ruta_imagen)
                }
            },
        },

    },

    mounted() {
        this.listarCategorias()
        this.listarMarcas()
    },

    methods: {

        /**
         * Vista previa de la imagen
         */
        previsualizarImagen(archivo) {

            /*
             * Si quitó el archivo desde el v-file-input
             */
            if (!archivo) {

                if (
                    this.imagenPreview &&
                    this.imagenPreview.startsWith('blob:')
                ) {
                    URL.revokeObjectURL(
                        this.imagenPreview
                    )
                }

                this.imagenPreview = null

                return
            }

            /*
             * Eliminamos preview anterior
             * si era un ObjectURL
             */
            if (
                this.imagenPreview &&
                this.imagenPreview.startsWith('blob:')
            ) {
                URL.revokeObjectURL(
                    this.imagenPreview
                )
            }

            /*
             * Creamos preview local
             */
            this.imagenPreview =
                URL.createObjectURL(archivo)
        },


        /**
         * Quitar imagen seleccionada
         */
        quitarImagen() {

            if (
                this.imagenPreview &&
                this.imagenPreview.startsWith('blob:')
            ) {
                URL.revokeObjectURL(
                    this.imagenPreview
                )
            }

            this.form.imagen = null

            this.imagenPreview = null
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


        /**
         * Guardar producto
         */
        async guardarProducto() {

            const formularioValido =
                this.$refs.formulario.validate()

            if (!formularioValido) {
                return
            }


            try {
                this.$preload(true);

                const formData = new FormData()

                formData.append('nombre', this.form.nombre)
                formData.append('descripcion', this.form.descripcion)
                formData.append('unidad_medida', this.form.unidad_medida)
                formData.append('categoria_id', this.form.categoria_id)

                formData.append('estado', this.form.estado ? '1' : '0')

                if (this.form.marca_id) {
                    formData.append('marca_id', this.form.marca_id)
                }

                if (this.form.precio !== null) {
                    formData.append('precio', this.form.precio)
                }

                if (this.form.imagen) {
                    formData.append('imagen', this.form.imagen)
                }

                if (
                    !this.productoSeleccionado ||
                    !this.productoSeleccionado.id
                ) {

                    await this.$axios.post('/productos/crear', formData)

                } else {

                    /*
                     * EDITAR
                     *
                     * Con archivos en Laravel suele ser
                     * más cómodo mandar POST + _method PUT.
                     */

                    formData.append(
                        '_method',
                        'PUT'
                    )

                    await this.$axios.post(
                        `/productos/${this.productoSeleccionado.id}`,
                        formData
                    )
                }

                this.$toast.success('Fomulario guardado exitosamente.')
                this.$emit('producto-guardado')
                this.cerrarModal()
            } catch (error) {
                this.$toast.error('Ha ocurrido un error al guardar el producto. Por favor, inténtelo de nuevo.')
            } finally {
                this.$preload(false)
            }
        },

        limpiarFormulario() {

            if (this.imagenPreview && this.imagenPreview.startsWith('blob:')
            ) {
                URL.revokeObjectURL(
                    this.imagenPreview
                )
            }

            this.form = {
                nombre: null,
                descripcion: null,
                imagen: null,
                estado: true,
                unidad_medida: null,
                categoria_id: null,
                marca_id: null,
                precio: null,
            }

            this.imagenPreview = null

            if (this.$refs.formulario) {

                this.$refs.formulario.resetValidation()
            }
        },


        /**
         * Cerrar modal
         */
        cerrarModal() {

            this.limpiarFormulario()

            this.$emit('cerrar')
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

        async listarMarcas() {
            try {
                this.loading.marcas = true;
                const response = await this.$axios.get('/marcas/listar');
                this.marcas = response.data;
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar las marcas. Por favor, inténtelo de nuevo.');
            } finally {
                this.loading.marcas = false;
            }
        }
    },


    beforeDestroy() {

        if (
            this.imagenPreview &&
            this.imagenPreview.startsWith('blob:')
        ) {
            URL.revokeObjectURL(
                this.imagenPreview
            )
        }
    },

}
</script>
