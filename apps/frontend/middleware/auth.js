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
}
