export default function ({ route, redirect }) {
  if (!process.client) {
    return
  }

  const token = sessionStorage.getItem('embacolsa_token')
  const rutaNormalizada = route.path.replace(/\/+$/, '') || '/'

  if (rutaNormalizada === '/login' && token) {
    return redirect('/modulo-parametrizacion')
  }

  const esRutaPublica =
    rutaNormalizada === '/' ||
    rutaNormalizada === '/login' ||
    rutaNormalizada.startsWith('/catalogo')

  if (esRutaPublica) {
    return
  }

  if (!token) {
    return redirect('/login')
  }

  const permisosPorRuta = [
    { prefijo: '/modulo-parametrizacion/productos', permiso: 'productos.ver' },
    { prefijo: '/modulo-parametrizacion/categorias', permiso: 'categorias.ver' },
    { prefijo: '/modulo-parametrizacion/marcas', permiso: 'marcas.ver' },
    { prefijo: '/modulo-parametrizacion/imagenes', permiso: 'imagenes.ver' },
    { prefijo: '/modulo-parametrizacion/usuarios', permiso: 'usuarios.ver' },
    { prefijo: '/modulo-parametrizacion/sitio-publico', permiso: 'administrar-sitio' },
  ]

  const regla = permisosPorRuta.find(item => rutaNormalizada.startsWith(item.prefijo))

  if (!regla) {
    return
  }

  try {
    const usuario = JSON.parse(sessionStorage.getItem('embacolsa_user') || '{}')

    if (usuario.es_super_admin) {
      return
    }

    if (Array.isArray(usuario.permisos) && usuario.permisos.includes(regla.permiso)) {
      return
    }
  } catch (error) {
    // Si el usuario local está corrupto, lo devolvemos al panel.
  }

  return redirect('/modulo-parametrizacion')
}
