<template>
    <div class="itens">
        <v-data-table
            :headers="headers"
            :items="table"
            :rows-per-page-items="[-1]"
            class="elevation-1"
            hide-actions
        >
            <template
                slot="items"
                slot-scope="props"
            >
                <tr
                    :class="definirClasseItem(props.item)"
                >
                    <td class="text-xs-center">
                        {{ props.item.Seq }}
                    </td>
                    <td class="text-xs-left">
                        {{ props.item.Item }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.Unidade }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.QtdeDias }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.Quantidade }}
                    </td>
                    <td class="text-xs-center">
                        {{ props.item.Ocorrencia }}
                    </td>
                    <td class="text-xs-right">
                        {{ props.item.vlUnitario | filtroFormatarParaReal }}
                    </td>
                    <td class="text-xs-right">
                        {{ props.item.vlSolicitado | filtroFormatarParaReal }}
                    </td>
                    <td class="justify-center layout px-0">
                        <v-icon
                            small
                            class="mr-2"
                            @click="editItem(props.item)"
                        >visibility</v-icon>
                    </td>
                </tr>
            </template>
            <template slot="footer">
                <tr
                    v-if="table && Object.keys(table).length > 0"
                    style="opacity: 0.5"
                >
                    <td colspan="7">
                        <b>Totais</b>
                    </td>
                    <td class="text-xs-right">
                        <b>{{ obterValorSolicitadoTotal(table) }}</b>
                    </td>
                    <td class="text-xs-right" />
                </tr>
            </template>
        </v-data-table>
        <v-dialog
            v-model="dialog"
            max-width="500px"
        >
            <v-card>
                <v-card-title>
                    <span class="headline">
                        Justificativa
                    </span>
                </v-card-title>

                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-flex
                                xs12
                                v-html="editedItem.JustProponente"
                            >
                            </v-flex>
                        </v-layout>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer />
                    <v-btn
                        color="blue darken-1"
                        flat
                        @click="close"
                    >
                        Cancel
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import planilhas from '@/mixins/planilhas';

export default {
    mixins: [planilhas],
    props: {
        table: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            headers: [
                { text: '#', align: 'center', value: 'Seq' },
                { text: 'Item', align: 'left', value: 'Item' },
                { text: 'Unidade', align: 'left', value: 'Unidade' },
                { text: 'Dias', align: 'center', value: 'QtdeDias' },
                { text: 'Qtde', align: 'center', value: 'Quantidade' },
                { text: 'Ocor.', align: 'center', value: 'Ocorrencia' },
                { text: 'Vl. Unitário', align: 'right', value: 'vlUnitario' },
                { text: 'Vl. Solicitado', align: 'right', value: 'vlSolicitado' },
                { text: 'Justificativa', align: 'left', value: 'JustProponente' },
            ],
            editedItem: {},
            dialog: false,
        };
    },
    methods: {
        editItem(item) {
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        close() {
            this.dialog = false;
            this.editedItem = Object.assign({});
        },
    },
};
</script>
