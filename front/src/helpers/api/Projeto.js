import * as api from './base';

export const buscaProjeto = (idPronac) => {
    const module = '/projeto';
    const controller = '/projeto';
    const action = '/get';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(`${module}${controller}${action}`, queryParams);
};

export const buscarProjetoCompleto = (idPronac) => {
    const module = '/projeto';
    const controller = '/dados-projeto';
    const action = '/get';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(`${module}${controller}${action}`, queryParams);
};

export const buscarTransferenciaRecursos = (idPronac, acao) => {
    const module = '/readequacao';
    const controller = '/transferencia-recursos-rest';
    const action = '/index';
    const queryParams = `?idPronac=${idPronac}&acao=${acao}`;
    return api.getRequest(`${module}${controller}${action}`, queryParams);
};

export const buscaProponente = (idPronac) => {
    const path = '/projeto/proponente-rest';
    const queryParams = `/idPronac/${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaHomologada = (idPronac) => {
    const path = '/projeto/orcamento/obter-planilha-homologada-ajax';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaOriginal = (idPronac) => {
    const path = '/projeto/orcamento/obter-planilha-proposta-original-ajax/';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaReadequada = (idPronac) => {
    const path = '/projeto/orcamento/obter-planilha-readequada-ajax/';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaAutorizada = (idPronac) => {
    const path = '/projeto/orcamento/obter-planilha-proposta-original-ajax/';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaAdequada = (idPronac) => {
    const path = '/projeto/orcamento/obter-planilha-proposta-adequada-ajax/';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarCertidoesNegativas = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/certidoes-negativas-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDocumentosAssinados = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/documentos-assinados-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDadosComplementares = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/dados-complementares-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDocumentosAnexados = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/documentos-anexados-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarLocalRealizacaoDeslocamento = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/local-realizacao-deslocamento-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarProvidenciaTomada = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/providencia-tomada-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarPlanoDistribuicaoIn2013 = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/plano-distribuicao-in2013-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarHistoricoEncaminhamento = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/historico-encaminhamento-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarTramitacaoDocumento = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/tramitacao-documento-rest';
    const metodo = '/get';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarTramitacaoProjeto = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/tramitacao-projeto-rest';
    const metodo = '/get';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarUltimaTramitacao = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/ultima-tramitacao-rest';
    const metodo = '/get';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarPlanoDistribuicaoIn2017 = (idPreProjeto) => {
    const modulo = '/projeto';
    const controller = '/plano-distribuicao-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPreProjeto=${idPreProjeto}`;
    return api.getRequest(path, queryParams);
};

export const buscarDiligenciaProposta = (idPreprojeto, idAvaliacaoProposta) => {
    const modulo = '/projeto';
    const controller = '/diligencia-proposta-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPreProjeto=${idPreprojeto}&id=${idAvaliacaoProposta}`;
    return api.getRequest(path, queryParams);
};

export const buscarDiligenciaAdequacao = (idPronac, idAvaliarAdequacaoProjeto) => {
    const modulo = '/projeto';
    const controller = '/diligencia-adequacao-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}&id=${idAvaliarAdequacaoProjeto}`;
    return api.getRequest(path, queryParams);
};

export const buscarDiligenciaProjeto = (idPronac, idDiligencia) => {
    const modulo = '/projeto';
    const controller = '/diligencia-projeto-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}&id=${idDiligencia}`;
    return api.getRequest(path, queryParams);
};

export const buscarDiligencia = (idPronac) => {
    const modulo = '/projeto';
    const controller = '/diligencia-projeto-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarMarcasAnexadas = (idPronac) => {
    const modulo = '/execucao';
    const controller = '/marcas-anexadas-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDadosReadequacoes = (idPronac) => {
    const modulo = '/execucao';
    const controller = '/dados-readequacoes-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarPedidoProrrogacao = (idPronac) => {
    const modulo = '/execucao';
    const controller = '/pedido-prorrogacao-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDadosFiscalizacaoLista = (idPronac) => {
    const modulo = '/execucao';
    const controller = '/fiscalizacao-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDadosFiscalizacaoVisualiza = (idPronac, idFiscalizacao) => {
    const modulo = '/execucao';
    const controller = '/fiscalizacao-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?id=${idFiscalizacao}&idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarContasBancarias = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/contas-bancarias-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarConciliacaoBancaria = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/conciliacao-bancaria-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${params.idPronac}&dtPagamentoInicio=${params.dtInicio}&dtPagamentoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarInconsistenciaBancaria = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/inconsistencia-bancaria-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${params.idPronac}&dtPagamentoInicio=${params.dtInicio}&dtPagamentoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarLiberacao = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/liberacao-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarSaldoContas = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/saldo-contas-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarExtratosBancarios = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/extratos-bancarios-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${params.idPronac}&dtLancamento=${params.dtLancamento}&dtLancamentoFim=${params.dtLancamentoFim}&tpConta=${params.tpConta}`;
    return api.getRequest(path, queryParams);
};

export const buscarExtratosBancariosConsolidado = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/extratos-bancarios-consolidado-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarDepositoEquivocado = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/deposito-equivocado-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${params.idPronac}&dtDevolucaoInicio=${params.dtInicio}&dtDevolucaoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarCaptacao = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/captacao-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${params.idPronac}&dtReciboInicio=${params.dtInicio}&dtReciboFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarDevolucoesIncentivador = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/devolucoes-incentivador-rest';
    const metodo = '/index';
    const path = `${modulo}${controller}${metodo}`;
    const queryParams = `?idPronac=${params.idPronac}&dtDevolucaoInicio=${params.dtInicio}&dtDevolucaoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};
