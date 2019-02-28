<template>
    <v-dialog
        v-model="dialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
        scrollable
    >
        <v-card
            tile
        >
            <v-toolbar
                card
                dark
                color="primary"
            >
                <v-btn
                    icon
                    dark
                    @click="dialog = false"
                >
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>
                    Diligências
                </v-toolbar-title>
            </v-toolbar>
            <v-card-text>
                <s-carregando
                    v-if="loading"
                    text="Carregando diligências"
                />
                <div v-else>
                    <v-data-table
                        :headers="headers"
                        :items="diligencias"
                        disable-initial-sort
                        class="elevation-0"
                        item-key="idDiligencia"
                    >
                        <template
                            slot="items"
                            slot-scope="props"
                        >
                            <td>
                                <span
                                    v-text="props.item.pronac"
                                />
                            </td>
                            <td>
                                <span
                                    v-text="props.item.nomeProjeto"
                                />
                            </td>
                            <td>
                                <span
                                    v-text="props.item.produto"
                                />
                            </td>
                            <td>
                                <span
                                    v-text="props.item.tipoDiligencia"
                                />
                            </td>
                            <td>
                                {{ props.item.dataSolicitacao }}
                            </td>
                            <td>{{ props.item.dataResposta }}</td>
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
                                    <span>Visualizar diligência</span>
                                </v-tooltip>
                            </td>
                        </template>
                    </v-data-table>
                    <s-diligencia-detalhamento-dialog
                        v-model="dialogDetalhamento"
                        :item="diligenciaVisualizacao"
                    />
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import SCarregando from '@/components/CarregandoVuetify';
import SDiligenciaDetalhamentoDialog from './DiligenciaDetalhamentoDialog';

export default {
    name: 'DiligenciasDialog',
    components: {
        SDiligenciaDetalhamentoDialog, SCarregando,
    },
    props: {
        value: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            dialog: false,
            dialogDetalhamento: false,
            loading: true,
            headers: [
                { text: 'Pronac', value: 'pronac' },
                { text: 'Nome Projeto', value: 'nomeProjeto' },
                { text: 'Produto', value: 'produto' },
                { text: 'Tipo', value: 'tipoDiligencia' },
                { text: 'Dt. Solicitação', value: 'dataSolicitacao' },
                { text: 'Dt. Resposta', value: 'dataResposta' },
                {
                    text: 'Ações', value: 'idDiligencia', align: 'center', sortable: false,
                },
            ],
            diligenciaVisualizacao: {
                type: Object,
                default: () => {},
            },
        };
    },
    computed: {
        ...mapGetters({
            diligencias: 'diligencia/getDiligencias',
        }),
    },
    watch: {
        value(val) {
            this.dialog = val;
        },
        dialog(val) {
            this.$emit('input', val);
        },
        diligencias() {
            console.log('diligencias');
            this.loading = false;
        },
    },
    methods: {
        // ...mapActions({
        //     obterProdutosSecundarios: 'parecer/obterProdutosSecundarios',
        // }),
        abrirModal(produto) {
            this.diligenciaVisualizacao = produto;
            this.dialogDetalhamento = true;
        },
    },
};
</script>
