import Vue from 'vue';
import Router from 'vue-router';
import Captacao from './components/captacao/registro';

Vue.use(Router);

const routes = [
    {
        path: '/captacao-recurso',
        name: 'Captacao',
        component: Captacao,
        meta: {
            title: 'Dados Bancario Captação Recursos',
        },
    },
];

export default new Router({ routes });
