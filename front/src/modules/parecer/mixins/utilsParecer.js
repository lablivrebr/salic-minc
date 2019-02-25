export default {
    methods: {
        obterUrlHistorico(produto) {
            const url = '/analisarprojetoparecer/historico';
            const idPronac = `idPronac/${produto.IdPRONAC}`;
            const idProduto = `idProduto/${produto.idProduto}`;
            const situacao = `stPrincipal/${produto.stPrincipal}`;
            const params = `${idPronac}/${idProduto}/${situacao}`;
            return `${url}/${params}`;
        },
        obterUrlDeclararImpedimento(produto) {
            const url = '/analisarprojetoparecer/devolver-parecer';
            const idPronac = `idPronac=${produto.IdPRONAC}`;
            const idProduto = `idProduto=${produto.idProduto}`;
            const situacao = `situacao=${produto.situacao}`;
            const idDistribuirParecer = `idD=${produto.idDistribuirParecer}`;
            const params = `${idPronac}&${idProduto}&${situacao}&${idDistribuirParecer}`;
            return `${url}?${params}`;
        },
        obterUrlDiligencia(produto) {
            const url = '/proposta/diligenciar/listardiligenciaanalista';
            const idPronac = `idPronac=${produto.IdPRONAC}`;
            const idProduto = `idProduto=${produto.idProduto}`;
            const situacao = `situacao=${produto.situacao}`;
            const tpDiligencia = 'tpDiligencia=124';
            const params = `${idPronac}&${idProduto}&${situacao}&${tpDiligencia}`;
            return `${url}?${params}`;
        },
        obterConfigDiligencia(produto) {
            let diligencia = {};
            switch (produto.stDiligencia) {
            case 1:
                diligencia = {
                    cor: 'yellow accent-4',
                    corIcone: 'yellow darken-4',
                    texto: `Diligenciado há ${produto.diasEmDiligencia} dia(s)`,
                };
                break;
            case 2:
                diligencia = {
                    cor: 'green lighten-3',
                    corIcone: 'green darken-4',
                    texto: 'Diligencia respondida',
                };
                break;
            case 3:
                diligencia = {
                    cor: 'orange lighten-3',
                    corIcone: 'orange darken-4',
                    texto: 'Diligencia não respondida',
                };
                break;
            default:
                diligencia = {
                    cor: 'grey lighten-3',
                    corIcone: 'blue-grey darken-2',
                    texto: 'Diligenciar proponente',
                };
                break;
            }
            return diligencia;
        },
    },
};
