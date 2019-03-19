import * as parecerHelperAPI from '@/helpers/api/Parecer';
import * as types from './types';

export const parecerMensagemSucesso = ({ commit }, msg) => {
    commit('noticias/SET_DADOS',
        {
            ativo: true,
            color: 'success',
            text: msg,
        },
        { root: true });
};

export const parecerMensagemErro = ({ commit }, msg) => {
    commit('noticias/SET_DADOS',
        {
            ativo: true,
            color: 'error',
            text: msg,
        },
        { root: true });
};

export const obterProdutosParaAnalise = ({ commit }) => {
    commit(types.SET_PRODUTOS, []);
    parecerHelperAPI.obterProdutosParaAnalise()
        .then((response) => {
            commit(types.SET_PRODUTOS, response.data.items);
        });
};

export const obterProdutoParaAnalise = ({ commit }, params) => {
    commit(types.SET_PRODUTO, {});
    parecerHelperAPI.obterProdutoParaAnalise(params)
        .then((response) => {
            commit(types.SET_PRODUTO, response.data);
        });
};

export const obterAnaLiseConteudo = ({ commit }, params) => {
    commit(types.SET_ANALISE_CONTEUDO, {});
    parecerHelperAPI.obterAnaliseConteudo(params)
        .then((response) => {
            commit(types.SET_ANALISE_CONTEUDO, response.data);
        });
};

export const salvarAnaLiseConteudo = async ({ commit, dispatch }, avaliacao) => parecerHelperAPI.salvarAnaliseConteudo(avaliacao)
    .then((response) => {
        commit(types.SET_ANALISE_CONTEUDO, avaliacao);
        dispatch('parecerMensagemSucesso', 'Salvo com sucesso!');
        return response.data;
    }).catch((e) => {
        dispatch('parecerMensagemErro', e.response.data.message);
        throw new TypeError(e.response.data.message, 'salvarAnaliseConteudo', 10);
    });

export const obterPlanilhaParaAnalise = ({ commit, dispatch }, params) => {
    commit(types.SET_PLANILHA_PARECER, []);
    parecerHelperAPI.obterPlanilhaParaAnalise(params)
        .then((response) => {
            commit(types.SET_PLANILHA_PARECER, response.data.items);
        }).catch((e) => {
            dispatch('parecerMensagemErro', e.response.data.error.message);
            throw new TypeError(e.response.data.error.message, 'obterPlanilhaParaAnalise', 10);
        });
};

export const obterProdutosSecundarios = ({ commit }, params) => {
    commit(types.SET_PRODUTOS_SECUNDARIOS, []);
    parecerHelperAPI.obterProdutosSecundarios(params)
        .then((response) => {
            commit(types.SET_PRODUTOS_SECUNDARIOS, response.data);
        });
};

export const salvarAvaliacaoItem = async ({ dispatch }, avaliacao) => parecerHelperAPI.salvarAvaliacaoItem(avaliacao)
    .then((response) => {
        dispatch('atualizarVariosItens', response.data.data.items);
        dispatch('parecerMensagemSucesso', response.data.message);
        return response.data;
    }).catch((e) => {
        dispatch('parecerMensagemErro', e.response.data.message);
        throw new TypeError(e.response.data.message, 'salvarAnaliseItem', 10);
    });

export const atualizarVariosItens = ({ commit }, data) => {
    data.forEach((item) => {
        commit(types.UPDATE_ITEM_PLANILHA, item);
    });
};

export const obterAnaliseConteudoSecundario = ({ commit }, params) => {
    commit(types.SET_ANALISE_CONTEUDO_SECUNDARIO, {});
    parecerHelperAPI.obterAnaliseConteudo(params)
        .then((response) => {
            commit(types.SET_ANALISE_CONTEUDO_SECUNDARIO, response.data);
        });
};

export const obterPlanilhaProdutoSecundario = ({ commit }, params) => {
    commit(types.SET_PLANILHA_SECUNDARIO, []);
    parecerHelperAPI.obterPlanilhaParaAnalise(params)
        .then((response) => {
            commit(types.SET_PLANILHA_SECUNDARIO, response.data);
        });
};

export const obterConsolidacao = ({ commit }, params) => {
    parecerHelperAPI.obterAnaliseConsolidacao(params)
        .then((response) => {
            commit(types.SET_CONSOLIDACAO, response.data);
        });
};

export const salvarAnaliseConsolidacao = async ({ commit, dispatch }, avaliacao) => parecerHelperAPI.salvarAnaliseConsolidacao(avaliacao)
    .then((response) => {
        commit(types.SET_CONSOLIDACAO, avaliacao);
        dispatch('parecerMensagemSucesso', 'Salvo com sucesso');
        return response.data;
    }).catch((e) => {
        dispatch('parecerMensagemErro', e.response.data.message);
        throw new TypeError(e.response.data.message, 'salvarAnaliseConsolidacao', 10);
    });

export const restaurarPlanilhaProduto = async ({ dispatch }, avaliacao) => parecerHelperAPI.restaurarPlanilhaProduto(avaliacao)
    .then((response) => {
        dispatch('parecerMensagemSucesso', response.data.message);
        return response.data;
    }).catch((e) => {
        dispatch('parecerMensagemErro', e.response.data.message);
        throw new TypeError(e.response.data.message, 'restaurarPlanilha', 10);
    });

export const finalizarAnalise = async ({ dispatch }, data) => parecerHelperAPI.finalizarAnalise(data)
    .then((response) => {
        dispatch('parecerMensagemSucesso', 'Salvo com sucesso!');
        return response.data;
    }).catch((e) => {
        dispatch('parecerMensagemErro', e.response.data.message);
        throw new TypeError(e.response.data.message, 'finalizarAnalise', 10);
    });
