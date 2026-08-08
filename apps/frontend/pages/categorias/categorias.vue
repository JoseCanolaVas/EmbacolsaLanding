<template>
    <v-card>

    </v-card>

</template>
<script>
export default {
    name: "CategoriasLanding",

    data() {
        return {
            
        };
    },

    mounted() {
        this.buscarCategorias();
    },

    methods: {
        async buscarCategorias() {
            try {



            } catch (error) {
                this.$toast.error("Ha ocurrido un error al buscar las categorías");
            }
        }
    },


};
</script>