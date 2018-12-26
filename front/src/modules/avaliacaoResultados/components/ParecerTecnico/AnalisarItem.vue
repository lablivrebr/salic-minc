<template>
    <v-layout row justify-center>
        <v-dialog
            value="isModalVisible === 'avaliacao-item'"
            scrollable
            fullscreen
            transition="dialog-bottom-transition"
            hide-overlay
        >
            <v-snackbar
                v-model="snackbarAlerta"
            >
                {{ snackbarTexto }}
                <v-btn
                    color="pink"
                    flat
                    @click="snackbarAlerta = false"
                >
                    Fechar
                </v-btn>
            </v-snackbar>
            <v-card>
                <v-toolbar dark color="green darken-3">
                    <v-btn icon dark @click.native="fecharModal">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Avaliar comprovantes
                        <span v-if="item">item <b>"{{ item.item }}"</b></span>
                    </v-toolbar-title>
                </v-toolbar>

                <v-card-text>
                    <v-subheader>Dados da Comprovação</v-subheader>
                    <v-container fluid grid-list-md class="pa-10 elevation-2">
                        <v-layout wrap>
                            <v-flex xs12 sm6 md4>
                                <b>Produto:</b> {{ descricaoProduto }}
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <b>Etapa:</b> {{ descricaoEtapa }}
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <b>Item de Custo:</b> {{ item.item }}
                            </v-flex>
                        </v-layout>
                        <v-divider class="my-2"></v-divider>
                        <v-layout wrap>
                            <v-flex xs12 sm6 md4>
                                <b>Valor Aprovado:</b> {{ item.varlorAprovado | moeda }}
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <b>Valor Comprovado:</b> {{ item.varlorComprovado | moeda }}
                            </v-flex>
                            <v-flex xs12 sm6 md4>
                                <b>Comprovação Validada:</b> {{ valorComprovacaoValidada | moeda }}
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <div v-if="comprovantes && !comprovantesIsLoading">
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
                                <v-layout v-if="Object.keys(itemEmAvaliacao).length > 0" row justify-center
                                          class="blue-grey lighten-5 pa-2">
                                    <v-card>
                                        <v-card-title class="py-1">
                                            <h3>{{itemEmAvaliacao.tpDocumento}} </h3>
                                            <v-btn
                                                round
                                                small
                                                :href="`/upload/abrir/id/${itemEmAvaliacao.arquivo.id}`"
                                                target="_blank"
                                            >
                                                {{itemEmAvaliacao.arquivo.nome}}
                                                <v-icon right>cloud_download</v-icon>
                                            </v-btn>
                                        </v-card-title>
                                        <v-divider></v-divider>
                                        <v-card-text>
                                            <v-container fluid grid-list-md class="pa-0">
                                                <v-layout wrap>
                                                    <v-flex xs12 sm6 md4>
                                                        <b>CNPJ/CPF</b>
                                                        <div>{{ itemEmAvaliacao.CNPJCPF | cnpjFilter }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md8>
                                                        <b>Fornecedor</b>
                                                        <div v-html="itemEmAvaliacao.fornecedor.nome"></div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <b>Comprovante</b>
                                                        <div>{{ itemEmAvaliacao.tpDocumento }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md3>
                                                        <b>Número</b>
                                                        <div>{{ itemEmAvaliacao.numero }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <b>Série</b>
                                                        <div>{{ itemEmAvaliacao.serie }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <b>Dt. Emissão do Comprovante</b>
                                                        <div>{{ itemEmAvaliacao.dataEmissao | formatarData }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md3>
                                                        <b>Forma de Pagamento</b>
                                                        <div v-html="itemEmAvaliacao.tpFormaDePagamento "></div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md3>
                                                        <b>Dt. do Pagamento</b>
                                                        <div>{{ itemEmAvaliacao.dtPagamento | formatarData }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md4>
                                                        <b>N&ordm; Documento Pagamento</b>
                                                        <div>{{ itemEmAvaliacao.numeroDocumento }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md3>
                                                        <b>Valor</b>
                                                        <div>{{ itemEmAvaliacao.valor | moeda }}</div>
                                                    </v-flex>
                                                    <v-flex xs12 sm6 md9>
                                                        <b>Justificativa do Proponente</b>
                                                        <div v-html="itemEmAvaliacao.justificativa"></div>
                                                    </v-flex>
                                                </v-layout>
                                                <v-divider class="my-3"></v-divider>
                                                <v-form
                                                    v-model="valid" lazy-validation
                                                    ref="form"
                                                >
                                                    <v-layout row wrap>
                                                        <v-flex xs12>
                                                            <b>Avaliação</b>
                                                            <v-radio-group
                                                                v-model="itemEmAvaliacao.stItemAvaliado"
                                                                :rules="[rules.required, rules.avaliacao]"
                                                                type="radio"
                                                                row
                                                            >
                                                                <v-radio label="Aprovado" value="1"
                                                                         name="stItemAvaliadoModel"
                                                                         color="green"></v-radio>
                                                                <v-radio label="Reprovado" value="3"
                                                                         name="stItemAvaliadoModel"
                                                                         color="red"></v-radio>
                                                            </v-radio-group>
                                                        </v-flex>
                                                        <v-flex xs12>
                                                            <v-textarea
                                                                auto-grow
                                                                box
                                                                label="Parecer"
                                                                v-model="itemEmAvaliacao.dsOcorrenciaDoTecnico"
                                                                :rules="[rules.parecer]"
                                                                autofocus
                                                            ></v-textarea>
                                                        </v-flex>
                                                    </v-layout>
                                                    <v-container grid-list-xs text-xs-center ma-0 pa-0>
                                                        <v-btn
                                                            :disabled="!valid"
                                                            :loading="loading"
                                                            @click="salvarAvaliacao(itemEmAvaliacao)"
                                                        >
                                                            <v-icon left dark>save</v-icon>
                                                            Salvar
                                                        </v-btn>
                                                    </v-container>
                                                </v-form>
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
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import moment from 'moment';
    import cnpjFilter from '@/filters/cnpj';
    import Carregando from '@/components/CarregandoVuetify';
    import Bar from '@/modules/foo/components/Bar';

    export default {
        name: 'AnalisarItem',
        props: [
            'item',
            'descricaoProduto',
            'descricaoEtapa',
            'idPronac',
            'uf',
            'produto',
            'idmunicipio',
            'etapa',
            'cdProduto',
            'cdUf',
        ],
        components: { Bar, Carregando },
        data() {
            return {
                comprovantesIsLoading: false,
                loading: false,
                snackbarAlerta: false,
                snackbarTexto: '',
                itemEmAvaliacao: {},
                valid: true,
                rules: {
                    required: v => !!v || 'Campo obrigatório',
                    avaliacao: v => v !== '4' || 'Avaliação deve ser aprovado ou reprovado',
                    parecer: v => (!!v || this.itemEmAvaliacao.stItemAvaliado !== '3') || 'Parecer é obrigatório',
                },
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
        computed: {
            ...mapGetters({
                comprovantes: 'avaliacaoResultados/comprovantes',
                isModalVisible: 'modal/default',
            }),
            valorComprovacaoValidada() {
                if (Object.keys(this.comprovantes).length === 0) {
                    return 0;
                }
                return this.comprovantes
                    .map((item) => {
                        if (item.stItemAvaliado === '1') {
                            return item.valor;
                        }
                        return 0;
                    }).reduce((total, valor) => total + valor);
            },
        },
        mounted() {
            if (this.isModalVisible === 'avaliacao-item') {
                this.atualizarComprovantes(true);
            }
        },
        methods: {
            ...mapActions({
                alterarPlanilha: 'avaliacaoResultados/alterarPlanilha',
                salvarAvaliacaoComprovante: 'avaliacaoResultados/salvarAvaliacaoComprovante',
                obterDadosItemComprovacao: 'avaliacaoResultados/obterDadosItemComprovacao',
                modalClose: 'modal/modalClose',
            }),
            getUrlParams() {
                return this.$route.params[0];
            },
            salvarAvaliacao(avaliacao) {
                if (!this.$refs.form.validate()) {
                    return false;
                }
                avaliacao = Object.assign({}, avaliacao);
                this.loading = true;
                this.salvarAvaliacaoComprovante(avaliacao).then((response) => {
                    this.snackbarTexto = response.message;
                    this.snackbarAlerta = true;
                    this.loading = false;
                }).catch((e) => {
                    this.snackbarTexto = e.message;
                    this.snackbarAlerta = true;
                    this.loading = false;
                });

                return true;
            },
            editarAvaliacao(props) {
                props.expanded = !props.expanded;
                this.itemEmAvaliacao = Object.assign({}, props.item);
            },
            atualizarComprovantes(loading) {
                let params = '';
                if (typeof this.getUrlParams() !== 'undefined') {
                    params = this.getUrlParams();
                } else {
                    const idPronac = `idPronac/${this.idPronac}`;
                    const uf = `uf/${this.uf}`;
                    const produto = `produto/${this.produto}`;
                    const idMunicipio = `idmunicipio/${this.idmunicipio}`;
                    const idPlanilhaItem = `idPlanilhaItem/${this.item.idPlanilhaItens}`;
                    const etapa = `etapa/${this.etapa}`;
                    params = `${idPronac}/${idPlanilhaItem}/${produto}/${uf}/${idMunicipio}/${etapa}`;
                }

                if (loading) {
                    this.comprovantesIsLoading = true;
                    this.obterDadosItemComprovacao(params).catch().then(() => {
                        this.comprovantesIsLoading = false;
                    });
                }
            },
            fecharModal() {
                this.modalClose();
                this.itemEmAvaliacao = {};
                this.alterarPlanilha({
                    cdProduto: this.cdProduto,
                    etapa: this.etapa,
                    cdUf: this.cdUf,
                    idmunicipio: this.idmunicipio,
                    idPlanilhaItem: this.item.idPlanilhaItens,
                });
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
