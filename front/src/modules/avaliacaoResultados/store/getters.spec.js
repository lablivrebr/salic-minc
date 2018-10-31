import * as getters from './getters';

describe('Teste getters - Avaliação de Resultados', () => {
    let state;

    beforeEach(() => {
        state = {
            dadosTabelaTecnico: [],
            projetosParaDistribuir: [],
            getProjetosAssinatura: [],
            getProjetosAssinar: [],
            getProjetosEmAssinatura: [],
            getProjetosHistorico: [],
            getProjetosLaudoFinal: [],
            getProjetosLaudoAssinar: [],
            getProjetosLaudoEmAssinatura: [],
            getProjetosLaudoFinalizados: [],
            dadosHistoricoEncaminhamento: [],
            planilha: [],
            consolidacaoAnalise: [],
            projetoAnalise: [],
        };
    });

    test('obterDadosTabelaTecnico', () => {
        const result = getters.dadosTabelaTecnico(state);
        expect(result).toEqual(state.dadosTabelaTecnico);
    });

    test('getProjetosParaDistribuir', () => {
        const result = getters.getProjetosParaDistribuir(state);
        expect(result).toEqual(state.projetosParaDistribuir);
    });

    test('getProjetosAssinatura', () => {
        const result = getters.getProjetosAssinatura(state);
        expect(result).toEqual(state.getProjetosAssinatura);
    });
    
    test('getProjetosAssinar', () => {
        const result = getters.getProjetosAssinar(state);
        expect(result).toEqual(state.getProjetosAssinar);
    });

    test('getProjetosEmAssinatura', () => {
        const result = getters.getProjetosEmAssinatura(state);
        expect(result).toEqual(state.getProjetosEmAssinatura);
    });

    test('getProjetosHistorico', () => {
        const result = getters.getProjetosHistorico(state);
        expect(result).toEqual(state.getProjetosHistorico);
    });

    test('getProjetosLaudoFinal', () => {
        const result = getters.getProjetosLaudoFinal(state);
        expect(result).toEqual(state.getProjetosLaudoFinal);
    });

    test('getProjetosLaudoAssinar', () => {
        const result = getters.getProjetosLaudoAssinar(state);
        expect(result).toEqual(state.getProjetosLaudoAssinar);
    });

    test('getProjetosLaudoEmAssinatura', () => {
        const result = getters.getProjetosLaudoEmAssinatura(state);
        expect(result).toEqual(state.getProjetosLaudoEmAssinatura);
    });
    
    test('dadosHistoricoEncaminhamento', () => {
        const result = getters.dadosHistoricoEncaminhamento(state);
        expect(result).toEqual(state.dadosHistoricoEncaminhamento);
    });

    test('planilha', () => {
        const result = getters.planilha(state);
        expect(result).toEqual(state.planilha);
    });

    test('consolidacaoAnalise', () => {
        const result = getters.consolidacaoAnalise(state);
        expect(result).toEqual(state.consolidacaoAnalise);
    });

    test('projetoAnalise', () => {
        const result = getters.projetoAnalise(state);
        expect(result).toEqual(state.projetoAnalise);
    });
    
    
});
