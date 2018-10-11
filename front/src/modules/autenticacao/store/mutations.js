import * as types from './types';

export const state = {
    usuario: {},
};

export const mutations = {
    [types.SET_USUARIO_LOGADO](state, dados) {
        state.usuario = dados;
    },
};
