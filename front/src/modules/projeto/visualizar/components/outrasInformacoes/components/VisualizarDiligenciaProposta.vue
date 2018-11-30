<template>
    <div>
        <v-data-table
                :headers="headers"
                :items="diligencias"
                class="elevation-1"
                rows-per-page-text="Items por Página"
                no-data-text="Nenhum dado encontrado"
        >
            <template slot="items" slot-scope="props">
                <td class="text-xs-center">
                    <v-btn flat icon>
                        <v-tooltip bottom>
                            <v-icon
                                    slot="activator"
                                    @click="showItem(props.item)"
                                    class="material-icons">visibility
                            </v-icon>
                            <span>Visualizar Projeto</span>
                        </v-tooltip>
                    </v-btn>
                </td>
                <td class="text-xs-center">{{ props.item.idPreprojeto }}</td>
                <td class="text-xs-center">{{ props.item.dataSolicitacao }}</td>
            </template>
            <template slot="pageText" slot-scope="props">
                Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
            </template>
        </v-data-table>
        <v-dialog v-model="dialog" width="90%">
            <v-card>
                <v-card-text v-if="Object.keys(dadosDiligencia).length > 0">
                    <v-container fluid>
                        <v-layout justify-space-around row wrap>
                            <v-flex s12 m6 lg2 offset-lg1 dark>
                                <b>DATA DA SOLICITA&Ccedil;&Atilde;O</b>
                                <p>{{ dadosDiligencia.dataSolicitacao }}</p>
                            </v-flex>
                            <v-flex s12 m6 lg3>
                                <b>DATA DA RESPOSTA</b>
                                <p>{{ dadosDiligencia.dataResposta }}</p>
                            </v-flex>
                        </v-layout>

                        <div v-if="dadosDiligencia.Solicitacao">
                            <v-layout justify-space-around row wrap>
                                <v-flex lg12 dark>
                                    <b>SOLICITAÇÃO</b>
                                </v-flex>
                                <v-flex>
                                    <p v-html="dadosDiligencia.Solicitacao"></p>
                                </v-flex>
                            </v-layout>
                        </div>

                        <div v-if="dadosDiligencia.Resposta">
                            <v-layout justify-space-around row wrap>
                                <v-flex lg12 dark>
                                    <b>RESPOSTA</b>
                                </v-flex>
                                <v-flex>
                                    <p v-html="dadosDiligencia.Resposta"></p>
                                </v-flex>
                            </v-layout>
                        </div>
                    </v-container>
                </v-card-text>
                <v-card-text v-else>
                    <Carregando :text="'Carregando ...'"></Carregando>
                </v-card-text>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                            color="red"
                            flat
                            @click="dialog = false">
                        Fechar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando';

    export default {
        name: 'VisualizarDiligenciaProposta',
        props: ['idPronac', 'diligencias'],
        components: {
            Carregando,
        },
        data() {
            return {
                dialog: false,
                headers: [
                    {
                        text: 'VISUALIZAR',
                        align: 'center',
                        sortable: false,
                        value: 'idPreprojeto',
                    },
                    {
                        text: 'NR PROPOSTA',
                        align: 'center',
                        value: 'idPreprojeto',
                    },
                    {
                        text: 'DATA DA SOLICITAÇÃO',
                        align: 'center',
                        value: 'dataSolicitacao',
                    },
                ],
            };
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosDiligencia: 'projeto/diligenciaProposta',
            }),
        },
        methods: {
            showItem(item) {
                const idPreprojeto = item.idPreprojeto;
                const valor = item.idAvaliacaoProposta;

                this.buscarDiligenciaProposta({ idPreprojeto, valor });
                this.dialog = true;
            },
            ...mapActions({
                buscarDiligenciaProposta: 'projeto/buscarDiligenciaProposta',
            }),
        },
    };
</script>

