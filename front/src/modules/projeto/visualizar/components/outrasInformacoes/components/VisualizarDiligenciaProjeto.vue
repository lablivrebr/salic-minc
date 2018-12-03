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
                <td class="text-xs-left" v-if="props.item.produto">
                    {{ props.item.produto }}
                </td>
                <td v-else class="text-xs-left"> - </td>
                <td class="text-xs-left">{{ props.item.tipoDiligencia }}</td>
                <td class="text-xs-right">{{ props.item.dataSolicitacao }}</td>
                <td class="text-xs-right">{{ props.item.dataResposta }}</td>
                <td class="text-xs-right">{{ props.item.prazoResposta }}</td>
                <td class="text-xs-left">Prorrogado</td>
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

                        <div v-if="dadosDiligencia.arquivos && Object.keys(dadosDiligencia.arquivos).length > 0">
                            <v-flex lg12 dark class="text-xs-center">
                                <b>ARQUIVOS ANEXADOS</b>
                            </v-flex>
                            <v-container grid-list-md>
                                <v-layout justify-space-around row wrap>
                                    <v-flex xs6>
                                        <b>Arquivo</b>
                                    </v-flex>
                                    <v-flex xs2>
                                        <b>Dt.Envio</b>
                                    </v-flex>
                                </v-layout>
                                <v-layout justify-space-around align-center row
                                          v-for="arquivo of dadosDiligencia.arquivos"
                                          :key="arquivo.idArquivo"
                                >
                                    <v-flex xs6>
                                        <p>
                                            <a :href="`/upload/abrir?id=${arquivo.idArquivo}`"
                                               target="_blank">
                                                {{ arquivo.nmArquivo }}
                                            </a>
                                        </p>
                                    </v-flex>
                                    <v-flex xs2>
                                        <p>
                                            {{ arquivo.dtEnvio }}
                                        </p>
                                    </v-flex>
                                </v-layout>
                            </v-container>
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
    import { mapGetters, mapActions } from 'vuex';
    import Carregando from '@/components/CarregandoVuetify';

    export default {
        name: 'VisualizarDiligenciaProjeto',
        props: ['idPronac', 'diligencias'],
        components: {
            Carregando,
        },
        data() {
            return {
                dialog: false,
                abaAtiva: -1,
                ativo: false,
                headers: [
                    {
                        text: 'PRODUTO',
                        align: 'left',
                        value: 'produto',
                    },
                    {
                        text: 'TIPO DE DILIGÊNCIA',
                        align: 'left',
                        value: 'tipoDiligencia',
                    },
                    {
                        text: 'DATA DA SOLICITAÇÃO',
                        align: 'center',
                        value: 'dataSolicitacao',
                    },
                    {
                        text: 'DATA DA RESPOSTA',
                        align: 'center',
                        value: 'dataResposta',
                    },
                    {
                        text: 'PRAZO DA RESPOSTA',
                        align: 'center',
                        value: 'prazoResposta',
                    },
                    {
                        text: 'PRORROGADO',
                        value: 'prorrogado',
                        sortable: false,
                        align: 'left',
                    },
                    {
                        text: 'VISUALIZAR',
                        align: 'center',
                        sortable: false,
                        value: 'produto',
                    },
                ],
            };
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosDiligencia: 'projeto/diligenciaProjeto',
            }),
        },
        methods: {
            showItem(item) {
                const idPronac = this.dadosProjeto.idPronac;
                const valor = item.idDiligencia;

                this.buscarDiligenciaProjeto({ idPronac, valor });
                this.dialog = true;
            },
            ...mapActions({
                buscarDiligenciaProjeto: 'projeto/buscarDiligenciaProjeto',
            }),
        },
    };
</script>

