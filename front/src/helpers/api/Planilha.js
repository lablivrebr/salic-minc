import * as api from './base';

export const obterUnidadesPlanilha = () => {
    const module = '/planilha';
    const controller = '/planilha-unidade-rest';
    const action = '';

    return api.getRequest(`${module}${controller}${action}`);
};
