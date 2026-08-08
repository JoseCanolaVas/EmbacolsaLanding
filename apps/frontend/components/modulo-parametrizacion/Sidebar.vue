<template>
    <v-list dense nav class="sidebar-menu">
        <v-subheader class="menu-label">
            Contenido
        </v-subheader>

        <v-list-item v-for="item in menu" :key="item.title" :to="item.route" router exact class="menu-item">
            <v-list-item-icon>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
                <v-list-item-title class="menu-title">
                    {{ item.title }}
                </v-list-item-title>
                <v-list-item-subtitle class="menu-subtitle">
                    {{ item.subtitle }}
                </v-list-item-subtitle>
            </v-list-item-content>
        </v-list-item>
    </v-list>
</template>

<script>
export default {
    name: 'Sidebar',

    data() {
        return {
            menu: [
                {
                    title: 'Categorías',
                    subtitle: 'Agrupa la vitrina',
                    icon: 'mdi-format-list-bulleted-type',
                    route: '/modulo-parametrizacion/categorias',
                },
                {
                    title: 'Productos',
                    subtitle: 'Catalogo visible',
                    icon: 'mdi-package-variant-closed',
                    route: '/modulo-parametrizacion/productos',
                },
                {
                    title: 'Imágenes',
                    subtitle: 'Logo, banners y piezas',
                    icon: 'mdi-image-multiple-outline',
                    route: '/modulo-parametrizacion/imagenes',
                },
                {
                    title: 'Usuarios',
                    subtitle: 'Accesos internos',
                    icon: 'mdi-account-group-outline',
                    route: '/modulo-parametrizacion/usuarios',
                },
                {
                    title: 'Marcas',
                    subtitle: 'Aliados y fabricantes',
                    icon: 'mdi-tag-multiple-outline',
                    route: '/modulo-parametrizacion/marcas',
                },
                {
                    title: 'Presentaciones',
                    subtitle: 'Formatos comerciales',
                    icon: 'mdi-cube-outline',
                    route: '/modulo-parametrizacion/presentaciones',
                },
                {
                    title: 'Roles',
                    subtitle: 'Perfiles',
                    icon: 'mdi-account-key-outline',
                    route: '/modulo-parametrizacion/roles',
                },
                {
                    title: 'Permisos',
                    subtitle: 'Control de acciones',
                    icon: 'mdi-lock-check-outline',
                    route: '/modulo-parametrizacion/permisos',
                },
            ],
        }
    },
}
</script>

<style scoped>
.sidebar-menu {
    background: transparent;
    padding: 8px 12px 120px;
}

.menu-label {
    color: #8eeaf0 !important;
    font-size: 11px;
    font-weight: 900;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.menu-item {
    border-radius: 8px;
    margin: 5px 0;
    min-height: 58px;
    transition: background .2s, transform .2s;
}

.menu-item:hover {
    background: rgba(255, 255, 255, .09) !important;
    transform: translateX(2px);
}

.menu-item.v-list-item--active {
    background: rgba(255, 255, 255, .16) !important;
    border-left: 4px solid #e9a11a;
}

.menu-item .v-icon {
    color: #cceff2;
}

.menu-item.v-list-item--active .v-icon {
    color: #fff;
}

.menu-title {
    color: #fff !important;
    font-size: 14px;
    font-weight: 800;
}

.menu-subtitle {
    color: #b9d4dc !important;
    font-size: 11px;
}

::v-deep .v-list-item--active::before,
::v-deep .theme--dark.v-list-item--active::before {
    opacity: 0 !important;
}
</style>
