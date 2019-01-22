<template>
    <v-card
        class="mb-2">
        <v-card-title
            primary
            class="title">Custos vinculados</v-card-title>
        <v-card-text>
            <v-data-table
                :headers="headers"
                :items="dados"
                class="elevation-1"
            >
                <template
                    slot="items"
                    slot-scope="props">
                    <td>{{ props.item.item }}</td>
                    <td>{{ props.item.dtCadastro | formatarData }}</td>
                    <td class="text-xs-right">{{ props.item.pcCalculo }}</td>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>
<script>
import slTabelaSimples from '@/components/slTabelaSimples';
import { utils } from '@/mixins/utils';

export default {
    name: 'PropostaCustosVinculados',
    components: {
        slTabelaSimples,
    },
    mixins: [utils],
    props: {
        arrayCustos: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            dados: [],
            headers: [
                { text: 'Item', value: 'item' },
                { text: 'Data', value: 'dtCadastro' },
                { text: 'Percentual', align: 'center', value: 'pcCalculo' },
            ],
        };
    },
    watch: {
        arrayCustos(value) {
            this.dados = value;
        },
    },
    mounted() {
        if (typeof this.arrayCustos !== 'undefined') {
            this.dados = this.arrayCustos;
        }
    },
};
</script>
