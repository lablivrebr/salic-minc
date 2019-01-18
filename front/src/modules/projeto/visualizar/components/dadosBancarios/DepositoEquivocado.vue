<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Depósito Equivocado'"/>
        </div>
        <div v-else>
            <v-card>
                <div v-if="Object.keys(dadosDepositoEquivocado).length > 0">
                    <v-container fluid>
                        <FiltroData
                            :text="'Escolha a Dt. Devolução:'"
                            @eventoFiltrarData="filtrarData"
                        />
                    </v-container>
                </div>
                <v-data-table
                    :headers="headers"
                    :items="dadosDepositoEquivocado"
                    :pagination.sync="pagination"
                    :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                    class="elevation-1 container-fluid"
                >
                    <template
                        slot="items"
                        slot-scope="props">
                        <td class="text-xs-left">
                            <v-btn
                                :href="`/agente/agentes/agentes/id/${props.item.idAgente}`"
                                style="text-decoration: none"
                                round
                                small
                            >
                                <span v-html="props.item.Nome"/>
                            </v-btn>
                        </td>

                        <td class="text-xs-center pl-5">
                            {{ props.item.dtCredito | formatarData }}
                        </td>
                        <td class="text-xs-center pl-5">
                            {{ props.item.dtLote | formatarData }}
                        </td>
                        <td class="text-xs-right">
                            {{ props.item.vlDeposito | filtroFormatarParaReal }}
                        </td>
                    </template>
                    <template
                        slot="pageText"
                        slot-scope="props">
                        Items {{ props.pageStart }}
                        - {{ props.pageStop }}
                        de {{ props.itemsLength }}
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </div>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';
import { utils } from '@/mixins/utils';
import FiltroData from './components/FiltroData';

export default {
    name: 'DepositoEquivocado',
    components: {
        Carregando,
        FiltroData,
    },
    mixins: [utils],
    data() {
        return {
            dtLote: null,
            date: '',
            modal: false,
            menu1: false,
            menu2: false,
            datestring: '',
            dateFim: '',
            menuFim: false,
            datestringFim: '',
            search: '',
            pagination: {
                sortBy: 'dtLote',
                descending: true,
            },
            selected: [],
            loading: true,
            headers: [
                {
                    text: 'INCENTIVADOR',
                    align: 'left',
                    value: 'Nome',
                },
                {
                    text: 'DT. CRÉDITO',
                    align: 'center',
                    value: 'dtCredito',
                },
                {
                    text: 'DT. DEVOLUÇÃO',
                    align: 'center',
                    value: 'dtLote',
                },
                {
                    text: 'VL. CAPTADO',
                    align: 'center',
                    value: 'vlDeposito',
                },
            ],
        };
    },
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            dadosDepositoEquivocado: 'projeto/depositoEquivocado',
        }),
    },
    watch: {
        dadosDepositoEquivocado() {
            this.loading = false;
        },
        dadosProjeto(value) {
            this.loading = true;
            this.buscarDepositoEquivocado(value.idPronac);
        },
    },
    mounted() {
        if (typeof this.dadosProjeto.idPronac !== 'undefined') {
            const params = {
                idPronac: this.dadosProjeto.idPronac,
                dtInicio: '',
                dtFim: '',
            };

            this.buscarDepositoEquivocado(params);
        }
    },
    methods: {
        ...mapActions({
            buscarDepositoEquivocado: 'projeto/buscarDepositoEquivocado',
        }),
        filtrarData(response) {
            const params = {
                idPronac: this.dadosProjeto.idPronac,
                dtInicio: response.dtInicio,
                dtFim: response.dtFim,
            };
            this.buscarDepositoEquivocado(params);
        },
    },
};
</script>
