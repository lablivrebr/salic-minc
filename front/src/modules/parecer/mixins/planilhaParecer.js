import planilhas from '@/mixins/planilhas';

export default {
    mixins: [planilhas],
    methods: {
        isLinhaAlterada(row) {
            const a1 = [
                row.Unidade,
                row.VlSolicitado,
                row.ocorrenciaprop,
                row.quantidadeprop,
                row.diasprop,
                row.valorUnitarioprop,
            ];
            const a2 = [
                row.idUnidade,
                row.VlSugeridoParecerista,
                row.ocorrenciaparc,
                row.quantidadeparc,
                row.diasparc,
                row.valorUnitarioparc,
            ];
            return JSON.stringify(a1) !== JSON.stringify(a2);
        },
        obterClasseItem(row) {
            return {
                'blue lighten-5': this.isLinhaAlterada(row),
                'grey lighten-3 grey--text text--darken-3': row.isDisponivelParaAnalise === false,
                ...this.definirClasseItem(row),
            };
        },
        obterEstiloItem(row) {
            return {
                cursor: row.isDisponivelParaAnalise === false ? 'not-allowed' : 'pointer',
            };
        },
    },
};
