import * as api from './base';

export const obterProdutosParaAnalise = () => {
    const module = '/parecer';
    const controller = '/analise-inicial-rest';
    const action = '';

    return api.getRequest(`${module}${controller}${action}`);
};

export const obterProdutoParaAnalise = (params) => {
    const module = '/parecer';
    const controller = '/analise-inicial-rest';
    const id = `id/${params.id}`;
    const idPronac = `idPronac/${params.idPronac}`;

    const queryParams = `/${id}/${idPronac}`;

    return api.getRequest(`${module}${controller}${queryParams}`);
};
