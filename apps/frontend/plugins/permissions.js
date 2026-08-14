export default function (_context, inject) {
    const obtenerUsuario = () => {
        if (!process.client) {
            return null
        }

        try {
            return JSON.parse(sessionStorage.getItem('embacolsa_user') || 'null')
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
}
