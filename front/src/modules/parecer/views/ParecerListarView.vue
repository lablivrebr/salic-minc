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
                            v-if="produtos.length > 0"
                            :headers="headers"
                            :items="produtos"
                            :rows-per-page-items="[15, 35, 50, {'text': 'Todos', value: -1}]"
                            item-key="idDistribuirParecer"
                            class="elevation-1"
                        >
                            <template
                                slot="items"
                                slot-scope="props">
                                <td>
                                    <v-tooltip
                                        bottom>
                                        <a
                                            slot="activator"
                                            :href="`/projeto/#/${props.item.IdPRONAC}`"
                                            target="_blank"
                                            class="mr-2">
                                            {{ props.item.PRONAC }}
                                        </a>
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
                                            color="primary">
                                            {{ props.item.dsProduto }}
                                        </v-btn>
                                        <span>Clique para análisar o produto {{ props.item.dsProduto }}</span>
                                    </v-tooltip>
                                </td>
                                <td class="text-xs-center">
                                    <v-tooltip
                                        v-if="props.item.stPrincipal === 1"
                                        bottom>
                                        <v-icon
                                            slot="activator"
                                            round
                                        >looks_one</v-icon>
                                        <span>Produto principal</span>
                                    </v-tooltip>
                                    <v-tooltip
                                        v-else
                                        bottom>
                                        <v-icon
                                            slot="activator"
                                            color="grey">looks_two</v-icon>
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
                                    <v-tooltip
                                        bottom>
                                        <v-btn
                                            slot="activator"
                                            :href="obterUrlHistorico(props.item)"
                                            flat
                                            icon
                                            class="mr-2">
                                            <v-icon
                                            >history</v-icon>
                                        </v-btn>
                                        <span>Visualizar histórico de distribuição deste produto</span>
                                    </v-tooltip>
                                    <v-tooltip
                                        bottom>
                                        <v-btn
                                            slot="activator"
                                            :href="obterUrlDeclararImpedimento(props.item)"
                                            flat
                                            icon
                                            class="mr-2">
                                            <v-icon
                                            >voice_over_off</v-icon>
                                        </v-btn>
                                        <span>Declarar impedimento para análise deste produto</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <template
                                slot="expand"
                                slot-scope="props">
                                <v-layout
                                    row
                                    justify-center
                                    class="blue-grey lighten-5 pa-2">
                                    <v-flex xs12>
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
                                    </v-flex>
                                </v-layout>
                            </template>
                            <template slot="no-data">
                                <div class="text-xs-center">Sem dados</div>
                            </template>
                        </v-data-table>
                        <s-carregando
                            v-else
                            :text="'Carregando lista de produtos'" />
                    </v-card-text>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { utils } from '@/mixins/utils';
import SCarregando from '@/components/CarregandoVuetify';

export default {
    name: 'ParecerListarView',
    components: { SCarregando },
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
                text: 'Produto para análise',
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
            {
                text: 'Ações', align: 'center', value: 'idProduto', sortable: false,
            },
        ],
        produtos: [],
        expand: false,
        loading: true,
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
        obterUrlHistorico(produto) {
            const url = '/analisarprojetoparecer/historico';
            const idPronac = `idPronac/${produto.IdPRONAC}`;
            const idProduto = `idProduto/${produto.idProduto}`;
            const situacao = `stPrincipal/${produto.stPrincipal}`;
            const params = `${idPronac}/${idProduto}/${situacao}`;
            return `${url}/${params}`;
        },
        obterUrlDeclararImpedimento(produto) {
            const url = '/analisarprojetoparecer/devolver-parecer';
            const idPronac = `idPronac=${produto.IdPRONAC}`;
            const idProduto = `idProduto=${produto.idProduto}`;
            const situacao = `situacao=${produto.situacao}`;
            const idDistribuirParecer = `idD=${produto.idDistribuirParecer}`;
            const params = `${idPronac}&${idProduto}&${situacao}&${idDistribuirParecer}`;
            return `${url}?${params}`;
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
                    cor: 'green lighten-3',
                    corIcone: 'green darken-4',
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
                    cor: 'grey lighten-3',
                    corIcone: 'grey darken-4',
                    texto: 'Diligenciar proponente',
                };
                break;
            }
            return diligencia;
        },
    },

};
</script>
