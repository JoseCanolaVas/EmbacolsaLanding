<template>
    <v-card flat class="roles-page">
        <div class="page-heading">
            <div>
                <span>Seguridad</span>
                <h2>Roles y permisos</h2>
                <p>
                    Crea perfiles reutilizables para controlar qué módulos y acciones puede usar cada usuario.
                </p>
            </div>

            <v-btn v-if="$can('roles.crear')" rounded depressed color="primary" @click="nuevoRol">
                <v-icon left>mdi-shield-plus-outline</v-icon>
                Crear rol
            </v-btn>
        </div>

        <v-row>
            <v-col cols="12" lg="5">
                <v-card outlined class="form-card">
                    <v-card-title>
                        {{ form.id ? 'Editar rol' : 'Nuevo rol' }}
                    </v-card-title>
                    <v-card-text>
                        <v-form ref="formulario">
                            <v-text-field v-model.trim="form.nombre" label="Nombre del rol" outlined dense rounded
                                prepend-inner-icon="mdi-shield-account-outline" :rules="[rules.required]" />

                            <v-text-field v-model.trim="form.slug" label="Slug" outlined dense rounded
                                hint="Si lo dejas vacío se genera automáticamente" persistent-hint />

                            <v-textarea v-model.trim="form.descripcion" label="Descripción" outlined rounded rows="2" />

                            <v-switch v-model="form.estado" label="Rol activo" inset />

                            <v-card outlined class="permissions-box">
                                <v-card-title class="py-3">
                                    Permisos asignados
                                    <v-spacer />
                                    <v-chip small color="primary" outlined>
                                        {{ form.permisos.length }}
                                    </v-chip>
                                </v-card-title>

                                <v-card-text>
                                    <div v-for="grupo in permisosAgrupados" :key="grupo.nombre" class="permission-group">
                                        <div class="permission-group-title">
                                            <strong>{{ grupo.nombre }}</strong>
                                            <v-btn x-small text color="primary" @click="alternarGrupo(grupo.permisos)">
                                                Alternar
                                            </v-btn>
                                        </div>

                                        <v-row dense>
                                            <v-col v-for="permiso in grupo.permisos" :key="permiso.value" cols="12" sm="6">
                                                <v-checkbox v-model="form.permisos" :label="permiso.label"
                                                    :value="permiso.value" dense hide-details />
                                            </v-col>
                                        </v-row>
                                    </div>
                                </v-card-text>
                            </v-card>
                        </v-form>
                    </v-card-text>

                    <v-card-actions>
                        <v-spacer />
                        <v-btn rounded outlined color="error" @click="nuevoRol">
                            Limpiar
                        </v-btn>
                        <v-btn v-if="puedeGuardar" rounded depressed color="primary" :loading="guardando" @click="guardarRol">
                            Guardar rol
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <v-col cols="12" lg="7">
                <v-card outlined class="table-card">
                    <v-card-title>
                        Roles configurados
                        <v-spacer />
                        <v-text-field v-model="buscar" dense outlined rounded clearable hide-details
                            prepend-inner-icon="mdi-magnify" label="Buscar rol" />
                    </v-card-title>

                    <v-data-table :items="rolesFiltrados" :headers="headers" :loading="cargando" disable-pagination
                        hide-default-footer>
                        <template v-slot:item.estado="{ item }">
                            <v-chip small :color="item.estado ? 'green' : 'grey'" dark>
                                {{ item.estado ? 'Activo' : 'Inactivo' }}
                            </v-chip>
                        </template>

                        <template v-slot:item.permisos="{ item }">
                            <v-chip small outlined color="primary">
                                {{ (item.permisos || []).length }} permisos
                            </v-chip>
                        </template>

                        <template v-slot:item.acciones="{ item }">
                            <v-btn v-if="$can('roles.editar')" icon color="primary" @click="editarRol(item)">
                                <v-icon>mdi-pencil-outline</v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
export default {
    layout: 'parametrizacion',

    data() {
        return {
            roles: [],
            permisos: [],
            buscar: '',
            cargando: false,
            guardando: false,
            form: this.formInicial(),
            headers: [
                { text: 'Nombre', value: 'nombre' },
                { text: 'Slug', value: 'slug' },
                { text: 'Permisos', value: 'permisos', align: 'center' },
                { text: 'Estado', value: 'estado', align: 'center' },
                { text: 'Acciones', value: 'acciones', align: 'center', sortable: false },
            ],
            rules: {
                required: value => !!value || 'Este campo es obligatorio',
            },
        }
    },

    computed: {
        puedeGuardar() {
            return this.form.id ? this.$can('roles.editar') : this.$can('roles.crear')
        },

        rolesFiltrados() {
            if (!this.buscar) {
                return this.roles
            }

            const busqueda = this.buscar.toLowerCase()

            return this.roles.filter(rol => `${rol.nombre} ${rol.slug} ${rol.descripcion || ''}`.toLowerCase().includes(busqueda))
        },

        permisosAgrupados() {
            const permisos = this.permisos.length ? this.permisos : this.$permissionsCatalog
            const grupos = {}

            permisos.forEach((permiso) => {
                const nombre = this.nombreGrupo(permiso.value)

                if (!grupos[nombre]) {
                    grupos[nombre] = []
                }

                grupos[nombre].push(permiso)
            })

            return Object.keys(grupos).map(nombre => ({
                nombre,
                permisos: grupos[nombre],
            }))
        },
    },

    mounted() {
        this.listarRoles()
    },

    methods: {
        formInicial() {
            return {
                id: null,
                nombre: '',
                slug: '',
                descripcion: '',
                permisos: ['panel.ver'],
                estado: true,
            }
        },

        async listarRoles() {
            try {
                this.cargando = true
                const response = await this.$axios.get('/roles/listar')
                this.roles = response.data?.roles || []
                this.permisos = response.data?.permisos || this.$permissionsCatalog
            } catch (error) {
                this.$toast.error('No se pudieron listar los roles.')
            } finally {
                this.cargando = false
            }
        },

        nuevoRol() {
            this.form = this.formInicial()

            if (this.$refs.formulario) {
                this.$refs.formulario.resetValidation()
            }
        },

        editarRol(rol) {
            this.form = {
                id: rol.id,
                nombre: rol.nombre || '',
                slug: rol.slug || '',
                descripcion: rol.descripcion || '',
                permisos: rol.permisos || [],
                estado: Boolean(rol.estado),
            }
        },

        async guardarRol() {
            if (!this.$refs.formulario.validate()) {
                return
            }

            try {
                this.guardando = true
                const payload = { ...this.form }

                if (payload.id) {
                    await this.$axios.put(`/roles/actualizar/${payload.id}`, payload)
                    this.$toast.success('Rol actualizado correctamente.')
                } else {
                    await this.$axios.post('/roles/crear', payload)
                    this.$toast.success('Rol creado correctamente.')
                }

                this.nuevoRol()
                await this.listarRoles()
            } catch (error) {
                this.$toast.error('No se pudo guardar el rol.')
            } finally {
                this.guardando = false
            }
        },

        alternarGrupo(permisos) {
            const valores = permisos.map(permiso => permiso.value)
            const todosSeleccionados = valores.every(valor => this.form.permisos.includes(valor))

            if (todosSeleccionados) {
                this.form.permisos = this.form.permisos.filter(valor => !valores.includes(valor))
                return
            }

            this.form.permisos = Array.from(new Set([...this.form.permisos, ...valores]))
        },

        nombreGrupo(permiso) {
            if (permiso === 'administrar-sitio') {
                return 'Sitio público'
            }

            const modulo = permiso.split('.')[0]
            const nombres = {
                panel: 'Panel',
                productos: 'Productos',
                categorias: 'Categorías',
                marcas: 'Marcas',
                imagenes: 'Imágenes',
                usuarios: 'Usuarios',
                roles: 'Roles y permisos',
            }

            return nombres[modulo] || 'Otros'
        },
    },
}
</script>

<style scoped>
.roles-page {
    background: transparent;
}

.page-heading,
.form-card,
.table-card {
    border-radius: 14px !important;
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
    margin: 0;
}

.permissions-box {
    background: #f8fbfd;
    border-radius: 14px !important;
}

.permission-group {
    border-bottom: 1px solid #e6edf4;
    padding: 12px 0;
}

.permission-group:last-child {
    border-bottom: 0;
}

.permission-group-title {
    align-items: center;
    color: #17365d;
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}
</style>
