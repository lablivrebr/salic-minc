<template>
    <v-layout>
        <v-card class="container-fluid row">
            <v-card-title primary-title>
                <div class="headline mb-0">
                    <h3>Nome Projeto - {{nomeProjeto}}</h3>
                    <div>
                        <h3>Pronac - {{pronac}}</h3>
                    </div>
                </div>
            </v-card-title>

            <v-card-actions>
                <v-btn flat color="green">
                    <router-link :to="{ name: 'dadosprojeto', params: { idPronac: dadosProjeto.idPronac }}">
                        <h6>Ver Projeto</h6>
                    </router-link>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-layout>
</template>
<script>
    import { mapGetters } from 'vuex';

    export default {
        name: 'IdentificacaoProjeto',
        props: ['pronac', 'nomeProjeto'],
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
            }),
        },
    };
</script>
