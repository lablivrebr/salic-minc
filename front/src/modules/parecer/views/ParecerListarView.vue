<template>
    <v-container
        fluid>
        <v-layout
            row
            wrap>
            <v-flex
                xs12
                md12>
                <v-data-table
                    :headers="headers"
                    :items="produtos"
                    class="elevation-1"
                >
                    <template
                        slot="items"
                        slot-scope="props">
                        <td>{{ props.item.PRONAC }}</td>
                        <td>{{ props.item.NomeProjeto }}</td>
                        <td>
                            <v-btn
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
                                class="mr-2">
                                {{ props.item.dsProduto }}
                            </v-btn>
                        </td>
                        <td class="text-xs-right">{{ props.item.stPrincipal }}</td>
                        <td class="text-xs-right">{{ props.item.DtDistribuicao | formatarData }}</td>
                        <td class="text-xs-right">{{ props.item.stDiligenciado }}</td>
                        <td class="text-xs-right">{{ props.item.DtEnvio | formatarData }}</td>
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
                    </template>
                    <template slot="no-data">
                        <div class="text-xs-center">Sem dados</div>
                    </template>
                </v-data-table>
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
            { text: 'Pronac', value: 'PRONAC' },
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
            { text: 'Tipo Produto', value: 'stPrincipal' },
            { text: 'Data de Recebimento', value: 'DtDistribuicao' },
            { text: 'Diligência', value: 'stDiligenciado' },
            { text: 'Dias diligência', value: 'DtEnvio', sortable: false },
            { text: 'Ações', value: 'idProduto', sortable: false },
        ],
        produtos: [],
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
    },
};
</script>
