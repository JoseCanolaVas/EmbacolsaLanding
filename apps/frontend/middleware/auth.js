export default function ({ route, redirect }) {
  if (route.path === '/login') {
    return
  }

  if (!process.client) {
    return
  }

  const token = sessionStorage.getItem('embacolsa_token')

  if (!token) {
    return redirect('/login');
  }
}