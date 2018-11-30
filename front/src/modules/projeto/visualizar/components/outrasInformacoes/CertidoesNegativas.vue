<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Certidoes Negativas'"></Carregando>
        </div>
        <div v-else-if="dados.certidoes">
            <v-data-table
                    :headers="headers"
                    :items="dados.certidoes"
                    class="elevation-1 container-fluid"
                    rows-per-page-text="Items por Página"
                    no-data-text="Nenhum dado encontrado"
            >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-left" v-html="props.item.dsCertidao"></td>
                    <td class="text-xs-right">{{ props.item.DtEmissao }}</td>
                    <td class="text-xs-right">{{ props.item.DtValidade }}</td>
                    <td class="text-xs-right">{{ props.item.Pronac }}</td>
                    <td class="text-xs-left" v-if="props.item.Situacao" v-html="props.item.Situacao"></td>
                    <td v-else class="text-xs-left">
                        Vencida
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

    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando_vuetify';

    export default {
        name: 'CertidoesNegativas',
        data() {
            return {
                search: '',
                pagination: {
                    sortBy: 'fat',
                },
                selected: [],
                loading: true,
                headers: [
                    {
                        text: 'CERTIDÕES',
                        align: 'left',
                        value: 'dsCertidao',
                    },
                    {
                        text: 'DATA DE EMISSÃO',
                        align: 'center',
                        value: 'DtEmissao',
                    },
                    {
                        text: 'DATA DE VALIDADE',
                        align: 'center',
                        value: 'DtValidade',
                    },
                    {
                        text: 'PRONAC',
                        align: 'center',
                        value: 'Pronac',
                    },
                    {
                        text: 'SITUAÇÃO',
                        align: 'left',
                        value: 'Situacao',
                    },
                ],
            };
        },
        components: {
            Carregando,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarCertidoesNegativas(this.dadosProjeto.idPronac);
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
                dados: 'projeto/certidoesNegativas',
            }),
        },
        methods: {
            ...mapActions({
                buscarCertidoesNegativas: 'projeto/buscarCertidoesNegativas',
            }),
        },
    };
</script>

