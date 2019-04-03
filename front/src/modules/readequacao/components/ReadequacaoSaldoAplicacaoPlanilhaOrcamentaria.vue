<template>
    <div class="planilha-orcamentaria card" v-if="planilha">
        <ul class="collapsible no-margin" data-collapsible="expandable">
            <li v-for="(fontes, fonte) of planilhaCompleta">
                <div class="collapsible-header active red-text fonte" :class="converterStringParaClasseCss(fonte)" v-if="isObject(fontes)">
                    <i class="material-icons">beenhere</i>{{fonte}}
                    <span class="badge">
                            <SalicFormatarValor
                                :valor="fontes.total"
                                :prefixo="prefixoValor"
                                />
                        </span>
                </div>
                <div class="collapsible-body no-padding">
                    <ul class="collapsible no-border no-margin" data-collapsible="expandable">
                        <li v-for="(produtos, produto) of fontes">
                            <div class="collapsible-header active green-text" style="padding-left: 30px;" :class="converterStringParaClasseCss(produto)" v-if="isObject(produtos)">
                                <i class="material-icons">perm_media</i>
                                <span v-html="produto"></span>
                                <span class="badge">
                                        <SalicFormatarValor
                                        :valor="produtos.total"
                                        :prefixo="prefixoValor"
                                        />
                                    </span>
                            </div>
                            <div class="collapsible-body no-padding no-border">
                                <ul class="collapsible no-border no-margin" data-collapsible="expandable">
                                    <li v-for="(etapas, etapa) of produtos">
                                        <div class="collapsible-header active orange-text" style="padding-left: 50px;" :class="converterStringParaClasseCss(etapa)" v-if="isObject(etapas)">
                                            <i class="material-icons">label</i>{{etapa}}
                                            <span class="badge">
                                                    <SalicFormatarValor
                                                    :valor="etapas.total"
                                                    :prefixo="prefixoValor"
                                                    />
                                                </span>
                                        </div>
                                        <div class="collapsible-body no-padding no-border">
                                            <ul class="collapsible no-border no-margin" data-collapsible="expandable">
                                                <li v-for="(locais, local) of etapas">
                                                    <div class="collapsible-header active blue-text" style="padding-left: 70px;" :class="converterStringParaClasseCss(local)" v-if="isObject(locais)">
                                                        <i class="material-icons">place</i>{{local}}
                                                        <span class="badge">
                                                                <SalicFormatarValor
                                                                    :valor="locais.total"
                                                                    :prefixo="prefixoValor"
                                                                    />
                                                             </span>
                                                    </div>
                                                    <div class="collapsible-body no-padding margin20 scroll-x">
                                                        <div class="center-align margin20" v-if="podeIncluirItem()">
                                                            <a class="waves-effect waves-light btn white-text btn-incluir-novo-item" v-on:click="incluirItem(locais, local, etapa, produto)">
                                                                <i class="material-icons left">add</i> incluir item neste munic&iacute;pio
                                                            </a>
                                                        </div>
                                                        <table class="bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th class="left-align">Item</th>
                                                                    <th class="left-align">Unidade</th>
                                                                    <th class="right-align">Dias</th>
                                                                    <th class="right-align">Qtde</th>
                                                                    <th class="right-align">Ocor.</th>
                                                                    <th class="right-align">Vl. Unit&aacute;rio</th>
                                                                    <th class="right-align">Vl. Aprovado</th>
                                                                    <th class="right-align">Vl. Comprovado</th>
                                                                    <th class="left-align">Justificativa</th>
                                                                    <th class="center-align" v-if="!disabled">A&ccedil;&atilde;o</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr v-for="row of locais" :key="row.idPlanilhaProposta" v-bind:class="defineCorLinha(row)">
                                                                    <td>{{row.Seq}}</td>
                                                                    <td>
                                                                        <div v-if="podeEditarItem(row)">
                                                                            <a style="cursor: pointer" v-bind:class="defineCorLinha(row)" v-on:click="editarItem(row)">{{row.Item}}</a>
                                                                        </div>
                                                                        <div v-else>
                                                                            {{row.Item}}
                                                                        </div>
                                                                    </td>
                                                                    <td>{{row.Unidade}}</td>
                                                                    <td class="right-align">{{row.QtdeDias}}</td>
                                                                    <td class="right-align">{{row.Quantidade}}</td>
                                                                    <td class="right-align">{{row.Ocorrencia}}</td>
                                                                    <td class="right-align">
                                                                        <SalicFormatarValor :valor="row.vlUnitario" :prefixo="prefixoValor" />
                                                                    </td>
                                                                    <td class="right-align">
                                                                        <SalicFormatarValor :valor="row.vlAprovado" :prefixo="prefixoValor" />
                                                                    </td>
                                                                    <td class="right-align">
                                                                        <SalicFormatarValor :valor="row.vlComprovado" :prefixo="prefixoValor" />
                                                                    </td>
                                                                    <td>{{row.dsJustificativa}}</td>
                                                                    <td class="center-align" v-if="!disabled">
                                                                        <div v-if="itemExcluido(row)">
                                                                            <span class="grey-text lighten-3">restaurar</span><br/>
                                                                            <a v-on:click="restaurarItem(row)" title="Restaurar item" class=" small waves-effect waves-light grey-text lighten-3">
                                                                                <i class="material-icons">restore</i>
                                                                            </a>
                                                                        </div>
                                                                        <div v-if="exibirExcluirItem(row)">
                                                                            <a title="Excluir item" class="small waves-effect waves-light red-text lighten-2" v-on:click="excluirItem(row)">
                                                                                <i class="material-icons">delete</i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <div class="card-action">
            <span><b>Valor total do projeto:</b>
                  <SalicFormatarValor
                :valor="planilhaCompleta.total"
                :prefixo="prefixoValor"
                />
            </span>
        </div>
        <div id="modalEditar" class="modal" v-if="disponivelEdicaoReadequacaoPlanilha">
            <div class="modal-header margin20">
                <h4 class="center-align">Alterar item</h4>
            </div>
            <div class="modal-content center-align">
                <planilha-orcamentaria-alterar-item :idPronac="idPronac" :unidades="unidades" v-on:fecharAlterar="fecharModalAlterar" v-on:atualizarItem="atualizarItens" v-bind:idPlanilhaAprovacao="idPlanilhaAprovacaoEdicao" v-on:atualizarSaldoEntrePlanilhas="atualizarSaldoEntrePlanilhas"
                    v-bind:item="itemEdicao">
                </planilha-orcamentaria-alterar-item>
            </div>
        </div>
        <div id="modalIncluir" class="modal" v-if="disponivelParaAdicaoItensReadequacaoPlanilha">
            <div class="modal-header margin20">
                <h4 class="center-align">Incluir item</h4>
            </div>
            <div class="modal-content center-align">
                <planilha-orcamentaria-incluir-item :idPronac="idPronac" :idReadequacao="idReadequacao" :unidades="unidades" :dados="dadosIncluir" v-on:fecharIncluir="fecharModalIncluir" v-on:atualizarIncluir="atualizarItens" v-on:atualizarSaldoEntrePlanilhas="atualizarSaldoEntrePlanilhas"
                    v-bind:idPlanilhaAprovacao="idPlanilhaAprovacaoEdicao">
                </planilha-orcamentaria-incluir-item>
            </div>
        </div>
    </div>
    <div v-else>Nenhuma planilha encontrada</div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import SalicFormatarValor from '@/components/SalicFormatarValor';
    import _ from 'lodash';
    import numeral from 'numeral';
    import {
        utils,
    } from '@/mixins/utils';
    import PlanilhaOrcamentariaAlterarItem from './PlanilhaOrcamentariaAlterarItem';
    import PlanilhaOrcamentariaIncluirItem from './PlanilhaOrcamentariaIncluirItem';

    export default {
        name: 'ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria',
        components: {
            PlanilhaOrcamentariaIncluirItem,
            PlanilhaOrcamentariaAlterarItem,
            SalicFormatarValor,
        },
        props: {
            idPronac: '',
            idReadequacao: '',
            objPlanilha: {},
            perfil: '',
            link: '',
            disabled: '',
            disponivelParaAdicaoItensReadequacaoPlanilha: '',
        },
        mixins: [utils],
        data() {
            return {
                planilha: {},
                unidades: [],
                idPlanilhaAprovacaoEdicao: '',
                itemEdicao: {},
                dadosIncluir: {
                    recurso: '',
                    uf: '',
                    municipio: '',
                    etapa: '',
                    produto: '',
                    listaItens: [],
                },
                perfilProponente: 1111,
                fonteRecursosFederais: 109,
                prefixoValor: 'R$ ',
            };
        },
        created() {
            this.obterUnidades();
        },
        mounted() {},
        methods: {
            obterUnidades() {
                const self = this;
		/*
                $3.ajax({
                    type: 'GET',
                    url: '/readequacao/saldo-aplicacao/carregar-unidades',
                    data: {
                        idPronac: self.idPronac,
                    },
                }).done(
                    (response) => {
                        self.unidades = response.unidades;
                    },
                );
*/
            },
            iniciarCollapsible() {
		/*
                $3('.planilha-orcamentaria .collapsible').each(
                    (key, element) => {
                        $3(element).collapsible();
                    },
                );
*/
            },
            converterStringParaClasseCss(text) {
                return text.toString().toLowerCase().trim()
                    .replace(/&/g, '-and-')
                    .replace(/[\s\W-]+/g, '-');
            },
            exibirExcluirItem(item) {
                if (this.perfil !== this.perfilProponente) {
                    return false;
                }
                if (!_.isNull(item.vlComprovado) &&
                    item.vlComprovado > 0) {
                    return false;
                }
                if (!this.itemExcluido(item)) {
                    return true;
                }
                return false;
            },
            defineCorLinha(item) {
                let cor = '';
                switch (item.tpAcao) {
                case 'E':
                    if (this.perfil === this.perfilProponente) {
                        cor = 'grey-text lighten-3';
                    } else {
                        cor = 'red-text lighten-3';
                    }
                    break;
                case 'I':
                    cor = 'green-text lighten-3';
                    break;
                case 'A':
                    cor = 'blue-text lighten-3';
                    break;
                default:
                    cor = 'black-text';
                    break;
                }
                return cor;
            },
            editarItem(item) {
                this.idPlanilhaAprovacaoEdicao = item.idPlanilhaAprovacao;
                this.itemEdicao = item;
                //$3('#modalEditar').modal('open');
            },
            podeEditarItem(item) {
                if (this.perfil === this.perfilProponente &&
                    this.link &&
                    item.vlComprovado < item.vlAprovado &&
                    this.disponivelEdicaoReadequacaoPlanilha &&
                    (item.tpAcao !== 'E' &&
                     item.idFonte === this.fonteRecursosFederais)) {
                    return true;
                }
                return false;
            },
            podeIncluirItem() {
                if (this.perfil === this.perfilProponente &&
                    this.disponivelParaAdicaoItensReadequacaoPlanilha &&
                    this.link) {
                    return true;
                }
                return false;
            },
            incluirItem(info, local, etapa, produto) {
                const firstKey = Object.keys(info)[0];
                const instance = info[firstKey];
                const uf = local.split(' - ')[0];
                const municipio = local.split(' - ')[1];
                etapa = etapa.split(' - ')[1];
                this.dadosIncluir = {
                    recurso: instance.FonteRecurso,
                    etapa,
                    uf,
                    municipio,
                    produto,
                    idRecurso: instance.idFonte,
                    idUnidade: instance.idUnidade,
                    idMunicipio: instance.idMunicipio,
                    idUF: instance.idUF,
                    idProduto: instance.idProduto,
                    idEtapa: instance.idEtapa,
                    idReadequacao: self.idReadequacao,
                    Ocorrencia: '',
                    ValorUnitario: '',
                    Dias: '',
                    listaItens: [],
                };
                this.obterItens(instance.idMunicipio, instance.idProduto, instance.idEtapa);
                $3('#modalIncluir').modal('open');
            },
            obterItens(idMunicipio, idProduto, idEtapa) {
                const self = this;
                $3.ajax({
                    type: 'POST',
                    url: '/readequacao/readequacoes',
                    data: {
                        idPronac: self.idPronac,
                        idMunicipio,
                        idProduto,
                        idEtapa,
                    },
                }).done(
                    (data) => {
                        self.dadosIncluir.listaItens = data;
                    },
                );
            },
            fecharModalAlterar() {
                $3('#modalEditar').modal('close');
            },
            fecharModalIncluir() {
                $3('#modalIncluir').modal('close');
            },
            atualizarItens() {
                this.$emit('atualizarPlanilha');
            },
            itemExcluido(item) {
                if (item.tpAcao !== 'E') {
                    return false;
                }
                return true;
            },
            excluirItem(item) {
                if (confirm('Tem certeza que deseja exclir o item?')) {
                    const self = this;
                    $3.ajax({
                        type: 'POST',
                        url: '/readequacao/readequacoes/excluir-item-solicitacao',
                        data: {
                            idPronac: self.idPronac,
                            idPlanilha: item.idPlanilhaAprovacao,
                        },
                    }).done(
                        () => {
                            item.tpAcao = 'E';
                            self.atualizarSaldoEntrePlanilhas();
                            self.mensagemSucesso('Item exclu&iacute;do com sucesso');
                        },
                    );
                }
            },
            restaurarItem(item) {
                const self = this;
                $3.ajax({
                    type: 'POST',
                    url: '/readequacao/readequacoes/alteracoes-tecnicas-no-item',
                    data: {
                        idPlanilha: item.idPlanilhaAprovacao,
                        tpAcao: item.tpAcao,
                    },
                }).done(
                    () => {
                        item.tpAcao = 'N';
                        self.atualizarSaldoEntrePlanilhas();
                        self.mensagemSucesso('Item restaurado com sucesso');
                    },
                );
            },
            atualizarSaldoEntrePlanilhas() {
                this.$emit('atualizarSaldoEntrePlanilhas');
            },
            valorFormatado(valor) {
                return valor;
            },
            ...mapGetters({
                disponivelEdicaoReadequacaoPlanilha: 'readequacao/saldoAplicacaoDisponivelEdicaoItem',
            }),
            ...mapActions({
                obterDisponivelEdicaoReadequacaoPlanilha: 'readequacao/obterDisponivelEdicaoItemSaldoAplicacao',
            }),
        },
        watch: {
            objPlanilha(value) {
                this.planilha = value;
                this.iniciarCollapsible();
                $3('#modalEditar').modal();
                $3('#modalEditar').css('height', '85%');
                $3('#modalEditar').css('max-height', '85%');
                if (this.podeIncluirItem()) {
                    $3('#modalIncluir').modal();
                    $3('#modalIncluir').css('height', '85%');
                    $3('#modalIncluir').css('max-height', '85%');
                }
            },
        },
        computed: {
            planilhaCompleta() {
                if (!_.isEmpty(this.objPlanilha)) {
                    this.planilha = this.objPlanilha;
                } else {
                    return 0;
                }
                let novaPlanilha = {};
                let totalProjeto = 0;
                let totalFonte = 0;
                let totalProduto = 0;
                let totalEtapa = 0;
                let totalLocal = 0;
                novaPlanilha = this.planilha;
                Object.entries(this.planilha).forEach(([fonte, produtos]) => {
                    totalFonte = 0;
                    Object.entries(produtos).forEach(([produto, etapas]) => {
                        totalProduto = 0;
                        Object.entries(etapas).forEach(([etapa, locais]) => {
                            totalEtapa = 0;
                            Object.entries(locais).forEach(([local, itens]) => {
                                totalLocal = 0;
                                Object.keys(itens).forEach(([cell]) => {
                                    totalLocal += cell.vlAprovado;
                                });

                                this.$set(this.planilha[fonte][produto][etapa][local], 'total', numeral(totalLocal).format('0.00'));
                                totalEtapa += totalLocal;
                            });
                            this.$set(this.planilha[fonte][produto][etapa], 'total', numeral(totalEtapa).format('0.00'));
                            totalProduto += totalEtapa;
                        });
                        this.$set(this.planilha[fonte][produto], 'total', numeral(totalProduto).format('0.00'));
                        totalFonte += totalProduto;
                    });
                    this.$set(this.planilha[fonte], 'total', numeral(totalFonte).format('0.00'));
                    totalProjeto += totalFonte;
                });
                this.$set(novaPlanilha, 'total', numeral(totalProjeto).format('0.00'));
                return novaPlanilha;
            },
        },
    };
</script>
