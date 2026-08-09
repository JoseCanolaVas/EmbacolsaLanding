import colors from 'vuetify/es5/util/colors'

export default {
    ssr: false,

    head: {
        titleTemplate: '%s | Embacolsa',
        title: 'Embacolsa - Embalajes y suministros',

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
        '~/plugins/toast.js',
        '~/plugins/preload.js'
    ],

    components: true,

    buildModules: [
        '@nuxtjs/vuetify',
    ],

    modules: [
        '@nuxtjs/axios',
    ],

    publicRuntimeConfig: {
        API_URL:
            process.env.API_URL ||
            'http://localhost:8000/api',
    },

    axios: {
        baseURL:
            process.env.API_URL ||
            'http://localhost:8000/api',
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
        extractCSS: true,
        optimization: {
            splitChunks: {
                chunks: 'all',
                automaticNameDelimiter: '.',
                name: true,
            },
        },
        postcss: {
            postcssOptions: {
                plugins: {},
            },
        },
    },
}
