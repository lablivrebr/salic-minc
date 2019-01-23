import * as types from './types';

export const state = {
    unidades: [],
};

export const mutations = {
    [types.SET_UNIDADES](state, dados) {
        state.unidades = dados;
    },
};
