import * as helperAPI from '@api-client/Autenticacao';
import * as types from './types';

export const usuarioLogado = ({ commit }) => {
    helperAPI.usuarioLogado()
        .then((response) => {
            const data = response.data;
            console.log('usuario logado', data.data.items);
            commit(types.SET_USUARIO_LOGADO, data.data.items);
        });
};
