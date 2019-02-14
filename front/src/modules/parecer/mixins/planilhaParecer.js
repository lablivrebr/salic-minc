import planilhas from '@/mixins/planilhas';

export default {
    mixins: [planilhas],
    methods: {
        isLinhaAlterada(row) {
            const proponente = [
                row.Unidade,
                row.VlSolicitado,
                row.ocorrenciaprop,
                row.quantidadeprop,
                row.diasprop,
                row.valorUnitarioprop,
                row.stCustoPraticado,
            ];
            const parecerista = [
                row.idUnidade,
                row.VlSugeridoParecerista,
                row.ocorrenciaparc,
                row.quantidadeparc,
                row.diasparc,
                row.valorUnitarioparc,
                row.valorUnitarioparc,
                row.valorUnitarioparc,
                row.stCustoPraticadoParc,
            ];
            return JSON.stringify(proponente) !== JSON.stringify(parecerista);
        },
        obterClasseItem(row, cell = 'stCustoPraticado') {
            return {
                'grey lighten-3 grey--text text--darken-3': row.isDisponivelParaAnalise === false && !this.isLinhaAlterada(row),
                ...this.definirClasseItem(row, cell),
                'light-blue lighten-5': this.isLinhaAlterada(row),
            };
        },
        obterEstiloItem(row) {
            return {
                cursor: row.isDisponivelParaAnalise === false ? 'not-allowed' : 'pointer',
            };
        },
        obterValorSolicitadoTotalParecer(table) {
            let soma = 0;

            Object.entries(table).forEach(([, cell]) => {
                if (cell.VlSolicitado !== undefined) {
                    soma += parseFloat(cell.VlSolicitado);
                }
            });

            return soma;
        },
        obterValorSugeridoTotalParecer(table) {
            let soma = 0;
            Object.entries(table).forEach(([, cell]) => {
                if (cell.valorUnitarioparc !== undefined) {
                    soma += (
                        parseFloat(cell.valorUnitarioparc)
                        * parseInt(cell.ocorrenciaparc, 10)
                        * parseInt(cell.quantidadeparc, 10)
                    );
                }
            });
            return soma;
        },
    },
};
