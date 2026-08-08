<template>
    <v-overlay :value="$preloadState.visible" z-index="9999">
        <v-progress-circular indeterminate size="64" width="6" color="white" />
    </v-overlay>
</template>

<script>
export default {
    name: 'AppPreload'
}
</script>