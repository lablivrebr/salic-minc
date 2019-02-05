import * as api from './base';

const buildData = (params) => {
    const bodyFormData = new FormData();

    Object.keys(params).forEach((key) => {
        bodyFormData.append(key, params[key]);
    });

    return bodyFormData;
};

export const buscaReadequacao = (params) => {
    const { idPronac, idTipoReadequacao } = params;
    const path = `/readequacao/readequacoes/obter-dados-readequacao/?idPronac=${idPronac}&idTipoReadequacao=${idTipoReadequacao}`;
    return api.getRequest(path);
};

export const updateReadequacaoSaldoAplicacao = (params) => {
    const path = '/readequacao/saldo-aplicacao/salvar-readequacao';
    return api.putRequest(path, buildData(params), params.idReadequacao);
};

export const excluirReadequacaoSaldoAplicacao = (params) => {
    const path = '/readequacao/saldo-aplicacao/excluir-readequacao';
    return api.postRequest(path, buildData(params));
};

export const obterDisponivelEdicaoItemSaldoAplicacao = (idPronac) => {
    const path = '/readequacao/saldo-aplicacao/disponivel-edicao-item/?idPronac=';
    return api.getRequest(path, idPronac);
};

export const adicionarDocumento = (params) => {
    const path = '/readequacao/readequacoes/salvar-documento/';
    return api.postRequest(path, buildData(params));
};

export const excluirDocumento = (params) => {
    const path = '/readequacao/readequacoes/excluir-documento/';
    return api.postRequest(path, buildData(params));
};