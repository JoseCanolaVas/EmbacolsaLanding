<template>
    <v-card class="mx-auto">
        <v-alert text type="info">{{ categoriaSeleccionada?.id ? 'Editar categoría' : 'Crear categoría' }}</v-alert>

        <v-card-text>
            <v-form ref="formulario">
                <v-row dense>

                    <v-col cols="12" md="12" sm="12">
                        <v-text-field label="Nombre de la categoría" outlined dense rounded v-model="form.nombre"
                            :rules="[rules.required]"></v-text-field>
                    </v-col>

                    <v-col cols="12" md="12" sm="12">
                        <v-text-field label="Descripción de la categoría" outlined dense rounded
                            v-model="form.descripcion" :rules="[rules.required]"></v-text-field>
                    </v-col>

                    <v-col cols="12" md="12" sm="12" v-if="categoriaSeleccionada?.id">
                        <v-switch v-model="form.estado" label="Estado" inset></v-switch>
                    </v-col>
                </v-row>

                <v-card-actions class="justify-center mt-4">
                    <v-btn color="error" rounded @click="cerrarModal()">cerrar <v-icon left>mdi-close</v-icon></v-btn>
                    <v-btn color="primary" rounded @click="guardarFormulario()">Guardar formulario <v-icon
                            left>mdi-content-save</v-icon></v-btn>
                </v-card-actions>
            </v-form>
        </v-card-text>
    </v-card>
</template>
<script>
export default {

    props: {
        categoriaSeleccionada: {
            type: Object,
            default: () => ({}),
        },
    },

    data() {
        return {
            form: {
                nombre: null,
                descripcion: null,
                estado: true,
            },
            rules: {
                required: value => !!value || 'Este campo es requerido',
            }
        };
    },

    watch: {
        categoriaSeleccionada: {
            immediate: true,
            handler(newVal) {
                if (newVal && Object.keys(newVal).length > 0) {
                    this.form.nombre = newVal.nombre || null;
                    this.form.descripcion = newVal.descripcion || null;
                    this.form.estado = newVal.estado !== undefined ? newVal.estado : true;
                }
            },
        },
    },

    methods: {
        async guardarFormulario() {
            if (!this.$refs.formulario.validate()) {
                this.$toast.warning('Por favor, complete todos los campos requeridos antes de guardar.');
                return;
            }

            try {
                this.$preload(true);

                const metodod = this.categoriaSeleccionada?.id ? 'put' : 'post';
                const url = this.categoriaSeleccionada?.id ? `/categorias/actualizar/${this.categoriaSeleccionada.id}` : '/categorias/crear';
                await this.$axios[metodod](url, this.form);

                this.$toast.success(this.categoriaSeleccionada?.id ? 'Categoría actualizada exitosamente.' : 'Categoría creada exitosamente.');
                this.$emit('recargar');
                this.cerrarModal();
            } catch (error) {
                this.$toast.error('Ocurrió un error al guardar la categoría. Por favor, inténtelo de nuevo.');
            } finally {
                this.$preload(false);
            }
        },

        cerrarModal() {
            this.$refs.formulario.resetValidation();
            this.form = {
                nombre: null,
                descripcion: null,
                estado: true,
            }
            this.$emit('cerrarModal');
        }
    },
};
</script>