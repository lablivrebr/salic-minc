<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="dadosListagem"
            class="elevation-1 container-fluid"
            rows-per-page-text="Items por Página"
            hide-actions
            no-data-text="Nenhum dado encontrado"
        >
            <template slot="items" slot-scope="props">
                <td class="text-xs-center">
                    <v-btn flat icon>
                        <v-tooltip bottom>
                            <v-icon
                                    slot="activator"
                                    @click="showItem(props.item.idFiscalizacao)"
                                    class="material-icons"
                                    dark>visibility
                            </v-icon>
                            <span>Visualizar Dados Fiscalizacao</span>
                        </v-tooltip>
                    </v-btn>
                </td>
                <td class="text-xs-center" v-html="props.item.dtInicio"></td>
                <td class="text-xs-center">{{ props.item.dtFim }}</td>
                <td class="text-xs-center">{{ props.item.cpfTecnico }}</td>
                <td class="text-xs-center">{{ props.item.nmTecnico }}</td>
            </template>
        </v-data-table>
        <v-layout row justify-center>
            <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
                <v-card>
                    <v-toolbar dark color="primary">
                        <v-btn icon dark @click="dialog = false">
                            <v-icon>close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Dados Fiscalizacao Completos</v-toolbar-title>
                        <v-spacer></v-spacer>
                    </v-toolbar>
                    <v-container style="max-width: 100%;">
                            <v-card>
                                <v-subheader class="primary justify-center">
                                    <div>
                                        <h3 class="display-1 white--text font-weight-light">Locais</h3>
                                    </div>
                                </v-subheader>
                                <v-container>
                                    <v-layout>
                                        <v-flex xs4 offset-xs2>
                                            <p><b>REGIAO</b></p>
                                        </v-flex>
                                        <v-flex xs4 offset-xs2 class="pl-4">
                                            <p><b>UF</b></p>
                                        </v-flex>
                                        <v-flex xs4 offset-xs2>
                                            <p><b>CIDADE</b></p>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout v-for="(dado, index) in dadosVisualizacao.locaisFiscalizacao" :key="index">
                                        <v-flex xs4 offset-xs2>
                                            <p>{{ dado.regiao }}</p>
                                        </v-flex>
                                        <v-flex xs4 offset-xs2>
                                            <p>{{ dado.uf }}</p>
                                        </v-flex>
                                        <v-flex xs4 offset-xs2>
                                            <p>{{ dado.cidade }}</p>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                            <v-card>
                                <v-subheader class="primary justify-center">
                                    <div>
                                        <h3 class="display-1 white--text font-weight-light">Oficializar Fiscalização</h3>
                                    </div>
                                </v-subheader>
                            </v-card>
                            <v-card>
                                <v-subheader class="justify-center">
                                    <div>
                                        <h4 class="display-1 grey--text text--darken-4 font-weight-light">Datas / Demandante</h4>
                                    </div>
                                </v-subheader>
                                <v-container v-for="(dado, index) in dadosVisualizacao.oficializarFiscalizacao" :key="index">
                                    <v-layout >
                                        <v-flex xs6 offset-xs2>
                                            <p><b>Dt. Inicio</b></p>
                                            {{ dado.dtInicio }} <br>
                                        </v-flex>
                                        <v-flex xs6 offset-xs2>
                                            <p><b>Dt. Fim</b></p>
                                            {{ dado.dtFim }} <br>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout >
                                        <v-flex xs6 offset-xs2>
                                            <br><p><b>Demandante da Fiscalização</b></p>
                                            <p v-if="dado.tpDemandante == 0" class="justify-center">SEFIC</p>
                                            <p v-else-if="dado.tpDemandante == 1">SAV</p>
                                        </v-flex>
                                        <v-flex xs6 offset-xs2>
                                            <br><p><b>Data de Resposta</b></p>
                                            <p v-if="dado.dtResposta.length > 1"> {{ dado.dtResposta }} </p>
                                            <p v-else-if="dado.dtResposta.length == 1" class="justify-center"> - </p>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                            <v-card>
                                <v-subheader class="justify-center">
                                    <div>
                                        <h4 class="display-1 grey--text text--darken-4 font-weight-light">Identificação do Técnico</h4>
                                    </div>
                                </v-subheader>
                                <v-container v-for="(dado, index) in dadosVisualizacao.oficializarFiscalizacao" :key="index">
                                    <v-layout>
                                        <v-flex xs6 offset-xs2>
                                            <p><b>CPF</b></p>
                                            {{ dado.cpfTecnico }} <br>
                                        </v-flex>
                                        <v-flex xs6 offset-xs2>
                                            <p><b>Técnico</b></p>
                                            {{ dado.nmTecnico }} <br>
                                        </v-flex>
                                    </v-layout>
                                    <v-layout>
                                        <v-flex xs10 offset-xs2>
                                            <br><p><b>Dados para Fiscalização</b></p>
                                            {{ dado.dsFiscalizacaoProjeto }}
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                            <v-card>
                                <v-subheader class="primary justify-center">
                                    <div>
                                        <h3 class="display-1 white--text font-weight-light">Fiscalização Concluída para Parecer</h3>
                                    </div>
                                </v-subheader>
                            </v-card>
                            <div v-if="parecer">
                                <v-card>
                                    <v-card-title class="justify-center">
                                        <h2 class="display-1 font-weight-light">Resumo da Execução</h2>
                                    </v-card-title>
                                    <v-container>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ações Programadas</b></p>
                                                    {{ parecer.resumoExecucao.dsAcoesProgramadas }}
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ações Executadas</b></p>
                                                    {{ parecer.resumoExecucao.dsAcoesExecutadas }}
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Benefícios Alcançados</b></p>
                                                    {{ parecer.resumoExecucao.dsBeneficioAlcancado }}
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Dificuldades Encontradas</b></p>
                                                    {{ parecer.resumoExecucao.dsDificuldadeEncontrada }}
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card v-if="parecer.stDtDeCorte == 1">
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Situação do Convênio na Realização da Fiscalização</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Situação SIAFI</b></p>
                                                    {{ parecer.stConvenioFiscalizacao.stSiafi }}
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Prestação de Contas Apresentada?</b></p>
                                                    <div v-html="parecer.stConvenioFiscalizacao.stPrestacaoContas"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Foram cumpridas as normas estabelecidas?</b></p>
                                                <div v-html="parecer.stConvenioFiscalizacao.stCumpridasNormas"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Foi cumprido o prazo para entrega da prestação de contas?</b></p>
                                                <div v-html="parecer.stConvenioFiscalizacao.stCumpridoPrazo"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Utilização de Recursos</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Foi apurado por unidade fiscalizadora ou auditora a aplicação irregular de recursos?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stApuracaoUFiscalizacao"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Comprovou a correta utilização dos recursos da contrapartida?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stComprovacaoUtilizacaoRecurso"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-if="parecer.stDtDeCorte == 0"><br><p><b>Há compatibilidade entre os recursos transferidos e a evolução do projeto?</b></p></div>
                                                <div v-else-if="parecer.stDtDeCorte == 1"><br><p><b>Há compatibilidade entre o desembolso e a evolução?</b></p></div>
                                                <div v-html="parecer.utilizacaoRecursos.stCompatibilidadeDesembolsoEvo"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ocorreu despesas com multas, juros, taxas bancárias ou correção monetária?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stOcorreuDespesas"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ocorreu pagamento de servidor público?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stPagamentoServidorPublico"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ocorreu despesa com taxa de administração?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stDespesaAdministracao"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Há transferência de recurso para clubes/associações ou outras entidades congêneres?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stTransferenciaRecurso"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Há despesas com publicidade, salvo as de caráter educativo, informativo ou de orientação social?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stDespesasPublicidade"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ocorreu aditamento prevendo alteração de objeto?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stOcorreuAditamento"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-if="parecer.stDtDeCorte == 0"><br><p><b>Os recursos de contrapartida foram depositados na conta do projeto?</b></p></div>
                                                <div v-else-if="parecer.stDtDeCorte == 1"><br><p><b>Não foram aplicados os recursos de contrapartida?</b></p></div>
                                                <div v-html="parecer.utilizacaoRecursos.stAplicadosRecursos"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ocorreu aplicação de recursos em outra finalidade que não a do objeto pactuado?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stAplicacaoRecursosFinalidade"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 0">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Os recursos captados estão sendo aplicados em conformidade com a legislação vigente?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stRecursosCaptados"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ocorreu saldo após o encerramento do projeto?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stSaldoAposEncerramento"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>O saldo verificado foi recolhido ao FNC?</b></p>
                                                <div v-html="parecer.utilizacaoRecursos.stSaldoVerificacaoFNC"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Comprovantes Fiscais de Despesa</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-if="parecer.stDtDeCorte == 0"><br><p><b>O proponente/convenente tem mantido a documentação relativa ao projeto em arquivo próprio?</b></p></div>
                                                <div v-else-if="parecer.stDtDeCorte == 1"><br><p><b>O processo está bem documentado?</b></p></div>
                                                <div v-html="parecer.comprovantesDespesa.stProcessoDocumentado"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>A documentação está completa e arquivada?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stDocumentacaoCompleta"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Guardam conformidade entre o executado e o aprovado?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stConformidadeExecucao"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-if="parecer.stDtDeCorte == 0"><br><p><b>Identificam o projeto com o número do Pronac/Convênio?</b></p></div>
                                                <div v-if="parecer.stDtDeCorte == 1"><br><p><b>Identificam o nome do projeto e o número do convênio?</b></p></div>
                                                <div v-html="parecer.comprovantesDespesa.stIdentificaProjeto"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Existem despesas anteriores ao prazo de vigência?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stDespesaAnterior"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Existem despesas posteriores ao prazo de vigência?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stDespesaPosterior"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>As despesas coincidem com as informadas na relação de pagamentos?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stDespesaCoincidem"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>As despesas estão devidamente relacionadas no extrato bancário?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stDespesaRelacionada"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Os comprovantes fiscais estão com o atesto do recebimento?</b></p>
                                                <div v-html="parecer.comprovantesDespesa.stComprovanteFiscal"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Divulgação</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Ciência ao Poder legislativo?</b></p>
                                                <div v-html="parecer.divulgacao.stCienciaLegislativo"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Contemplou as exigências legais?</b></p>
                                                <div v-html="parecer.divulgacao.stExigenciaLegal"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Há material informativo do Projeto?</b></p>
                                                <div v-html="parecer.divulgacao.stMaterialInformativo"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Execução</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Alcançou a finalidade esperada?</b></p>
                                                <div v-html="parecer.execucao.stFinalidadeEsperada"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 1">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>As metas/etapas do Plano de Trabalho foram executadas integralmente?</b></p>
                                                <div v-html="parecer.execucao.stPlanoTrabalho"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-if="parecer.stDtDeCorte == 0"><br><p><b>O projeto está sendo executado de acordo com o aprovado?</b></p></div>
                                                <div v-if="parecer.stDtDeCorte == 1"><br><p><b>A execução respeitou o aprovado?</b></p></div>
                                                <div v-html="parecer.execucao.stExecucaoAprovado"></div>
                                            </v-flex>
                                        </v-layout>
                                        <v-layout v-if="parecer.stDtDeCorte == 0">
                                            <v-flex xs10 offset-xs1>
                                                <br><p><b>Observações</b></p>
                                                <div v-html="parecer.execucao.dsObservacao"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Empregos gerados em decorrência do projeto</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container >
                                        <v-layout>
                                            <v-flex xs4 offset-xs1>
                                                <br><p><b>Diretos: </b>{{ parecer.empregosGeradosProjeto.qtEmpregoDireto }}</p>
                                            </v-flex>
                                            <v-flex xs4 offset-xs1>
                                                <br><p><b>Indiretos: </b>{{ parecer.empregosGeradosProjeto.qtEmpregoIndireto }}</p>
                                            </v-flex>
                                            <v-flex xs4 offset-xs1>
                                                <br><p><b>Total: </b>{{ parecer.empregosGeradosProjeto.qtEmpregoTotal }}</p>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Evidências</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container >
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-html="parecer.empregosGeradosProjeto.dsEvidencia"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Recomendações da Equipe</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container >
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-html="parecer.empregosGeradosProjeto.dsRecomendacaoEquipe"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Conclusão da Equipe</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container >
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-if="parecer.empregosGeradosProjeto.dsConclusaoEquipe.length > 1" v-html="parecer.empregosGeradosProjeto.dsConclusaoEquipe"></div>
                                                <div v-else>Não se Aplica.</div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Parecer da Fiscalização</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container >
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-html="parecer.empregosGeradosProjeto.dsParecerTecnico"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                                <v-card>
                                    <v-subheader class="justify-center">
                                        <div>
                                            <h4 class="display-1 grey--text text--darken-4 font-weight-light">Parecer do Coordenador</h4>
                                        </div>
                                    </v-subheader>
                                    <v-container >
                                        <v-layout>
                                            <v-flex xs10 offset-xs1>
                                                <div v-html="parecer.empregosGeradosProjeto.dsParecer"></div>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-card>
                            </div>
                            <v-card>
                                <v-subheader class="justify-center">
                                    <div>
                                        <h4 class="display-1 grey--text text--darken-4 font-weight-light">Anexos</h4>
                                    </div>
                                </v-subheader>
                                <v-container v-for="(dado, index) in dadosVisualizacao.arquivosFiscalizacao" :key="index">
                                    <v-layout>
                                        <v-flex xs10 offset-xs1>
                                            <a :href="`/upload/abrir?id=${dado.idArquivo}`"><v-icon color="black"> file_copy</v-icon>{{ dado.nmArquivo }}</a>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                            <v-footer
                                height="auto"
                            >
                                <v-card
                                    class="flex"
                                    flat
                                    tile
                                >
                                    <v-card-actions class="justify-center">
                                        <v-btn @click="dialog = false" class="primary">
                                            FECHAR
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-footer>
                    </v-container>
                </v-card>
            </v-dialog>
        </v-layout>
    </div>
</template>
<script>

    import { mapActions, mapGetters } from 'vuex';

    let contador = 0;

    export default {
        name: 'DadosFiscalizacao',
        data() {
            return {
                dialog: false,
                loading: true,
                headers: [
                    {
                        text: 'VISUALIZAR',
                        align: 'center',
                        sortable: false,
                        value: 'dados',
                    },
                    {
                        text: 'DT. INICIO',
                        align: 'center',
                        value: 'dtInicio',
                    },
                    {
                        text: 'DT. FIM',
                        align: 'center',
                        value: 'dtFim',
                    },
                    {
                        text: 'CPF TECNICO',
                        align: 'center',
                        value: 'cpfTecnico',
                    },
                    {
                        text: 'NOME TECNICO',
                        align: 'center',
                        value: 'nmTecnico',
                    },
                ],
            };
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDadosFiscalizacaoLista(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dadosListagem() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosListagem: 'projeto/dadosFiscalizacaoLista',
                dadosVisualizacao: 'projeto/dadosFiscalizacaoVisualiza',
            }),
            parecer() {
                return this.dadosVisualizacao.fiscalizacaoConcluidaParecer;
            },
        },
        methods: {
            showItem(idFiscalizacao) {
                const idPronac = this.dadosProjeto.idPronac;

                this.buscarDadosFiscalizacaoVisualiza({ idPronac, idFiscalizacao });
                this.dialog = true;
            },
            definirClasseTimeline() {
                 contador++;

                return {
                    'v-timeline-item--left': parseInt(contador % 2, 10) === 0,
                    'v-timeline-item--right': parseInt(contador % 2, 10) !== 0,
                };
            },
            ...mapActions({
                buscarDadosFiscalizacaoLista: 'projeto/buscarDadosFiscalizacaoLista',
                buscarDadosFiscalizacaoVisualiza: 'projeto/buscarDadosFiscalizacaoVisualiza',
            }),
        },
    };
</script>

