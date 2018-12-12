Vue.component('readequacao-transferencia-recursos', {
    template: `
<div class='readequacao-transferencia-recursos'>
	<div class="card">
    <div class="card-content">
		  <span class="card-title">Projeto transferidor</span>
			<div class="row">
        <div class="col s2">
          <b>Pronac: </b>{{ projetoTransferidor.pronac }}<br>
        </div>
        <div class="col s4">
          <b>Projeto: </b><span v-html="projetoTransferidor.nome"></span>
        </div>
        <div class="col s2">
          <b>&Aacute;rea: </b><span v-html="projetoTransferidor.area"></span>
        </div>
        <div class="col s2">
          <b>Vl. a Comprovar: </b><span style="white-space:nowrap;">R$ {{ valorFormatado(this.projetoTransferidor.valorComprovar) }}</span>
        </div>
        <div class="col s2">
          <b>Saldo dispon&iacute;vel: </b><span style="white-space:nowrap;">R$ {{ valorFormatado(saldoDisponivel) }}</span>
        </div>
      </div>
		</div>
  </div>
	
  <ul v-if="!disabled"  class="collapsible">
    <li id="collapsible-first">
      <div class="collapsible-header active"><i class="material-icons">assignment</i>Solicita&ccedil;&atilde;o de readequa&ccedil;&atilde;o</div>
      <div class="collapsible-body">
	      <readequacao-formulario
					ref="formulario"
					:id-pronac="idPronac"
					:disabled="disabled"
					:id-tipo-readequacao="idTipoReadequacao"
					:componente-ds-solicitacao='componente'
					:objReadequacao="readequacao"
					v-on:eventoAtualizarReadequacao="atualizarReadequacao"
					v-on:eventoSalvarReadequacao="salvarReadequacao"
					>
	      </readequacao-formulario>
      </div>
    </li>
    <li>
			<div v-show="disponivelEditarProjetosRecebedores" class="collapsible-header"><i class="material-icons">list</i>Projetos recebedores</div>
			<div class="collapsible-body card" >
				<div class="card-content">
					<span class="card-title">Projetos recebedores</span>
					<table v-show="exibeProjetosRecebedores" class="animated fadeIn">
						<thead>
							<th>Pronac</th>
							<th>Nome do projeto</th>
							<th>Vl. transfer&ecirc;ncia</th>
						</thead>
						<tbody>
							<tr v-for="(projeto, index) in projetosRecebedores" class="animated fadeIn">
								<td>{{ projeto.pronac }}</td>
								<td>{{ projeto.nome}}</td>
								<td colspan="2">R$ {{ valorFormatado(projeto.vlRecebido)}}</td>
								<td class="right">
									<a href="javascript:void(0)"
										 v-on:click="excluirRecebedor(index, projeto.idSolicitacaoTransferenciaRecursos)"
										 class="btn small">
										<i class="material-icons">delete</i>					
									</a>
								</td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="2"></td>
								<td class="right">Total transferido: </td>
								<td>R$ {{ valorFormatado(totalRecebido) }} </td>
							</tr>
						</tfoot>
					</table>
					<form class="row">
						<div v-if="disponivelAdicionarRecebedor">
							
							<div class="col s6">
								<label>Pronac recebedor</label>
								<input type="text" 
											 ref="projetoRecebedorIdPronac"
											 v-model="projetoRecebedor.pronac" 
                       @blur="selecionaPronacRecebedor"
											 />
								<span>{{ projetoRecebedor.nome }}</span>
								</span>
							</div>
							
 							<div class="input-field col s3">
								<input-money
									ref="projetoRecebedorValorRecebido"
									v-on:ev="projetoRecebedor.vlRecebido = $event"
									v-bind:value="projetoRecebedor.valorComprovar"
									:disabled="!disponivelAdicionarRecebedor">
								</input-money>
								<label for="valor_recebido">Valor recebido</label>
							</div>		
							<div class="center-align padding20 col s3">
								<a href="javascript:void(0)"
									 v-on:click="incluirRecebedor"
									 :disabled="!disponivelAdicionarRecebedor"
									 class="btn">Adicionar recebedor</a>
							</div>
							
						</div>
						
						<div v-else-if="!disponivelAdicionarRecebedor">
							<label>Sem projetos recebedores dispon&iacute;veis!</label>
						</div>
				
					</form>
				</div>
	    </div>
    </li>
    <div class="card">
      <div class="right-align padding20 col s12">
				<a v-show="readequacao.idReadequacao" class="waves-light waves-effect btn red modal-trigger" href="#modalExcluir">Excluir</a>
				
	      <button
	        v-on:click="finalizarReadequacao"
					:disabled="!disponivelFinalizar()"
					class="btn">Finalizar</button>
			</div>
    </div>
  </ul>
  <template v-if="disabled">
		<div class="card">
			<readequacao-formulario
				ref="formulario"
				:id-pronac="idPronac"
        :disabled="disabled"
				:id-tipo-readequacao="idTipoReadequacao"
				:componente-ds-solicitacao='componente'
        :objReadequacao="readequacao"
        v-on:eventoAtualizarReadequacao="atualizarReadequacao"
        v-on:eventoSalvarReadequacao="salvarReadequacao"
				>
	    </readequacao-formulario>
		</div>
		
		<div class="card">
			<div class="card-content">
				<div class="card">
					<span class="card-title">Projetos recebedores</span>
					<div class="card-content">
						<table v-show="exibeProjetosRecebedores">
							<thead>
								<th>Pronac</th>
								<th>Nome do projeto</th>
								<th>Vl. transfer&ecirc;ncia</th>
							</thead>
							<tbody>
								<tr v-for="(projeto, index) in projetosRecebedores" class="animated fadeIn">
									<td>{{ projeto.pronac }}</td>
									<td>{{ projeto.nome}}</td>
									<td>R$ {{ projeto.vlRecebido}}</td>
								</tr>
							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td class="right">Total transferido: </td>
									<td>R$ {{ totalRecebido }} </td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
	</template>

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
</div>
	    `,
    props: {
        'idPronac': '',
        'idTipoReadequacao': '',
        'siEncaminhamento': '',
        'disabled': false
    },
    mixins: [utils],
    data: function () {
        var readequacao = {
                'idPronac': null,
                'idReadequacao': null,
                'justificativa': '',
                'arquivo': null,
                'idTipoReadequacao': null,
                'dsSolicitacao': '',
                'idArquivo': null,
                'nomeArquivo': null
            },
            projetoTransferidor = function () {
                return defaultProjetoTransferidor();
            },
            projetoRecebedor = {},
            tiposTransferencia = [
                {
                    'id': 1,
                    'nome': 'N\xE3o homologados'
                },
                {
                    'id': 2,
                    'nome': 'Homologados'
                },
                {
                    'id': 3,
                    'nome': 'Recursos remanescentes'
                }
            ],
            areasRecebedoresMultiplos = [
                {
                    'id': 7,
                    'area': 'Patrimônio cultural'
                },
                {
                    'id': 9,
                    'area': 'Museus e memória'
                }
            ];

        return {
            projetoTransferidor,
            readequacao,
            projetoRecebedor,
            tiposTransferencia,
            projetosRecebedores: [],
            areasRecebedoresMultiplos,
            componente: 'readequacao-transferencia-recursos-tipo-transferencia'
        }
    },
    created: function () {
        this.projetoRecebedor = this.defaultProjetoRecebedor();
        this.obterDadosReadequacao();
        this.obterDadosProjetoTransferidor();
    },
    mounted: function () {
    },
    methods: {
        defaultProjetoRecebedor: function () {
            return {
                idSolicitacaoTransferenciaRecursos: "",
                nome: "",
                idPronac: "",
                vlRecebido: "0.00",
                pronac: "",
                disponivel: false
            };
        },
        defaultProjetoTransferidor: function () {
            return {
                pronac: null,
                idPronac: null,
                pronac: '',
                nome: '',
                area: '',
                valorComprovar: 0.00,
                saldoDisponivel: 0.00
            }
        },
        incluirRecebedor: function () {
            if (this.projetoRecebedor.pronac == '' ||
                this.projetoRecebedor.pronac == undefined
            ) {
                this.mensagemAlerta("\xC9 obrigat\xF3rio informar o projeto recebedor!");
                this.$refs.projetoRecebedorIdPronac.focus();
                return;
            }
            if (this.projetoRecebedor.vlRecebido == '' ||
                this.projetoRecebedor.vlRecebido == undefined
            ) {
                this.mensagemAlerta("\xC9 obrigat\xF3rio informar o valor a ser recebido pelo projeto!");
                this.$refs.projetoRecebedorValorRecebido.$refs.input.focus();
                return;
            }

            if (this.projetoRecebedor.vlRecebido <= 0) {
                this.mensagemAlerta("Informe um valor positivo a ser recebido pelo projeto!");
                this.$refs.projetoRecebedorValorRecebido.$refs.input.focus();
                return;
            }

            var vlRecebido = parseFloat(this.projetoRecebedor.vlRecebido.replace(",", "."));
            vlRecebido = vlRecebido.toFixed(2);
            this.projetoRecebedor.vlRecebido = vlRecebido.replace(".", ",");

            var somaTransferencia = parseFloat(this.projetoRecebedor.vlRecebido.replace(",", ".")) + parseFloat(this.totalRecebido);
            somaTransferencia = somaTransferencia.toFixed(2);
            var saldoDisponivel = parseFloat(this.projetoTransferidor.saldoDisponivel);
            saldoDisponivel = saldoDisponivel.toFixed(2)

            if (parseFloat(somaTransferencia) > parseFloat(this.projetoTransferidor.saldoDisponivel)) {
                this.mensagemAlerta("Voc\xEA ultrapassou o saldo dispon\xEDvel para transfer\xEAncia, que \xE9 de R$ " + saldoDisponivel + "!");
                this.$refs.projetoRecebedorValorRecebido.$refs.input.focus();
                return;
            }

            var self = this;
            $3.ajax({
                type: "POST",
                url: "/readequacao/transferencia-recursos/incluir-projeto-recebedor",
                data: {
                    idReadequacao: self.readequacao.idReadequacao,
                    dsSolicitacao: self.readequacao.dsSolicitacao,
                    idPronac: self.projetoTransferidor.idPronac,
                    idPronacRecebedor: self.projetoRecebedor.idPronac,
                    vlRecebido: self.projetoRecebedor.vlRecebido
                }
            }).done(function (response) {
                if (response.resposta == true) {
                    self.projetoRecebedor.idSolicitacaoTransferenciaRecursos = response.projetoRecebedor.idSolicitacaoTransferenciaRecursos;
                    self.projetoRecebedor.vlRecebido = response.projetoRecebedor.vlRecebido;
                    self.projetosRecebedores.push(
                        self.projetoRecebedor
                    );

                    self.projetoRecebedor = self.defaultProjetoRecebedor();
                    self.projetoRecebedor.valorComprovar = "0";
                    self.$refs.projetoRecebedorValorRecebido.$refs.input.value = "0";
                } else {
                    self.mensagemAlerta("Erro ao incluir o projeto: <br/>" + response.msg);
                    return;
                }
            });
        },
        excluirRecebedor: function (index, idSolicitacaoTransferenciaRecursos) {
            var self = this;
            $3.ajax({
                type: "POST",
                url: "/readequacao/transferencia-recursos/excluir-projeto-recebedor",
                data: {
                    idPronac: self.idPronac,
                    idSolicitacaoTransferenciaRecursos
                }
            }).done(function (response) {
                if (response.resposta) {
                    Vue.delete(self.projetosRecebedores, index);
                    self.mensagemSucesso(response.msg);
                }
            });
        },

        salvarReadequacao: function (readequacao) {
            if (readequacao.dsSolicitacao == '' ||
                readequacao.dsSolicitacao == undefined
            ) {
                this.mensagemAlerta("\xC9 obrigat\xF3rio informar o tipo da transfer\xEAncia!");
                this.$refs.formulario.$children[0].$refs.readequacaoTipoTransferencia.focus();
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
                url: "/readequacao/transferencia-recursos/salvar-readequacao",
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.readequacao.idReadequacao,
                    justificativa: self.readequacao.justificativa,
                    dsSolicitacao: self.readequacao.dsSolicitacao
                }
            }).done(function (response) {
                $3('.collapsible').collapsible('close', 0);
                $3('.collapsible').collapsible('open', 1);
                self.readequacao.idReadequacao = response.readequacao;
                self.mensagemSucesso(response.msg);
            });
        },
        atualizarReadequacao: function (readequacao) {
            this.readequacao = readequacao;
        },
        finalizarReadequacao: function () {
            let self = this;
            $3.ajax({
                type: "POST",
                url: "/readequacao/transferencia-recursos/finalizar-solicitacao-transferencia-recursos",
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.readequacao.idReadequacao
                }
            }).done(function (response) {
                if (response.error) {
                    self.mensagemAlerta("Ocorreu um erro na finaliza&ccedil;&atilde;o da readequa&ccedil;&atilde;o.");
                } else {
                    self.mensagemSucesso("Solicita&ccedil;&atilde;o de transfer&ecirc;ncia de recursos finalizada.");
                    voltar();
                }
            });
        },
        excluirReadequacao: function () {
            $3('#modalExcluir .modal-content h4').html('');
            $3('#modalExcluir .modal-footer').html('<h5>Removendo os dados, aguarde...</h5>');

            let self = this;

            $3.ajax({
                type: "GET",
                url: "/readequacao/transferencia-recursos/excluir-readequacao",
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.readequacao.idReadequacao
                }
            }).done(function (response) {
                self.mensagemSucesso(response.msg);
                $3('#modalExcluir').modal('close');
                self.projetosRecebedores = [];
                self.projetoRecebedor = self.defaultProjetoRecebedor();
                self.readequacao = {
                    'idPronac': null,
                    'idReadequacao': null,
                    'justificativa': '',
                    'arquivo': null,
                    'idTipoReadequacao': null,
                    'dsSolicitacao': '',
                    'idArquivo': null,
                    'nomeArquivo': null
                };
                $3('.collapsible').collapsible('open', 0);
                $3('.collapsible').collapsible('close', 1);
            }).fail(function (response) {
                self.mensagemErro(response.msg)
                $3('#modalExcluir').modal('close');
            });
        },
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
                }
            });
        },
        obterDadosProjetoTransferidor: function () {
            let self = this;

            $3.ajax({
                type: "GET",
                url: "/readequacao/transferencia-recursos/dados-projeto-transferidor",
                data: {
                    idPronac: self.idPronac
                }
            }).done(function (response) {
                self.projetoTransferidor = response.projeto;
            });
        },
        obterProjetosRecebedores: function () {
            let self = this;
            $3.ajax({
                type: "GET",
                url: "/readequacao/transferencia-recursos/listar-projetos-recebedores",
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.readequacao.idReadequacao
                }
            }).done(function (response) {
                self.projetosRecebedores = response.projetos;
            });
        },
        updateRecebedor: function (e) {
            if (e.target.options.selectedIndex > -1) {
                this.projetoRecebedor.nome = e.target.options[e.target.options.selectedIndex].dataset.nome;
            }
        },
        selecionaPronacRecebedor: function () {
            let self = this;

            if (this.projetoRecebedor.pronac == '') {
                return;
            }

            if (isNaN(this.projetoRecebedor.pronac)) {
                this.projetoRecebedor.pronac = '';
                self.mensagemAlerta("O PRONAC informado &eacute; inv&aacute;lido.");
                return;
            }

            $3.ajax({
                type: "POST",
                url: "/readequacao/transferencia-recursos/verificar-pronac-disponivel-receber",
                data: {
                    idPronac: self.idPronac,
                    idReadequacao: self.readequacao.idReadequacao,
                    pronacRecebedor: self.projetoRecebedor.pronac
                }
            }).done(function (response) {
                if (!response.projetoDisponivel) {
                    self.mensagemAlerta("O PRONAC informado &eacute; duplicado ou inv&aacute;lido.");
                    self.projetoRecebedor.pronac = '';
                    self.projetoRecebedor.disponivel = false;
                    self.$refs.projetoRecebedorIdPronac.focus();
                } else {
                    self.projetoRecebedor.nome = response.nomeProjeto;
                    self.projetoRecebedor.idPronac = response.idPronac;
                    self.projetoRecebedor.disponivel = true;
                }
            });
        },
        areasEspeciais: function () {
            let self = this;
            if (_.find(this.areasRecebedoresMultiplos, function (i) {
                if (i.id == self.projetoTransferidor.idArea) {
                    return true
                }
            })) {
                return true;
            } else {
                return;
            }
        },
        disponivelFinalizar: function () {
            if (this.projetosRecebedores.length > 0 &&
                this.totalRecebido <= this.projetoTransferidor.saldoDisponivel
            ) {
                return true;
            } else {
                return false;
            }
        },
        valorFormatado: function (valor) {
            return this.converterParaMoedaPontuado(valor);
        },

    },
    watch: {
        readequacao: function () {
            if (this.readequacao.idReadequacao != null) {
                $3('#modalExcluir').modal();
                $3('#modalExcluir').css('height', '20%');
                this.obterProjetosRecebedores();
            }
        },
    },
    computed: {
        totalRecebido: function () {
            self = this;

            return this.projetosRecebedores.reduce(function (total, projeto) {
                var resultado = parseFloat(total) + parseFloat(projeto.vlRecebido);
                return resultado.toFixed(2);
            }, 0);
        },
        saldoDisponivel: function () {
            var saldo = parseFloat(this.projetoTransferidor.valorComprovar) - parseFloat(this.totalRecebido);
            return saldo;
        },
        disponivelEditarProjetosRecebedores: function () {
            if (typeof this.readequacao.idReadequacao == 'undefined') {
                return false;
            } else {
                if (this.readequacao.idReadequacao == null || this.readequacao.idReadequacao == '') {
                    return false;
                }
            }
            return true;
        },
        disponivelAdicionarRecebedor: function () {
            if (this.totalRecebido == this.projetoTransferidor.saldoDisponivel) {
                return false;
            } else {
                return true;
            }
        },
        disponivelAdicionarRecebedores: function () {
            if (this.areasEspeciais()) {
                return true;
            } else {
                if ((this.readequacao.idReadequacao == null
                    || this.readequacao.idReadequacao == 'undefined')
                ) {
                    return false;
                } else if (this.projetosRecebedores.length == 0) {
                    return false;
                } else {
                    return true;
                }
            }
        },
        exibeProjetosRecebedores: function () {
            if (this.projetosRecebedores.length > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
})
