<template>
    <div class="itens">
        <v-data-table
            :headers="headers"
            :items="table"
            :rows-per-page-items="[-1]"
            :loading="loading"
            item-key="idPlanilhaProjeto"
            class="elevation-1"
            hide-actions
        >
            <v-progress-linear
                slot="progress"
                color="blue"
                indeterminate/>
            <template
                slot="items"
                slot-scope="props"
            >
                <tr>
                    <td class="text-xs-center">{{ props.item.Seq }}</td>
                    <td class="text-xs-left">{{ props.item.Item }}</td>
                    <td class="text-xs-center">{{ props.item.UnidadeProposta }}</td>
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
    name: 'SPlanilhaItensVisualizar',
    mixins: [planilhas],
    props: {
        table: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            valid: false,
            expand: false,
            loading: false,
            headers: [
                { text: '#', align: 'center', value: 'Seq' },
                { text: 'Item', align: 'left', value: 'Item' },
                { text: 'Unidade', align: 'left', value: 'UnidadeProposta' },
                { text: 'Dias', align: 'center', value: 'diasprop' },
                { text: 'Qtde', align: 'center', value: 'quantidadeprop' },
                { text: 'Ocor.', align: 'center', value: 'ocorrenciaprop' },
                { text: 'Vl. Unitário', align: 'right', value: 'valorUnitarioprop' },
                { text: 'Vl. Solicitado', align: 'right', value: 'VlSolicitado' },
                { text: 'Just. Proponente', align: 'left', value: 'justificitivaproponente' },
                { text: 'Valor Sugerido', align: 'left', value: 'VlSugeridoParecerista' },
                { text: 'Just. Parecerista', align: 'left', value: 'dsJustificativaParecerista' },
            ],
            itemEmEdicao: {},
            select: {},
        };
    },
    methods: {
        isLinhaAlterada(row) {
            const a1 = [
                row.Unidade,
                row.VlSolicitado,
                row.ocorrenciaprop,
                row.quantidadeprop,
                row.diasprop,
                row.valorUnitarioprop,
            ];
            const a2 = [
                row.idUnidade,
                row.VlSugeridoParecerista,
                row.ocorrenciaparc,
                row.quantidadeparc,
                row.diasparc,
                row.valorUnitarioparc,
            ];
            return JSON.stringify(a1) !== JSON.stringify(a2);
        },
        obterClasseItem(row) {
            return {
                'blue lighten-5': this.isLinhaAlterada(row),
                'grey lighten-3 grey--text text--darken-3': row.isDisponivelParaAnalise === false,
                ...this.definirClasseItem(row),
            };
        },
        obterEstiloItem(row) {
            return {
                cursor: row.isDisponivelParaAnalise === false ? 'not-allowed' : 'pointer',
            };
        },
    },
};
</script>
