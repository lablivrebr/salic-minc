import Vue from 'vue';
import Router from 'vue-router';
import ComponenteEncaminhar from './components/ComponenteEncaminhar';
import EmitirParecer from './components/EmitirParecer';
import Painel from './components/Painel';
import TipoAvaliacao from './components/TipoAvaliacao';
import Planilha from './components/Planilha';
import AnaliseComprovantes from './components/AnaliseComprovantes';
import Diligenciar from './components/Diligenciar';
import Encaminhar from './components/Encaminhar';
import Historico from './components/Historico';

Vue.use(Router);

const routes = [
    {
        path: '/componente-encaminhar',
        name: 'ListBar',
        component: ComponenteEncaminhar,
        meta: {
            title: 'Principal',
        },
    },
    {
        path: '/emitir-parecer/:id',
        name: 'EmitirEditar',
        component: EmitirParecer,
        meta: {
            title: 'Principal',
        },
    },
    {
        path: '/emitir-parecer',
        name: 'Emitir',
        component: EmitirParecer,
        meta: {
            title: 'Principal',
        },
    },
    {
        path: '/tipo-avaliacao',
        name: 'Tipo Avaliacao',
        component: TipoAvaliacao,
        meta: {
            title: 'Tipo Avaliacao',
        },
    },
    {
        path: '/planilha',
        name: 'Analise Planilha',
        component: Planilha,
        meta: {
            title: 'Analise da planilha',
        },
    },
    {
        path: '/analise-comprovantes',
        name: 'Analise',
        component: AnaliseComprovantes,
        meta: {
            title: 'Analise dos comprovantes',
        },
    },
    {
        path: '/diligenciar',
        name: 'Diligenciar',
        component: Diligenciar,
        meta: {
            title: 'Dilengiar o proponente',
        },
    },
    {
        path: '/encaminhar',
        name: 'Encaminhar',
        component: Encaminhar,
        meta: {
            title: 'Encaminhar',
        },
    },
    {
        path: '/historico',
        name: 'Diligenciar',
        component: Historico,
        meta: {
            title: 'Historico dos encaminhamentos',
        },
    },
    {
        path: '*',
        name: 'Painel',
        component: Painel,
        meta: {
            title: 'Painel',
        },
    },
];

export default new Router({ routes });
