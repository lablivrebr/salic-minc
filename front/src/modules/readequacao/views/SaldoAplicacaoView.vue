<template>
<div>
  <v-container fluid>
    <v-toolbar
      color="#0A420E"
      dark
      >
      <v-btn icon
	     color="#0A420E"
	     href="javascript:voltar()"
	     >
	<v-icon color="white">arrow_back</v-icon>
      </v-btn>
      <v-spacer></v-spacer>
      <v-toolbar-title>Readequação Saldo de Aplicação</v-toolbar-title>
    </v-toolbar>
    
    <v-layout column row pb-2>
      <v-flex xs12>
	<v-card class="card--flex-toolbar">
	  <v-toolbar
	    color="green"
	    dark
	    >
	    <v-toolbar-title>{{ dadosProjeto.Pronac }} - {{ dadosProjeto.NomeProjeto }}</v-toolbar-title>
	  </v-toolbar>
	</v-card>
	
	<v-expansion-panel>
	  <v-expansion-panel-content
	    :key="formulario_readequacao"
	    >
	    <div slot="header">Dados da readequação</div>
	    <v-card>
	      <ReadequacaoFormulario
		:dados="getReadequacao"
		>		
	      </ReadequacaoFormulario>
	    </v-card>
	  </v-expansion-panel-content>
	  <v-expansion-panel-content
	    :key="planilha"
	    >
	    <div slot="header">Edição da Planilha</div>
	    <v-card>
	      edição da planilha
	    </v-card>
	  </v-expansion-panel-content>
	</v-expansion-panel>    
	  
	</v-flex>
    </v-layout>

    <div v-show="exibirBotaoIniciar">
      <button class="waves-effect waves-light btn btn-primary small btn-novaproposta"
              id="novo"
              v-on:click="solicitarUsoSaldo()"
              >
	<i class="material-icons left">border_color</i>Solicitar uso do saldo de aplica&ccedil;&atilde;o
      </button>
    </div>
  
</v-container>

<div class="readequacao-saldo-aplicacao container-fluid">
  
  <ul
    class="collapsible"
    v-if="!disabled"
    v-show="exibirPaineis"
    >
    <li id="collapsible">
      <div class="collapsible-header active"><i class="material-icons">assignment</i>Solicita&ccedil;&atilde;o de readequa&ccedil;&atilde;o</div>
      <div class="collapsible-body">
        <readequacao-formulario
          ref="formulario"
          :id-pronac="idPronac"
          :disabled="disabled"
          :id-tipo-readequacao="idTipoReadequacao"
          :componente-ds-solicitacao='componenteFormulario'
          :objReadequacao="dadosReadequacao"
          v-on:eventoAtualizarReadequacao="atualizarReadequacao"
          >
        </readequacao-formulario>
      </div>
    </li>
    <li>
      <div class="collapsible-header">
        <i class="material-icons">list</i>
        Editar planilha or&ccedil;ament&aacute;ria
      </div>
      <div class="collapsible-body" v-if="solicitacaoIniciada">
        <div class="card">
          <div class="card-content">
            <readequacao-saldo-aplicacao-resumo
              :valorSaldoAplicacao="valorSaldoAplicacao"
              :valorEntrePlanilhasLimpo="valorEntrePlanilhasLimpo"
              :valorSaldoDisponivelParaUso="valorSaldoDisponivelParaUso"
              :valorSaldoUtilizado="valorSaldoUtilizado"
              :valorSaldoDisponivelParaUsoNegativo="valorSaldoDisponivelParaUsoNegativo"
              :valorSaldoDisponivelParaUsoNeutro="valorSaldoDisponivelParaUsoNeutro"
              :valorSaldoDisponivelParaUsoPositivo="valorSaldoDisponivelParaUsoPositivo"
              :valorSaldoUtilizadoPositivo="valorSaldoUtilizadoPositivo"
              :valorSaldoUtilizadoNeutro="valorSaldoUtilizadoNeutro"
              :readequacaoAlterada="readequacaoAlterada"                                        
              :valorSaldoUtilizadoNegativo="valorSaldoUtilizadoNegativo"
              >
            </readequacao-saldo-aplicacao-resumo>
          </div>
        </div>
        
        <div class="card">
          <div class="card-content">
            <planilha-orcamentaria
              :id-pronac="idPronac"
              :tipo-planilha="tipoPlanilha"
              :link="1"
              :id-readequacao="dadosReadequacao.idReadequacao"
              :componente-planilha="componentePlanilha"
              :perfil="perfil"
              :disabled="disabled"
              :disponivelParaAdicaoItensReadequacaoPlanilha="disponivelParaAdicaoItensReadequacaoPlanilha"
              v-on:atualizarSaldoEntrePlanilhas="carregarValorEntrePlanilhas"
              >
            </planilha-orcamentaria>
          </div>
        </div>
        
        <div class="card">
          <div class="card-content">
            <readequacao-saldo-aplicacao-resumo
              :valorSaldoAplicacao="valorSaldoAplicacao"
              :valorEntrePlanilhasLimpo="valorEntrePlanilhasLimpo"
              :valorSaldoDisponivelParaUso="valorSaldoDisponivelParaUso"
              :valorSaldoUtilizado="valorSaldoUtilizado"
              :valorSaldoDisponivelParaUsoNegativo="valorSaldoDisponivelParaUsoNegativo"
              :valorSaldoDisponivelParaUsoPositivo="valorSaldoDisponivelParaUsoPositivo"
              :valorSaldoUtilizadoPositivo="valorSaldoUtilizadoPositivo"
              :valorSaldoUtilizadoNeutro="valorSaldoUtilizadoNeutro"
              :readequacaoAlterada="readequacaoAlterada"
              :valorSaldoUtilizadoNegativo="valorSaldoUtilizadoNegativo"
              >
            </readequacao-saldo-aplicacao-resumo>
          </div>
        </div>
        
      </div>
      <div class="collapsible-body card" v-else>
        <span>Preencha o valor do saldo dispon&iacute;vel para poder iniciar as altera&ccedil;&atilde;oes na planilha or&ccedil;ament&aacute;ria.</span>
      </div>
    </li>
  </ul>
  <div v-if="disabled">
    <div class="card">
      <div class="card-content">
        <h4>Saldo de aplica&ccedil;&atildeo declarado</h4>
        <h6 class="blue-text lighten-1">R$ {{valorSaldoAplicacao}}</h6>
      </div>
    </div>
    <div class="card">
      <div class="card-content">
        <planilha-orcamentaria
          :id-pronac="idPronac"
          :tipo-planilha="tipoPlanilha"
          :link="1"
          :id-readequacao="dadosReadequacao.idReadequacao"
          :componente-planilha="componentePlanilha"
          :perfil="perfil"
          :disabled="disabled"
          :disponivelParaAdicaoItensReadequacaoPlanilha="disponivelParaAdicaoItensReadequacaoPlanilha"
          v-on:atualizarSaldoEntrePlanilhas="carregarValorEntrePlanilhas"
          >
        </planilha-orcamentaria>
      </div>
    </div>
  </div>
  
  <div class="card" v-if="mostrarBotoes">
    <div class="card-content">
      <div class="row">
        <div class="right-align padding20 col s12">
          <button
            class="waves-light waves-effect btn red modal-trigger"
            v-on:click="prepararExcluirReadequacao()"
            >Excluir</button>
          <a
            class="waves-light waves-effect btn modal-trigger"
            href="#modalFinalizar"
            :disabled="!podeFinalizarReadequacao"
            >Finalizar</a>
        </div>
      </div>
    </div>
  </div>
  
  <div id="modalExcluir" class="modal">
    <div class="modal-content center-align">
      <h4>Tem certeza que deseja excluir a redequa&ccedil;&atilde;o?</h4>
    </div>
    <div class="modal-footer">
      <a class="waves-effect waves-green btn-flat red white-text"
         v-on:click="excluirReadequacao">Excluir
      </a>
      <a class="modal-close waves-effect waves-green btn-flat"
         href="#!">Cancelar
      </a>                                                                                                
    </div>
  </div>                                                
  <div id="modalFinalizar" class="modal">
    <div class="modal-content center-align">
      <h4>Tem certeza que deseja finalizar a redequa&ccedil;&atilde;o?</h4>
    </div>
    <div class="modal-footer">
      <a 
        class="waves-effect waves-green btn-flat green white-text"
        v-on:click="finalizarReadequacao">Finalizar
      </a>
      <a class="modal-close waves-effect waves-green btn-flat"
         href="#!">Cancelar
      </a>                                                                                                
    </div>
  </div>                                                
  
  <div v-if="mostrarMensagemFinal" class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s1 right-align"><i class="medium green-text material-icons">check_circle</i></div>
        <div class="col s11">
          <p><b>Solicita&ccedil;&atilde;o enviada com sucesso!</b></p>
          <p>Sua solicita&ccedil;&atilde;o agora est&aacute; para an&aacute;lise t&eacute;cnica do MinC.</p>
          <p>Para acompanhar, acesse o menu lateral 'Execu&ccedil;&atilde;o -> Dados das readequa&ccedil;&otilde;es'
            em <a :href="'/projeto/#incentivo/' + idPronac">consultar dados do projeto</a>.</p>
        </div>
      </div>
    </div>
  </div>
</div>

</div> <!-- remover quando finalizar migraçao para vuetify -->
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import { utils } from '@/mixins/utils';
import ReadequacaoSaldoAplicacaoSaldo from '../components/ReadequacaoSaldoAplicacaoSaldo';
import ReadequacaoSaldoAplicacaoResumo from '../components/ReadequacaoSaldoAplicacaoResumo';
import ReadequacaoFormulario from '../components/ReadequacaoFormulario';
import ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria from '../components/ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria';
import PlanilhaOrcamentariaAlterarItem from '../components/PlanilhaOrcamentariaAlterarItem';
import PlanilhaOrcamentariaIncluirItem from '../components/PlanilhaOrcamentariaIncluirItem';
import PlanilhaOrcamentaria from '../components/PlanilhaOrcamentaria';

export default {
    name: 'SaldoAplicacaoView',
    components: {
        ReadequacaoSaldoAplicacaoResumo,
        ReadequacaoSaldoAplicacaoSaldo,
        ReadequacaoFormulario,
        PlanilhaOrcamentariaAlterarItem,
        PlanilhaOrcamentariaIncluirItem,
        ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria,
        PlanilhaOrcamentaria,
    },
    mixins: [utils],
    data() {
        return {
            disabled: false,
            idPronac: '',
            pronac: '',
            nomeProjeto: '',
            idTipoReadequacao: 22,
            siEncaminhamento: 12,
            perfil: 1111,
            exibirBotaoIniciar: false,
            exibirPaineis: false,
            solicitacaoIniciada: false,
            mostrarMensagemFinal: false,
            valorEntrePlanilhas: [],
            tipoPlanilha: 7,
            componenteFormulario: 'ReadequacaoSaldoAplicacaoSaldo',
            componentePlanilha: 'ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria',
            disponivelParaAdicaoItensReadequacaoPlanilha: false,
            readequacaoAlterada: false,
        };
    },
    created() {
        if (typeof this.$route.params.idPronac !== 'undefined') {
            this.idPronac = this.$route.params.idPronac;
            if (Object.keys(this.dadosProjeto).length === 0) {
                this.buscaProjeto(this.idPronac);
            }
        }
        if (typeof this.dadosReadequacao.idReadequacao === 'undefined') {
            const idPronac = this.idPronac;
            const idTipoReadequacao = this.idTipoReadequacao;
            const stEstagioAtual = 'proponente';
            this.buscaReadequacaoPronacTipo({ idPronac, idTipoReadequacao, stEstagioAtual });
        }
	/*
        $3(document).ajaxStart(() => {
            $3('#container-loading').fadeIn('slow');
        });
        $3(document).ajaxComplete(() => {
            $3('#container-loading').fadeOut('slow');
        });
*/
    },
    methods: {
        solicitarUsoSaldo() {
            const self = this;
	    /*
            $3.ajax({
                url: '/readequacao/saldo-aplicacao/solicitar-uso-saldo',
                type: 'POST',
                data: {
                    idPronac: self.idPronac,
                },
            }).done(
                () => {
                    const idPronac = self.idPronac;
                    const idTipoReadequacao = self.idTipoReadequacao;
                    const stEstagioAtual = 'proponente';
                    self.buscaReadequacaoPronacTipo({ idPronac, idTipoReadequacao, stEstagioAtual });
                    self.exibirPaineis = true;
                    self.exibirBotaoIniciar = false;
                    self.disponivelEdicaoReadequacaoPlanilha(self.idPronac);
                    self.carregarValorEntrePlanilhas();
                },
            );
*/
        },
        atualizarReadequacao(readequacao) {
            this.readequacaoAlterada = true;
            this.readequacao = readequacao;
        },
        carregarValorEntrePlanilhas() {
            const self = this;
	    /*
            $3.ajax({
                type: 'GET',
                url: '/readequacao/saldo-aplicacao/carregar-valor-entre-planilhas',
                data: {
                    idPronac: self.idPronac,
                    idTipoReadequacao: self.idTipoReadequacao,
                },
            }).done((response) => {
                self.valorEntrePlanilhas = response.valorEntrePlanilhas;
            });
*/
        },
        prepararExcluirReadequacao() {
            this.excluirReadequacao({
                idPronac: this.idPronac,
                idReadequacao: this.dadosReadequacao.idReadequacao,
            });
            /*
            $3('#modalExcluir .modal-content h4').html('');
            $3('#modalExcluir .modal-footer').html('<h5>Removendo os dados, aguarde...</h5>');
            let self = this;
            $3.ajax({
                type: "GET",
                url: "/readequacao/saldo-aplicacao/excluir-readequacao",
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.dadosReadequacao.idReadequacao
                }
            }).done(function (response) {
                // TODO: alterar o store
                self.restaurarFormulario();
                self.mensagemSucesso(response.msg);
                self.solicitacaoIniciada = false;
                self.exibirBotaoIniciar = true;
                self.exibirPaineis = false;
            }).fail(function (response) {
                self.mensagemErro(response.responseJSON.msg)
            });
*/
        },
        finalizarReadequacao() {
            const self = this;
	    /*
            $3.ajax({
                type: 'POST',
                url: '/readequacao/saldo-aplicacao/finalizar-readequacao',
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.dadosReadequacao.idReadequacao,
                },
            }).done(
                (response) => {
                    self.mensagemSucesso(response.msg);
                    self.exibirPaineis = false;
                    self.exibirBotaoIniciar = false;
                    self.mostrarMensagemFinal = true;
                    self.dadosReadequacao.idReadequacao = '';
                    $3('#modalFinalizar').modal('close');
                },
            ).fail(
                (response) => {
                    self.mensagemErro(response.responseJSON.msg);
                },
            );*/
        },
        restaurarFormulario() {
            this.readequacao = {
                idPronac: null,
                idReadequacao: null,
                justificativa: '',
                arquivo: null,
                idTipoReadequacao: null,
                dsSolicitacao: '',
                idArquivo: null,
                nomeArquivo: null,
            };
            this.readequacaoAlterada = false;
        },
        corValor(valor) {
            let cor = '';
            if (valor > 0) {
                cor = 'positivo';
            } else if (valor < 0) {
                cor = 'negativo';
            }
            return cor;
        },
        ...mapActions({
            buscaProjeto: 'projeto/buscaProjeto',
            buscaReadequacaoPronacTipo: 'readequacao/buscaReadequacaoPronacTipo',
            excluirReadequacao: 'readequacao/excluirReadequacao',
            obterDisponivelEdicaoReadequacaoPlanilha: 'readequacao/obterDisponivelEdicaoItemSaldoAplicacao',
        }),
    },
    watch: {
        $route(to, from) {
            if (
                typeof to.params.idPronac !== 'undefined' &&
                    to.params.idPronac !== from.params.idPronac
            ) {
                const idPronac = to.params.idPronac;
                const idTipoReadequacao = this.idTipoReadequacao;
                const URL_MENU = '';
                const stEstagioAtual = 'proponente';
                this.buscaReadequacaoPronacTipo({ idPronac, idTipoReadequacao, stEstagioAtual });
                this.urlAjax = URL_MENU + to.params.idPronac;
            }
        },
        dadosReadequacao() {
            if (typeof this.dadosReadequacao.idReadequacao !== 'undefined') {
                this.exibirBotaoIniciar = false;
                //$3('.collapsible').collapsible();
                const idPronac = this.dadosProjeto.idPronac;
                this.obterDisponivelEdicaoReadequacaoPlanilha(idPronac);
                this.carregarValorEntrePlanilhas();
                this.solicitacaoIniciada = true;
                this.exibirPaineis = true;
            } else {
                this.exibirBotaoIniciar = true;
            }
        },
        solicitacaoIniciada() {
            //$3('#modalExcluir').modal();
            //$3('#modalExcluir').css('height', '20%');
            //$3('#modalFinalizar').modal();
            //$3('#modalFinalizar').css('height', '20%');
        },
    },
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            dadosReadequacao: 'readequacao/getReadequacao',
        }),
        vlDiferencaEntrePlanilhas() {
            if (typeof this.valorEntrePlanilhas.vlDiferencaPlanilhas !== 'undefined') {
                return this.valorEntrePlanilhas.vlDiferencaPlanilhas;
            }
            return false;
        },
        valorEntrePlanilhasLimpo() {
            return (
                this.valorEntrePlanilhas.PlanilhaAtivaTotal - this.valorEntrePlanilhas.PlanilhaReadequadaTotal
            ).toFixed(2);
        },
        valorSaldoAplicacao() {
            return this.dadosReadequacao.dsSolicitacao;
        },
        valorSaldoDisponivelParaUso() {
            return Number(this.valorSaldoAplicacao) + Number(this.valorEntrePlanilhasLimpo);
        },
        valorSaldoUtilizado() {
            return this.valorEntrePlanilhas.PlanilhaReadequadaTotal - this.valorEntrePlanilhas.PlanilhaAtivaTotal;
        },
        valorSaldoDisponivelParaUsoPositivo() {
            if (this.valorSaldoDisponivelParaUso > 0) {
                return true;
            }
            return false;
        },
        valorSaldoDisponivelParaUsoNeutro() {
            if (this.valorSaldoDisponivelParaUso === 0) {
                return true;
            }
            return false;
        },
        valorSaldoDisponivelParaUsoNegativo() {
            if (this.valorSaldoDisponivelParaUso < 0) {
                return true;
            }
            return false;
        },
        valorSaldoUtilizadoPositivo() {
            if (this.valorSaldoUtilizado > 0) {
                return true;
            }
            return false;
        },
        valorSaldoUtilizadoNeutro() {
            if (this.valorSaldoUtilizado === 0) {
                return true;
            }
            return false;
        },
        valorSaldoUtilizadoNegativo() {
            if (this.valorSaldoUtilizado < 0) {
                return true;
            }
            return false;
        },
        podeFinalizarReadequacao() {
            if ((this.valorSaldoDisponivelParaUsoPositivo
              || this.valorSaldoDisponivelParaUsoNeutro)
                && this.valorSaldoUtilizadoPositivo
                && !this.readequacaoAlterada
            ) {
                return true;
            }
            return false;
        },
        mostrarBotoes() {
            if (typeof this.dadosReadequacao === 'undefined') {
                return false;
            }
            return true;
        },
    },
};
</script>
