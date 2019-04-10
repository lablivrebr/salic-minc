import * as types from './types';

export const state = {
    produtos: [],
    produto: {},
    analiseConteudo: {},
    planilhaParecer: [],
    produtosSecundarios: [],
    planilhaSecundario: [],
    planilhaSelecionados: [],
    analiseConteudoSecundario: {},
    consolidacao: {},
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
    [types.SET_ANALISE_CONTEUDO_SECUNDARIO](state, analise) {
        state.analiseConteudoSecundario = analise;
    },
    [types.SET_PLANILHA_SECUNDARIO](state, planilha) {
        state.planilhaSecundario = planilha;
    },
    [types.SET_CONSOLIDACAO](state, dados) {
        state.consolidacao = dados;
    },
    [types.UPDATE_ITEM_PLANILHA](state, params) {
        const index = state.planilhaParecer.findIndex(
            item => parseInt(item.idPlanilhaProjeto, 10) === parseInt(params.idPlanilhaProjeto, 10),
        );

        if (index >= 0) {
            Object.assign(state.planilhaParecer[index], params);
        }
    },
};
