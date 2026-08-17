// plugins/axios.js

export default function ({ $axios, redirect }) {
    $axios.onRequest((config) => {
        if (process.client) {
            const token = sessionStorage.getItem('softnova_token')

            if (token) {
                config.headers.common.Authorization = `Bearer ${token}`
            }
        }

        return config
    })

    $axios.onError((error) => {
        const status = error.response?.status

        if (status === 401 && process.client) {
            sessionStorage.removeItem('softnova_token')
            sessionStorage.removeItem('softnova_user')

            if (window.location.pathname !== '/login') {
                redirect('/login')
            }
        }

        return Promise.reject(error)
    })
}
