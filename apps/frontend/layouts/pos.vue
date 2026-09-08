<template>
  <v-app class="pos-shell">
    <Nuxt />
    <AppToast />
    <AppPreload />
  </v-app>
</template>

<script>
import AppToast from '~/components/AppToast.vue'
import AppPreload from '~/components/AppPreload.vue'

export default {
  components: {
    AppToast,
    AppPreload,
  },
}
</script>

<style scoped>
.pos-shell {
  background:
    radial-gradient(circle at 8% 12%, rgba(30, 136, 229, .14), transparent 28%),
    radial-gradient(circle at 92% 8%, rgba(25, 196, 99, .12), transparent 26%),
    #f4f8fb;
  color: #243b53;
}
</style>
