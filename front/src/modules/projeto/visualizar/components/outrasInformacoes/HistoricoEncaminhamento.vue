<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Histórico Encaminhamento'"/>
        </div>
        <div v-else-if="dados.Encaminhamentos">
            <v-data-table
                :headers="headers"
                :items="dados.Encaminhamentos"
                :pagination.sync="pagination"
                :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                class="elevation-1 container-fluid"
            >
                <template
                    slot="items"
                    slot-scope="props">
                    <td class="text-xs-left">{{ props.item.Produto }}</td>
                    <td class="text-xs-left">{{ props.item.Unidade }}</td>
                    <td
                        class="text-xs-left"
                        v-html="props.item.Observacao"/>
                    <td class="text-xs-right">{{ props.item.DtEnvio }}</td>
                    <td class="text-xs-right">{{ props.item.DtRetorno }}</td>
                    <td class="text-xs-right">{{ props.item.qtDias }}</td>
                </template>
                <template
                    slot="pageText"
                    slot-scope="props">
                    Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
            </v-data-table>
        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';

export default {
    name: 'HistoricoEncaminhamento',
    components: {
        Carregando,
    },
    data() {
        return {
            search: '',
            pagination: {
                rowsPerPage: 10,
                sortBy: 'fat',
            },
            selected: [],
            loading: true,
            headers: [
                {
                    text: 'PRODUTO',
                    align: 'left',
                    value: 'Produto',
                },
                {
                    text: 'UNIDADE',
                    align: 'left',
                    value: 'Unidade',
                },
                {
                    text: 'OBSERVAÇÃO',
                    align: 'left',
                    value: 'Observacao',
                },
                {
                    text: 'DT. ENVIO',
                    align: 'center',
                    value: 'DtEnvio',
                },
                {
                    text: 'DT. RETORNO',
                    align: 'center',
                    value: 'DtRetorno',
                },
                {
                    text: 'QT. DIAS',
                    align: 'center',
                    value: 'qtDias',
                },
            ],
        };
    },
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            dados: 'projeto/historicoEncaminhamento',
        }),
    },
    watch: {
        dados() {
            this.loading = false;
        },
    },
    mounted() {
        if (typeof this.dadosProjeto.idPronac !== 'undefined') {
            this.buscarHistoricoEncaminhamento(this.dadosProjeto.idPronac);
        }
    },
    methods: {
        ...mapActions({
            buscarHistoricoEncaminhamento: 'projeto/buscarHistoricoEncaminhamento',
        }),
    },
};
</script>
