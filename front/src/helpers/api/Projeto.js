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

export const buscaPlanilhaOriginal = (idPreProjeto) => {
    const path = '/proposta/visualizar/obter-planilha-proposta-original-ajax/';
    const queryParams = `?idPreProjeto=${idPreProjeto}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaReadequada = (idPronac) => {
    const path = '/projeto/orcamento/obter-planilha-readequada-ajax/';
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaAutorizada = (idPreProjeto) => {
    const path = '/proposta/visualizar/obter-planilha-proposta-original-ajax/';
    const queryParams = `?idPreProjeto=${idPreProjeto}`;
    return api.getRequest(path, queryParams);
};

export const buscaPlanilhaAdequada = (idPreProjeto) => {
    const path = '/proposta/visualizar/obter-planilha-proposta-adequada-ajax/';
    const queryParams = `?idPreProjeto=${idPreProjeto}`;
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
