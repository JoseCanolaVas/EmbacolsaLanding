<template>
    <v-card class="mx-auto">
        <v-alert text type="info">
            {{ bodegaSeleccionada?.id ? 'Editar bodega' : 'Crear bodega' }}
        </v-alert>

        <v-card-text>
            <v-form ref="formulario">
                <v-row dense>
                    <v-col cols="12" md="7">
                        <v-text-field v-model.trim="form.nombre" label="Nombre de la bodega" outlined dense rounded
                            prepend-inner-icon="mdi-warehouse" :rules="[rules.required]" />
                    </v-col>

                    <v-col cols="12" md="5">
                        <v-text-field v-model.trim="form.codigo" label="Código" outlined dense rounded
                            prepend-inner-icon="mdi-barcode" />
                    </v-col>

                    <v-col cols="12">
                        <v-text-field v-model.trim="form.ubicacion" label="Ubicación" outlined dense rounded
                            prepend-inner-icon="mdi-map-marker-outline" />
                    </v-col>

                    <v-col cols="12">
                        <v-textarea v-model.trim="form.descripcion" label="Descripción" outlined dense rounded
                            rows="3" auto-grow prepend-inner-icon="mdi-text-long" />
                    </v-col>

                    <v-col v-if="bodegaSeleccionada?.id" cols="12">
                        <v-switch v-model="form.estado" label="Estado" inset />
                    </v-col>
                </v-row>

                <v-card-actions class="justify-center mt-4">
                    <v-btn color="error" rounded @click="cerrarModal">
                        Cerrar
                        <v-icon left>mdi-close</v-icon>
                    </v-btn>

                    <v-btn color="primary" rounded :loading="guardando" @click="guardarFormulario">
                        Guardar formulario
                        <v-icon left>mdi-content-save</v-icon>
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    props: {
        bodegaSeleccionada: {
            type: Object,
            default: () => ({}),
        },
    },

    data() {
        return {
            guardando: false,
            form: this.formInicial(),
            rules: {
                required: value => !!value || 'Este campo es requerido',
            },
        }
    },

    watch: {
        bodegaSeleccionada: {
            immediate: true,
            handler(bodega) {
                if (!bodega || !bodega.id) {
                    this.form = this.formInicial()
                    return
                }

                this.form = {
                    nombre: bodega.nombre || null,
                    codigo: bodega.codigo || null,
                    ubicacion: bodega.ubicacion || null,
                    descripcion: bodega.descripcion || null,
                    estado: bodega.estado !== undefined ? bodega.estado : true,
                }
            },
        },
    },

    methods: {
        formInicial() {
            return {
                nombre: null,
                codigo: null,
                ubicacion: null,
                descripcion: null,
                estado: true,
            }
        },

        async guardarFormulario() {
            if (!this.$refs.formulario.validate()) {
                this.$toast.warning('Por favor, complete los campos requeridos.')
                return
            }

            try {
                this.guardando = true
                this.$preload(true)

                const method = this.bodegaSeleccionada?.id ? 'put' : 'post'
                const url = this.bodegaSeleccionada?.id
                    ? `/bodegas/actualizar/${this.bodegaSeleccionada.id}`
                    : '/bodegas/crear'

                await this.$axios[method](url, this.form)

                this.$toast.success(this.bodegaSeleccionada?.id ? 'Bodega actualizada exitosamente.' : 'Bodega creada exitosamente.')
                this.$emit('recargar')
                this.cerrarModal()
            } catch (error) {
                this.$toast.error('Ocurrió un error al guardar la bodega.')
            } finally {
                this.guardando = false
                this.$preload(false)
            }
        },

        cerrarModal() {
            this.form = this.formInicial()

            if (this.$refs.formulario) {
                this.$refs.formulario.resetValidation()
            }

            this.$emit('cerrarModal')
        },
    },
}
</script>
