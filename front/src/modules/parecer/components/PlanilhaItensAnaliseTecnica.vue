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
                slot-scope="props">
                <tr
                    :class="definirClasseItem(props.item)"
                >
                    <td class="text-xs-center">{{ props.item.Seq }}</td>
                    <td class="text-xs-left">{{ props.item.Item }}</td>
                    <td class="text-xs-center">{{ props.item.UnidadeProjeto }}</td>
                    <td class="text-xs-center">{{ props.item.diasprop }}</td>
                    <td class="text-xs-center">{{ props.item.quantidadeprop }}</td>
                    <td class="text-xs-center">{{ props.item.ocorrenciaprop }}</td>
                    <td class="text-xs-right">{{ props.item.valorUnitarioprop | filtroFormatarParaReal }}</td>
                    <td class="text-xs-right">{{ props.item.VlSolicitado | filtroFormatarParaReal }}</td>
                    <td
                        class="text-xs-justify"
                        width="30%"
                        v-html="props.item.justificitivaproponente"/>
                    <td class="text-xs-right">{{ props.item.VlSugeridoParecerista | filtroFormatarParaReal }}</td>
                    <td
                        class="text-xs-justify"
                        width="30%"
                        v-html="props.item.dsJustificativaParecerista"/>
                </tr>
            </template>
            <template slot="footer">
                <tr
                    v-if="table && Object.keys(table).length > 0"
                    style="opacity: 0.5">
                    <td colspan="7"><b>Totais</b></td>
                    <td class="text-xs-right"><b>{{ obterValorSolicitadoTotal(table) }}</b></td>
                    <td/>
                    <td class="text-xs-right"><b>{{ obterValorSugeridoTotal(table) }}</b></td>
                    <td colspan="2"/>
                </tr>
            </template>
        </v-data-table>
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
                { text: 'Unidade', align: 'left', value: 'UnidadeProjeto' },
                { text: 'Dias', align: 'center', value: 'diasprop' },
                { text: 'Qtde', align: 'center', value: 'quantidadeprop' },
                { text: 'Ocor.', align: 'center', value: 'ocorrenciaprop' },
                { text: 'Vl. Unitário', align: 'right', value: 'valorUnitarioprop' },
                { text: 'Vl. Solicitado', align: 'right', value: 'VlSolicitado' },
                { text: 'Just. Proponente', align: 'left', value: 'justificitivaproponente' },
                { text: 'Valor Sugerido', align: 'left', value: 'VlSugeridoParecerista' },
                { text: 'Just. Parecerista', align: 'left', value: 'dsJustificativaParecerista' },
            ],
        };
    },
};
</script>
