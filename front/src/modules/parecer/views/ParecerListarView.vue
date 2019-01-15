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
                        <td>
                            <v-btn
                                :to="{
                                    name: 'parecer-analisar-view',
                                    params: {
                                        id: props.item.idProduto,
                                        idPronac: props.item.IdPRONAC,
                                    }
                                }"
                                flat
                                class="mr-2">
                                {{ props.item.dsProduto }}
                            </v-btn>
                        </td>
                        <td>{{ props.item.PRONAC }}</td>
                        <td>{{ props.item.NomeProjeto }}</td>
                        <td class="text-xs-right">{{ props.item.DtDistribuicao }}</td>
                        <td class="text-xs-right">{{ props.item.stDiligenciado }}</td>
                        <td class="text-xs-right">{{ props.item.DtEnvio }}</td>
                        <td class="text-xs-right">{{ props.item.DtEnvio }}</td>
                        <td class="justify-center layout px-0">
                            <v-btn
                                :to="{
                                    name: 'parecer-analisar-view',
                                    params: {
                                        id: props.item.idProduto,
                                        idPronac: props.item.IdPRONAC,
                                    }
                                }"
                                flat
                                icon
                                class="mr-2">
                                <v-icon>edit</v-icon>
                            </v-btn>

                            <v-icon
                                small
                                @click="deleteItem(props.item)"
                            >
                                delete
                            </v-icon>
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

export default {
    name: 'ParecerListarView',
    data: () => ({
        dialog: false,
        headers: [
            {
                text: 'Produto',
                align: 'left',
                value: 'dsProduto',
            },
            { text: 'Pronac', value: 'PRONAC' },
            {
                text: 'Nome do Projeto',
                align: 'left',
                value: 'NomeProjeto',
            },
            { text: 'Tipo Produto', value: 'stPrincipal' },
            { text: 'Data de Recebimento', value: 'DtDistribuicao' },
            { text: 'Diligência', value: 'stDiligenciado' },
            { text: 'Dias diligência', value: 'DtEnvio', sortable: false },
            { text: 'Ações', value: 'idProduto', sortable: false },
        ],
        produtos: [],
        editedIndex: -1,
        editedItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
        defaultItem: {
            name: '',
            calories: 0,
            fat: 0,
            carbs: 0,
            protein: 0,
        },
    }),

    computed: {
        ...mapGetters({
            obterProdutos: 'parecer/getProdutos',
        }),
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item';
        },
    },
    watch: {
        dialog(val) {
            val || this.close();
        },
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

        editItem(item) {
            this.editedIndex = this.produtos.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },
        deleteItem(item) {
            const index = this.produtos.indexOf(item);
            confirm('Are you sure you want to delete this item?') && this.produtos.splice(index, 1);
        },

        close() {
            this.dialog = false;
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
        },
        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.produtos[this.editedIndex], this.editedItem);
            } else {
                this.produtos.push(this.editedItem);
            }
            this.close();
        },
    },
};
</script>
