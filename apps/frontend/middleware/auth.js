export default function ({ route, redirect }) {
  if (!process.client) {
    return
  }

  const token = sessionStorage.getItem('embacolsa_token')

  const rutasPublicas = ['/login', '/']

  const esRutaPublica = rutasPublicas.includes(route.path)

  if (!token && !esRutaPublica) {
    return redirect('/login')
  }

  if (token && route.path === '/login') {
    return redirect('/')
  }
}
