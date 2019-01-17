import * as parecerHelperAPI from '@/helpers/api/Parecer';
import * as types from './types';

export const obterProdutosParaAnalise = ({ commit }) => {
    parecerHelperAPI.obterProdutosParaAnalise()
        .then((response) => {
            const { data } = response;
            commit(types.SET_PRODUTOS, data.items);
        });
};

export const obterProdutoParaAnalise = ({ commit }, params) => {
    parecerHelperAPI.obterProdutoParaAnalise(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_PRODUTO, data);
        });
};

export const obterAnaLiseConteudo = ({ commit }, params) => {
    parecerHelperAPI.obterAnaliseConteudo(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_ANALISE_CONTEUDO, data);
        });
};

export const salvarAnaLiseConteudo = async ({ commit }, avaliacao) => {
    const valor = await parecerHelperAPI.salvarAnaliseConteudo(avaliacao)
        .then((response) => {
            commit(types.SET_ANALISE_CONTEUDO, avaliacao);
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'success',
                    text: 'Salvo com sucesso!',
                },
                { root: true });
            return response.data;
        }).catch((e) => {
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'error',
                    text: e.response.data.message,
                },
                { root: true })
            throw new TypeError(e.response.data.message, 'salvarAnaliseConteudo', 10);
        });
    return valor;
};

export const obterPlanilhaParaAnalise = ({ commit }, params) => {
    parecerHelperAPI.obterPlanilhaParaAnalise(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_PLANILHA_PARECER, data);
        });
};
