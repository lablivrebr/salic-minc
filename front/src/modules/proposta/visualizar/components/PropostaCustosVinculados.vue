<template>
    <v-card
        class="mb-2">
        <v-card-title
            primary
            class="title">Custos vinculados</v-card-title>
        <v-data-table
            :headers="headers"
            :items="dados.lines"
            class="elevation-1"
        >
            <template
                slot="items"
                slot-scope="props">
                <td>{{ props.item.item }}</td>
                <td>{{ props.item.dtCadastro | formatarData }}</td>
                <td>{{ props.item.pcCalculo }}</td>
            </template>
        </v-data-table>
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
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            dados: [],
            headers: [
                { text: 'Item', value: 'item' },
                { text: 'Data', value: 'dtCadastro' },
                { text: 'Percentual', value: 'pcCalculo' },
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
