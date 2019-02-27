import * as types from './types';

export const state = {
    readequacoesProponente: {},
    readequacoesAnalise: {},
    readequacoesFinalizadas: {},
    readequacao: {},
    saldoAplicacao: {},
    saldoAplicacaoDisponivelEdicaoItem: {},
    campoAtual: {},
};

export const mutations = {
    [types.GET_READEQUACOES_PROPONENTE](state, readequacoes) {
        state.readequacoesProponente = readequacoes;
    },
    [types.GET_READEQUACOES_ANALISE](state, readequacoes) {
        state.readequacoesAnalise = readequacoes;
    },
    [types.GET_READEQUACOES_FINALIZADAS](state, readequacoes) {
        state.readequacoesFinalizadas = readequacoes;
    },
    [types.SET_READEQUACAO](state, readequacao) {
        state.readequacao = readequacao;
    },
    [types.GET_READEQUACAO](state, readequacao) {
        state.readequacao = readequacao;
    },
    [types.GET_CAMPO_ATUAL](state, campoAtual) {
	state.campoAtual = campoAtual;
    },
    [types.UPDATE_READEQUACAO](state, readequacao) {
        state.readequacao = readequacao;
    },
    [types.EXCLUIR_READEQUACAO](state) {
        state.readequacao = {};
    },
    [types.ADICIONAR_DOCUMENTO](state, data) {
        const idDocumento = data.idDocumento;
        const nomeArquivo = data.nomeArquivo;
        state.readequacao.idDocumento = idDocumento;
        state.readequacao.nomeArquivo = nomeArquivo;
    },
    [types.EXCLUIR_DOCUMENTO](state) {
        state.readequacao.idDocumento = '';
        state.readequacao.nomeArquivo = '';
    },
    [types.UPDATE_READEQUACAO_DS_SOLICITACAO](state, dsSolicitacao) {
        state.readequacao.items.dsSolicitacao = dsSolicitacao;
    },    
    [types.UPDATE_READEQUACAO_SALDO_APLICACAO_DS_SOLICITACAO](state, dsSolicitacao) {
        state.readequacao.saldoAplicacaoDsSolicitacao = dsSolicitacao;
    },
    [types.OBTER_DISPONIVEL_EDICAO_ITEM_SALDO_APLICACAO](state, disponivel) {
        state.readequacao.saldoAplicacaoDisponivelEdicaoItem = disponivel;
    },
};
