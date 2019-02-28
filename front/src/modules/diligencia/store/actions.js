import * as types from './types';
import * as diligenciaAPI from '@/helpers/api/Diligencia';

export const obterDiligencias = ({ commit }, params) => {
    diligenciaAPI.obterDiligencias(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_DILIGENCIAS, data.items);
        });
};

export const obterDiligenciasProduto = ({ commit }, params) => {
    diligenciaAPI.obterDiligenciasProduto(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_DILIGENCIAS, data.items);
        });
};

export const obterDiligencia = ({ commit }, params) => {
    diligenciaAPI.obterDiligencia(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_DILIGENCIA, data);
        });
};
