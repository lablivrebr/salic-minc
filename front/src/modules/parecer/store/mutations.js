import * as types from './types';

export const state = {
    produtos: [],
    produto: {},
    analiseConteudo: {},
    planilhaParecer: {},
};

export const mutations = {
    [types.SET_PRODUTOS](state, produtos) {
        state.produtos = produtos;
    },
    [types.SET_PRODUTO](state, produto) {
        state.produto = produto;
    },
    [types.SET_ANALISE_CONTEUDO](state, analise) {
        state.analiseConteudo = analise;
    },
    [types.SET_PLANILHA_PARECER](state, analise) {
        state.planilhaParecer = analise;
    },
};
