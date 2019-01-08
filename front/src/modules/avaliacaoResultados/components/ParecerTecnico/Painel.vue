<template>
    <v-container fluid>
        <v-subheader>
            <h2>{{ route.meta.title }}</h2>
        </v-subheader>
        <v-card>
            <v-tabs
                centered
                color="primary"
                dark
                icons-and-text
            >
                <v-tabs-slider color="deep-orange accent-3"/>
                <v-tab
                    v-if="getUsuario.grupo_ativo == 125"
                    href="#tab-0"
                >
                    <template v-if="Object.keys(getProjetosParaDistribuir).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="secondary"
                            dark
                        />
                    </template>
                    <template v-else>
                        Distribuir
                        <v-icon>assignment_ind</v-icon>
                    </template>
                </v-tab>
                <v-tab href="#tab-1">
                    <template v-if="Object.keys(dadosTabelaTecnico).length == 0">
                        <v-progress-circular
                            indeterminate
                            color="secondary"
                            dark
                        />
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
                        />
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
                        />
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
                    />
                </v-tab-item>
                <v-tab-item
                    :value="'tab-1'"
                    :key="1"
                >
                    <v-card
                        v-if="dadosTabelaTecnico"
                        flat
                    >
                        <v-card-text>
                            <TabelaProjetos
                                v-if="(getUsuario.grupo_ativo == 125 || getUsuario.grupo_ativo == 126)"
                                :analisar="true"
                                :dados="dadosTabelaTecnico"
                                :componentes="listaAcoesCoordenador"
                                :mostrar-tecnico="true"
                            />
                            <TabelaProjetos
                                v-else
                                :analisar="true"
                                :dados="dadosTabelaTecnico"
                                :componentes="listaAcoesTecnico"
                            />
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
                            />
                            <TabelaProjetos
                                v-else-if="(getUsuario.grupo_ativo == CONST.PERFIL_COORDENADOR_GERAL)"
                                :dados="getProjetosAssinarCoordenadorGeral"
                                :componentes="listaAcoesAssinarCoordenadorGeral"
                            />
                            <TabelaProjetos
                                v-else
                                :dados="getProjetosFinalizados"
                                :componentes="listaAcoesAssinar"
                            />
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
                            />
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
import TabelaProjetos from './components/TabelaProjetos';
import Historico from '../components/Historico';
import Encaminhar from './components/ComponenteEncaminhar';
import AnaliseButton from '../analise/analisarButton';
import AssinarButton from '../analise/AssinarButton';
import Devolver from '../components/Devolver';
import VisualizarPlanilhaButtton from '../analise/VisualizarPlanilhaButtton';
import Diligencias from '../components/HistoricoDiligencias';
import VisualizarParecer from '../components/VisualizarParecer';

export default {
    name: 'Painel',
    components: {
        TabelaProjetos,
    },
    data() {
        return {
            projetoAnaliseDados: { code: 300, items: [] },
            listaAcoesTecnico: {
                atual: '',
                proximo: '',
                acoes: [Diligencias, Historico, AnaliseButton, VisualizarParecer],
            },
            listaAcoesAssinar: {
                usuario: this.getUsuario,
                atual: CONST.ESTADO_PARECER_FINALIZADO,
                proximo: CONST.ESTADO_ANALISE_PARECER,
                idTipoDoAtoAdministrativo: CONST.ATO_ADMINISTRATIVO_PARECER_TECNICO,
                acoes: [Diligencias, Historico, AssinarButton, Devolver, VisualizarPlanilhaButtton, VisualizarParecer],
            },
            listaAcoesCoordenador: {
                usuario: this.getUsuario,
                atual: '',
                proximo: '',
                acoes: [
                    Diligencias,
                    Encaminhar,
                    Historico,
                    VisualizarPlanilhaButtton,
                    VisualizarParecer,
                ] },
            listaAcoesAssinarCoordenadorGeral: {
                usuario: this.getUsuario,
                atual: CONST.ESTADO_AGUARDANDO_ASSINATURA_COORDENADOR_PARECER,
                proximo: CONST.ESTADO_ANALISE_PARECER,
                idTipoDoAtoAdministrativo: CONST.ATO_ADMINISTRATIVO_PARECER_TECNICO,
                acoes: [Diligencias, Historico, AssinarButton, Devolver, VisualizarPlanilhaButtton, VisualizarParecer],
            },
            distribuirAcoes: { atual: '', proximo: '', acoes: [Encaminhar] },
            historicoAcoes: { atual: '', proximo: '', acoes: [Historico, VisualizarPlanilhaButtton] },
            CONST: '',
        };
    },
    created() {
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
        this.projetosAssinatura({ estado: 'historico' });


        Vue.set(this.listaAcoesAssinar, 'usuario', this.getUsuario);
        Vue.set(this.listaAcoesCoordenador, 'usuario', this.getUsuario);
        Vue.set(this.listaAcoesAssinarCoordenadorGeral, 'usuario', this.getUsuario);
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
            getProjetosAssinarCoordenador: 'avaliacaoResultados/getProjetosAssinarCoordenador',
            getProjetosAssinarCoordenadorGeral: 'avaliacaoResultados/getProjetosAssinarCoordenadorGeral',
            route: 'route',
        }),
    },
};
</script>
