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
