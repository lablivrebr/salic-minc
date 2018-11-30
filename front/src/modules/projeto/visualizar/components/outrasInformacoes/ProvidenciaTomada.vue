<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Providência Tomada'"></Carregando>
        </div>
        <div v-else-if="dados.providenciaTomada">
           <v-data-table
                    :headers="headers"
                    :items="dados.providenciaTomada"
                    class="elevation-1 container-fluid"
                    rows-per-page-text="Items por Página"
                    :pagination.sync="pagination"
                    :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                    no-data-text="Nenhum dado encontrado"
           >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-right">{{ props.item.DtSituacao }}</td>
                    <td class="text-xs-right">{{ props.item.Situacao }}</td>
                    <td class="text-xs-left">{{ props.item.ProvidenciaTomada }}</td>
                    <td class="text-xs-right" v-if="props.item.cnpjcpf">
                        {{ props.item.cnpjcpf | cnpjFilter }}
                    </td>
                    <td class="text-xs-left" v-else>
                        Nao se aplica.
                    </td>
                    <td class="text-xs-left">{{ props.item.usuario }}</td>
                </template>
                <template slot="pageText" slot-scope="props">
                    Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
            </v-data-table>
        </div>
    </div>

</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando_vuetify';
    import cnpjFilter from '@/filters/cnpj';

    export default {
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
                        text: 'DT. SITUAÇÃO',
                        align: 'right',
                        value: 'DtSituacao',
                    },
                    {
                        text: 'SITUAÇÃO',
                        align: 'right',
                        value: 'Situacao',
                    },
                    {
                        text: 'PROVIDÊNCIA TOMADA',
                        align: 'left',
                        value: 'ProvidenciaTomada',
                    },
                    {
                        text: 'CPF',
                        align: 'center',
                        value: 'cnpjcpf',
                    },
                    {
                        text: 'NOME',
                        align: 'left',
                        value: 'usuario',
                    },
                ],
            };
        },

        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarProvidenciaTomada(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados() {
                this.loading = false;
            },
        },

        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/providenciaTomada',
            }),
        },
        methods: {
            ...mapActions({
                buscarProvidenciaTomada: 'projeto/buscarProvidenciaTomada',
            }),
        },
        filters: {
            cnpjFilter,
        },
    };
</script>
