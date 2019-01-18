import * as types from './types';

export const state = {
    produtos: [],
    produto: {},
    analiseConteudo: {},
    planilhaParecer: {},
    produtosSecundarios: [],
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
    [types.SET_PLANILHA_PARECER](state, planilha) {
        state.planilhaParecer = planilha;
    },
    [types.SET_PRODUTOS_SECUNDARIOS](state, produtos) {
        state.produtosSecundarios = produtos;
    },
};
