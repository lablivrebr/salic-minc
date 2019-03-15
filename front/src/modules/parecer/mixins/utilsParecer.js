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
    },
};
