import * as types from './types';
import * as planilhaAPI from '@/helpers/api/Planilha';

export const obterUnidadesPlanilha = ({ commit }) => {
    planilhaAPI.obterUnidadesPlanilha()
        .then((response) => {
            const { data } = response;
            commit(types.SET_UNIDADES, data.items);
        });
};

export const obterMediana = ({ commit }, params) => {
    commit(types.SET_MEDIANA, {});
    planilhaAPI.obterMediana(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_MEDIANA, data.data);
        });
};
