<template>
    <div>
        <div v-if="loading">
            <carregando :text="'Carregando Marcas Anexadas'"/>
        </div>
        <div v-else-if="dados">
            <v-card>
                <v-card-title>
                    <h6>Marcas Anexadas</h6>
                </v-card-title>
                <v-data-table
                        :headers="headers"
                        :items="dados"
                        class="elevation-1 container-fluid mb-2"
                        rows-per-page-text="Items por Página"
                        :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
                        no-data-text="Nenhum dado encontrado"
                >
                    <template slot="items" slot-scope="props">
                        <td class="text-xs-left">{{ props.item.dsDocumento }}</td>
                        <td class="text-xs-right">{{ props.item.dtEnvio | formatarData }}</td>
                        <td class="text-xs-left">{{ props.item.stAtivoDocumentoProjeto }}</td>
                        <td class="text-xs-center">
                            <v-tooltip left>
                                <v-btn
                                        :loading="parseInt(props.item.idDocumento) === loadingButton"
                                        style="text-decoration: none"
                                        slot="activator"
                                        color="blue"
                                        @click.native="loadingButton = parseInt(props.item.idDocumento)"
                                        :href="`/upload/abrir?id=${props.item.idArquivo}`"
                                        dark
                                >
                                    <v-icon dark>cloud_download</v-icon>
                                </v-btn>
                                <span>{{ props.item.nmArquivo }}</span>
                            </v-tooltip>
                        </td>
                    </template>
                    <template slot="pageText" slot-scope="props">
                        Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
                    </template>
                </v-data-table>
            </v-card>
        </div>
    </div>
</template>
<script>

    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando';
    import moment from 'moment';

    export default {
        name: 'MarcasAnexadas',
        props: ['idPronac'],
        data() {
            return {
                search: '',
                pagination: {
                    rowsPerPage: 10,
                    sortBy: 'fat',
                },
                loading: true,
                loadingButton: -1,
                selected: [],
                headers: [
                    {
                        text: 'Observações',
                        align: 'left',
                        value: 'dsDocumento',
                    },
                    {
                        text: 'Dt. Envio',
                        align: 'center',
                        value: 'dtEnvio',
                    },
                    {
                        text: 'Estado',
                        align: 'left',
                        value: 'stAtivoDocumentoProjeto',
                    },
                    {
                        text: 'Arquivo',
                        align: 'center',
                        value: 'nmArquivo',
                    },
                ],
            };
        },
        filters: {
            formatarData(date) {
                if (date.length === 0) {
                    return '-';
                }
                return moment(date).format('DD/MM/YYYY');
            },
        },
        components: {
            Carregando,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarMarcasAnexadas(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            loadingButton() {
                setTimeout(() => (this.loadingButton = -1), 2000);
            },
            dados() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/marcasAnexadas',
            }),
        },
        methods: {
            ...mapActions({
                buscarMarcasAnexadas: 'projeto/buscarMarcasAnexadas',
            }),
        },
    };
</script>

