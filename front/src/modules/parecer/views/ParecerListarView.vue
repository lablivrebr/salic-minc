<template>
    <v-container
        fluid>
        <v-layout
            row
            wrap>
            <v-flex
                xs12
                md12>
                <v-card
                    class="mb-2">
                    <v-card-title
                        primary
                        class="title">Produtos para an&aacute;lise inicial</v-card-title>
                    <v-divider/>
                    <v-card-text>
                        <v-data-table
                            :headers="headers"
                            :items="produtos"
                            :rows-per-page-items="[15, 35, 50, {'text': 'Todos', value: -1}]"
                            item-key="idDistribuirParecer"
                            class="elevation-1"
                        >
                            <template
                                slot="items"
                                slot-scope="props">
                                <tr
                                    @click="props.expanded = !props.expanded"
                                >
                                    <td>
                                        <v-tooltip
                                            bottom>
                                            <v-btn
                                                slot="activator"
                                                :href="`/projeto/#/${props.item.IdPRONAC}`"
                                                small
                                                round
                                                class="mr-2">
                                                {{ props.item.PRONAC }}
                                            </v-btn>
                                            <span>Consultar projeto {{ props.item.NomeProjeto }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td>{{ props.item.NomeProjeto }}</td>
                                    <td>
                                        <v-tooltip bottom>
                                            <v-btn
                                                slot="activator"
                                                :to="{
                                                    name: 'analise-conteudo',
                                                    params: {
                                                        id: props.item.idProduto,
                                                        idPronac: props.item.IdPRONAC,
                                                        produtoPrincipal: props.item.stPrincipal,
                                                    }
                                                }"
                                                small
                                                round
                                                color="primary"
                                                dark
                                                class="mr-2">
                                                {{ props.item.dsProduto }}
                                            </v-btn>
                                            <span>Clique para análisar o produto {{ props.item.dsProduto }}</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-center">
                                        <v-tooltip
                                            v-if="props.item.stPrincipal === 1"
                                            bottom>
                                            <v-btn
                                                slot="activator"
                                                :to="{
                                                    name: 'analise-conteudo',
                                                    params: {
                                                        id: props.item.idProduto,
                                                        idPronac: props.item.IdPRONAC,
                                                        produtoPrincipal: props.item.stPrincipal,
                                                    }
                                                }"
                                                icon
                                            >
                                                <v-icon
                                                    round
                                                    color="orange">looks_one</v-icon>
                                            </v-btn>
                                            <span>Produto principal</span>
                                        </v-tooltip>
                                        <v-tooltip
                                            v-else
                                            bottom>
                                            <v-btn
                                                slot="activator"
                                                :to="{
                                                    name: 'analise-conteudo',
                                                    params: {
                                                        id: props.item.idProduto,
                                                        idPronac: props.item.IdPRONAC,
                                                        produtoPrincipal: props.item.stPrincipal,
                                                    }
                                                }"
                                                icon
                                            >
                                                <v-icon
                                                    color="grey">looks_two</v-icon>
                                            </v-btn>
                                            <span>Produto secundário</span>
                                        </v-tooltip>
                                    </td>
                                    <td class="text-xs-right">{{ props.item.DtDistribuicao | formatarData }}</td>
                                    <td class="text-xs-center">
                                        <v-tooltip
                                            bottom>
                                            <v-btn
                                                slot="activator"
                                                :href="obterUrlDiligencia(props.item)"
                                                :color="obterConfigDiligencia(props.item).cor"
                                                target="_blank"
                                                dark
                                                icon
                                                small
                                            >
                                                <v-badge
                                                    :value="props.item.diasEmDiligencia > 0"
                                                    color="grey lighten-1"
                                                    overlap
                                                    left>
                                                    <span slot="badge">{{ props.item.diasEmDiligencia }}</span>
                                                    <v-icon
                                                        :color="obterConfigDiligencia(props.item).corIcone">
                                                        notification_important
                                                    </v-icon>
                                                </v-badge>
                                            </v-btn>
                                            <span> {{ obterConfigDiligencia(props.item).texto }} </span>
                                        </v-tooltip>
                                    </td>
                                    <td class="justify-center layout px-0">
                                        <v-btn
                                            :to="{
                                                name: 'analise-conteudo',
                                                params: {
                                                    id: props.item.idProduto,
                                                    idPronac: props.item.IdPRONAC,
                                                    produtoPrincipal: props.item.stPrincipal,
                                                }
                                            }"
                                            flat
                                            icon
                                            class="mr-2">
                                            <v-icon>edit</v-icon>
                                        </v-btn>
                                    </td>
                                </tr>
                            </template>
                            <template
                                slot="expand"
                                slot-scope="props">
                                <v-layout
                                    row
                                    justify-center
                                    class="blue-grey lighten-5 pa-2">
                                    <v-card-flex xs12>
                                        <v-card>
                                            <v-card-text>
                                                <v-container fluid>
                                                    <v-layout
                                                        row
                                                        wrap>
                                                        <v-flex
                                                            xs12
                                                            md12
                                                        >
                                                            <b>Situação do Projeto:</b>
                                                            <div>{{ props.item.situacao }}</div>
                                                        </v-flex>
                                                        <v-flex
                                                            xs12
                                                            md12
                                                        >
                                                            <b>Situação do Projeto:</b>
                                                            <div>{{ props.item.situacao }}</div>
                                                        </v-flex>
                                                        <v-flex
                                                            xs12
                                                            md12
                                                        >
                                                            <b>Data diligência:</b>
                                                            <div>{{ props.item.DtSolicitacao | formatarData }}</div>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-text>
                                        </v-card>
                                    </v-card-flex>
                                </v-layout>
                            </template>
                            <template slot="no-data">
                                <div class="text-xs-center">Sem dados</div>
                            </template>
                        </v-data-table>
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { utils } from '@/mixins/utils';

export default {
    name: 'ParecerListarView',
    mixins: [utils],
    data: () => ({
        headers: [
            {
                text: 'Pronac',
                value: 'PRONAC',
                width: '1',
            },
            {
                text: 'Nome do Projeto',
                align: 'left',
                value: 'NomeProjeto',
            },
            {
                text: 'Produto',
                align: 'left',
                value: 'dsProduto',
            },
            {
                text: 'Tipo',
                value: 'stPrincipal',
                width: '2',
            },
            { text: 'Dt. de Recebimento', value: 'DtDistribuicao', width: '2' },
            { text: 'Diligência', width: '2', value: 'stDiligencia' },
            { text: 'Ações', value: 'idProduto', sortable: false },
        ],
        produtos: [],
        expand: false,
    }),
    computed: {
        ...mapGetters({
            obterProdutos: 'parecer/getProdutos',
        }),
    },
    watch: {
        obterProdutos(val) {
            this.produtos = val;
        },
    },

    created() {
        this.initialize();
        this.obterProdutosParaAnalise();
    },
    methods: {
        ...mapActions({
            obterProdutosParaAnalise: 'parecer/obterProdutosParaAnalise',
        }),
        initialize() {
            this.produtos = [];
        },
        obterUrlDiligencia(produto) {
            const url = '/proposta/diligenciar/listardiligenciaanalista';
            const idPronac = `idPronac=${produto.IdPRONAC}`;
            const idProduto = `idProduto=${produto.idProduto}`;
            const situacao = `situacao=${produto.situacao}`;
            const tpDiligencia = 'tpDiligencia=124';
            const params = `${idPronac}&${idProduto}&${situacao}&${tpDiligencia}`;
            return `${url}?${params}`;
        },
        obterConfigDiligencia(produto) {
            let diligencia = {};
            switch (produto.stDiligencia) {
            case 1:
                diligencia = {
                    cor: 'yellow accent-4',
                    corIcone: 'yellow darken-4',
                    texto: `Diligenciado há ${produto.diasEmDiligencia} dia(s)`,
                };
                break;
            case 2:
                diligencia = {
                    cor: 'cyan lighten-3',
                    corIcone: 'cyan darken-4',
                    texto: 'Diligencia respondida',
                };
                break;
            case 3:
                diligencia = {
                    cor: 'orange lighten-3',
                    corIcone: 'orange darken-4',
                    texto: 'Diligencia não respondida',
                };
                break;
            default:
                diligencia = {
                    cor: 'green lighten-3',
                    corIcone: 'green darken-4',
                    texto: 'A Diligenciar',
                };
                break;
            }
            return diligencia;
        },
    },

};
</script>
