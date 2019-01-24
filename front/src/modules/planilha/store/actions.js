import * as types from './types';
import * as planilhaAPI from '@/helpers/api/Planilha';

export const obterUnidadesPlanilha = ({ commit }) => {
    planilhaAPI.obterUnidadesPlanilha()
        .then((response) => {
            const { data } = response;
            commit(types.SET_UNIDADES, data.items);
        });
};
