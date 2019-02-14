import * as api from './base';

const buildData = (params) => {
    const bodyFormData = new FormData();

    Object.keys(params).forEach((key) => {
        bodyFormData.append(key, params[key]);
    });

    return bodyFormData;
};

export const obterProdutosParaAnalise = () => {
    const module = '/parecer';
    const controller = '/produto-rest';
    const action = '';

    return api.getRequest(`${module}${controller}${action}`);
};

export const obterProdutoParaAnalise = (params) => {
    const module = '/parecer';
    const controller = '/produto-rest';
    const id = `id/${params.id}`;
    const idPronac = `idPronac/${params.idPronac}`;

    const queryParams = `/${id}/${idPronac}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};


export const obterAnaliseConteudo = (params) => {
    const module = '/parecer';
    const controller = '/analise-inicial-conteudo-rest';
    const id = `id/${params.id}`;
    const idPronac = `idPronac/${params.idPronac}`;

    const queryParams = `/${id}/${idPronac}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};

export const salvarAnaliseConteudo = params => api.postRequest('/parecer/analise-inicial-conteudo-rest/', buildData(params));

export const obterPlanilhaParaAnalise = (params) => {
    const module = '/parecer';
    const controller = '/analise-inicial-custo-rest';
    const id = `id/${params.id}`;
    const idPronac = `idPronac/${params.idPronac}`;
    const stPrincipal = `stPrincipal/${params.stPrincipal}`;

    const queryParams = `/${id}/${idPronac}/${stPrincipal}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};

export const obterProdutosSecundarios = (params) => {
    const module = '/parecer';
    const controller = '/analise-inicial-outros-produtos-rest';
    const idProduto = `id/${params.idProduto}`;
    const idPronac = `idPronac/${params.idPronac}`;

    const queryParams = `/${idProduto}/${idPronac}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};

export const salvarAvaliacaoItem = params => api.postRequest('/parecer/analise-inicial-custo-rest/', buildData(params));

export const obterAnaliseConsolidacao = (params) => {
    const module = '/parecer';
    const controller = '/analise-inicial-consolidacao-rest';
    const id = `id/${params.id}`;
    const idPronac = `idPronac/${params.idPronac}`;

    const queryParams = `/${id}/${idPronac}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};

export const salvarAnaliseConsolidacao = params => api.postRequest('/parecer/analise-inicial-consolidacao-rest/', buildData(params));

export const restaurarPlanilhaProduto = params => api.postRequest('/parecer/planilha-produto-rest', buildData(params));
