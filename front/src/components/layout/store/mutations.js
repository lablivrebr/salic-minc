import * as types from './types';

export const state = {
    perfisDisponiveis: [],
    solicitacoes: {},
    quantidadeSolicitacoes: 0,
    modoNoturno: false,
    versao: {},
};

export const mutations = {
    [types.SET_PERFIS_DISPONIVEIS](state, dados) {
        state.perfisDisponiveis = dados;
    },
    [types.SET_SOLICITACOES](state, dados) {
        state.solicitacoes = dados;
    },
    [types.SET_QUANTIDADE_SOLICITACOES](state, dados) {
        state.quantidadeSolicitacoes = dados;
    },
    [types.SET_VERSAO](state, dados) {
        state.versao = dados;
    },
    [types.SET_MODO_NOTURNO](state, status) {
        state.modoNoturno = status;
    },
};
