Vue.component('readequacao-saldo-aplicacao', {
    template: `
<div class='readequacao-saldo-aplicacao'>
	<div class="card">
		<div class="card-content">
		  <div class="col s2">
				<b>Pronac: </b>{{ pronac }}<br>
		  </div>
			<div class="col s8">
				<b>Projeto: </b><span v-html="nomeProjeto"></span>
			</div>
			<br/>
		</div>
	</div>
	
	<div v-show="exibirBotaoIniciar">
		<button class="waves-effect waves-light btn btn-primary small btn-novaproposta"
						name=""
					  v-on:click="solicitarUsoSaldo()"
						id="novo">
			<i class="material-icons left">border_color</i>Solicitar uso do saldo de aplica&ccedil;&atilde;o
		</button>
	</div>
	<ul v-if="!disabled" class="collapsible" v-show="exibirPaineis">
		<li id="collapsible-first">
			<div class="collapsible-header active"><i class="material-icons">assignment</i>Solicita&ccedil;&atilde;o de readequa&ccedil;&atilde;o</div>
			<div class="collapsible-body">
				<readequacao-formulario
					ref="formulario"
					:id-pronac="idPronac"
					:disabled="disabled"
					:id-tipo-readequacao="idTipoReadequacao"
					:componente-ds-solicitacao='componenteFormulario'
					:objReadequacao="readequacao"
					v-on:eventoAtualizarReadequacao="atualizarReadequacao"
					v-on:eventoSalvarReadequacao="salvarReadequacao"
					>
				</readequacao-formulario>
			</div>
		</li>
		<li>
			<div class="collapsible-header"><i class="material-icons">list</i>Editar planilha or&ccedil;ament&aacute;ria</div>
			<div class="collapsible-body card" v-if="solicitacaoIniciada">
				<div class="row center-align">
					<h4>Saldo</h4>
					<div class="col s4 center-align green lighten-3">
						<h6>Rendimento declarado</h6>
						<span style="font-weight:bold">R$ {{valorSaldoAplicacaoFormatado}}</span>
					</div>					
					<div class="col s4 center-align" v-bind:class="{ 'blue lighten-3': valorSaldoDisponivelParaUsoPositivo, 'red lighten-3': valorSaldoDisponivelParaUsoNegativo }">
						<h6>Dispon&iacute;vel</h6>
						<span style="font-weight:bold">R$ {{valorSaldoDisponivelParaUsoFormatado}}</span>
					</div>
					<div class="col s4 center-align" v-bind:class="{ 'blue lighten-3': valorSaldoUtilizadoPositivo, 'red lighten-3': valorSaldoUtilizadoNegativo }">
						<h6>Utilizado</h6>
						<span style="font-weight:bold">R$ {{valorSaldoUtilizadoFormatado}}</span>
					</div>
				</div>
				<div class="row center-align" v-show="valorSaldoDisponivelParaUsoNegativo">
					<div class="col s12 center-align">
						<span style="font-weight:bold" class="">Diminua os valores da planilha em R$ {{valorSaldoDisponivelParaUsoMensagem}} para poder finalizar a solicita&ccedil;&atilde;o.</span>
					</div>
				</div>
				<div class="row center-align" v-show="valorSaldoUtilizadoNegativo">
					<div class="col s12 center-align">
						<span style="font-weight:bold" class="">O total da planilha &eacute; menor que o valor original; saldo de aplica&ccedil;&atilde;o n&atilde;o utilizado.</span>
					</div>
				</div>							
				
				<div class="card-content">
					<planilha-orcamentaria
						:id-pronac="idPronac"
						:tipo-planilha="tipoPlanilha"
						:link="1"
						:id-readequacao="readequacao.idReadequacao"
						:componente-planilha="componentePlanilha"
						:perfil="perfil"
						:disponivelParaAdicaoItensReadequacaoPlanilha="disponivelParaAdicaoItensReadequacaoPlanilha"
						:disponivelParaEdicaoReadequacaoPlanilha="disponivelParaEdicaoReadequacaoPlanilha"
						v-on:atualizarSaldoEntrePlanilhas="carregarValorEntrePlanilhas"
						>
					</planilha-orcamentaria>
				</div>

				<div class="row center-align">
					<h4>Saldo</h4>
					<div class="col s4 center-align">
						<h6>Rendimento declarado</h6>
						<span style="font-weight:bold">R$ {{valorSaldoAplicacaoFormatado}}</span>
					</div>					
					<div class="col s4 center-align" v-bind:class="{ 'blue lighten-3': valorSaldoDisponivelParaUsoPositivo, 'red lighten-3': valorSaldoDisponivelParaUsoNegativo }">
						<h6>Dispon&iacute;vel</h6>
						<span style="font-weight:bold">R$ {{valorSaldoDisponivelParaUsoFormatado}}</span>
					</div>
					<div class="col s4 center-align" v-bind:class="{ 'blue lighten-3': valorSaldoUtilizadoPositivo, 'red lighten-3': valorSaldoUtilizadoNegativo }">
						<h6>Utilizado</h6>
						<span style="font-weight:bold">R$ {{valorSaldoUtilizadoFormatado}}</span>
					</div>
				</div>
				<div class="row center-align" v-show="valorSaldoDisponivelParaUsoNegativo">
					<div class="col s12 center-align">
						<span style="font-weight:bold" class="">Diminua os valores da planilha em R$ {{valorSaldoDisponivelParaUsoMensagem}} para poder finalizar a solicita&ccedil;&atilde;o.</span>
					</div>
				</div>
				<div class="row center-align" v-show="valorSaldoUtilizadoNegativo">
					<div class="col s12 center-align">
						<span style="font-weight:bold" class="">O total da planilha &eacute; menor que o valor original; saldo de aplica&ccedil;&atilde;o n&atilde;o utilizado.</span>
					</div>
				</div>				
			</div>
			<div class="collapsible-body card" v-else>
				<span>Preencha o valor do saldo dispon&iacute;vel para poder iniciar as altera&ccedil;&atilde;oes na planilha or&ccedil;ament&aacute;ria.</span>
			</div>
		</li>
	</ul>
	<div class="card" v-show="readequacao.idReadequacao">
		<div class="card-content">
			<div class="row">
				<div class="right-align padding20 col s12">
					<a
						class="waves-light waves-effect btn red modal-trigger"
						href="#modalExcluir">Excluir</a>
					<a
						class="waves-light waves-effect btn modal-trigger"
						:disabled="!podeFinalizarReadequacao"
						href="#modalFinalizar">Finalizar</a>
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

</div>
    `,
    props: {
	'idPronac': '',
	'idTipoReadequacao': '',
	'nomeProjeto': '',
	'pronac' : '',
	'siEncaminhamento': '',
	'perfil': '',
	'disponivelParaAdicaoItensReadequacaoPlanilha': '',
	'disabled': false
    },
    mixins: [utils],
    data: function() {
	var readequacao = {
	    'idPronac': null,
	    'idReadequacao': null,
	    'justificativa': '',
	    'arquivo': null,
	    'idTipoReadequacao': null,
	    'dsSolicitacao': 0,
	    'idArquivo' : null,
	    'nomeArquivo': null
	};
	
	return {
	    readequacao,
	    exibirBotaoIniciar: false,
	    exibirPaineis: false,
	    solicitacaoIniciada: false,
	    valorEntrePlanilhas: [],
	    tipoPlanilha: 7,
	    componenteFormulario: 'readequacao-saldo-aplicacao-saldo',
	    componentePlanilha: 'readequacao-saldo-planilha-orcamentaria',
	    disponivelParaEdicaoReadequacaoPlanilha: ''
	}
    },
    created: function() {
	this.obterDadosReadequacao();
    },
    mounted: function() {
    },
    methods: {
        obterDadosReadequacao: function () {
            let self = this;
            $3.ajax({
                type: "GET",
                url: "/readequacao/readequacoes/obter-dados-readequacao",
                data: {
                    idTipoReadequacao: self.idTipoReadequacao,
                    idPronac: self.idPronac,
		    siEncaminhamento: self.siEncaminhamento
                }
            }).done(function (response) {
		if (_.isObject(response.readequacao)) {
                    self.readequacao = response.readequacao;
		    if (typeof response.readequacao.idReadequacao != 'undefined') {
			self.verificarDisponivelParaEdicaoReadequacaoPlanilha();
			self.carregarValorEntrePlanilhas();
			
			if (self.readequacao.dsSolicitacao > 0
			    && (typeof self.readequacao.idReadequacao != undefined
			     || self.readequacao.idReadequacao > 0)
			) {
			    self.solicitacaoIniciada = true;
			}
		    }
		}
            });
        },
	solicitarUsoSaldo: function() {
	    let self = this;
	    
	    $3.ajax({
		url: "/readequacao/saldo-aplicacao/solicitar-uso-saldo",
		type: 'POST',
		data: {
		    idPronac: self.idPronac
		},
	    }).done( function(response) {
		self.readequacao = response.readequacao;
		self.exibirPaineis = true;
		self.exibirBotaoIniciar = false;
		self.verificarDisponivelParaEdicaoReadequacaoPlanilha();
		self.carregarValorEntrePlanilhas();
	    });
	    
	},
	salvarReadequacao: function(readequacao) {
	    if (readequacao.dsSolicitacao == ''
		|| readequacao.dsSolicitacao == undefined
		|| readequacao.dsSolicitacao == 0
	    ) {
		this.mensagemAlerta("\xC9 obrigat\xF3rio informar o saldo dispon\xEDvel.!");
		this.$refs.formulario.$children[0].$refs.readequacaoSaldo.focus();
		return;		
	    }
	    
	    if (readequacao.justificativa.length == 0) {
		this.mensagemAlerta("\xC9 obrigat\xF3rio preencher a justificativa da readequa\xE7\xE3o!");
		this.$refs.formulario.$refs.readequacaoJustificativa.focus();
		
		return;
	    }
	    
	    let self = this;
            $3.ajax({
                type: "POST",
                url: "/readequacao/saldo-aplicacao/salvar-readequacao",
		data: {
		    idPronac: this.idPronac,
		    idReadequacao: readequacao.idReadequacao,
		    justificativa: readequacao.justificativa,
		    dsSolicitacao: readequacao.dsSolicitacao
		}
            }).done(function (response) {
		self.readequacao = readequacao;
		self.solicitacaoIniciada = true;
                self.mensagemSucesso(response.msg);
            });
	},
	atualizarReadequacao: function (readequacao) {
            this.readequacao = readequacao;
        },
	verificarDisponivelParaEdicaoReadequacaoPlanilha: function() {
	    let self = this;
	    $3.ajax({
		type: "GET",
		url: "/readequacao/saldo-aplicacao/verificar-disponivel-para-edicao-readequacao-planilha",
		data: {
		    idPronac: self.idPronac
		}
	    }).done(function(response) {
		self.disponivelParaEdicaoReadequacaoPlanilha = response.disponivelParaEdicaoReadequacaoPlanilha;
	    });
	},
	carregarValorEntrePlanilhas: function() {
	    let self = this;
	    $3.ajax({
		type: "GET",
		url: "/readequacao/saldo-aplicacao/carregar-valor-entre-planilhas",
		data: {
		    idPronac: self.idPronac,
		    idTipoReadequacao: 22
		}
	    }).done(function(response) {
		self.valorEntrePlanilhas = response.valorEntrePlanilhas;
	    });
	},
	excluirReadequacao: function () {
	    $3('#modalExcluir .modal-content h4').html('');
	    $3('#modalExcluir .modal-footer').html('<h5>Removendo os dados, aguarde...</h5>');
	    
	    let self = this;
	    
	    $3.ajax({
		type: "GET",
		url: "/readequacao/saldo-aplicacao/excluir-readequacao",
		data: {
		    idPronac: self.idPronac,
		    idReadequacao: self.readequacao.idReadequacao
		}
	    }).done(function (response) {
		self.restaurarFormulario();
                self.mensagemSucesso(response.msg);
		$3('.collapsible').collapsible('open', 0);
		$3('.collapsible').collapsible('close', 0);
		self.solicitacaoIniciada = false;
		self.exibirBotaoIniciar = true;
		self.exibirPaineis = false;
		$3('#modalExcluir').modal('close');
            }).fail(function (response) {
                self.mensagemErro(response.responseJSON.msg)
		$3('#modalExcluir').modal('close');
            });
	},
	finalizarReadequacao: function() {
	    // TODO
	},
	restaurarFormulario: function() {
	    this.readequacao = {
		'idPronac': null,
		'idReadequacao': null,
		'justificativa': '',
		'arquivo': null,
		'idTipoReadequacao': null,
		'dsSolicitacao': '',
		'idArquivo' : null,
		'nomeArquivo': null
	    };
	},
	corValor: function(valor) {
	    let cor = '';
	    if (valor > 0) {
		cor = 'positivo';
	    } else if (valor < 0) {
		cor = 'negativo';
	    }
	    return cor;
	}
    },
    watch: {
	readequacao: function() {
	    if ((this.readequacao.idReadequacao == null
	       ||this.readequacao.idReadequacao == undefined)
		&& (!this.solicitacaoIniciada)) {
		this.exibirBotaoIniciar = true;
	    }

	    if (typeof this.readequacao.idReadequacao == 'string') {
		this.exibirPaineis = true;
	    }
	},
	solicitacaoIniciada: function() {
	    $3('#modalExcluir').modal();
	    $3('#modalExcluir').css('height', '20%');
	    $3('#modalFinalizar').modal();
	    $3('#modalFinalizar').css('height', '20%');
	}
    },
    computed: {
	vlDiferencaEntrePlanilhas: function() {
	    if (typeof this.valorEntrePlanilhas.vlDiferencaPlanilhas != 'undefined') {
		return this.valorEntrePlanilhas.vlDiferencaPlanilhas;
	    }
	},
	valorEntrePlanilhasLimpo: function() {
	    return (this.valorEntrePlanilhas.PlanilhaAtivaTotal - this.valorEntrePlanilhas.PlanilhaReadequadaTotal).toFixed(2);
	},
	valorSaldoAplicacao: function() {
	    let valorSaldoAplicacao = parseFloat(this.readequacao.dsSolicitacao);
	    return valorSaldoAplicacao;
	},
	valorSaldoAplicacaoFormatado: function() {
	    return numeral(this.valorSaldoAplicacao).format();
	},
	valorSaldoDisponivelParaUso: function() {
	    return this.valorSaldoAplicacao  + parseFloat(this.valorEntrePlanilhasLimpo);
	},
	valorSaldoDisponivelParaUsoPositivo: function() {
	    if (this.valorSaldoDisponivelParaUso > 0) {
		return true;
	    }
	},
	valorSaldoDisponivelParaUsoNeutro: function() {
	    if (this.valorSaldoDisponivelParaUso == 0) {
		return true;
	    }
	},
	valorSaldoDisponivelParaUsoNegativo: function() {
	    if (this.valorSaldoDisponivelParaUso < 0) {
		return true;
	    }
	},
	valorSaldoDisponivelParaUsoMensagem: function() {
	    return numeral(this.valorSaldoDisponivelParaUso * -1).format();
	},
	valorSaldoDisponivelParaUsoFormatado: function() {
	    return numeral(this.valorSaldoDisponivelParaUso).format();
	},
	valorSaldoUtilizado: function() {
	    return this.valorEntrePlanilhas.PlanilhaReadequadaTotal - this.valorEntrePlanilhas.PlanilhaAtivaTotal;
	},
	valorSaldoUtilizadoPositivo: function() {
	    if (this.valorSaldoUtilizado > 0) {
		return true;
	    } else {
		return false;
	    }
	},
	valorSaldoUtilizadoNegativo: function() {
	    if (this.valorSaldoUtilizado < 0) {
		return true;
	    } else {
		return false;
	    }
	},
	valorSaldoUtilizadoFormatado: function() {
	    return numeral(this.valorSaldoUtilizado).format();
	},
	podeFinalizarReadequacao: function() {
	    if ((this.valorSaldoDisponivelParaUsoPositivo
		 || this.valorSaldoDisponivelParaUsoNeutro)
		&& this.valorSaldoUtilizadoPositivo
	       ) {
		return true;
	    } else {
		return false;
	    }
	}
    }
});
