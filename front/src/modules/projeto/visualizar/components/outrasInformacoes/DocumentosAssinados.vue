<template>
    <div v-if="loading">
        <Carregando :text="'Carregando Documentos Assinados'"/>
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
            <template
                slot="items"
                slot-scope="props">
                <td class="text-xs-left">{{ props.item.nomeProjeto }}</td>
                <td class="text-xs-left">{{ props.item.dsAtoAdministrativo }}</td>
                <td class="text-xs-right">{{ props.item.dt_criacao }}</td>
                <td class="text-xs-center">
                    <v-tooltip left>
                        <v-btn
                            slot="activator"
                            :href="
                                `/assinatura/index`+
                                    `/visualizar-documento-assinado`+
                                    `/idPronac/${props.item.IdPRONAC}`+
                            `?idDocumentoAssinatura=${props.item.idDocumentoAssinatura}`"
                            style="text-decoration: none"
                            fab
                            dark
                            small
                            color="teal"
                            target="_blank"
                        >
                            <v-icon dark>search</v-icon>
                        </v-btn>
                        <span>Visualizar</span>
                    </v-tooltip>
                </td>
                <td class="text-xs-center">
                    <v-btn
                        slot="activator"
                        :to="{ name: 'dadosprojeto', params: { idPronac: dadosProjeto.idPronac }}"
                        style="text-decoration: none"
                        color="primary"
                        class="center">
                        {{ props.item.pronac }}
                    </v-btn>
                </td>
            </template>
            <template
                slot="pageText"
                slot-scope="props">
                Items {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
            </template>
        </v-data-table>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';

export default {
    name: 'DocumentosAssinados',
    components: {
        Carregando,
    },
    props: {
        idPronac: {
            type: Number,
            default: 0,
        },
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
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            dados: 'projeto/documentosAssinados',
        }),
    },
    watch: {
        dados() {
            this.loading = false;
        },
    },
    mounted() {
        if (typeof this.dadosProjeto.idPronac !== 'undefined') {
            this.buscarDocumentosAssinados(this.dadosProjeto.idPronac);
        }
    },
    methods: {
        ...mapActions({
            buscarDocumentosAssinados: 'projeto/buscarDocumentosAssinados',
        }),
    },
};
</script>
