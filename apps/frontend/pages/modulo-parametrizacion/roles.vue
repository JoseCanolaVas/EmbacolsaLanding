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
                            <v-btn small rounded outlined color="primary" @click="verPermisosRol(item)">
                                <v-icon left small>mdi-eye-outline</v-icon>
                                {{ (item.permisos || []).length }} permisos
                            </v-btn>
                        </template>

                        <template v-slot:item.acciones="{ item }">
                            <v-btn v-if="$can('roles.editar')" icon color="primary" @click="editarRol(item)">
                                <v-icon>mdi-pencil-outline</v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-card>

                <v-card v-if="$can('permisos.ver')" outlined class="table-card mt-5">
                    <v-card-title>
                        Permisos del sistema
                        <v-spacer />
                        <v-text-field v-model="buscarPermiso" dense outlined rounded clearable hide-details
                            prepend-inner-icon="mdi-magnify" label="Buscar permiso" />
                    </v-card-title>

                    <v-card-text>
                        <v-form ref="formularioPermiso">
                            <v-row dense>
                                <v-col cols="12" md="4">
                                    <v-text-field v-model.trim="permisoForm.name" label="Código del permiso" outlined dense rounded
                                        prepend-inner-icon="mdi-key-outline" :rules="[rules.required]" />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field v-model.trim="permisoForm.label" label="Nombre visible" outlined dense rounded
                                        prepend-inner-icon="mdi-label-outline" />
                                </v-col>
                                <v-col cols="12" md="4">
                                    <v-text-field v-model.trim="permisoForm.grupo" label="Grupo" outlined dense rounded
                                        prepend-inner-icon="mdi-folder-key-outline" />
                                </v-col>
                                <v-col cols="12">
                                    <v-textarea v-model.trim="permisoForm.descripcion" label="Descripción" outlined rounded rows="2" />
                                </v-col>
                                <v-col cols="12" class="d-flex justify-end">
                                    <v-btn rounded outlined color="error" class="mr-2" @click="nuevoPermiso">
                                        Limpiar 
                                        <v-icon>mdi-close</v-icon>
                                    </v-btn>
                                    <v-btn v-if="puedeGuardarPermiso" rounded depressed color="primary" :loading="guardandoPermiso"
                                        @click="guardarPermiso">
                                        {{ permisoForm.id ? 'Actualizar permiso' : 'Crear permiso' }}
                                        <v-icon>{{ permisoForm.id ? 'mdi-pencil' : 'mdi-plus' }}</v-icon>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>

                    <v-data-table :items="permisosFiltrados" :headers="headersPermisos" :loading="cargandoPermisos"
                        disable-pagination hide-default-footer>
                        <template v-slot:item.estado="{ item }">
                            <v-chip small :color="item.estado ? 'green' : 'grey'" dark>
                                {{ item.estado ? 'Activo' : 'Inactivo' }}
                            </v-chip>
                        </template>

                        <template v-slot:item.acciones="{ item }">
                            <v-btn v-if="$can('permisos.editar')" icon color="primary" @click="editarPermiso(item)">
                                <v-icon>mdi-pencil-outline</v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>
                </v-card>
            </v-col>
        </v-row>

        <v-dialog v-model="modalPermisosRol" max-width="760px">
            <v-card class="role-permissions-modal">
                <v-card-title class="role-permissions-header">
                    <div>
                        <span>Detalle de permisos</span>
                        <h3>{{ rolDetalle?.nombre || 'Rol' }}</h3>
                        <p>{{ rolDetalle?.descripcion || 'Permisos asignados actualmente a este rol.' }}</p>
                    </div>

                    <v-btn icon color="white" @click="cerrarPermisosRol">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text class="pa-5">
                    <div class="role-summary mb-4">
                        <v-chip color="primary" outlined>
                            <v-icon left small>mdi-shield-account-outline</v-icon>
                            {{ rolDetalle?.slug }}
                        </v-chip>
                        <v-chip :color="rolDetalle?.estado ? 'green' : 'grey'" dark>
                            {{ rolDetalle?.estado ? 'Activo' : 'Inactivo' }}
                        </v-chip>
                        <v-chip color="teal" outlined>
                            {{ permisosRolDetalle.length }} permisos asignados
                        </v-chip>
                    </div>

                    <v-alert v-if="!permisosRolDetalle.length" text type="warning" class="mb-0">
                        Este rol no tiene permisos asignados todavía.
                    </v-alert>

                    <div v-else>
                        <div v-for="grupo in permisosRolAgrupados" :key="grupo.nombre" class="detail-permission-group">
                            <div class="detail-permission-title">
                                <v-icon color="primary" class="mr-2">mdi-folder-key-outline</v-icon>
                                <strong>{{ grupo.nombre }}</strong>
                                <v-spacer />
                                <v-chip x-small color="primary" outlined>
                                    {{ grupo.permisos.length }}
                                </v-chip>
                            </div>

                            <div class="detail-permission-chips">
                                <v-chip v-for="permiso in grupo.permisos" :key="permiso.value" small outlined color="primary">
                                    {{ permiso.label }}
                                </v-chip>
                            </div>
                        </div>
                    </div>
                </v-card-text>

                <v-card-actions class="px-5 pb-5">
                    <v-spacer />
                    <v-btn rounded outlined color="primary" @click="cerrarPermisosRol">
                        Cerrar
                    </v-btn>
                    <v-btn v-if="$can('roles.editar')" rounded depressed color="primary" @click="editarRolDesdeDetalle">
                        <v-icon left>mdi-pencil-outline</v-icon>
                        Editar este rol
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
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
            modalPermisosRol: false,
            rolDetalle: null,
            cargando: false,
            guardando: false,
            guardandoPermiso: false,
            cargandoPermisos: false,
            form: this.formInicial(),
            permisoForm: this.permisoInicial(),
            headers: [
                { text: 'Nombre', value: 'nombre' },
                { text: 'Slug', value: 'slug' },
                { text: 'Permisos', value: 'permisos', align: 'center' },
                { text: 'Estado', value: 'estado', align: 'center' },
                { text: 'Acciones', value: 'acciones', align: 'center', sortable: false },
            ],
            buscarPermiso: '',
            headersPermisos: [
                { text: 'Permiso', value: 'name' },
                { text: 'Nombre visible', value: 'label' },
                { text: 'Grupo', value: 'grupo' },
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

        puedeGuardarPermiso() {
            return this.permisoForm.id ? this.$can('permisos.editar') : this.$can('permisos.crear')
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
                const nombre = permiso.grupo || this.nombreGrupo(permiso.value)

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

        permisosFiltrados() {
            const permisos = this.permisos.length ? this.permisos : this.$permissionsCatalog

            if (!this.buscarPermiso) {
                return permisos
            }

            const busqueda = this.buscarPermiso.toLowerCase()

            return permisos.filter(permiso => `${permiso.name || permiso.value} ${permiso.label} ${permiso.grupo || ''}`.toLowerCase().includes(busqueda))
        },

        permisosRolDetalle() {
            if (!this.rolDetalle) {
                return []
            }

            const permisosRol = this.rolDetalle.permisos || []
            const catalogo = this.permisos.length ? this.permisos : this.$permissionsCatalog

            return permisosRol.map((valor) => {
                const permiso = catalogo.find(item => item.value === valor || item.name === valor)

                return permiso || {
                    value: valor,
                    name: valor,
                    label: this.etiquetaPermiso(valor),
                    grupo: this.nombreGrupo(valor),
                }
            })
        },

        permisosRolAgrupados() {
            const grupos = {}

            this.permisosRolDetalle.forEach((permiso) => {
                const nombre = permiso.grupo || this.nombreGrupo(permiso.value || permiso.name)

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
        this.listarPermisos()
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

        permisoInicial() {
            return {
                id: null,
                name: '',
                label: '',
                grupo: '',
                descripcion: '',
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

        async listarPermisos() {
            if (!this.$can('permisos.ver')) {
                return
            }

            try {
                this.cargandoPermisos = true
                const response = await this.$axios.post('/permisos/listar', { buscar: this.buscarPermiso })
                this.permisos = response.data?.permisos || this.$permissionsCatalog
            } catch (error) {
                this.$toast.error('No se pudieron listar los permisos.')
            } finally {
                this.cargandoPermisos = false
            }
        },

        nuevoRol() {
            this.form = this.formInicial()

            if (this.$refs.formulario) {
                this.$refs.formulario.resetValidation()
            }
        },

        nuevoPermiso() {
            this.permisoForm = this.permisoInicial()

            if (this.$refs.formularioPermiso) {
                this.$refs.formularioPermiso.resetValidation()
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

        verPermisosRol(rol) {
            this.rolDetalle = { ...rol }
            this.modalPermisosRol = true
        },

        cerrarPermisosRol() {
            this.modalPermisosRol = false
            this.rolDetalle = null
        },

        editarRolDesdeDetalle() {
            const rol = { ...this.rolDetalle }
            this.cerrarPermisosRol()
            this.editarRol(rol)
        },

        editarPermiso(permiso) {
            this.permisoForm = {
                id: permiso.id,
                name: permiso.name || permiso.value || '',
                label: permiso.label || '',
                grupo: permiso.grupo || '',
                descripcion: permiso.descripcion || '',
                estado: permiso.estado !== false,
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
                await this.listarPermisos()
            } catch (error) {
                this.$toast.error('No se pudo guardar el rol.')
            } finally {
                this.guardando = false
            }
        },

        async guardarPermiso() {
            if (!this.$refs.formularioPermiso.validate()) {
                return
            }

            try {
                this.guardandoPermiso = true
                const payload = { ...this.permisoForm }

                if (payload.id) {
                    await this.$axios.put(`/permisos/actualizar/${payload.id}`, payload)
                    this.$toast.success('Permiso actualizado correctamente.')
                } else {
                    await this.$axios.post('/permisos/crear', payload)
                    this.$toast.success('Permiso creado correctamente.')
                }

                this.nuevoPermiso()
                await this.listarPermisos()
            } catch (error) {
                this.$toast.error('No se pudo guardar el permiso.')
            } finally {
                this.guardandoPermiso = false
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
                bodegas: 'Bodegas',
                ventas: 'Ventas',
                imagenes: 'Imágenes',
                usuarios: 'Usuarios',
                roles: 'Roles y permisos',
            }

            return nombres[modulo] || 'Otros'
        },

        etiquetaPermiso(permiso) {
            return permiso
                .replace(/[.-]/g, ' ')
                .replace(/\b\w/g, letra => letra.toUpperCase())
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

.role-permissions-modal {
    border-radius: 22px !important;
    overflow: hidden;
}

.role-permissions-header {
    align-items: flex-start;
    background: linear-gradient(135deg, #12386f 0%, #0d7880 100%);
    color: #fff;
    display: flex;
    justify-content: space-between;
    padding: 26px 30px;
}

.role-permissions-header span {
    color: #74f2f2;
    font-size: 11px;
    font-weight: 950;
    letter-spacing: 1.4px;
    text-transform: uppercase;
}

.role-permissions-header h3 {
    font-size: 30px;
    font-weight: 950;
    margin: 5px 0;
}

.role-permissions-header p {
    color: rgba(255, 255, 255, .78);
    font-size: 14px;
    margin: 0;
}

.role-summary,
.detail-permission-title,
.detail-permission-chips {
    align-items: center;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.detail-permission-group {
    background: linear-gradient(145deg, #ffffff, #f7fbfd);
    border: 1px solid #e0eaf2;
    border-radius: 16px;
    margin-bottom: 12px;
    padding: 16px;
}

.detail-permission-title {
    color: #17365d;
    margin-bottom: 12px;
}
</style>
