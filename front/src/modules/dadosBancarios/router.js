import Vue from 'vue';
import Router from 'vue-router';
import Registrar from './components/captacao/Registrar';
import TabelaVizualizacao from './components/captacao/TabelaVizualizacao';
import Vizualizar from './components/captacao/Vizualizar';

Vue.use(Router);

const routes = [
    {
        path: '/captacao-recurso',
        name: 'TabelaVizualizacao',
        component: TabelaVizualizacao,
        meta: {
            title: 'Dados Bancario Captação Recursos',
        },
    },
    {
        path: '/captacao-recurso/registrar',
        name: 'Registrar',
        component: Registrar,
    },
    {
        path: '/captacao-recurso/vizualizar',
        name: 'Vizualizar',
        component: Vizualizar,
    },
];

export default new Router({ routes });
