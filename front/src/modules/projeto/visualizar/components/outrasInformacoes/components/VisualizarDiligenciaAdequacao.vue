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
                <td class="text-xs-right">{{ props.item.dtAvaliacao }}</td>
                <td class="text-xs-left" v-html="props.item.tipoDiligencia"></td>
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

        <v-dialog v-model="dialog" width="80%">
            <v-card>
                <v-card-text v-if="Object.keys(dadosDiligencia).length > 0">
                    <v-container fluid>
                        <div v-if="dadosDiligencia.dsAvaliacao">
                            <v-layout justify-space-around row wrap>
                                <v-flex lg12 dark>
                                    <b>SOLICITAÇÃO</b>
                                </v-flex>
                                <v-flex>
                                    <p v-html="dadosDiligencia.dsAvaliacao"></p>
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
    import Carregando from '@/components/CarregandoVuetify';

    export default {
        name: 'VisualizarDiligenciaAdequacao',
        props: ['idPronac', 'diligencias'],
        components: {
            Carregando,
        },
        data() {
            return {
                dialog: false,
                headers: [
                    {
                        text: 'DATA DA AVALIAÇÃO',
                        align: 'center',
                        value: 'dtAvaliacao',
                    },
                    {
                        text: 'TIPO DE DILIGÊNCIA',
                        align: 'left',
                        value: 'tipoDiligencia',
                    },
                    {
                        text: 'VISUALIZAR',
                        align: 'center',
                        sortable: false,
                        value: '',
                    },
                ],
            };
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosDiligencia: 'projeto/diligenciaAdequacao',
            }),
        },
        methods: {
            showItem(item) {
                const idPronac = this.dadosProjeto.idPronac;
                const valor = item.idAvaliarAdequacaoProjeto;

                this.buscarDiligenciaAdequacao({ idPronac, valor });
                this.dialog = true;
            },
            ...mapActions({
                buscarDiligenciaAdequacao: 'projeto/buscarDiligenciaAdequacao',
            }),
        },
    };
</script>

