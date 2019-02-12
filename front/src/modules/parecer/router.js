import AnaliseDeConteudo from './components/AnaliseDeConteudo';
import AnaliseDeCustos from './components/AnaliseDeCustos';
import AnaliseOutrosProdutos from './components/AnaliseOutrosProdutos';
import AnaliseConsolidacao from './components/AnaliseConsolidacao';
import AnaliseFinalizacao from './components/AnaliseFinalizacao';

const ParecerAnalisarView = () => import(/* webpackChunkName: "parecer-analisar-view" */ './views/ParecerAnalisarView');
const ParecerListarView = () => import(/* webpackChunkName: "parecer-listar-view" */ './views/ParecerListarView');

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
        path: '/parecer/analise-inicial/analisar/:id/:idPronac/:produtoPrincipal',
        component: ParecerAnalisarView,
        children: [
            {
                path: '',
                name: 'analise-conteudo',
                component: AnaliseDeConteudo,
                meta: {
                    title: 'Análise inicial',
                },
            },
            {
                path: 'custos',
                name: 'analise-de-custos',
                component: AnaliseDeCustos,
            },
            {
                path: 'outros-produtos',
                name: 'analise-outros-produtos',
                component: AnaliseOutrosProdutos,
            },
            {
                path: 'consolidacao',
                name: 'analise-consolidacao',
                component: AnaliseConsolidacao,
            },
            {
                path: 'finalizacao',
                name: 'analise-finalizacao',
                component: AnaliseFinalizacao,
            },
        ],
    },
];
