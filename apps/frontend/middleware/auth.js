export default function ({ route, redirect }) {
  if (!process.client) {
    return
  }

  const token = sessionStorage.getItem('embacolsa_token')

  if (route.path === '/login' && token) {
    return redirect('/modulo-parametrizacion')
  }

  const esRutaPublica =
    route.path === '/' ||
    route.path === '/login' ||
    route.path.startsWith('/catalogo')

  if (esRutaPublica) {
    return
  }

  if (!token) {
    return redirect('/login')
  }
}
