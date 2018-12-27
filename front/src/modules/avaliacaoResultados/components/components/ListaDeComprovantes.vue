<template>
    <div v-if="comprovantes && !loading">
        <v-subheader>Comprovantes</v-subheader>
        <v-data-table
            :headers="comprovantesHeaders"
            :items="comprovantes"
            :rows-per-page-items="[10, 25, 50, {'text': 'Todos', value: -1}]"
            class="elevation-1"
            item-key="idComprovantePagamento"
        >
            <template slot="items" slot-scope="props">
                <tr @click="editarAvaliacao(props)" style="cursor: pointer">
                    <td>{{props.item.fornecedor.nome}}</td>
                    <td>{{ props.item.tpDocumento }}</td>
                    <td class="text-xs-right">{{ props.item.dtPagamento | formatarData }}</td>
                    <td class="text-xs-right">{{ props.item.vlComprovacao | moeda}}</td>
                    <td class="text-xs-right">
                        <v-chip small
                                :color="props.item.stItemAvaliado | filtrarCorSituacao"
                                text-color="white">
                            <v-avatar>
                                <v-icon>{{ props.item.stItemAvaliado | filtrarIconeSituacao }}</v-icon>
                            </v-avatar>
                            {{ props.item.stItemAvaliado | filtrarLabelSituacao }}
                        </v-chip>
                    </td>
                </tr>
            </template>
            <template slot="expand" slot-scope="props">
                <v-layout v-if="Object.keys(itemEmEdicao).length > 0" row justify-center
                          class="blue-grey lighten-5 pa-2">
                    <v-card>
                        <v-card-title class="py-1">
                            <h3>{{itemEmEdicao.tpDocumento}} </h3>
                            <v-btn
                                round
                                small
                                :href="`/upload/abrir/id/${itemEmEdicao.arquivo.id}`"
                                target="_blank"
                            >
                                {{itemEmEdicao.arquivo.nome}}
                                <v-icon right>cloud_download</v-icon>
                            </v-btn>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-container fluid grid-list-md class="pa-0">
                                <v-layout wrap>
                                    <v-flex xs12 sm6 md4>
                                        <b>CNPJ/CPF</b>
                                        <div>{{ itemEmEdicao.CNPJCPF | cnpjFilter }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md8>
                                        <b>Fornecedor</b>
                                        <div v-html="itemEmEdicao.fornecedor.nome"></div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md4>
                                        <b>Comprovante</b>
                                        <div>{{ itemEmEdicao.tpDocumento }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <b>Número</b>
                                        <div>{{ itemEmEdicao.numero }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md4>
                                        <b>Série</b>
                                        <div>{{ itemEmEdicao.serie }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md4>
                                        <b>Dt. Emissão do Comprovante</b>
                                        <div>{{ itemEmEdicao.dataEmissao | formatarData }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <b>Forma de Pagamento</b>
                                        <div v-html="itemEmEdicao.tpFormaDePagamento "></div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <b>Dt. do Pagamento</b>
                                        <div>{{ itemEmEdicao.dtPagamento | formatarData }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md4>
                                        <b>N&ordm; Documento Pagamento</b>
                                        <div>{{ itemEmEdicao.numeroDocumento }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md3>
                                        <b>Valor</b>
                                        <div>{{ itemEmEdicao.valor | moeda }}</div>
                                    </v-flex>
                                    <v-flex xs12 sm6 md9>
                                        <b>Justificativa do Proponente</b>
                                        <div v-html="itemEmEdicao.justificativa"></div>
                                    </v-flex>
                                </v-layout>
                                <v-divider class="my-3"></v-divider>
                                <slot name="slot-comprovantes" v-bind:props="itemEmEdicao">
                                    <v-layout wrap>
                                        <v-flex xs12 sm12 md12>
                                            <b>Avaliação: </b> {{ itemEmEdicao.stItemAvaliado | filtrarLabelSituacao }}
                                        </v-flex>
                                        <v-flex xs12 sm12 md12 v-if="itemEmEdicao.dsOcorrenciaDoTecnico.length > 3">
                                         <b>Parecer da Avaliação: </b>
                                            <div v-html="itemEmEdicao.dsOcorrenciaDoTecnico"></div>
                                        </v-flex>
                                    </v-layout>
                                </slot>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-layout>
            </template>
        </v-data-table>
    </div>
    <div v-else>
        <carregando :text="'Carregando comprovantes...'"></carregando>
    </div>
</template>

<script>
    import moment from 'moment';
    import cnpjFilter from '@/filters/cnpj';
    import Carregando from '@/components/CarregandoVuetify';

    export default {
        name: 'CardComprovantes',
        props: ['comprovantes'],
        data() {
            return {
                loading: true,
                itemEmEdicao: {},
                comprovantesHeaders: [
                    {
                        text: 'Fornecedor',
                        align: 'left',
                        sortable: true,
                        value: 'fornecedor.nome',
                    },
                    {
                        text: 'Tipo',
                        value: 'tpDocumento',
                    },
                    {
                        text: 'Dt. Pagamento',
                        value: 'dataPagamento',
                        width: '10%',
                    },
                    {
                        text: 'Valor (R$)',
                        value: 'vlComprovacao',
                        width: '10%',
                    },
                    {
                        text: 'Situação',
                        value: 'stItemAvaliado',
                        width: '15%',
                    },
                ],
            };
        },
        components: {
            Carregando,
        },
        methods: {
            editarAvaliacao(props) {
                props.expanded = !props.expanded;
                this.itemEmEdicao = Object.assign({}, props.item);
            },
        },
        watch: {
            comprovantes(valor) {
                this.loading = true;
                if (Object.keys(valor).length > 0) {
                    this.loading = false;
                }
            },
        },
        filters: {
            formatarData(date) {
                if (date.length === 0) {
                    return '---';
                }
                return moment(date).format('DD/MM/YYYY');
            },
            filtrarCorSituacao(situacao) {
                switch (situacao) {
                case '1':
                    return 'green';
                case '3':
                    return 'red';
                default:
                    return 'grey';
                }
            },
            filtrarIconeSituacao(situacao) {
                switch (situacao) {
                case '1':
                    return 'thumb_up';
                case '3':
                    return 'thumb_down';
                default:
                    return 'thumbs_up_down';
                }
            },
            filtrarLabelSituacao(situacao) {
                switch (situacao) {
                case '1':
                    return 'Aprovado';
                case '3':
                    return 'Reprovado';
                default:
                    return 'Não avaliado';
                }
            },
            cnpjFilter,
            moeda: (moedaString) => {
                const moeda = Number(moedaString);
                return moeda.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
            },
        },
    };
</script>
