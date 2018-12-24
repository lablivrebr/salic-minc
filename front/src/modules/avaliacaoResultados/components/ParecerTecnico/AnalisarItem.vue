<template>
    <v-layout row justify-center>
        <v-dialog
            v-model="dialog"
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

            <v-btn
                slot="activator"
                color="red"
                dark
                small
                title="Comprovar Item"
            >
                <v-icon>gavel</v-icon>
            </v-btn>
            <v-card>
                <v-toolbar dark color="green darken-3">
                    <v-btn icon dark @click.native="fecharModal">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Avaliar comprovantes
                        <!--<span v-if="dadosItem">item <b>"{{dadosItem.Item}}"</b></span>-->
                    </v-toolbar-title>
                </v-toolbar>

                <v-card-text v-if="comprovantes && !comprovantesIsLoading">
                    <v-subheader>Dados da Comprovação</v-subheader>
                    <v-data-table
                        class="elevation-2"
                        hide-headers
                        :items="[]"
                        hide-actions
                    >
                        <template slot="no-data">
                            <tr>
                                <td left><b>Produto:</b></td>
                                <td>{{dadosItem.Produto}}</td>
                                <td left><b>Etapa:</b></td>
                                <td>{{dadosItem.Etapa}}</td>
                                <td left><b>Item de Custo:</b></td>
                                <td>{{dadosItem.Item}}</td>
                            </tr>
                            <tr>
                                <td left><b>Valor Aprovado:</b></td>
                                <td>{{moeda(dadosItem.vlAprovado)}}</td>
                                <td left><b>Valor Comprovado:</b></td>
                                <td>{{moeda(dadosItem.vlComprovado)}}</td>
                                <td left><b>Comprovação Validada:</b></td>
                                <td>{{moeda(dadosItem.ComprovacaoValidada)}}</td>
                            </tr>
                        </template>
                    </v-data-table>

                    <v-subheader>Comprovantes</v-subheader>
                    <v-data-table
                        :headers="comprovantesHeaders"
                        :items="comprovantes"
                        class="elevation-1"
                        item-key="idComprovantePagamento"
                    >
                        <template slot="items" slot-scope="props">
                            <tr @click="editarAvaliacao(props)" style="cursor: pointer">
                                <td>{{props.item.fornecedor.nome}}</td>
                                <td>{{ props.item.tpDocumento }}</td>
                                <td class="text-xs-right">{{ props.item.dtPagamento | formatarData }}</td>
                                <td class="text-xs-right">{{moeda(props.item.vlComprovacao)}}</td>
                                <td class="text-xs-right">
                                    <v-chip small
                                            :color="props.item.stItemAvaliado | filtrarCorSituacao" text-color="white">
                                        <v-avatar>
                                            <v-icon>{{ props.item.stItemAvaliado | filtrarIconeSituacao }}</v-icon>
                                        </v-avatar>
                                        {{ props.item.stItemAvaliado | filtrarLabelSituacao }}
                                    </v-chip>
                                </td>
                            </tr>
                        </template>
                        <template slot="expand" slot-scope="props">
                            <v-layout v-if="Object.keys(itemEmAvaliacao).length > 0" row justify-center class="blue-grey lighten-5 pa-2">
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
                                                    <div>{{ moeda(itemEmAvaliacao.valor) }}</div>
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
                                                                     name="stItemAvaliadoModel" color="red"></v-radio>
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
                                                        @click="salvarAvaliacao(itemEmAvaliacao); loader = 'loading'"
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
                </v-card-text>
                <v-card-text v-else>
                    <carregando :text="'Carregando comprovantes...'"></carregando>
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
    import EditorTexto from '../components/EditorTexto';

    export default {
        name: 'AnalisarItem',
        props: [
            'idPronac',
            'uf',
            'produto',
            'idmunicipio',
            'idPlanilhaItem',
            'etapa',
            'stItemAvaliado',
            'cdProduto',
            'cdUf',
        ],
        components: { Bar, Carregando, EditorTexto },
        data() {
            return {
                renderDadosComprovante: [],
                comprovantesIsLoading: false,
                dadosItemComprovacaoCopia: this.dadosItemComprovacao,
                loader: null,
                loading: false,
                form: {},
                snackbarAlerta: false,
                snackbarTexto: '',
                dsJustificativa: {},
                stItemAvaliadoModel: {},
                dialog: false,
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
                dadosItem: 'avaliacaoResultados/dadosItemComprovacao',
                comprovantes: 'avaliacaoResultados/comprovantes',
            }),
        },
        watch: {
            dialog(val) {
                if (val) this.atualizarComprovantes(true);
            },
        },
        methods: {
            ...mapActions({
                // alterarAvaliacaoComprovante: 'avaliacaoResultados/alterarAvaliacaoComprovante',
                alterarPlanilha: 'avaliacaoResultados/alterarPlanilha',
                salvarAvaliacaoComprovante: 'avaliacaoResultados/salvarAvaliacaoComprovante',
                obterDadosItemComprovacao: 'avaliacaoResultados/obterDadosItemComprovacao',
            }),
            getUrlParamsToJson() {
                let urlParams = this.$route.params[0];
                urlParams = urlParams.split('/');

                const dados = {};

                urlParams.forEach((valor, index) => {
                    if ((index % 2) !== 0) {
                        dados[urlParams[index - 1]] = valor;
                    }
                });
                return dados;
            },
            getUrlParams() {
                return this.$route.params[0];
            },
            moeda: (moedaString) => {
                const moeda = Number(moedaString);
                return moeda.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
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
                    params = `idPronac/${this.idPronac}/uf/${this.uf}/produto/${this.produto}/idmunicipio/${this.idmunicipio}/idPlanilhaItem/${this.idPlanilhaItem}/etapa/${this.etapa}`;
                }

                if (loading) {
                    this.comprovantesIsLoading = true;
                    this.obterDadosItemComprovacao(params).catch().then(() => {
                        this.comprovantesIsLoading = false;
                    });
                }
            },
            fecharModal() {
                this.dialog = false;
                this.itemEmAvaliacao = {};
                this.alterarPlanilha({
                    cdProduto: this.cdProduto,
                    etapa: this.etapa,
                    cdUf: this.cdUf,
                    idmunicipio: this.idmunicipio,
                    idPlanilhaItem: this.idPlanilhaItem,
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
        },
    };
</script>
