import * as projetoHelperAPI from '@/helpers/api/Projeto';
import { state } from './mutations';
import * as types from './types';

export const buscaProjeto = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaProjeto(idPronac)
        .then((response) => {
            const { data } = response;
            const projeto = data.data;
            commit(types.SET_PROJETO, projeto);
        });
};

export const buscarProjetoCompleto = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarProjetoCompleto(idPronac)
        .then((response) => {
            const { data } = response;
            const projeto = data.data;
            commit(types.SET_PROJETO, projeto);
        });
};

export const buscaProponente = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaProponente(idPronac)
        .then((response) => {
            const { data } = response;
            const proponente = data.data;
            commit(types.SET_PROPONENTE, proponente);
        });
};

export const buscaPlanilhaHomologada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaHomologada(idPronac)
        .then((response) => {
            const { data } = response;
            const planilhaHomologada = data.data;
            commit(types.SET_PLANILHA_HOMOLOGADA, planilhaHomologada);
        });
};

export const buscaPlanilhaOriginal = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaOriginal(idPronac)
        .then((response) => {
            const { data } = response;
            const planilhaOriginal = data.data;
            commit(types.SET_PLANILHA_ORIGINAL, planilhaOriginal);
        });
};

export const buscaPlanilhaReadequada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaReadequada(idPronac)
        .then((response) => {
            const { data } = response;
            const planilhaReadequada = data.data;
            commit(types.SET_PLANILHA_READEQUADA, planilhaReadequada);
        });
};

export const buscaPlanilhaAutorizada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaAutorizada(idPronac)
        .then((response) => {
            const { data } = response;
            const planilhaAutorizada = data.data;
            commit(types.SET_PLANILHA_AUTORIZADA, planilhaAutorizada);
        });
};

export const buscaPlanilhaAdequada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaAdequada(idPronac)
        .then((response) => {
            const { data } = response;
            const planilhaAdequada = data.data;
            commit(types.SET_PLANILHA_ADEQUADA, planilhaAdequada);
        });
};


export const buscarTransferenciaRecursos = ({ commit }, acao) => {
    const { projeto } = state;
    const { idPronac } = projeto;
    projetoHelperAPI.buscarTransferenciaRecursos(idPronac, acao)
        .then((response) => {
            const { data } = response;
            const transferenciaRecursos = data.data;
            commit(types.SET_TRANSFERENCIA_RECURSOS, transferenciaRecursos);
        });
};

export const buscarCertidoesNegativas = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarCertidoesNegativas(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_CERTIDOES_NEGATIVAS, data);
        });
};

export const buscarDocumentosAssinados = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarDocumentosAssinados(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DOCUMENTOS_ASSINADOS, data);
        });
};

export const buscarDadosComplementares = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarDadosComplementares(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DADOS_COMPLEMENTARES, data);
        });
};

export const buscarDocumentosAnexados = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarDocumentosAnexados(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DOCUMENTOS_ANEXADOS, data);
        });
};
export const buscarLocalRealizacaoDeslocamento = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarLocalRealizacaoDeslocamento(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_LOCAL_REALIZACAO_DESLOCAMENTO, data);
        });
};

export const buscarProvidenciaTomada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarProvidenciaTomada(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_PROVIDENCIA_TOMADA, data);
        });
};

export const buscarPlanoDistribuicaoIn2013 = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarPlanoDistribuicaoIn2013(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_PLANO_DISTRIBUICAO_IN2013, data);
        });
};

export const buscarHistoricoEncaminhamento = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarHistoricoEncaminhamento(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_HISTORICO_ENCAMINHAMENTO, data);
        });
};

export const buscarTramitacaoDocumento = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarTramitacaoDocumento(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_TRAMITACAO_DOCUMENTO, data);
        });
};

export const buscarTramitacaoProjeto = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarTramitacaoProjeto(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_TRAMITACAO_PROJETO, data);
        });
};

export const buscarUltimaTramitacao = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarUltimaTramitacao(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_ULTIMA_TRAMITACAO, data);
        });
};

export const buscarPlanoDistribuicaoIn2017 = ({ commit }, idPreProjeto) => {
    projetoHelperAPI.buscarPlanoDistribuicaoIn2017(idPreProjeto)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_PLANO_DISTRIBUICAO_IN2017, data);
        });
};

export const buscarDiligenciaProposta = ({ commit }, value) => {
    const { idPreprojeto, valor } = value;
    projetoHelperAPI.buscarDiligenciaProposta(idPreprojeto, valor)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DILIGENCIA_PROPOSTA, data);
        });
};

export const buscarDiligenciaAdequacao = ({ commit }, value) => {
    const { idPronac, valor } = value;
    projetoHelperAPI.buscarDiligenciaAdequacao(idPronac, valor)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DILIGENCIA_ADEQUACAO, data);
        });
};

export const buscarDiligenciaProjeto = ({ commit }, value) => {
    const { idPronac, valor } = value;
    projetoHelperAPI.buscarDiligenciaProjeto(idPronac, valor)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DILIGENCIA_PROJETO, data);
        });
};

export const buscarDiligencia = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarDiligencia(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DILIGENCIA, data);
        });
};

export const buscarMarcasAnexadas = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarMarcasAnexadas(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_MARCAS_ANEXADAS, data);
        });
};

export const buscarDadosReadequacoes = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarDadosReadequacoes(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DADOS_READEQUACOES, data);
        });
};

export const buscarPedidoProrrogacao = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarPedidoProrrogacao(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_PEDIDO_PRORROGACAO, data);
        });
};

export const buscarDadosFiscalizacaoLista = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarDadosFiscalizacaoLista(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DADOS_FISCALIZACAO_LISTA, data);
        });
};

export const buscarDadosFiscalizacaoVisualiza = ({ commit }, value) => {
    const { idPronac, idFiscalizacao } = value;
    projetoHelperAPI.buscarDadosFiscalizacaoVisualiza(idPronac, idFiscalizacao)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DADOS_FISCALIZACAO_VISUALIZA, data);
        });
};

export const buscarContasBancarias = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarContasBancarias(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_CONTAS_BANCARIAS, data);
        });
};

export const buscarConciliacaoBancaria = ({ commit }, params) => {
    projetoHelperAPI.buscarConciliacaoBancaria(params)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_CONCILIACAO_BANCARIA, data);
        });
};

export const buscarInconsistenciaBancaria = ({ commit }, params) => {
    projetoHelperAPI.buscarInconsistenciaBancaria(params)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_INCONSISTENCIA_BANCARIA, data);
        });
};

export const buscarLiberacao = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarLiberacao(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_LIBERACAO, data);
        });
};

export const buscarSaldoContas = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarSaldoContas(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_SALDO_CONTAS, data);
        });
};

export const buscarExtratosBancarios = ({ commit }, params) => {
    projetoHelperAPI.buscarExtratosBancarios(params)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_EXTRATOS_BANCARIOS, data);
        });
};

export const buscarExtratosBancariosConsolidado = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarExtratosBancariosConsolidado(idPronac)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_EXTRATOS_BANCARIOS_CONSOLIDADO, data);
        });
};

export const buscarCaptacao = ({ commit }, params) => {
    projetoHelperAPI.buscarCaptacao(params)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_CAPTACAO, data);
        });
};

export const buscarDevolucoesIncentivador = ({ commit }, params) => {
    projetoHelperAPI.buscarDevolucoesIncentivador(params)
        .then((response) => {
            const data = response.data.data.items;
            commit(types.SET_DEVOLUCOES_INCENTIVADOR, data);
        });
};
