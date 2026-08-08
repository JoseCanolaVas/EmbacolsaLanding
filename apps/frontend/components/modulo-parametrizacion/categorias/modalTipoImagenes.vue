<template>
    <v-card class="mx-auto">

        <v-alert text type="info">{{ tipoImagenSeleccionada?.id ? 'Editar Tipo de Imagen' : 'Crear Nuevo Tipo de Imagen'
        }}</v-alert>

        <v-card-text>
            <v-form ref="formulario">
                <v-row dense>

                    <v-col cols="12" md="12" sm="12">
                        <v-text-field label="Nombre " outlined dense rounded v-model="form.nombre"
                            :rules="[rules.required]"></v-text-field>
                    </v-col>

                    <v-col cols="12" md="12" sm="12">
                        <v-text-field label="Descripción" outlined dense rounded v-model="form.descripcion"
                            :rules="[rules.required]"></v-text-field>
                    </v-col>

                    <v-col cols="12" md="12" sm="12" v-if="tipoImagenSeleccionada?.id">
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
        tipoImagenSeleccionada: {
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
                required: value => !!value || 'Este campo es obligatorio',
            },
        }
    },

    watch: {
        tipoImagenSeleccionada: {
            immediate: true,
            handler(newVal) {
                if (newVal && newVal.id) {
                    this.form = { ...newVal };
                }
            },
        },
    },

    methods: {
        async guardarFormulario() {

            if (!this.$refs.formulario.validate()) {
                this.$toast.error('Por favor complete los campos requeridos');
                return;
            }

            try {
                this.$preload(true);

                const method = this.tipoImagenSeleccionada?.id ? 'put' : 'post';
                const url = this.tipoImagenSeleccionada?.id ? `/tipo-imagenes/actualizar/${this.tipoImagenSeleccionada.id}` : '/tipo-imagenes/crear';

                await this.$axios[method](url, this.form);
                this.$toast.success('Formulario guardado correctamente');
                this.$emit('recargar');
                this.cerrarModal();
            } catch (error) {
                this.$toast.error('Ha ocurrido un Error al guardar el formulario');
            } finally {
                this.$preload(false);
            }
        },

        cerrarModal() {
            this.form = {
                nombre: null,
                descripcion: null,
                estado: true,
            };

            this.$refs.formulario.resetValidation();
            this.$emit('cerrarModal');
        },
    }

}
</script>