<template>
    <v-container fluid>
        <v-card>
            <v-tabs
                centered
                color="green"
                dark
                icons-and-text
            >
                <v-tabs-slider color="deep-orange accent-3"></v-tabs-slider>
                <v-tab href="#tab-0"
                    v-if="getUsuario.grupo_ativo == 125"
                >
                    <template v-if="Object.keys(getProjetosParaDistribuir).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Encaminhar
                        <v-icon>assignment_ind</v-icon>
                    </template>
                </v-tab>
                <v-tab href="#tab-1">
                    <template v-if="Object.keys(dadosTabelaTecnico).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="primary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Em Analise
                        <v-icon>gavel</v-icon>
                    </template>
                </v-tab>

                <v-tab href="#tab-2">
                     Assinar
                    <v-icon>done</v-icon>
                </v-tab>

                <v-tab href="#tab-4">
                     Historico
                    <v-icon>history</v-icon>
                </v-tab>

                <v-tab-item
                    :value="'tab-0'"
                    :key="0"
                >
                    <TabelaProjetos
                        v-if="getProjetosParaDistribuir"
                        :dados="getProjetosParaDistribuir"
                        :componentes="distribuirAcoes"
                    ></TabelaProjetos>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-1'"
                    :key="1"
                >
                    <v-card flat
                        v-if="dadosTabelaTecnico"
                    >
                        <v-card-text>
                            <TabelaProjetos
                                v-if="getUsuario.grupo_ativo == 125"
                                :analisar="true"
                                :dados="dadosTabelaTecnico"
                                :componentes="listaAcoesCoordenador"
                                :mostrarTecnico="true"
                            ></TabelaProjetos>
                            <TabelaProjetos
                                v-else
                                :analisar="true"
                                :dados="dadosTabelaTecnico"
                                :componentes="listaAcoesTecnico"
                            ></TabelaProjetos>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
                <v-tab-item
                    :value="'tab-2'"
                    :key="2"
                >
                    <v-card flat>
                        <v-card-text>
                            <TabelaProjetos
                                :dados="getProjetosFinalizados"
                                :componentes="listaAcoesAssinar"
                            ></TabelaProjetos>
                        </v-card-text>
                    </v-card>
                </v-tab-item>

                <v-tab-item
                    :value="'tab-4'"
                    :key="4"
                >
                    <v-card flat>
                        <v-card-text>
                            <TabelaProjetos
                                :dados="getProjetosHistorico"
                                :componentes="listaAcoesTecnico"
                            ></TabelaProjetos>
                        </v-card-text>
                    </v-card>
                </v-tab-item>

            </v-tabs>
        </v-card>
    </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import TabelaProjetos from './TabelaProjetos';
import Historico from './Historico';
import Encaminhar from './ComponenteEncaminhar';
import AnaliseButton from './analise/analisarButton';
import AssinarButton from './analise/AssinarButton';
import Devolver from './Devolver';

export default {
    name: 'Painel',
    created() {
        this.distribuir({ estadoid: 6 });

        this.projetosAssinatura({ estado: 'assinar' });
        this.projetosAssinatura({ estado: 'em_assinatura' });
        this.projetosAssinatura({ estado: 'historico' });

        this.syncRevisao({ estadoid: 13 });
        this.usuarioLogado();
    },
    mounted() {
    },
    watch: {
        getUsuario(val) {
            if (Object.keys(val).length > 0 && parseInt(val.usu_codigo, 10) !== 0) {
                let projetosTecnico = {};
                let projetosFinalizados = {};

                if (parseInt(this.getUsuario.grupo_ativo, 10) === 125) {
                    projetosTecnico = {
                        estadoid: 5,
                    };

                    projetosFinalizados = {
                        estadoid: 6,
                    };
                } else {
                    projetosTecnico = {
                        estadoid: 5,
                        idAgente: this.getUsuario.usu_codigo,
                    };

                    projetosFinalizados = {
                        estadoid: 6,
                        idAgente: this.getUsuario.usu_codigo,
                    };
                }

                this.obterDadosTabelaTecnico(projetosTecnico);
                this.projetosFinalizados(projetosFinalizados);
                this.distribuir({ estadoid: 6 });
                this.syncRevisao({ estadoid: 13 });
            }
        },
    },
    data() {
        return {
            listaAcoesTecnico: { atual: '', proximo: '', acoes: [Historico, AnaliseButton] },
            listaAcoesAssinar: { atual: '6', proximo: '5', acoes: [Historico, AssinarButton, Devolver] },
            listaAcoesCoordenador: { atual: '', proximo: '', acoes: [Encaminhar, Historico] },
            distribuirAcoes: { atual: '', proximo: '', acoes: [Encaminhar] },
        };
    },
    components: {
        TabelaProjetos,
    },
    methods: {
        ...mapActions({
            obterDadosTabelaTecnico: 'avaliacaoResultados/obterDadosTabelaTecnico',
            projetosFinalizados: 'avaliacaoResultados/projetosFinalizados',
            projetosAssinatura: 'avaliacaoResultados/projetosAssinatura',
            distribuir: 'avaliacaoResultados/projetosParaDistribuir',
            syncRevisao: 'avaliacaoResultados/projetosRevisao',
            usuarioLogado: 'autenticacao/usuarioLogado',
        }),
    },
    computed: {
        ...mapGetters({
            dadosTabelaTecnico: 'avaliacaoResultados/dadosTabelaTecnico',
            getProjetosFinalizados: 'avaliacaoResultados/getProjetosFinalizados',
            getProjetosAssinar: 'avaliacaoResultados/getProjetosAssinar',
            getProjetosEmAssinatura: 'avaliacaoResultados/getProjetosEmAssinatura',
            getProjetosHistorico: 'avaliacaoResultados/getProjetosHistorico',
            getProjetosParaDistribuir: 'avaliacaoResultados/getProjetosParaDistribuir',
            getUsuario: 'autenticacao/getUsuario',
            getProjetosRevisao: 'avaliacaoResultados/getProjetosRevisao',
        }),
    },
};
</script>
