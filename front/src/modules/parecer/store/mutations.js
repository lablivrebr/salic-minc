import * as types from './types';

export const state = {
    produtos: [],
    produto: {},
    analiseConteudo: {},
    planilhaParecer: {},
    produtosSecundarios: [],
    planilhaSecundario: {},
    analiseConteudoSecundario: {},
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
    [types.UPDATE_ITEM_PLANILHA](state, params) {
        const produto = params.idProduto !== 0 ? params.Produto : 'Administração do Projeto';
        const fonte = params.FonteRecurso;
        const etapa = params.Etapa;
        const regiao = `${params.UF} - ${params.Cidade}`;

        const items = state.planilhaParecer[fonte][produto][etapa][regiao].itens;

        const index = items.findIndex(
            item => item.idPlanilhaProjeto === params.idPlanilhaProjeto,
        );
        Object.assign(items[index], params);
    },
};
