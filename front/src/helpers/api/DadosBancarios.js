import * as api from './base';

export const buscarContasBancarias = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/contas-bancarias-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarConciliacaoBancaria = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/conciliacao-bancaria-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${params.idPronac}&dtPagamentoInicio=${params.dtInicio}&dtPagamentoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarInconsistenciaBancaria = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/inconsistencia-bancaria-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${params.idPronac}&dtPagamentoInicio=${params.dtInicio}&dtPagamentoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarLiberacao = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/liberacao-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarSaldoContas = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/saldo-contas-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarExtratosBancarios = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/extratos-bancarios-rest';
    const path = `${modulo}${controller}`;
    const idPronac = `idPronac=${params.idPronac}`;
    const dtLancamento = `dtLancamento=${params.dtLancamento}`;
    const dtLancamentoFim = `dtLancamentoFim=${params.dtLancamentoFim}`;
    const tpConta = `tpConta=${params.tpConta}`;

    const queryParams = `?${idPronac}&${dtLancamento}&${dtLancamentoFim}&${tpConta}`;
    return api.getRequest(path, queryParams);
};

export const buscarExtratosBancariosConsolidado = (idPronac) => {
    const modulo = '/dados-bancarios';
    const controller = '/extratos-bancarios-consolidado-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${idPronac}`;
    return api.getRequest(path, queryParams);
};

export const buscarCaptacao = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/captacao-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${params.idPronac}&dtReciboInicio=${params.dtInicio}&dtReciboFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};

export const buscarDevolucoesIncentivador = (params) => {
    const modulo = '/dados-bancarios';
    const controller = '/devolucoes-rest';
    const path = `${modulo}${controller}`;
    const queryParams = `?idPronac=${params.idPronac}&dtDevolucaoInicio=${params.dtInicio}&dtDevolucaoFim=${params.dtFim}`;
    return api.getRequest(path, queryParams);
};
