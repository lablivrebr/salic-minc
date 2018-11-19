import * as helperAPI from '@/helpers/api/Autenticacao';
import * as types from './types';

export const usuarioLogado = ({ commit }) => {
    helperAPI.usuarioLogado()
        .then((response) => {
            const data = response.data;
            commit(types.SET_USUARIO_LOGADO, data.data.items);
        });
};
