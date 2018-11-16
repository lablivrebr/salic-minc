import Vue from 'vue';
import Router from 'vue-router';
import PaginaInicial from '@/modules/paginaInicial';
import Pagina404 from '@/components/404';
import RotasFoo from '@/modules/foo/router';

Vue.use(Router);

const DadosProjeto = () => import(/* webpackChunkName: "dados-projeto" */ '@/modules/projeto/visualizar/components/DadosProjeto');
const Projeto = () => import(/* webpackChunkName: "visualizar-projeto" */ '@/modules/projeto/visualizar/Visualizar2');

const baseRoutes = [
    {
        path: '/',
        name: 'Página Inicial',
        component: PaginaInicial,
    },
    {
        path: '/projetos/:idPronac',
        component: Projeto,
        children: [
            {
                path: '',
                name: 'dadosprojeto',
                component: DadosProjeto,
                meta: {
                    title: 'Dados do Projeto',
                },
            },
        ],
    },
    {
        path: '*',
        component: Pagina404,
    },
];

let routes = [];
routes = routes.concat(RotasFoo);
routes = routes.concat(baseRoutes);

export default new Router({
    routes,
});
