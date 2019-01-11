<template>
    <div>
        <v-card>
            <v-card-title>
                <h6>Tramita&ccedil;&atilde;o do Projeto</h6>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="dados"
                class="elevation-1 container-fluid mb-2"
                rows-per-page-text="Items por Página"
                no-data-text="Nenhum dado encontrado"
            >
                <template
                    slot="items"
                    slot-scope="props">
                    <td class="text-xs-left">{{ props.item.Origem }}</td>
                    <td class="text-xs-right">{{ props.item.dtTramitacaoEnvio }}</td>
                    <td class="text-xs-left">{{ props.item.Destino }}</td>
                    <td class="text-xs-right">{{ props.item.dtTramitacaoRecebida }}</td>
                    <td class="text-xs-left">{{ props.item.Situacao }}</td>
                    <td class="text-xs-left">{{ props.item.meDespacho }}</td>
                </template>
                <template
                    slot="pageText"
                    slot-scope="props">
                    Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'TramitacaoProjeto',
    props: ['idPronac'],
    data() {
        return {
            search: '',
            pagination: {
                sortBy: 'fat',
            },
            selected: [],
            headers: [
                {
                    text: 'ORIGEM',
                    align: 'left',
                    value: 'Origem',
                },
                {
                    text: 'DT.ENVIO',
                    align: 'center',
                    value: 'dtTramitacaoEnvio',
                },
                {
                    text: 'DESTINO',
                    align: 'left',
                    value: 'Destino',
                },
                {
                    text: 'DT.RECEBIMENTO',
                    align: 'center',
                    value: 'dtTramitacaoRecebida',
                },
                {
                    text: 'ESTADO',
                    align: 'left',
                    value: 'Situacao',
                },
                {
                    text: 'DESPACHO',
                    align: 'left',
                    value: 'meDespacho',
                },
            ],
        };
    },
    mounted() {
        if (typeof this.idPronac !== 'undefined') {
            this.buscarTramitacaoProjeto(this.idPronac);
        }
    },
    computed: {
        ...mapGetters({
            dados: 'projeto/tramitacaoProjeto',
        }),
    },
    methods: {
        ...mapActions({
            buscarTramitacaoProjeto: 'projeto/buscarTramitacaoProjeto',
        }),
    },
};
</script>
