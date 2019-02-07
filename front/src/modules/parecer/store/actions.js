import * as parecerHelperAPI from '@/helpers/api/Parecer';
import * as types from './types';

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
            commit(types.SET_PLANILHA_PARECER, data);
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
    // data.forEach((item) => {
    //     commit(types.UPDATE_ITEM_PLANILHA, item);
    // });
    function calcularPlanilhaRecursivo(plan) {
        console.log('teste');
        if (typeof plan === 'object' && typeof plan.itens === 'undefined') {
            console.log('teste2', plan);
            Object.keys(plan).map((key) => {
                console.log('teste3');
                if (typeof plan[key] === 'object') {
                    console.log('chamar novamente2', plan[key]);
                    calcularPlanilhaRecursivo(plan[key]);
                }
                return true;
            });
        }

        if (plan.itens) {
            console.log('itenss');
            plan.itens.forEach((item) => {
                console.log('chamandooooo', item);
            });
        }
        return true;
    }
    calcularPlanilhaRecursivo(state.planilhaParecer);
};


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
