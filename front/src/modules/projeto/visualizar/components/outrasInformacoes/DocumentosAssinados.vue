<template>
    <div v-if="loading">
        <Carregando :text="'Carregando Documentos Assinados'"></Carregando>
    </div>
    <div v-else>
        <v-data-table
                :headers="headers"
                :items="dados"
                :search="search"
                :pagination.sync="pagination"
                class="elevation-1"
                rows-per-page-text="Items por Página"
                no-data-text="Nenhum dado encontrado"
        >
            <template slot="items" slot-scope="props">
                <td class="text-xs-left">{{ props.item.nomeProjeto }}</td>
                <td class="text-xs-left">{{ props.item.dsAtoAdministrativo }}</td>
                <td class="text-xs-right">{{ props.item.dt_criacao }}</td>
                <td class="text-xs-center">
                    <v-tooltip left>
                        <v-btn
                                style="text-decoration: none"
                                fab dark small
                                slot="activator"
                                color="teal"
                                :href="`/assinatura/index/visualizar-documento-assinado/idPronac/${props.item.IdPRONAC}?idDocumentoAssinatura=${props.item.idDocumentoAssinatura}`"
                                target="_blank"
                                dark
                        >
                            <v-icon dark>search</v-icon>
                        </v-btn>
                        <span>Visualizar</span>
                    </v-tooltip>
                </td>
                <td class="text-xs-center">
                    <v-btn
                            style="text-decoration: none"
                            slot="activator"
                            color="primary"
                            class="center"
                            :to="{ name: 'dadosprojeto', params: { idPronac: dadosProjeto.idPronac }}">
                        {{ props.item.pronac }}
                    </v-btn>
                </td>
            </template>
            <template slot="pageText" slot-scope="props">
                Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
            </template>
        </v-data-table>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando_vuetify';

    export default {
        name: 'DocumentosAssinados',
        props: ['idPronac'],
        components: {
            Carregando,
        },
        data() {
            return {
                loading: true,
                search: '',
                pagination: {
                    sortBy: 'fat',
                },
                selected: [],
                headers: [
                    {
                        align: 'left',
                        text: 'NOME DO PROJETO',
                        value: 'nomeProjeto',
                    },
                    {
                        align: 'left',
                        text: 'ATO ADMINISTRATIVO',
                        value: 'dsAtoAdministrativo',
                    },
                    {
                        align: 'center',
                        text: 'DATA',
                        value: 'dt_criacao',
                    },
                    {
                        align: 'center',
                        sortable: false,
                        text: 'VER',
                    },
                    {
                        align: 'center',
                        text: 'PRONAC',
                        sortable: false,
                        value: 'pronac',
                    },
                ],
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDocumentosAssinados(this.dadosProjeto.idPronac);
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
                dados: 'projeto/documentosAssinados',
            }),
        },
        methods: {
            ...mapActions({
                buscarDocumentosAssinados: 'projeto/buscarDocumentosAssinados',
            }),
        },
    };
</script>