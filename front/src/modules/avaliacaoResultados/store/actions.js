import * as desencapsularResponse from '@/helpers/actions';
import * as avaliacaoResultadosHelperAPI from '@/helpers/api/AvaliacaoResultados';
import * as types from './types';

export const dadosMenu = ({ commit }) => {
    avaliacaoResultadosHelperAPI.dadosMenu()
        .then((response) => {
            const data = response.data;
            const dadosTabela = data.data;
            commit(types.SET_REGISTROS_TABELA, dadosTabela);
        });
};

export const getDadosEmissaoParecer = ({ commit }, param) => {
    const p = new Promise((resolve) => {
        avaliacaoResultadosHelperAPI.parecerConsolidacao(param)
            .then((response) => {
                const data = response.data.data.items;

                commit(types.GET_PROPONENTE, data.proponente);
                commit(types.GET_PROJETO, data.projeto);
                commit(types.GET_PARECER, data.parecer);
                commit(types.GET_CONSOLIDACAO_PARECER, data.consolidacaoComprovantes);
                commit(types.GET_OBJETO_PARECER, data.objetoParecer);
                resolve();
            }).catch(() => { });
    });
    return p;
};

export const salvarParecer = (_, params) => {
    const p = new Promise((resolve) => {
        avaliacaoResultadosHelperAPI.criarParecer(params)
            .then(() => {
                resolve();
            });
    });

    return p;
};

export const mockAvaliacaDesempenho = ({ commit }) => {
    commit(types.MOCK_AVALIACAO_RESULTADOS);
};

export const obterDestinatarios = ({ commit }) => {
    avaliacaoResultadosHelperAPI.obterDestinatarios()
        .then((response) => {
            const data = response.data;
            const destinatariosEncaminhamento = data.data;
            commit(types.DESTINATARIOS_ENCAMINHAMENTO, destinatariosEncaminhamento.items);
        });
};

export const obterDadosTabelaTecnico = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.obterDadosTabelaTecnico(params)
        .then((response) => {
            const data = response.data;
            const dadosTabela = data.data;
            commit(types.PROJETOS_AVALIACAO_TECNICA, dadosTabela);
        });
};

export const projetosFinalizados = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.obterDadosTabelaTecnico(params)
        .then((response) => {
            const data = response.data;
            const dadosTabela = data.data;
            commit(types.SET_DADOS_PROJETOS_FINALIZADOS, dadosTabela);
        });
};

export const obterHistoricoEncaminhamento = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.obterHistoricoEncaminhamento(params)
        .then((response) => {
            const dadosEncaminhamento = response.data.data;
            commit(types.HISTORICO_ENCAMINHAMENTO, dadosEncaminhamento.items);
        });
};

export const getTipoAvaliacao = ({ commit }, params) => {
    const p = new Promise((resolve) => {
        avaliacaoResultadosHelperAPI.getTipoAvaliacao(params)
            .then((response) => {
                const data = response.data.data.items;

                commit(types.GET_TIPO_AVALIACAO, data);
                resolve();
            }).catch(() => { });
    });
    return p;
};

export const redirectLinkAvaliacaoResultadoTipo = ({ commit }, params) => {
    if (params.percentual === 0) {
        commit(types.LINK_REDIRECIONAMENTO_TIPO_AVALIACAO_RESULTADO, `/prestacao-contas/realizar-prestacao-contas/index/idPronac/${params.idPronac}`);
    } else {
        commit(types.LINK_REDIRECIONAMENTO_TIPO_AVALIACAO_RESULTADO, `/prestacao-contas/prestacao-contas/amostragem/idPronac/${params.idPronac}/tipoAvaliacao/${params.percentual}`);
    }
};

export const planilha = ({ commit }, params) => {
    commit(types.GET_PLANILHA, {});
    avaliacaoResultadosHelperAPI.planilha(params)
        .then((response) => {
            const planilha = response.data;
            commit(types.GET_PLANILHA, planilha);
        });
};

export const projetoAnalise = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.projetoAnalise(params)
        .then((response) => {
            const projetoAnalise = response.data;
            commit(types.GET_PROJETO_ANALISE, projetoAnalise);
        });
};

export const consolidacaoAnalise = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.consolidacaoAnalise(params)
        .then((response) => {
            const consolidacaoAnalise = response.data;
            commit(types.GET_CONSOLIDACAO_ANALISE, consolidacaoAnalise);
        });
};

export const finalizarParecer = (_, params) => {
    avaliacaoResultadosHelperAPI.alterarEstado(params)
        .then(() => {
        });
};

export const encaminharParaTecnico = ({ commit, dispatch }, params) => {
    commit(types.SET_DADOS_PROJETOS_PARA_DISTRIBUIR, {});
    commit(types.PROJETOS_AVALIACAO_TECNICA, {});
    avaliacaoResultadosHelperAPI
        .alterarEstado(params)
        .then(() => {
            dispatch('projetosParaDistribuir');
            dispatch('obterDadosTabelaTecnico', { estadoid: 5 });
        })
    ;
};

export const alterarParecer = ({ commit }, param) => {
    commit(types.SET_PARECER, param);
};

export const obterDadosItemComprovacao = ({ commit }, params) => avaliacaoResultadosHelperAPI
    .obterDadosItemComprovacao(params)
    .then((response) => {
        const itemComprovacao = response.data.data;
        commit(types.GET_DADOS_ITEM_COMPROVACAO, itemComprovacao.items);
    });

export const getLaudoFinal = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.obterLaudoFinal(params)
        .then((response) => {
            const dados = response.data.data;
            commit(types.GET_PARECER_LAUDO_FINAL, dados);
        });
};

export const salvarLaudoFinal = ({ commit }, data) => {
    avaliacaoResultadosHelperAPI.criarParecerLaudoFinal(data)
        .then(() => {
            commit('noticias/SET_DADOS', { ativo: true, color: 'success', text: 'Salvo com sucesso!' }, { root: true });
        });
};

export const finalizarLaudoFinal = ({ commit }, data) => {
    avaliacaoResultadosHelperAPI.alterarEstado(data)
        .then(() => {
            commit('noticias/SET_DADOS', { ativo: true, color: 'success', text: 'Finalizado com sucesso!' }, { root: true });
        });
};

export const enviarDiligencia = (_, data) => {
    avaliacaoResultadosHelperAPI.criarDiligencia(data)
        .then(() => {
        });
};

export const projetosParaDistribuir = ({ commit }) => {
    avaliacaoResultadosHelperAPI.obterProjetosParaDistribuir()
        .then((response) => {
            const data = response.data;
            commit(types.SET_DADOS_PROJETOS_PARA_DISTRIBUIR, data);
        });
};

export const projetosAssinatura = ({ commit }, params) => {
    let type = '';
    switch (params.estado) {
    case 'em_assinatura':
        type = types.SET_DADOS_PROJETOS_EM_ASSINATURA;
        break;
    case 'historico':
        type = types.SET_DADOS_PROJETOS_HISTORICO;
        break;
    case 'assinar':
    default:
        type = types.SET_DADOS_PROJETOS_ASSINAR;
    }

    avaliacaoResultadosHelperAPI.obterProjetosAssinatura(params)
        .then((response) => {
            const data = response.data;
            const dadosTabela = data.data;
            commit(type, dadosTabela);
        });
};

export const obterProjetosLaudoFinal = ({ commit }, param) => {
    avaliacaoResultadosHelperAPI.obterProjetosLaudoFinal(param)
        .then((response) => {
            const data = response.data;
            const dadosTabela = data.data;
            commit(types.SET_DADOS_PROJETOS_LAUDO_FINAL, dadosTabela);
        });
};

export const obterProjetosLaudoAssinar = ({ commit }, param) => {
    avaliacaoResultadosHelperAPI.obterProjetosLaudoFinal(param)
        .then((response) => {
            const dadosTabela = response.data.data;
            commit(types.SET_DADOS_PROJETOS_LAUDO_ASSINAR, dadosTabela);
        });
};

export const obterProjetosLaudoEmAssinatura = ({ commit }, param) => {
    avaliacaoResultadosHelperAPI.obterProjetosLaudoFinal(param)
        .then((response) => {
            const dadosTabela = response.data.data;
            commit(types.SET_DADOS_PROJETOS_LAUDO_EM_ASSINATURA, dadosTabela);
        });
};

export const obterProjetosLaudoFinalizados = ({ commit }, param) => {
    avaliacaoResultadosHelperAPI.obterProjetosLaudoFinal(param)
        .then((response) => {
            const dadosTabela = response.data.data;
            commit(types.SET_DADOS_PROJETOS_LAUDO_FINALIZADOS, dadosTabela);
        });
};

export const projetosRevisao = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.projetosRevisao(params)
        .then((response) => {
            const projetosRevisao = response.data.data;
            commit(types.SYNC_PROJETOS_REVISAO, projetosRevisao);
        });
};

export const buscarDetalhamentoItens = ({ commit }, idPronac) => {
    avaliacaoResultadosHelperAPI.buscarDetalhamentoItens(idPronac)
        .then((response) => {
            const itens = desencapsularResponse.default(response);
            commit(types.SET_ITENS_BUSCA_COMPROVANTES, itens);
        });
};


export const buscarComprovantes = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.buscarComprovantes(params)
        .then((response) => {
            const data = response.data;
            const itens = data.data;
            commit(types.SET_COMPROVANTES, itens);
        });
};

export const devolverProjeto = ({ commit, dispatch }, params) => {
    avaliacaoResultadosHelperAPI.alterarEstado(params)
        .then((response) => {
            const devolverProjeto = response.data;
            commit(types.SET_DADOS_PROJETOS_FINALIZADOS, {});
            commit(types.SET_DEVOLVER_PROJETO, devolverProjeto);
            dispatch('projetosFinalizados', { estadoid: 6 });
        });
};

export const projetosAssinarCoordenador = ({ commit }) => {
    avaliacaoResultadosHelperAPI.projetosPorEstado({ estadoid: 9 })
        .then((response) => {
            const dados = response.data;
            commit(types.SYNC_PROJETOS_ASSINAR_COORDENADOR, dados.data);
        });
};

export const projetosAssinarCoordenadorGeral = ({ commit }) => {
    avaliacaoResultadosHelperAPI.projetosPorEstado({ estadoid: 15 })
        .then((response) => {
            const dados = response.data;
            commit(types.SYNC_PROJETOS_ASSINAR_COORDENADOR_GERAL, dados.data);
        });
};

export const salvarAvaliacaoComprovante = (_, params) =>
    avaliacaoResultadosHelperAPI.salvarAvaliacaoComprovante(params);

export const alterarAvaliacaoComprovante = ({ commit }, params) =>
    commit(types.ALTERAR_DADOS_ITEM_COMPROVACAO, params);

export const alterarPlanilha = ({ commit }, params) =>
    commit(types.ALTERAR_PLANILHA, params);
