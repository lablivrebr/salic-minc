<template>
    <s-carregando
        v-if="loading"
        text="Carregando outros produtos do projeto"
    />
    <div v-else>
        <v-data-table
            :headers="headers"
            :items="produtosSecundarios"
            disable-initial-sort
            class="elevation-0"
            item-key="idDistribuirParecer"
        >
            <template
                slot="items"
                slot-scope="props"
            >
                <td>
                    <v-tooltip
                        v-if="props.item.idAgenteParecerista === produto.idAgenteParecerista"
                        bottom
                    >
                        <router-link
                            slot="activator"
                            :to="{
                                name: 'analise-conteudo',
                                params: {
                                    id: props.item.idProduto,
                                    idPronac: props.item.IdPRONAC,
                                    produtoPrincipal: props.item.stPrincipal,
                                }
                            }"
                            color="primary"
                        >
                            {{ props.item.Produto }}
                        </router-link>
                        <span>Clique para análisar o produto {{ props.item.Produto }}</span>
                    </v-tooltip>
                    <span
                        v-else
                        v-text="props.item.Produto"
                    />
                </td>
                <td>
                    <v-tooltip
                        v-if="props.item.stPrincipal === 1"
                        bottom
                    >
                        <v-icon
                            slot="activator"
                            round
                        >
                            looks_one
                        </v-icon>
                        <span>Produto principal</span>
                    </v-tooltip>
                    <v-tooltip
                        v-else
                        bottom
                    >
                        <v-icon
                            slot="activator"
                            color="grey"
                        >
                            looks_two
                        </v-icon>
                        <span>Produto secundário</span>
                    </v-tooltip>
                </td>
                <td>{{ props.item.DtDistribuicaoPT }}</td>
                <td v-html="props.item.Obs " />
                <td class="justify-center layout px-0">
                    <v-tooltip
                        bottom
                    >
                        <v-btn
                            slot="activator"
                            flat
                            icon
                            class="mr-2"
                            @click="abrirModal(props.item)"
                        >
                            <v-icon>
                                visibility
                            </v-icon>
                        </v-btn>
                        <span>Visualizar dados deste produto</span>
                    </v-tooltip>
                </td>
            </template>
        </v-data-table>
        <s-analise-outros-produtos-dialog
            v-model="dialog"
            :produto="produtoVisualizacao"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import SCarregando from '@/components/CarregandoVuetify';
import SAnaliseOutrosProdutosDialog from './AnaliseOutrosProdutosDialog';

export default {
    name: 'AnaliseOutrosProdutos',
    components: {
        SAnaliseOutrosProdutosDialog, SCarregando,
    },
    props: {
        active: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            dialog: false,
            loading: true,
            headers: [
                { text: 'Produto', value: 'Produto' },
                { text: 'Tipo', value: 'stPrincipal' },
                { text: 'Dt. Distribuição', value: 'DtDistribuicaoPT' },
                { text: 'Situação', value: 'Obs' },
                {
                    text: 'Ações', value: 'idDistribuirParecer', align: 'center', sortable: false,
                },
            ],
            produtoVisualizacao: {
                type: Object,
                default: () => {},
            },
        };
    },
    computed: {
        ...mapGetters({
            produtosSecundarios: 'parecer/getProdutosSecundarios',
            produto: 'parecer/getProduto',
        }),
    },
    watch: {
        produtosSecundarios(val) {
            this.loading = val.length === 0;
        },
    },
    created() {
        this.obterProdutosSecundarios({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
    },
    methods: {
        ...mapActions({
            obterProdutosSecundarios: 'parecer/obterProdutosSecundarios',
        }),
        abrirModal(produto) {
            this.produtoVisualizacao = produto;
            this.dialog = true;
        },
    },
};
</script>
