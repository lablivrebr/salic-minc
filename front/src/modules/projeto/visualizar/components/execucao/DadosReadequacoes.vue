<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Dados das Readequações'"></Carregando>
        </div>
        <div v-else>
            <v-expansion-panel popout focusable>
                <v-expansion-panel-content
                        class="elevation-1"
                        v-for="(dado, index) in dados.dadosReadequacoes"
                        :key="index"
                >
                    <v-layout slot="header" class="primary--text">
                        <v-icon class="mr-3 primary--text">subject</v-icon>
                        <span>{{ dado.dsReadequacao }}</span>
                    </v-layout>
                    <v-container fluid>
                        <v-card class="elevation-2">
                            <v-card-text class="pl-5">
                                <v-container fluid>
                                    <div>
                                        <v-layout row justify-space-between>
                                            <v-card>
                                                <v-card-text>
                                                    <b>ARQUIVO</b><br>
                                                    <a
                                                            v-if="dado.idArquivo"
                                                            :href="`/upload/abrir?id=${dado.idArquivo}`"
                                                    >
                                                        <span v-html="dado.nmArquivo"></span>
                                                    </a>
                                                    <span v-else>
                                                     -
                                                </span>
                                                </v-card-text>
                                            </v-card>
                                            <v-card>
                                                <v-card-text>
                                                    <b>DATA ENVIO</b>
                                                    <p v-if="dado.dtEnvio">
                                                        {{ dado.dtEnvio | formatarData }}
                                                    </p>
                                                    <p v-else>
                                                        -
                                                    </p>
                                                </v-card-text>
                                            </v-card>

                                            <v-card>
                                                <v-card-text>
                                                    <b>DATA SOLICITAÇÃO</b>
                                                    <p>{{ dado.dtSolicitacao | formatarData }} </p>
                                                </v-card-text>
                                            </v-card>
                                        </v-layout>

                                        <v-layout row justify-space-between>
                                            <v-card>
                                                <v-card-text>
                                                    <b>DADOS DA SOLICITAÇÃO</b>
                                                    <p v-html="dado.dsSolicitacao"></p>
                                                </v-card-text>
                                            </v-card>
                                        </v-layout>

                                        <v-layout row justify-space-between>
                                            <v-card>
                                                <v-card-text>
                                                    <b>JUSTIFICATIVA DA SOLICITAÇÃO</b>
                                                    <p v-html="dado.dsJustificativa"></p>
                                                </v-card-text>
                                            </v-card>
                                        </v-layout>
                                    </div>
                                    <div v-if="validarAcessoSituacao(dado.siEncaminhamento)">
                                        <v-layout row justify-space-between>
                                            <v-card>
                                                <v-card-text>
                                                    <b>SITUAÇÃO</b>
                                                    <p v-if="dado.stAtendimento === 'I'">
                                                        Rejeitado
                                                    </p>
                                                    <p v-else>
                                                        Recebido
                                                    </p>
                                                </v-card-text>
                                            </v-card>

                                            <v-card>
                                                <v-card-text>
                                                    <b>DATA AVALIAÇÃO</b>
                                                    <p v-if="dado.dtAvaliador">
                                                        {{ dado.dtAvaliador | formatarData }}
                                                    </p>
                                                    <p v-else>
                                                        -
                                                    </p>
                                                </v-card-text>
                                            </v-card>

                                            <v-card>
                                                <v-card-text>
                                                    <b>DESCRIÇÃO DA AVALIAÇÃO</b>
                                                    <p v-html="dado.dsAvaliacao"></p>
                                                </v-card-text>
                                            </v-card>
                                        </v-layout>
                                    </div>
                                    <div v-if="dado.siEncaminhamento === 15">
                                        <v-list v-for="(parecer, index) in dado.pareceres" :key="index">
                                            <v-layout row justify-space-between>
                                                <v-card>
                                                    <v-card-text>
                                                        <b>PARECER FAVORÁVEL?</b>
                                                        <p v-if="parecer.ParecerFavoravel === '2'">
                                                            SIM
                                                        </p>
                                                        <p v-else>
                                                            NÂO
                                                        </p>
                                                    </v-card-text>
                                                </v-card>

                                                <v-card>
                                                    <v-card-text>
                                                        <b>DATA PARECER</b>
                                                        <p> {{ parecer.DtParecer | formatarData }}</p>
                                                    </v-card-text>
                                                </v-card>
                                            </v-layout>

                                            <v-layout row justify-space-between>
                                                <v-card>
                                                    <v-card-text>
                                                        <b>DESCRIÇÃO DO PARECER - TÉCNICO / PARECERISTA</b>
                                                        <p v-html="parecer.ResumoParecer"></p>
                                                    </v-card-text>
                                                </v-card>
                                            </v-layout>
                                        </v-list>
                                    </div>
                                </v-container>
                            </v-card-text>
                        </v-card>
                    </v-container>
                </v-expansion-panel-content>
            </v-expansion-panel>

            <ReadequacoesDevolvidas/>
        </div>
    </div>
</template>
<script>

    import { mapActions, mapGetters } from 'vuex';
    import moment from 'moment';
    import Carregando from '@/components/CarregandoVuetify';
    import ReadequacoesDevolvidas from './components/ReadequacoesDevolvidas';

    export default {
        name: 'DadosReadequacoes',
        props: ['idPronac'],
        data() {
            return {
                loading: true,
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDadosReadequacoes(this.dadosProjeto.idPronac);
            }
        },
        components: {
            Carregando,
            ReadequacoesDevolvidas,
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/dadosReadequacoes',
            }),
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },
        filters: {
            formatarData(date) {
                if (date.length === 0) {
                    return '-';
                }
                return moment(date).format('DD/MM/YYYY');
            },
        },
        methods: {
            ...mapActions({
                buscarDadosReadequacoes: 'projeto/buscarDadosReadequacoes',
            }),
            validarAcessoSituacao(siEncaminhamento) {
                const permitidos = [2, 3, 4, 5, 6, 7, 9, 10, 15];
                return permitidos.indexOf(siEncaminhamento) !== -1;
            },
        },
    };
</script>

