<template>
    <v-container fluid>
        <h1 class="font-weight-regular">Laudo final</h1>
        <v-card>
            <v-tabs
                centered
                color="green darken-1"
                dark
                icons-and-text
            >
                <v-tabs-slider color="deep-orange accent-3"></v-tabs-slider>
                <v-tab href="#tab-0">
                    <template v-if="Object.keys(getProjetosLaudoFinal).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Em Analise
                        <v-icon>how_to_reg</v-icon>
                    </template>
                </v-tab>
                <v-tab href="#tab-1">
                     Assinar
                    <v-icon>done</v-icon>
                </v-tab>
                <v-tab href="#tab-2">
                     Em assinatura
                    <v-icon>done_all</v-icon>
                </v-tab>
                <v-tab href="#tab-3">
                     Finalizados
                    <v-icon>collections_bookmark</v-icon>
                </v-tab>

                <v-tab-item
                    :value="'tab-0'"
                    :key="0"
                >
                    <Laudo :dados="getProjetosLaudoFinal"
                            :acao="'analisar'"
                    ></Laudo>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-1'"
                    :key="1"
                >
                    <Laudo :dados="getProjetosLaudoAssinar"
                            :acao="'assinar'"
                    ></Laudo>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-2'"
                    :key="2"
                >
                    <Laudo :dados="getProjetosLaudoEmAssinatura"
                            :acao="'visualizar'"
                    ></Laudo>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-3'"
                    :key="3"
                >
                    <Laudo :dados="getProjetosLaudoFinalizados"
                            :acao="'visualizar'"
                    ></Laudo>
                </v-tab-item>
            </v-tabs>
        </v-card>
    </v-container>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import Laudo from './Laudo';

export default {
    name: 'PainelLaudo',
    created() {
        this.obterProjetosLaudoFinal({ estadoId: 10 });
        this.obterProjetosLaudoAssinar({ estadoId: 14 });
        this.obterProjetosLaudoEmAssinatura({ estadoId: 11 });
        this.obterProjetosLaudoFinalizados({ estadoId: 12 });
    },
    components: {
        Laudo,
    },
    methods: {
        ...mapActions({
            obterProjetosLaudoFinal: 'avaliacaoResultados/obterProjetosLaudoFinal',
            obterProjetosLaudoAssinar: 'avaliacaoResultados/obterProjetosLaudoAssinar',
            obterProjetosLaudoEmAssinatura: 'avaliacaoResultados/obterProjetosLaudoEmAssinatura',
            obterProjetosLaudoFinalizados: 'avaliacaoResultados/obterProjetosLaudoFinalizados',
        }),
    },
    computed: {
        ...mapGetters({
            getProjetosLaudoFinal: 'avaliacaoResultados/getProjetosLaudoFinal',
            getProjetosLaudoAssinar: 'avaliacaoResultados/getProjetosLaudoAssinar',
            getProjetosLaudoEmAssinatura: 'avaliacaoResultados/getProjetosLaudoEmAssinatura',
            getProjetosLaudoFinalizados: 'avaliacaoResultados/getProjetosLaudoFinalizados',
        }),
    },
};
</script>