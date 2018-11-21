import * as paginaInicialHelperAPI from '@api-client/PaginaInicial';
import * as types from './types';

export const buscarComunicados = ({ commit }, idPronac) => {
    paginaInicialHelperAPI.buscarComunicados(idPronac)
        .then((response) => {
            const data = response.data.data;
            commit(types.SET_COMUNICADOS, data.items);
        });
};

