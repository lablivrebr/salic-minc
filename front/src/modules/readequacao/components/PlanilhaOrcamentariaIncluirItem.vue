<template>
    <div>
        <table class="bordered">
            <thead>
                <tr>
                    <th class="center-align">Recursos</th>
                    <th class="center-align">UF</th>
                    <th class="center-align">Munic&iacute;pio</th>
                    <th class="center-align">Produto</th>
                    <th class="center-align">Etapa</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="center-align">{{dados.recurso}}</td>
                    <td class="center-align">{{dados.uf}}</td>
                    <td class="center-align">{{dados.municipio}}</td>
                    <td class="center-align">{{dados.produto}}</td>
                    <td class="center-align">{{dados.etapa}}</td>
                </tr>
            </tbody>
        </table>

        <br/>
        <h6>Dados do item</h6>

        <div class="row">
            <div class="col s6">
                <span>Nome do item</span>
                <select ref="itemIdItem" class="browser-default" v-model="item.idItem">
    	  <option selected></option>
    	  <option
    	    v-for="itemLista in dados.listaItens"
    	    v-bind:value="itemLista.idPlanilhaItens"
    	    >{{itemLista.Item}}</option>
    	</select>
            </div>
        </div>
        <br/>

        <form class="col s12">

            <div class="row">
                <div class="col s3">
                    <span>Unidade</span>
                    <select class="browser-default" ref="itemPlanilhaUnidade" v-model="item.idUnidade" @change="atualizarUnidade">
    	    <option
    	      v-for="unidade in unidades"
    	      v-bind:value="unidade.idUnidade"
    	      >{{unidade.Descricao}}</option>
    	  </select>
                </div>

                <div class="input-field col s2">
                    <input placeholder="Qtd" id="qtd" type="text" class="validate" ref="itemQtd" v-model="item.Quantidade">
                    <label for="qtd">Qtd</label>
                </div>

                <div class="input-field col s2">
                    <input placeholder="Ocorr\xEAncia" id="ocorrencia" type="text" class="validate" ref="itemOcorrencia" v-model="item.Ocorrencia">
                    <label for="ocorrencia">Ocorr&ecirc;ncia</label>
                </div>

                <div class="input-field col s2">
                    <input-money ref="itemValorUnitario" v-bind:value="item.ValorUnitario" v-on:ev="item.ValorUnitario = $event">
                    </input-money>
                    <label for="vl_unitario">Vl. Unit&aacute;rio</label>
                </div>

                <div class="input-field col s1">
                    <input placeholder="Dias" id="dias" type="text" class="validate" ref="itemDias" v-model="item.QtdeDias">
                    <label for="dias">Dias</label>
                </div>

                <div class="input-field col s2">
                    <span>Total</span><br/>
                    <span>R$ {{totalItemFormatado}}</span>
                </div>

            </div>
            <div class="row">
                <div class="input-field col s12">
                    <textarea id="dsJustificativa" ref="itemJustificativa" class="materialize-textarea" v-model="item.Justificativa"></textarea>
                    <label for="dsJustificativa">Justificativa</label>
                </div>
            </div>

            <div class="row">
                <div class="right-align padding20 col s12">
                    <a title="Incluir item" class="waves-effect waves-light btn white-text" v-on:click="incluirItem">
    	    salvar
    	  </a>

                    <a title="cancelar" class="waves-effect waves-light btn white-text" v-on:click="cancelar">
    	    cancelar
    	  </a>

                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import {
        utils,
    } from '@/mixins/utils';
    import InputMoney from '@/components/InputMoney';

    export default {
        name: 'PlanilhaOrcamentariaIncluirItem',
        components: {
            InputMoney,
        },
        props: {
            idPronac: '',
            idReadequacao: '',
            idPlanilhaAprovacao: '',
            dados: {
                recurso: '',
                uf: '',
                municipio: '',
                etapa: '',
                produto: '',
                idRecurso: '',
                idUnidade: '',
                idMunicipio: '',
                idProduto: '',
                idEtapa: '',
                Ocorrencia: 0,
                Quantidade: 0,
                ValorUnitario: '',
                Dias: '',
                listaItens: [{
                    idPlanilhaItens: '',
                    Item: '',
                }],
            },
            unidades: {},
        },
        data() {
            return {
                item: {
                    idUnidade: '',
                    Quantidade: null,
                    Ocorrencia: null,
                    ValorUnitario: '',
                    QtdeDias: '',
                    idItem: '',
                    Justificativa: '',
                },
            };
        },
        mixins: [utils],
        methods: {
            incluirItem() {
                if (this.item.idItem === '') {
                    this.mensagemAlerta('\xC9 obrigat\xF3rio selecionar um item!');
                    this.$refs.itemIdItem.focus();
                    return;
                }
                if (this.item.Quantidade === '') {
                    this.mensagemAlerta('\xC9 obrigat\xF3rio informar a quantidade!');
                    this.$refs.itemQtd.focus();
                    return;
                }
                if (this.item.Ocorrencia === '') {
                    this.mensagemAlerta('\xC9 obrigat\xF3rio informar a ocorr&ecirc;ncia!');
                    this.$refs.itemOcorrencia.focus();
                    return;
                }
                if (this.item.ValorUnitario === '') {
                    this.mensagemAlerta('\xC9 obrigat\xF3rio informar o valor unit&aacute;rio!');
                    this.$refs.itemValorUnitario.focus();
                    return;
                }
                if (this.item.QtdeDias === '') {
                    this.mensagemAlerta('\xC9 obrigat\xF3rio informar o n&uacute;mero de dias!');
                    this.$refs.itemDias.focus();
                    return;
                }
                if (this.item.Justificativa === '') {
                    this.mensagemAlerta('\xC9 obrigat\xF3rio informar a justificativa!');
                    this.$refs.itemJustificativa.focus();
                    return;
                }
                const self = this;
		/*
                $3.ajax({
                    type: 'POST',
                    url: '/readequacao/readequacoes/incluir-item-planilha-readequacao',
                    data: {
                        idPronac: self.idPronac,
                        newRecursos: self.dados.idRecurso,
                        newUF: self.dados.idUF,
                        newMunicipio: self.dados.idMunicipio,
                        newEtapa: self.dados.idEtapa,
                        newProduto: self.dados.idProduto,
                        newItem: self.item.idItem,
                        newUnidade: self.item.idUnidade,
                        newQuantidade: self.item.Quantidade,
                        newOcorrencia: self.item.Ocorrencia,
                        newValorUnitario: self.item.ValorUnitario,
                        newDias: self.item.QtdeDias,
                        newJustificativa: self.item.Justificativa,
                        idReadequacao: self.idReadequacao,
                    },
                }).done(
                    () => {
                        self.$emit('atualizarIncluir');
                        self.$emit('atualizarSaldoEntrePlanilhas');
                        self.$emit('fecharIncluir');
                        self.resetData();
                    },
                );
*/
            },
            cancelar() {
                this.resetData();
                this.$emit('fecharIncluir');
            },
            atualizarUnidade(e) {
                this.item.descUnidade = this.unidades[e.target.options.selectedIndex].Descricao;
            },
            resetData() {
                this.item = {
                    idUnidade: '',
                    Quantidade: null,
                    Ocorrencia: null,
                    ValorUnitario: '',
                    QtdeDias: '',
                    idItem: '',
                    Justificativa: '',
                };
            },
        },
        computed: {
            totalItem() {
                if (this.item.Ocorrencia > 0 &&
                    this.item.Quantidade > 0 &&
                    this.item.ValorUnitario !== ''
                ) {
                    return this.item.Ocorrencia * this.item.Quantidade * this.item.ValorUnitario;
                }
                return 0;
            },
            totalItemFormatado() {
                return this.converterParaMoedaPontuado(this.totalItem);
            },
        },
    };
</script>
