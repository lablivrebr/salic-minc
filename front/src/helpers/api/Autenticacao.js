import * as api from './base';

const buildData = (params) => {
    const bodyFormData = new FormData();

    Object.keys(params).forEach((key) => {
        bodyFormData.append(key, params[key]);
    });

    return bodyFormData;
};

const path = '/navegacao/menu-principal';

export const usuarioLogado = () => api.getRequest('/autenticacao/usuario/usuario/logado');