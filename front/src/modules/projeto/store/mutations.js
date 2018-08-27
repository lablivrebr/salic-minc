import * as types from './types';

export const state = {
    projeto: {},
    proponente: {},
    transferenciaRecursos: [],
};

export const mutations = {
    [types.SET_PROJETO](state, projeto) {
        state.projeto = projeto;
    },
    [types.SET_TRANSFERENCIA_RECURSOS](state, transferenciaRecursos) {
        state.transferenciaRecursos = transferenciaRecursos;
    },
    [types.SET_PROPONENTE](state, proponente) {
        state.proponente = proponente;
    },
};
