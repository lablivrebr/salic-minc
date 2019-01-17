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
    const controller = '/analise-conteudo-rest';
    const id = `id/${params.id}`;
    const idPronac = `idPronac/${params.idPronac}`;

    const queryParams = `/${id}/${idPronac}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};

export const salvarAnaliseConteudo = params => api.postRequest('/parecer/analise-conteudo-rest/', buildData(params));
