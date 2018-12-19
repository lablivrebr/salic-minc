<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Diligências do Projeto'"></Carregando>
        </div>
        <v-flex v-else>
            <v-expansion-panel popout focusable>
                <v-expansion-panel-content class="elevation-1">
                    <v-layout slot="header" class="primary--text">
                        <v-icon class="mr-3 primary--text">perm_media</v-icon>
                        Diligência Proposta ({{dados.diligenciaProposta.length}})
                    </v-layout>
                    <v-card>
                        <v-card-text>
                            <VisualizarDiligenciaProposta
                                    :idPronac="idPronac"
                                    :diligencias="dados.diligenciaProposta"
                            >
                            </VisualizarDiligenciaProposta>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>

                <v-expansion-panel-content class="elevation-1">
                    <v-layout slot="header" class="primary--text">
                        <v-icon class="mr-3 primary--text">perm_media</v-icon>
                        Diligências da Adequação do Projeto ({{dados.diligenciaAdequacao.length}})
                    </v-layout>
                    <v-card>
                        <v-card-text>
                            <VisualizarDiligenciaAdequacao
                                    :idPronac="idPronac"
                                    :diligencias="dados.diligenciaAdequacao"
                            >
                            </VisualizarDiligenciaAdequacao>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>

                <v-expansion-panel-content class="elevation-1">
                    <v-layout slot="header" class="primary--text">
                        <v-icon class="mr-3 primary--text">perm_media</v-icon>
                        Diligência Projeto ({{dados.diligenciaProjeto.length}})
                    </v-layout>
                    <v-card>
                        <v-card-text>
                            <VisualizarDiligenciaProjeto
                                    :idPronac="idPronac"
                                    :diligencias="dados.diligenciaProjeto"
                            >
                            </VisualizarDiligenciaProjeto>
                        </v-card-text>
                    </v-card>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-flex>
    </div>

</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import Carregando from '@/components/CarregandoVuetify';
    import VisualizarDiligenciaProposta from './components/VisualizarDiligenciaProposta';
    import VisualizarDiligenciaAdequacao from './components/VisualizarDiligenciaAdequacao';
    import VisualizarDiligenciaProjeto from './components/VisualizarDiligenciaProjeto';

    export default {
        name: 'DiligenciaProjeto',
        components: {
            Carregando,
            VisualizarDiligenciaProposta,
            VisualizarDiligenciaAdequacao,
            VisualizarDiligenciaProjeto,
        },
        data() {
            return {
                loading: true,
                idPronac: {},
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDiligencia(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/diligencia',
            }),
        },
        methods: {
            ...mapActions({
                buscarDiligencia: 'projeto/buscarDiligencia',
            }),
        },
    };
</script>

