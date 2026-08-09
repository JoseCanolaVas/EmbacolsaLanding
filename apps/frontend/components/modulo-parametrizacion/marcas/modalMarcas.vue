<template>
    <v-card class="mx-auto">
        <v-alert text type="info">
            {{ marcaSeleccionada?.id ? 'Editar marca' : 'Crear marca' }}
        </v-alert>

        <v-card-text>
            <v-form ref="formulario">
                <v-row dense>
                    <v-col cols="12">
                        <v-text-field v-model.trim="form.nombre" label="Nombre de la marca" outlined dense rounded
                            prepend-inner-icon="mdi-tag-outline" :rules="[rules.required]" />
                    </v-col>

                    <v-col cols="12">
                        <v-text-field v-model.trim="form.descripcion" label="Descripción" outlined dense rounded
                            prepend-inner-icon="mdi-text-long" />
                    </v-col>

                    <v-col v-if="marcaSeleccionada?.id" cols="12">
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
        marcaSeleccionada: {
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
        marcaSeleccionada: {
            immediate: true,
            handler(marca) {
                if (!marca || !marca.id) {
                    this.form = this.formInicial()
                    return
                }

                this.form = {
                    nombre: marca.nombre || null,
                    descripcion: marca.descripcion || null,
                    estado: marca.estado !== undefined ? marca.estado : true,
                }
            },
        },
    },

    methods: {
        formInicial() {
            return {
                nombre: null,
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

                const method = this.marcaSeleccionada?.id ? 'put' : 'post'
                const url = this.marcaSeleccionada?.id
                    ? `/marcas/actualizar/${this.marcaSeleccionada.id}`
                    : '/marcas/crear'

                await this.$axios[method](url, this.form)

                this.$toast.success(this.marcaSeleccionada?.id ? 'Marca actualizada exitosamente.' : 'Marca creada exitosamente.')
                this.$emit('recargar')
                this.cerrarModal()
            } catch (error) {
                this.$toast.error('Ocurrió un error al guardar la marca.')
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
