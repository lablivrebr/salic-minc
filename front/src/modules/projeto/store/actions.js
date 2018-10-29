import * as projetoHelperAPI from '@/helpers/api/Projeto';
import { state } from './mutations';
import * as types from './types';

export const buscaProjeto = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaProjeto(idPronac)
        .then((response) => {
            const data = response.data;
            const projeto = data.data;
            commit(types.SET_PROJETO, projeto);
        });
};

export const buscarProjetoCompleto = ({ commit }, idPronac) => {
    projetoHelperAPI.buscarProjetoCompleto(idPronac)
        .then((response) => {
            const data = response.data;
            const projeto = data.data;
            commit(types.SET_PROJETO, projeto);
        });
};

export const buscaProponente = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaProponente(idPronac)
        .then((response) => {
            const data = response.data;
            const proponente = data.data;
            commit(types.SET_PROPONENTE, proponente);
        });
};

export const buscaPlanilhaHomologada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaHomologada(idPronac)
        .then((response) => {
            const data = response.data;
            const planilhaHomologada = data.data;
            commit(types.SET_PLANILHA_HOMOLOGADA, planilhaHomologada);
        });
};

export const buscaPlanilhaOriginal = ({ commit }, idPreProjeto) => {
    projetoHelperAPI.buscaPlanilhaOriginal(idPreProjeto)
        .then((response) => {
            const data = response.data;
            const planilhaOriginal = data.data;
            commit(types.SET_PLANILHA_ORIGINAL, planilhaOriginal);
        });
};

export const buscaPlanilhaReadequada = ({ commit }, idPronac) => {
    projetoHelperAPI.buscaPlanilhaReadequada(idPronac)
        .then((response) => {
            const data = response.data;
            const planilhaReadequada = data.data;
            commit(types.SET_PLANILHA_READEQUADA, planilhaReadequada);
        });
};

export const buscaPlanilhaAutorizada = ({ commit }, idPreProjeto) => {
    projetoHelperAPI.buscaPlanilhaAutorizada(idPreProjeto)
        .then((response) => {
            const data = response.data;
            const planilhaAutorizada = data.data;
            commit(types.SET_PLANILHA_AUTORIZADA, planilhaAutorizada);
        });
};

export const buscaPlanilhaAdequada = ({ commit }, idPreProjeto) => {
    projetoHelperAPI.buscaPlanilhaAdequada(idPreProjeto)
        .then((response) => {
            const data = response.data;
            const planilhaAdequada = data.data;
            commit(types.SET_PLANILHA_ADEQUADA, planilhaAdequada);
        });
};


export const buscarTransferenciaRecursos = ({ commit }, acao) => {
    const projeto = state.projeto;
    const idPronac = projeto.idPronac;
    projetoHelperAPI.buscarTransferenciaRecursos(idPronac, acao)
        .then((response) => {
            const data = response.data;
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

export const buscarDiligenciaProposta = ({ commit }, dados) => {
    projetoHelperAPI.buscarDiligenciaProposta(dados)
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
