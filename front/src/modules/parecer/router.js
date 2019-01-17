const ParecerAnalisarView = () => import(/* webpackChunkName: "parecer-analisar-view" */ './views/ParecerAnalisarView');
const ParecerListarView = () => import(/* webpackChunkName: "parecer-listar-view" */ './views/ParecerListarView')
const AnaliseDeConteudo = () => import(/* webpackChunkName: "parecer-analise-conteudo" */ './components/AnaliseDeConteudo');
const AnaliseDeCustos = () => import(/* webpackChunkName: "parecer-analise-custos" */ './components/AnaliseDeCustos');
const ProdutosSecundarios = () => import(/* webpackChunkName: "parecer-produtos-secundarios" */ './components/ProdutosSecundarios');
const Consolidacao = () => import(/* webpackChunkName: "parecer-consolidacao" */ './components/FinalizarAnalise');

export default [
    {
        path: '/parecer/analise-inicial',
        name: 'parecer-listar-view',
        component: ParecerListarView,
        meta: {
            title: 'Produtos para análise',
        },
    },
    {
        path: '/parecer/analise-inicial/analisar/:id/:idPronac',
        component: ParecerAnalisarView,
        meta: {
            title: 'Análise inicial',
        },
        children: [
            {
                path: '',
                name: 'analise-conteudo',
                component: AnaliseDeConteudo,
            },
            {
                path: 'custos',
                name: 'analise-de-custos',
                component: AnaliseDeCustos,
            },
            {
                path: 'secundarios',
                name: 'produtos-secundarios',
                component: ProdutosSecundarios,
            },
            {
                path: 'consolidacao',
                name: 'parecer-consolidacao',
                component: Consolidacao,
            },
        ],
    },
];
