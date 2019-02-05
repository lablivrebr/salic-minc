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
                <tr
                    :class="obterClasseItem(props.item)"
                    :style="obterEstiloItem(props.item)"
                >
                    <td class="text-xs-center">{{ props.item.Seq }}</td>
                    <td class="text-xs-left">{{ props.item.Item }}</td>
                    <td class="text-xs-center">{{ props.item.UnidadeProposta }}</td>
                    <td class="text-xs-center">{{ props.item.diasprop }}</td>
                    <td class="text-xs-center">{{ props.item.quantidadeprop }}</td>
                    <td class="text-xs-center">{{ props.item.ocorrenciaprop }}</td>
                    <td class="text-xs-right">{{ props.item.valorUnitarioprop | filtroFormatarParaReal }}</td>
                    <td class="text-xs-right">{{ props.item.VlSolicitado | filtroFormatarParaReal }}</td>
                </tr>
            </template>
            <template slot="footer">
                <tr
                    v-if="table && Object.keys(table).length > 0"
                    style="opacity: 0.5">
                    <td colspan="7"><b>Totais</b></td>
                    <td class="text-xs-right"><b>{{ obterValorSolicitadoTotalParecer(table) | formatarParaReal }}</b></td>
                </tr>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import MxPlanilhas from '@/mixins/planilhas';
import MxPlanilhaParecer from '../mixins/planilhaParecer';
import { utils } from '@/mixins/utils';

export default {
    name: 'SPlanilhaItensComparacao',
    mixins: [MxPlanilhas, MxPlanilhaParecer, utils],
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
            ],
            itemEmEdicao: {},
            select: {},
        };
    },
};
</script>
