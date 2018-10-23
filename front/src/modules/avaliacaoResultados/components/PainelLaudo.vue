<template>
    <v-container fluid>
        <v-card>
            <v-toolbar
                color="green darken-1"
                dark
                tabs
                height="40px"
            >
                <v-toolbar-title>Laudo final</v-toolbar-title>
            </v-toolbar>
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
                    :id="'tab-0'"
                    :key="0"
                >
                    <v-card flat>
                        <Laudo :dados="getProjetosLaudoFinal"
                               :acao="true"
                        ></Laudo>
                    </v-card>
                </v-tab-item>
                <v-tab-item
                    :id="'tab-1'"
                    :key="1"
                >
                    <v-card flat>
                        <Laudo :dados="getProjetosLaudoAssinar"
                        ></Laudo>
                    </v-card>
                </v-tab-item>
                <v-tab-item
                    :id="'tab-2'"
                    :key="2"
                >
                    <v-card flat>
                        <v-card>
                            <Laudo :dados="getProjetosLaudoEmAssinatura"
                            ></Laudo>
                        </v-card>
                    </v-card>
                </v-tab-item>
                <v-tab-item
                    :id="'tab-3'"
                    :key="3"
                >
                    <v-card flat>
                        <v-card>
                            <Laudo :dados="getProjetosLaudoFinalizados"
                            ></Laudo>
                        </v-card>
                    </v-card>
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
    data() {
        return { };
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
            usuarioLogado: 'autenticacao/usuarioLogado',
        }),
    },
    computed: {
        ...mapGetters({
            getProjetosLaudoFinal: 'avaliacaoResultados/getProjetosLaudoFinal',
            getProjetosLaudoAssinar: 'avaliacaoResultados/getProjetosLaudoAssinar',
            getProjetosLaudoEmAssinatura: 'avaliacaoResultados/getProjetosLaudoEmAssinatura',
            getProjetosLaudoFinalizados: 'avaliacaoResultados/getProjetosLaudoFinalizados',
            getUsuario: 'autenticacao/getUsuario',
        }),
    },
};
</script>