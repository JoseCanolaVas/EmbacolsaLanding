import colors from 'vuetify/es5/util/colors'

const isDev = process.env.NODE_ENV !== 'production'
const apiUrl = process.env.API_URL || '/api'
const backendUrl = process.env.BACKEND_URL || 'http://localhost:8000'

export default {
    ssr: false,
    target: 'static',

    head: {
        titleTemplate: '%s | NovaCell',
        title: 'NovaCell',

        htmlAttrs: {
            lang: 'es',
        },

        meta: [
            {
                charset: 'utf-8',
            },
            {
                name: 'viewport',
                content: 'width=device-width, initial-scale=1',
            },
            {
                hid: 'description',
                name: 'description',
                content:
                    'Soluciones de empaque, embalaje y seguridad para empresas en Colombia.',
            },
            {
                name: 'format-detection',
                content: 'telephone=no',
            },
        ],

        link: [
            {
                rel: 'icon',
                type: 'image/x-icon',
                href: '/favicon.ico',
            },
        ],
    },

    css: [
        '@mdi/font/css/materialdesignicons.css',
    ],

    plugins: [
        '~/plugins/axios.js',
        '~/plugins/permissions.js',
        '~/plugins/toast.js',
        '~/plugins/preload.js'
    ],

    components: true,

    buildModules: [
        '@nuxtjs/vuetify',
    ],

    modules: [
        '@nuxtjs/axios',
        '@nuxtjs/proxy',
    ],

    env: {
        API_URL: apiUrl,
    },

    publicRuntimeConfig: {
        API_URL: apiUrl,
    },

    axios: {
        baseURL: apiUrl,
        browserBaseURL: apiUrl,
        proxy: apiUrl === '/api',
    },

    proxy: {
        '/api/': {
            target: backendUrl,
            changeOrigin: true,
        },
        '/storage/': {
            target: backendUrl,
            changeOrigin: true,
        },
    },

    router: {
        middleware: ['auth'],
        prefetchLinks: false,
    },

    vuetify: {
        icons: {
            iconfont: 'mdi',
        },

        customVariables: [
            '~/assets/variables.scss',
        ],

        theme: {
            dark: false,

            themes: {
                light: {
                    primary: colors.blue.darken2,
                    accent: colors.grey.darken3,
                    secondary: colors.amber.darken3,
                    info: colors.teal.lighten1,
                    warning: colors.amber.base,
                    error: colors.deepOrange.accent4,
                    success: colors.green.accent3,
                },
            },
        },
    },

    build: {
        cache: true,
        hardSource: isDev,
        parallel: true,
        extractCSS: !isDev,
        optimization: {
            splitChunks: {
                chunks: 'all',
                automaticNameDelimiter: '.',
                name: !isDev,
            },
        },
        loaders: {
            vue: {
                prettify: false,
            },
        },
        postcss: {
            postcssOptions: {
                plugins: {},
            },
        },
    },
}
