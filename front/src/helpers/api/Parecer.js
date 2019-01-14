import * as api from './base';

export const obterProdutosParaAnalise = () => {
    const module = '/parecer';
    const controller = '/analise-inicial-rest';
    const action = '';

    return api.getRequest(`${module}${controller}${action}`);
};
