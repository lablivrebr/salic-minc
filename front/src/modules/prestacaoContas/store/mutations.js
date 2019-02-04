import * as types from './types';

export const state = {
    pagamentosConsolidados: [],
    pagamentosUfMunicipio: [],
    relatoriosTrimestrais: [],
    execucaoReceitaDespesa: [],
    relatorioFisico: [],
};

export const mutations = {
    [types.SET_PAGAMENTOS_CONSOLIDADOS](state, dados) {
        state.pagamentosConsolidados = dados;
    },
    [types.SET_PAGAMENTOS_UF_MUNICIPIO](state, dados) {
        state.pagamentosUfMunicipio = dados;
    },
    [types.SET_RELATORIOS_TRIMESTRAIS](state, dados) {
        state.relatoriosTrimestrais = dados;
    },
    [types.SET_EXECUCAO_RECEITA_DESPESA](state, dados) {
        state.execucaoReceitaDespesa = dados;
    },
    [types.SET_RELATORIO_FISICO](state, dados) {
        state.relatorioFisico = dados;
    },
};
