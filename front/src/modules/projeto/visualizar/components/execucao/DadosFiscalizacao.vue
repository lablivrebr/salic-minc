<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Dados Fiscalização'"/>
        </div>
        <div v-else-if="dadosListagem">
            <v-data-table
                :headers="headers"
                :items="dadosListagem"
                class="elevation-1 container-fluid"
                rows-per-page-text="Items por Página"
                hide-actions
                no-data-text="Nenhum dado encontrado"
            >
                <template
                    slot="items"
                    slot-scope="props">
                    <td
                        class="text-xs-center"
                        v-html="props.item.dtInicio"/>
                    <td class="text-xs-center">{{ props.item.dtFim }}</td>
                    <td class="text-xs-left">{{ props.item.cpfTecnico | cnpjFilter }}</td>
                    <td class="text-xs-left">{{ props.item.nmTecnico }}</td>
                    <td class="text-xs-center">
                        <v-btn
                            flat
                            icon>
                            <v-tooltip bottom>
                                <v-icon
                                    slot="activator"
                                    class="material-icons"
                                    dark
                                    @click="showItem(props.item.idFiscalizacao)">visibility
                                </v-icon>
                                <span>Visualizar Dados Fiscalizacao</span>
                            </v-tooltip>
                        </v-btn>
                    </td>
                </template>
            </v-data-table>
        </div>
        <v-layout
            row
            justify-center>
            <VisualizarFiscalizacao
                :dados-visualizacao="dadosVisualizacao"
                :dialog="dialog"/>
        </v-layout>
    </div>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';
import cnpjFilter from '@/filters/cnpj';
import VisualizarFiscalizacao from './components/VisualizarFiscalizacao';

export default {
    name: 'DadosFiscalizacao',
    filters: {
        cnpjFilter,
    },
    components: {
        VisualizarFiscalizacao,
        Carregando,
    },
    data() {
        return {
            dialog: false,
            loading: true,
            headers: [
                {
                    text: 'DT. INÍCIO',
                    align: 'center',
                    value: 'dtInicio',
                },
                {
                    text: 'DT. FIM',
                    align: 'center',
                    value: 'dtFim',
                },
                {
                    text: 'CPF TÉCNICO',
                    align: 'left',
                    value: 'cpfTecnico',
                },
                {
                    text: 'NOME TÉCNICO',
                    align: 'left',
                    value: 'nmTecnico',
                },
                {
                    text: 'VISUALIZAR',
                    align: 'center',
                    sortable: false,
                    value: 'dados',
                },
            ],
        };
    },
    watch: {
        dadosListagem() {
            this.loading = false;
        },
    },
    mounted() {
        if (typeof this.dadosProjeto.idPronac !== 'undefined') {
            this.buscarDadosFiscalizacaoLista(this.dadosProjeto.idPronac);
        }
    },
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            dadosListagem: 'projeto/dadosFiscalizacaoLista',
            dadosVisualizacao: 'projeto/dadosFiscalizacaoVisualiza',
        }),
    },
    methods: {
        showItem(idFiscalizacao) {
            const idPronac = this.dadosProjeto.idPronac;

            this.modalOpen(true);
            this.buscarDadosFiscalizacaoVisualiza({ idPronac, idFiscalizacao });
        },
        ...mapActions({
            buscarDadosFiscalizacaoLista: 'projeto/buscarDadosFiscalizacaoLista',
            buscarDadosFiscalizacaoVisualiza: 'projeto/buscarDadosFiscalizacaoVisualiza',
            modalOpen: 'modal/modalOpen',
        }),
    },
};
</script>
