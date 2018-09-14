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

export const criarRegistro = ({ commit }, params) => {
    fooHelperAPI.criarRegistro(params)
        .then((response) => {
            const data = response.data;
            const registro = data.data;
            commit(types.SET_REGISTRO_TABELA, registro);
        });
};

export const setRegistroAtivo = ({ commit }, registro) => {
    commit(types.SET_REGISTRO_ATIVO, registro);
};

export const removerRegistro = ({ commit }, registro) => {
    avaliacaoResultadosHelperAPI.removerRegistro(registro)
        .then(() => {
            commit(types.REMOVER_REGISTRO, registro);
        });
};

export const getIndex = ({ commit }) => { };

export const getDadosEmissaoParecer = ({ commit }, param) => {
    return new Promise((resolve) => {
        avaliacaoResultadosHelperAPI.parecerConsolidacao(param)
            .then((response) => {

                const data = response.data.data.items;

                commit(types.GET_PROPONENTE, data.proponente);
                commit(types.GET_PROJETO, data.projeto);
                commit(types.GET_PARECER, data.parecer);
                commit(types.GET_CONSOLIDACAO_PARECER, data.consolidacaoComprovantes);
                resolve();
            }).catch(error => console.info(error));
    });
};

export const salvarParecer = ({ commit }, params) => {

    return new Promise((resolve) => {
        avaliacaoResultadosHelperAPI.criarParecer(params)
            .then( (response) => {
                console.info(response);
                resolve();
            })
    });

};

export const mockAvaliacaDesempenho = ({ commit }) => {
    commit(types.MOCK_AVALIACAO_RESULTADOS, Mock);
};

export const getDestinatariosEncaminhamento = ({ commit }, params) => {
   // var  params = {
   //      "idorgao" : 303,
   //      "idPerfilDestino" : 125,
   //      "verifica" : "a",
   //  };

    avaliacaoResultadosHelperAPI.getTeste(params)
        .then((response) => {
           // const data = response.data;
           // const dadosTabela = data.data;
            commit(types.DESTINATARIOS_ENCAMINHAMENTO, response.data);
        });
    // console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
    // console.log(params);
    // avaliacaoResultadosHelperAPI.buscarDestinatariosParaEncaminhamento(param)
    //     .then((destinatarios) => {
    //         console.log(destinatarios)
    //         commit(types.DESTINATARIOS_ENCAMINHAMENTO, destinatarios);
    //     });
};

export const obterDadosTabelaTecnico = ({ commit }) => {
    avaliacaoResultadosHelperAPI.obterDadosTabelaTecnico()
        .then((response) => {
            const data = response.data;
            const dadosTabela = data.data;
            commit(types.PROJETOS_AVALIACAO_TECNICA, dadosTabela);
        });
};

export const obterHistoricoEncaminhamento = ({ commit }, params) => {
    avaliacaoResultadosHelperAPI.obterHistoricoEncaminhamento(params)
        .then((response) => {
            const dadosEncaminhamento = response.data.data;
            console.log(dadosEncaminhamento );
            commit(types.HISTORICO_ENCAMINHAMENTO, dadosEncaminhamento.items);
        });
};