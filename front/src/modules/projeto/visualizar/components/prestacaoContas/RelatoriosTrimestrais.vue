<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Pagamentos Consolidados'"/>
        </div>
        <div v-else-if="dados">
            <v-data-table
                :pagination.sync="pagination"
                :headers="headers"
                :items="dados"
                :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                item-key="id"
                class="elevation-1 container-fluid"
            >
                <template
                    slot="items"
                    slot-scope="props">
                    <td class="text-xs-left">
                        {{ props.item.dtInicio | formatarData }}
                        até
                        {{ props.item.dtFim | formatarData }}
                    </td>
                    <td class="text-xs-center pl-5">{{ props.item.dtComprovante | formatarData }}</td>
                    <td
                        class="text-xs-left"
                        v-html="props.item.siComprovanteTrimestral"/>
                </template>
            </v-data-table>
        </div>
    </div>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';
import { utils } from '@/mixins/utils';

export default {
    name: 'RelatoriosTrimestrais',
    components: {
        Carregando,
    },
    mixins: [utils],
    data() {
        return {
            search: '',
            pagination: {
                sortBy: 'dtComprovante',
                descending: true,
            },
            selected: [],
            loading: true,
            headers: [
                {
                    text: 'Período',
                    align: 'left',
                    value: 'dtInicio+dtFim',
                },
                {
                    text: 'Dt. Cadastro',
                    align: 'center',
                    value: 'dtComprovante',
                },
                {
                    text: 'Status',
                    align: 'left',
                    value: 'siComprovanteTrimestral',
                },
            ],
        };
    },
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            dados: 'prestacaoContas/relatoriosTrimestrais',
        }),
    },
    watch: {
        dadosProjeto(value) {
            this.loading = false;
            this.buscarRelatoriosTrimestrais(value.idPronac);
        },
        dados() {
            this.loading = false;
        },
    },
    mounted() {
        if (typeof this.dadosProjeto.idPronac !== 'undefined') {
            this.buscarRelatoriosTrimestrais(this.dadosProjeto.idPronac);
        }
    },
    methods: {
        ...mapActions({
            buscarRelatoriosTrimestrais: 'prestacaoContas/buscarRelatoriosTrimestrais',
        }),
    },
};
</script>
