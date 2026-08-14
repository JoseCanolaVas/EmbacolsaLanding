<template>
    <v-card class="mx-auto">
        <v-alert text type="info">
            {{ usuarioSeleccionado?.id ? 'Editar usuario' : 'Crear usuario' }}
        </v-alert>

        <v-card-text>
            <v-form ref="formulario">
                <v-row dense>
                    <v-col cols="12" md="6">
                        <v-text-field v-model.trim="form.nombre" label="Nombre" outlined dense rounded
                            prepend-inner-icon="mdi-account-outline" :rules="[rules.required]" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field v-model.trim="form.apellido" label="Apellido" outlined dense rounded
                            prepend-inner-icon="mdi-account-outline" :rules="[rules.required]" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field v-model.trim="form.email" label="Correo" outlined dense rounded
                            prepend-inner-icon="mdi-email-outline" :rules="[rules.required, rules.email]" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field v-model.trim="form.telefono" label="Teléfono" outlined dense rounded
                            prepend-inner-icon="mdi-phone-outline" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-text-field v-model="form.password" :label="usuarioSeleccionado?.id ? 'Nueva contraseña' : 'Contraseña'"
                            outlined dense rounded prepend-inner-icon="mdi-lock-outline"
                            :append-icon="verPassword ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                            :type="verPassword ? 'text' : 'password'" :rules="passwordRules"
                            @click:append="verPassword = !verPassword" />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-switch v-model="form.es_super_admin" label="Super administrador" inset />
                    </v-col>

                    <v-col cols="12" md="6">
                        <v-select v-model="form.rol" :items="roles" label="Rol operativo" outlined dense rounded
                            prepend-inner-icon="mdi-account-key-outline" />
                    </v-col>

                    <v-col v-if="!form.es_super_admin" cols="12">
                        <v-card outlined class="permissions-card">
                            <v-card-title class="py-3">
                                Permisos del panel
                            </v-card-title>
                            <v-card-text>
                                <v-row dense>
                                    <v-col v-for="permiso in permisosDisponibles" :key="permiso.value" cols="12" md="6">
                                        <v-checkbox v-model="form.permisos" :value="permiso.value" dense hide-details
                                            :label="permiso.label" />
                                    </v-col>
                                </v-row>
                            </v-card-text>
                        </v-card>
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
        usuarioSeleccionado: {
            type: Object,
            default: () => ({}),
        },
    },

    data() {
        return {
            guardando: false,
            verPassword: false,
            form: this.formInicial(),
            roles: [
                { text: 'Super administrador', value: 'super_admin' },
                { text: 'Administrador', value: 'administrador' },
                { text: 'Editor de catálogo', value: 'editor_catalogo' },
                { text: 'Comercial', value: 'comercial' },
                { text: 'Solo consulta', value: 'consulta' },
            ],
            permisosDisponibles: [
                { label: 'Ver panel', value: 'panel.ver' },
                { label: 'Ver productos', value: 'productos.ver' },
                { label: 'Crear productos', value: 'productos.crear' },
                { label: 'Editar productos', value: 'productos.editar' },
                { label: 'Ver categorías', value: 'categorias.ver' },
                { label: 'Crear categorías', value: 'categorias.crear' },
                { label: 'Editar categorías', value: 'categorias.editar' },
                { label: 'Ver marcas', value: 'marcas.ver' },
                { label: 'Crear marcas', value: 'marcas.crear' },
                { label: 'Editar marcas', value: 'marcas.editar' },
                { label: 'Ver imágenes', value: 'imagenes.ver' },
                { label: 'Crear imágenes', value: 'imagenes.crear' },
                { label: 'Editar imágenes', value: 'imagenes.editar' },
                { label: 'Eliminar imágenes', value: 'imagenes.eliminar' },
                { label: 'Ver usuarios', value: 'usuarios.ver' },
                { label: 'Crear usuarios', value: 'usuarios.crear' },
                { label: 'Editar usuarios', value: 'usuarios.editar' },
                { label: 'Administrar sitio público', value: 'administrar-sitio' },
            ],
            rules: {
                required: value => !!value || 'Este campo es requerido',
                email: value => /.+@.+\..+/.test(value) || 'Ingrese un correo válido',
            },
        }
    },

    computed: {
        passwordRules() {
            if (this.usuarioSeleccionado?.id) {
                return [
                    value => !value || value.length >= 6 || 'Mínimo 6 caracteres',
                ]
            }

            return [
                this.rules.required,
                value => value?.length >= 6 || 'Mínimo 6 caracteres',
            ]
        },
    },

    watch: {
        usuarioSeleccionado: {
            immediate: true,
            handler(usuario) {
                if (!usuario || !usuario.id) {
                    this.form = this.formInicial()
                    return
                }

                this.form = {
                    nombre: usuario.nombre || null,
                    apellido: usuario.apellido || null,
                    email: usuario.email || null,
                    telefono: usuario.telefono || null,
                    password: null,
                    es_super_admin: Boolean(usuario.es_super_admin),
                    rol: usuario.rol || (usuario.es_super_admin ? 'super_admin' : 'editor_catalogo'),
                    permisos: usuario.permisos || [],
                }
            },
        },
    },

    methods: {
        formInicial() {
            return {
                nombre: null,
                apellido: null,
                email: null,
                telefono: null,
                password: null,
                es_super_admin: false,
                rol: 'editor_catalogo',
                permisos: [
                    'panel.ver',
                    'productos.ver',
                    'categorias.ver',
                    'marcas.ver',
                    'imagenes.ver',
                    'administrar-sitio',
                ],
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

                const payload = { ...this.form }

                if (this.usuarioSeleccionado?.id && !payload.password) {
                    delete payload.password
                }

                if (payload.es_super_admin) {
                    payload.rol = 'super_admin'
                    payload.permisos = []
                }

                const method = this.usuarioSeleccionado?.id ? 'post' : 'post'
                const url = this.usuarioSeleccionado?.id
                    ? `/usuarios/actualizar/${this.usuarioSeleccionado.id}`
                    : '/usuarios/crear'

                await this.$axios[method](url, payload)

                this.$toast.success(this.usuarioSeleccionado?.id ? 'Usuario actualizado exitosamente.' : 'Usuario creado exitosamente.')
                this.$emit('recargar')
                this.cerrarModal()
            } catch (error) {
                this.$toast.error('Ocurrió un error al guardar el usuario.')
            } finally {
                this.guardando = false
                this.$preload(false)
            }
        },

        cerrarModal() {
            this.form = this.formInicial()
            this.verPassword = false

            if (this.$refs.formulario) {
                this.$refs.formulario.resetValidation()
            }

            this.$emit('cerrarModal')
        },
    },
}
</script>

<style scoped>
.permissions-card {
    background: linear-gradient(145deg, #ffffff, #f6f9fc);
    border-color: #dfe8f0 !important;
    border-radius: 16px !important;
}

.permissions-card .v-card__title {
    color: #17365d;
    font-size: 16px;
    font-weight: 900;
}
</style>
