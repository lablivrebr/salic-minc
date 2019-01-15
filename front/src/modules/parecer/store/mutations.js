import * as types from './types';

export const state = {
    produtos: [],
    produto: {},
};

export const mutations = {
    [types.SET_PRODUTOS](state, produtos) {
        state.produtos = produtos;
    },
    [types.SET_PRODUTO](state, produto) {
        state.produto = produto;
    },
};
