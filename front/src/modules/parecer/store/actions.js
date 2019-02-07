import * as parecerHelperAPI from '@/helpers/api/Parecer';
import * as types from './types';
import _ from 'lodash';

export const obterProdutosParaAnalise = ({ commit }) => {
    commit(types.SET_PRODUTOS, []);
    parecerHelperAPI.obterProdutosParaAnalise()
        .then((response) => {
            const { data } = response;
            commit(types.SET_PRODUTOS, data.items);
        });
};

export const obterProdutoParaAnalise = ({ commit }, params) => {
    commit(types.SET_PRODUTO, {});
    parecerHelperAPI.obterProdutoParaAnalise(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_PRODUTO, data);
        });
};

export const obterAnaLiseConteudo = ({ commit }, params) => {
    commit(types.SET_ANALISE_CONTEUDO, {});
    parecerHelperAPI.obterAnaliseConteudo(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_ANALISE_CONTEUDO, data);
        });
};

export const salvarAnaLiseConteudo = async ({ commit }, avaliacao) => {
    const valor = await parecerHelperAPI.salvarAnaliseConteudo(avaliacao)
        .then((response) => {
            commit(types.SET_ANALISE_CONTEUDO, avaliacao);
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'success',
                    text: 'Salvo com sucesso!',
                },
                { root: true });
            return response.data;
        }).catch((e) => {
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'error',
                    text: e.response.data.message,
                },
                { root: true });
            throw new TypeError(e.response.data.message, 'salvarAnaliseConteudo', 10);
        });
    return valor;
};

export const obterPlanilhaParaAnalise = ({ commit }, params) => {
    commit(types.SET_PLANILHA_PARECER, {});
    parecerHelperAPI.obterPlanilhaParaAnalise(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_PLANILHA_PARECER, data.items);
        });
};

export const obterProdutosSecundarios = ({ commit }, params) => {
    commit(types.SET_PRODUTOS_SECUNDARIOS, []);
    parecerHelperAPI.obterProdutosSecundarios(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_PRODUTOS_SECUNDARIOS, data);
        });
};

export const salvarAvaliacaoItem = async ({ commit, dispatch }, avaliacao) => {
    const valor = await parecerHelperAPI.salvarAvaliacaoItem(avaliacao)
        .then((response) => {
            commit(types.UPDATE_ITEM_PLANILHA, avaliacao);
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'success',
                    text: response.data.message,
                },
                { root: true });
            dispatch('atualizarCustosVinculados', response.data.data.custosVinculados);
            dispatch('recalcularTotaisPlanilha');
            return response.data;
        }).catch((e) => {
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'error',
                    text: e.response.data.message,
                },
                { root: true });
            throw new TypeError(e.response.data.message, 'salvarAnaliseItem', 10);
        });
    return valor;
};

export const atualizarCustosVinculados = ({ commit }, data) => {
    data.forEach((item) => {
        commit(types.UPDATE_ITEM_PLANILHA, item);
    });
};

export const recalcularTotaisPlanilha = ({ state, commit }) => {
    // const planilha = obterPlanilhaZerada(state.planilhaParecer);
    // function calcularPlanilhaRecursivo(plan) {
    //     if (typeof plan === 'object' && typeof plan.itens === 'undefined') {
    //         Object.keys(plan).map((key) => {
    //             if (typeof plan[key] === 'object') {
    //                 calcularPlanilhaRecursivo(plan[key]);
    //             }
    //             return true;
    //         });
    //     }
    //
    //     if (plan.itens) {
    //         plan.itens.forEach((item) => {
    //             const fonte = item.FonteRecurso;
    //             const produto = item.idProduto !== 0 ? item.Produto : 'Administração do Projeto';
    //             const etapa = item.Etapa;
    //             const regiao = `${item.UF} - ${item.Cidade}`;
    //
    //             planilha.total += item.VlSolicitado;
    //             planilha.totalSugerido += item.VlSugeridoParecerista;
    //             planilha[fonte].total += item.VlSolicitado;
    //             planilha[fonte].totalSugerido += item.VlSugeridoParecerista;
    //             planilha[fonte][produto].total += item.VlSolicitado;
    //             planilha[fonte][produto].totalSugerido += item.VlSugeridoParecerista;
    //             planilha[fonte][produto][etapa].total += item.VlSolicitado;
    //             planilha[fonte][produto][etapa].totalSugerido += item.VlSugeridoParecerista;
    //             planilha[fonte][produto][etapa][regiao].total += item.VlSolicitado;
    //             planilha[fonte][produto][etapa][regiao].totalSugerido += item.VlSugeridoParecerista;
    //         });
    //     }
    //     return true;
    // }
    // calcularPlanilhaRecursivo(state.planilhaParecer);
    // commit(types.SET_PLANILHA_PARECER, planilha);
    // console.log('planilhaaa', planilha);
};

function obterPlanilhaZerada(novaPlanilha) {
    // const planilha = novaPlanilha;
    // function calcularPlanilhaRecursivo(plan) {
    //     if (typeof plan === 'object' && typeof plan.itens === 'undefined') {
    //         Object.keys(plan).map((key) => {
    //             if (typeof plan[key] === 'object') {
    //                 calcularPlanilhaRecursivo(plan[key]);
    //             }
    //             return true;
    //         });
    //     }
    //
    //     if (plan.itens) {
    //         plan.itens.forEach((item) => {
    //             const fonte = item.FonteRecurso;
    //             const produto = item.idProduto !== 0 ? item.Produto : 'Administração do Projeto';
    //             const etapa = item.Etapa;
    //             const regiao = `${item.UF} - ${item.Cidade}`;
    //
    //             planilha.total = 0;
    //             planilha.totalSugerido = 0;
    //             planilha[fonte].total = 0;
    //             planilha[fonte].totalSugerido = 0;
    //             planilha[fonte][produto].total = 0;
    //             planilha[fonte][produto].totalSugerido = 0;
    //             planilha[fonte][produto][etapa].total = 0;
    //             planilha[fonte][produto][etapa].totalSugerido = 0;
    //             planilha[fonte][produto][etapa][regiao].total = 0;
    //             planilha[fonte][produto][etapa][regiao].totalSugerido = 0;
    //         });
    //     }
    //     return true;
    // }
    // calcularPlanilhaRecursivo(novaPlanilha);
    // return planilha;
}


export const obterAnaliseConteudoSecundario = ({ commit }, params) => {
    parecerHelperAPI.obterAnaliseConteudo(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_ANALISE_CONTEUDO_SECUNDARIO, data);
        });
};

export const obterPlanilhaProdutoSecundario = ({ commit }, params) => {
    parecerHelperAPI.obterPlanilhaParaAnalise(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_PLANILHA_SECUNDARIO, data);
        });
};

export const obterConsolidacao = ({ commit }, params) => {
    parecerHelperAPI.obterAnaliseConsolidacao(params)
        .then((response) => {
            const { data } = response;
            commit(types.SET_CONSOLIDACAO, data);
        });
};

export const salvarAnaliseConsolidacao = async ({ commit }, avaliacao) => {
    const valor = await parecerHelperAPI.salvarAnaliseConsolidacao(avaliacao)
        .then((response) => {
            commit(types.SET_CONSOLIDACAO, avaliacao);
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'success',
                    text: 'Salvo com sucesso!',
                },
                { root: true });
            return response.data;
        }).catch((e) => {
            commit('noticias/SET_DADOS',
                {
                    ativo: true,
                    color: 'error',
                    text: e.response.data.message,
                },
                { root: true });
            throw new TypeError(e.response.data.message, 'salvarAnaliseConsolidacao', 10);
        });
    return valor;
};
