<template>
    <v-list dense nav class="sidebar-menu">
        <v-list-item v-for="item in menu" :key="item.title" :to="item.route" router exact class="menu-item">
            <v-list-item-icon>
                <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-content>
                <v-list-item-title class="menu-title">
                    {{ item.title }}
                </v-list-item-title>
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
                    title: 'Inicio',
                    icon: 'mdi-view-dashboard-outline',
                    route: '/modulo-parametrizacion',
                },
                {
                    title: 'Categorías',
                    icon: 'mdi-format-list-bulleted-type',
                    route: '/modulo-parametrizacion/categorias',
                },
                {
                    title: 'Productos',
                    icon: 'mdi-package-variant-closed',
                    route: '/modulo-parametrizacion/productos',
                },
                {
                    title: 'Gestion de Contenido',
                    icon: 'mdi-image-multiple-outline',
                    route: '/modulo-parametrizacion/imagenes',
                },
                {
                    title: 'Usuarios',
                    icon: 'mdi-account-group-outline',
                    route: '/modulo-parametrizacion/usuarios',
                },
                {
                    title: 'Marcas',
                    icon: 'mdi-tag-multiple-outline',
                    route: '/modulo-parametrizacion/marcas',
                },
            ],
        }
    },
}
</script>

<style scoped>
.sidebar-menu {
    background: transparent;
    padding: 6px 12px 76px;
}

.menu-item {
    border-radius: 6px;
    margin: 3px 0;
    min-height: 46px;
    padding-left: 12px !important;
    transition: background .2s, border-color .2s;
}

.menu-item:hover {
    background: rgba(255, 255, 255, .08) !important;
}

.menu-item.v-list-item--active {
    background: rgba(255, 255, 255, .14) !important;
    border-left: 3px solid #f0b429;
}

.menu-item .v-icon {
    color: rgba(255, 255, 255, .74);
    font-size: 21px;
}

.menu-item.v-list-item--active .v-icon {
    color: #fff;
}

.menu-title {
    color: #fff !important;
    font-size: 14px;
    font-weight: 500;
}

::v-deep .v-list-item--active::before,
::v-deep .theme--dark.v-list-item--active::before {
    opacity: 0 !important;
}
</style>
