<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Documentos Anexados'"></Carregando>
        </div>
        <div v-else-if="documentosAnexados.documentos">
            <v-data-table
                    :headers="headers"
                    :items="indexItems"
                    item-key="id"
                    :search="search"
                    class="elevation-1"
                    :pagination.sync="pagination"
                    rows-per-page-text="Items por Página"
                    :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                    no-data-text="Nenhum dado encontrado"
            >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-right">{{ props.item.id + 1 }}</td>
                    <td class="text-xs-left">{{ props.item.Anexado }}</td>
                    <td class="text-xs-right">{{ props.item.Data | formatarData }}</td>
                    <td class="text-xs-left">{{ props.item.Descricao }}</td>
                    <td class="text-xs-left">
                        <a
                                :loading="parseInt(props.item.id) === loadingButton"
                                style="text-decoration: none"
                                slot="activator"
                                @click.native="loadingButton = parseInt(props.item.id)"
                                :href="`/consultardadosprojeto/abrir-documentos-anexados?id=${props.item.idArquivo}&tipo=${props.item.AgenteDoc}&idPronac=${dadosProjeto.idPronac}`"

                        >
                            <v-btn round small>{{ props.item.NoArquivo }}</v-btn>
                        </a>
                    </td>
                </template>
                <template slot="pageText" slot-scope="props">
                    Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                </template>
            </v-data-table>
        </div>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import Carregando from '@/components/CarregandoVuetify';
    import moment from 'moment';

    export default {
        name: 'DocumentosAnexados',
        props: ['idPronac'],
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
                indexDocumentosAnexados: 0,
                selected: [],
                loading: true,
                loadingButton: -1,
                headers: [
                    {
                        text: 'N°',
                        align: 'center',
                        value: 'id',
                    },
                    {
                        align: 'left',
                        text: 'CLASSIFICAÇÃO',
                        value: 'Anexado',
                    },
                    {
                        align: 'left',
                        text: 'DATA',
                        value: 'Data',
                    },
                    {
                        align: 'left',
                        text: 'TIPO DE DOCUMENTO',
                        value: 'Descricao',
                    },
                    {
                        align: 'left',
                        text: 'DOCUMENTO',
                        value: 'NoArquivo',
                    },
                ],
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDocumentosAnexados(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados(value) {
                this.informacoes = value.informacoes;
            },
            documentosAnexados() {
                this.loading = false;
            },
            loadingButton() {
                setTimeout(() => (this.loadingButton = -1), 2000);
            },
        },
        methods: {
            ...mapActions({
                buscarDocumentosAnexados: 'projeto/buscarDocumentosAnexados',
            }),
        },
        filters: {
            formatarData(date) {
                if (date.length === 0) {
                    return '-';
                }
                return moment(date).format('DD/MM/YYYY');
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                documentosAnexados: 'projeto/documentosAnexados',
            }),
            indexItems() {
                const currentItems = this.documentosAnexados.documentos;
                return currentItems.map((item, index) => ({
                    id: index,
                    ...item,
                }));
            },
        },
    };
</script>
