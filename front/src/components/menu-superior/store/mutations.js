import * as types from './types';

export const state = {
    perfisDisponiveis: [],
    usuarioAtivo: {},
    grupoAtivo: {},
    grupoSelecionadoIndex: 0,
};

export const mutations = {
    [types.SET_PERFIS_DISPONIVEIS](state, dados) {
        state.perfisDisponiveis = dados;
    },
    [types.SET_USUARIO_ATIVO](state, dados) {
        state.usuarioAtivo = dados;
    },
    [types.SET_GRUPO_ATIVO](state, dados) {
        state.grupoAtivo = dados;
    },
    [types.SET_GRUPO_SELECIONADO_INDEX](state, dados) {
        state.grupoSelecionadoIndex = dados;
    },
};
