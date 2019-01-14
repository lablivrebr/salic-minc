import * as types from './types';

export const state = {
    produtos: [],
};

export const mutations = {
    [types.SET_PRODUTOS](state, produtos) {
        state.produtos = produtos;
    },
};
