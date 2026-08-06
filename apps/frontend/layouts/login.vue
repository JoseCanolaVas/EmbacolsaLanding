<template>
    <v-app>
        <v-main>
            <Nuxt />
        </v-main>
        <AppToast />
    </v-app>
</template>

<script>
import AppToast from '~/components/AppToast.vue'

export default {
    name: 'LoginLayout',

    components: {
        AppToast,
    },
}
</script>