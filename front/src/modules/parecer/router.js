const ParecerAnalisarView = () => import(/* webpackChunkName: "parecer-analisar-view" */ './views/ParecerAnalisarView');
const ParecerListarView = () => import(/* webpackChunkName: "parecer-listar-view" */ './views/ParecerListarView')

export default [
    {
        path: '/parecer/analise-inicial',
        name: 'parecer-listar-view',
        component: ParecerListarView,
        meta: {
            title: 'Proponente',
        },
    },
    {
        path: '/parecer/analise-inicial/analisar/:id/:idPronac',
        component: ParecerAnalisarView,
        name: 'parecer-analisar-view',
        // children: [
        //     {
        //         path: '',
        //         name: 'dadosprojeto',
        //         component: DadosProjeto,
        //         meta: {
        //             title: 'Dados do Projeto',
        //         },
        //     },
        // ],
    },
];
