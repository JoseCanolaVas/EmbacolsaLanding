<template>
    <v-card flat class="mb-4">
        <v-card-title>
            <v-toolbar flat>
                <v-toolbar-title>
                    <div class="d-flex align-center">
                        <v-icon color="warning" class="mr-3" size="30">
                            mdi-account-group
                        </v-icon>
                        <span>Usuarios</span>
                    </div>
                </v-toolbar-title>
                <v-divider class="mx-4" inset vertical></v-divider>
            </v-toolbar>
        </v-card-title>

        <v-card-subtitle class="mb-4 mt-5" align="center">
            <h3>
                <b class="justify-center">
                    <v-icon>mdi-information-slab-circle-outline</v-icon>
                    Administra los usuarios que pueden ingresar al panel.
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
                    <v-btn v-if="$can('usuarios.crear')" color="primary" rounded @click="abrirModalUsuario()">
                        <v-icon left>mdi-plus</v-icon>
                        Crear usuario
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>

        <v-card-text class="mt-5">
            <v-data-table :items="usuarios" :headers="headersUsuarios" :loading="loading.usuarios" disable-pagination
                hide-default-footer :search="buscar">
                <template v-slot:item.nombre_completo="{ item }">
                    {{ item.nombre }} {{ item.apellido }}
                </template>

                <template v-slot:item.es_super_admin="{ item }">
                    <v-chip :color="item.es_super_admin ? 'green' : 'grey'" dark small>
                        {{ item.es_super_admin ? 'Super admin' : 'Usuario' }}
                    </v-chip>
                </template>

                <template v-slot:item.acciones="{ item }">
                    <v-icon v-if="$can('usuarios.editar')" color="teal" @click="abrirModalUsuario(item)">
                        mdi-pencil
                    </v-icon>
                </template>
            </v-data-table>
        </v-card-text>

        <v-dialog v-model="modalUsuario" max-width="650px">
            <ModalUsuarios :usuarioSeleccionado="usuarioSeleccionado" @cerrarModal="modalUsuario = false"
                @recargar="listarUsuarios" />
        </v-dialog>
    </v-card>
</template>

<script>
import ModalUsuarios from '~/components/modulo-parametrizacion/usuarios/modalUsuarios.vue'

export default {
    layout: 'parametrizacion',

    components: {
        ModalUsuarios,
    },

    data() {
        return {
            buscar: '',
            usuarios: [],
            usuarioSeleccionado: {},
            modalUsuario: false,
            loading: {
                usuarios: false,
            },
            headersUsuarios: [
                { text: 'Nombre', value: 'nombre_completo', align: 'center' },
                { text: 'Correo', value: 'email', align: 'center' },
                { text: 'Teléfono', value: 'telefono', align: 'center' },
                { text: 'Perfil', value: 'es_super_admin', align: 'center' },
                { text: 'Acciones', value: 'acciones', sortable: false, align: 'center' },
            ],
        }
    },

    mounted() {
        this.listarUsuarios()
    },

    methods: {
        async listarUsuarios() {
            try {
                this.loading.usuarios = true
                const response = await this.$axios.get('/usuarios/listar')
                this.usuarios = response.data || []
            } catch (error) {
                this.$toast.error('Ocurrió un error al listar los usuarios.')
            } finally {
                this.loading.usuarios = false
            }
        },

        abrirModalUsuario(item = {}) {
            this.usuarioSeleccionado = { ...item }
            this.modalUsuario = true
        },
    },
}
</script>
