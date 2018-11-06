<template>
    <v-container fluid>
        <v-subheader>
            <h2>{{route.meta.title}}</h2>
        </v-subheader>
        <v-card>
            <v-tabs
                centered
                color="primary"
                dark
                icons-and-text
            >
                <v-tabs-slider color="deep-orange accent-3"></v-tabs-slider>
                <v-tab
                    href="#tab-0"
                    v-if="getUsuario.grupo_ativo == 125"
                >
                    <template v-if="Object.keys(getProjetosParaDistribuir).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="secondary"
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
                            color="secondary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Em Analise
                        <v-icon>gavel</v-icon>
                    </template>
                </v-tab>

                <v-tab href="#tab-2">
                    <template v-if="Object.keys(getProjetosFinalizados).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="secondary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Assinar
                        <v-icon>edit</v-icon>
                    </template>
                </v-tab>

                <v-tab href="#tab-4">
                    <template v-if="Object.keys(getProjetosHistorico).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="secondary"
                            dark
                        ></v-progress-circular>
                    </template>
                    <template v-else>
                        Historico
                        <v-icon>history</v-icon>
                    </template>
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
                                v-if="(getUsuario.grupo_ativo == 125 || getUsuario.grupo_ativo == 126)"
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
                                v-if="(getUsuario.grupo_ativo == CONST.PERFIL_COORDENADOR)"
                                :dados="getProjetosAssinarCoordenador"
                                :componentes="listaAcoesAssinar"
                            ></TabelaProjetos>
                            <TabelaProjetos
                                v-else-if="(getUsuario.grupo_ativo == CONST.PERFIL_COORDENADOR_GERAL)"
                                :dados="getProjetosAssinarCoordenadorGeral"
                                :componentes="listaAcoesAssinarCoordenadorGeral"
                            ></TabelaProjetos>
                            <TabelaProjetos
                                v-else
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
                                :componentes="historicoAcoes"
                            ></TabelaProjetos>
                        </v-card-text>
                    </v-card>
                </v-tab-item>
            </v-tabs>
        </v-card>
    </v-container>
</template>
<script>

import Vue from 'vue';
import { mapActions, mapGetters } from 'vuex';
import CONST from '../../const';
import TabelaProjetos from '../TabelaProjetos';
import Historico from '../components/Historico';
import Encaminhar from '../ComponenteEncaminhar';
import AnaliseButton from '../analise/analisarButton';
import AssinarButton from '../analise/AssinarButton';
import Devolver from '../components/Devolver';
import VisualizarPlanilhaButtton from '../analise/VisualizarPlanilhaButtton';

export default {
    name: 'Painel',
    created() {
        this.projetosAssinatura({ estado: 'historico' });

        this.CONST = CONST;

        let projetosTecnico = {};
        let projetosFinalizados = {};

        if (
            parseInt(this.getUsuario.grupo_ativo, 10) === 125
            || parseInt(this.getUsuario.grupo_ativo, 10) === 126
        ) {
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

        this.distribuir();
        this.obterDadosTabelaTecnico(projetosTecnico);
        this.projetosFinalizados(projetosFinalizados);
        this.projetosAssinarCoordenador();
        this.projetosAssinarCoordenadorGeral();
        Vue.set(this.listaAcoesAssinar, 'usuario', this.getUsuario);
    },
    watch: {
    },
    data() {
        return {
            listaAcoesTecnico: {
                atual: '',
                proximo: '',
                acoes: [Historico, AnaliseButton],
            },
            listaAcoesAssinar: {
                usuario: this.getUsuario,
                atual: CONST.ESTADO_PARECER_FINALIZADO,
                proximo: CONST.ESTADO_ANALISE_PARECER,
                idTipoDoAtoAdministrativo: CONST.ATO_ADMINISTRATIVO_PARECER_TECNICO,
                acoes: [Historico, AssinarButton, Devolver, VisualizarPlanilhaButtton],
            },
            listaAcoesCoordenador: {
                usuario: this.getUsuario,
                atual: '',
                proximo: '',
                acoes: [Encaminhar, Historico, VisualizarPlanilhaButtton] },
            listaAcoesAssinarCoordenadorGeral: {
                usuario: this.getUsuario,
                atual: CONST.ESTADO_AGUARDANDO_ASSINATURA_COORDENADOR_PARECER,
                proximo: CONST.ESTADO_ANALISE_PARECER,
                idTipoDoAtoAdministrativo: CONST.ATO_ADMINISTRATIVO_PARECER_TECNICO,
                acoes: [Historico, AssinarButton, Devolver, VisualizarPlanilhaButtton],
            },
            distribuirAcoes: { atual: '', proximo: '', acoes: [Encaminhar] },
            historicoAcoes: { atual: '', proximo: '', acoes: [Historico, VisualizarPlanilhaButtton] },
            CONST: '',
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
            projetosAssinarCoordenador: 'avaliacaoResultados/projetosAssinarCoordenador',
            projetosAssinarCoordenadorGeral: 'avaliacaoResultados/projetosAssinarCoordenadorGeral',
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
            getProjetosAssinarCoordenador: 'avaliacaoResultados/getProjetosAssinarCoordenador',
            getProjetosAssinarCoordenadorGeral: 'avaliacaoResultados/getProjetosAssinarCoordenadorGeral',
            route: 'route',
        }),
    },
};
</script>
