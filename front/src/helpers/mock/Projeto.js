import projeto from './projeto/projeto.json';
import sidebar from './projeto/sidebar.json';


const fetch = (mockData, time = 0) => {
    const response = {
        data: mockData,
    };
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(response);
        }, time);
    });
};

export const buscarProjetoCompleto = (idPronac) => {
    if (idPronac === 0) {
        return {};
    }
    return fetch(projeto, 1000);
};

/* eslint-disable */
export const buscaProjeto = (idPronac) => {};

export const buscarTransferenciaRecursos = (idPronac, acao) => {};

export const buscaProponente = (idPronac) => {};

export const buscaPlanilhaHomologada = (idPronac) => {};

export const buscaPlanilhaOriginal = (idPronac) => {};

export const buscaPlanilhaReadequada = (idPronac) => {};

export const buscaPlanilhaAutorizada = (idPronac) => {};

export const buscaPlanilhaAdequada = (idPronac) => {};

export const buscarCertidoesNegativas = (idPronac) => {};

export const buscarDocumentosAssinados = (idPronac) => {};

export const buscarDadosComplementares = (idPronac) => {};

export const buscarDocumentosAnexados = (idPronac) => {};

export const buscarLocalRealizacaoDeslocamento = (idPronac) => {};

export const buscarProvidenciaTomada = (idPronac) => {};

export const buscarPlanoDistribuicaoIn2013 = (idPronac) => {};

export const buscarHistoricoEncaminhamento = (idPronac) => {};

export const buscarTramitacaoDocumento = (idPronac) => {};

export const buscarTramitacaoProjeto = (idPronac) => {};

export const buscarUltimaTramitacao = (idPronac) => {};

export const buscarPlanoDistribuicaoIn2017 = (idPreProjeto) => {};

export const buscarDiligenciaProposta = (idPreprojeto, idAvaliacaoProposta) => {};

export const buscarDiligenciaAdequacao = (idPronac, idAvaliarAdequacaoProjeto) => {};

export const buscarDiligenciaProjeto = (idPronac, idDiligencia) => {};

export const buscarDiligencia = (idPronac) => {};

export const obterMenuProjeto = idPronac => fetch(sidebar, 1000);
