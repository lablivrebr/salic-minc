<template>
    <div>
        <v-card class="my-1; " hover v-for="(comunicado, index) in comunicados" :key="index"
                v-if="pagination.page == (index+1)">
            <v-toolbar
                card
                dense
            >
                <v-toolbar-title>
                    <span class="subheading">Comunicado {{ index + 1 }}</span>
                </v-toolbar-title>

            </v-toolbar>
            <v-card-text v-html="comunicado.comunicado"></v-card-text>
        </v-card>
        <div class="text-xs-center mt-2">
            <v-pagination v-model="pagination.page" :length="pages"></v-pagination>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: 'comunicados',
        data: () => ({
            pagination: {
                page: 1,
                rowsPerPage: 1,
            },
        }),
        created() {
            this.obterComunicados();
        },
        computed: {
            ...mapGetters({
                comunicados: 'paginaInicial/obterComunicados',
            }),
            pages() {
                return this.comunicados.length;
            },
        },
        methods: {
            ...mapActions({
                obterComunicados: 'paginaInicial/buscarComunicados',
            }),
        },
    };
</script>

<style scoped>

</style>
