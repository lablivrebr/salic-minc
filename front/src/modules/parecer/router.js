import AnaliseDeConteudo from './components/AnaliseDeConteudo';
import AnaliseDeCustos from './components/AnaliseDeCustos';
import ProdutosSecundarios from './components/ProdutosSecundarios';
import Consolidacao from './components/Consolidacao';

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
