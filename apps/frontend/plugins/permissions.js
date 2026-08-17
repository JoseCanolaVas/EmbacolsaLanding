export default function (_context, inject) {
    const permisosSistema = [
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
        { label: 'Ver roles', value: 'roles.ver' },
        { label: 'Crear roles', value: 'roles.crear' },
        { label: 'Editar roles', value: 'roles.editar' },
        { label: 'Administrar sitio público', value: 'administrar-sitio' },
    ]

    const obtenerUsuario = () => {
        if (!process.client) {
            return null
        }

        try {
            return JSON.parse(sessionStorage.getItem('softnova_user') || 'null')
        } catch (error) {
            return null
        }
    }

    const can = (permiso) => {
        const usuario = obtenerUsuario()

        if (!usuario) {
            return false
        }

        if (usuario.es_super_admin) {
            return true
        }

        return Array.isArray(usuario.permisos) && usuario.permisos.includes(permiso)
    }

    inject('currentUser', obtenerUsuario)
    inject('can', can)
    inject('permissionsCatalog', permisosSistema)
}
