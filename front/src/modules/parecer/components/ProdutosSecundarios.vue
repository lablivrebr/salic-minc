<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="produtosSecundarios"
            class="elevation-0"
            item-key="idDistribuirParecer"
        >
            <template
                slot="items"
                slot-scope="props">
                <td>{{ props.item.Produto }}</td>
                <td>
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
                <td>{{ props.item.DtDistribuicaoPT }}</td>
                <td v-html="props.item.Obs "/>
                <td class="justify-center layout px-0">
                    <v-tooltip
                        bottom>
                        <v-btn
                            slot="activator"
                            flat
                            icon
                            class="mr-2"
                            @click="visualizarDetalhesProduto(props.item)">
                            <v-icon
                            >visibility</v-icon>
                        </v-btn>
                        <span>Visualizar dados deste produto</span>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>
        <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
            @keydown.esc="dialog = false"
        >
            <v-card
                v-if="produto"
                tile>
                <v-toolbar
                    card
                    dark
                    color="primary">
                    <v-btn
                        icon
                        dark
                        @click="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        Visualizar análise do Produto
                        <b>{{ produto.Produto }}</b>
                    </v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-expansion-panel
                        v-show="!loading"
                        :value="[true, true]"
                        expand>
                        <v-expansion-panel-content>
                            <div slot="header">
                                <v-icon class="material-icons">assignment</v-icon>
                                Análise do conteúdo
                            </div>
                            <v-layout
                                wrap
                                class="pa-3">
                                <v-flex
                                    xs12
                                    sm12
                                    md12>
                                    <p><b>Parecer de Conteúdo do Produto</b></p>
                                    <div
                                        v-html="analiseConteudo.ParecerDeConteudo"/>
                                </v-flex>
                                <v-flex
                                    xs12
                                    sm12
                                    md12>
                                    <b>Parecer favorável: </b> {{ analiseConteudo.ParecerFavoravel | formatarLabelSimOuNao }}
                                </v-flex>
                            </v-layout>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content>
                            <div slot="header">
                                <v-icon class="material-icons">assignment</v-icon>
                                Análise de custo
                            </div>
                            <s-planilha
                                v-if="Object.keys(planilha).length > 0"
                                :array-planilha="planilha">
                                <template slot-scope="slotProps">
                                    <s-planilha-itens-visualizar :table="slotProps.itens"/>
                                </template>
                            </s-planilha>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card-text>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { utils } from '@/mixins/utils';
import SPlanilha from '@/components/Planilha/Planilha';
import SPlanilhaItensVisualizar from './PlanilhaItensVisualizar';

export default {
    name: 'ProdutosSecundarios',
    components: { SPlanilhaItensVisualizar, SPlanilha },
    mixins: [utils],
    props: {
        active: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            headers: [
                { text: 'Produto', value: 'Produto' },
                { text: 'Tipo', value: 'stPrincipal' },
                { text: 'Dt. Distribuição', value: 'DtDistribuicaoPT' },
                { text: 'Situação', value: 'Obs' },
                {
                    text: 'Ações', value: 'idDistribuirParecer', align: 'center', sortable: false,
                },
            ],
            produto: {
                type: Object,
                default: () => {},
            },
            dialog: false,
            loading: false,
        };
    },
    computed: {
        ...mapGetters({
            produtosSecundarios: 'parecer/getProdutosSecundarios',
            analiseConteudo: 'parecer/getAnaliseConteudoSecundario',
            planilha: 'parecer/getPlanilhaSecundario',
        }),
    },
    created() {
        this.obterProdutosSecundarios({
            idProduto: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
    },
    methods: {
        ...mapActions({
            obterProdutosSecundarios: 'parecer/obterProdutosSecundarios',
            obterAnaliseConteudoSecundario: 'parecer/obterAnaliseConteudoSecundario',
            obterPlanilha: 'parecer/obterPlanilhaProdutoSecundario',
        }),
        visualizarDetalhesProduto(produto) {
            this.produto = produto;
            this.obterAnaliseConteudoSecundario({
                id: produto.idProduto,
                idPronac: produto.IdPRONAC,
            });

            this.obterPlanilha({
                id: produto.idProduto,
                idPronac: produto.IdPRONAC,
                stPrincipal: produto.stPrincipal,
            });
            this.dialog = true;
        },
    },
};
</script>
