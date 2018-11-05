import Vue from 'vue';
import Router from 'vue-router';
import EmitirParecer from './components/ParecerTecnico/EmitirParecer';
import Painel from './components/ParecerTecnico/Painel';
import TipoAvaliacao from './components/TipoAvaliacao';
import Planilha from './components/ParecerTecnico/Planilha';
import VisualizarPlanilha from './components/components/VisualizarPlanilha';
import AnaliseComprovantes from './components/ParecerTecnico/AnaliseComprovantes';
import Diligenciar from './components/ParecerTecnico/Diligenciar';
import EmitirLaudoFinal from './components/LaudoFinal/EmitirLaudoFinal';
import Laudo from './components/LaudoFinal/PainelLaudo';
import AnalisarItem from './components/ParecerTecnico/AnalisarItem';
import VisualizarParecer from './components/LaudoFinal/VisualizarParecer';
import VisualizarLaudo from './components/LaudoFinal/VisualizarLaudo';

Vue.use(Router);

const routes = [
    {
        path: '/emitir-parecer/:id',
        name: 'EmitirEditar',
        component: EmitirParecer,
        meta: {
            title: 'Principal',
        },
    },
    {
        path: '/tipo-avaliacao/:id',
        name: 'tipoAvaliacao',
        component: TipoAvaliacao,
        meta: {
            title: 'Tipo Avaliacao',
        },
    },
    {
        path: '/planilha/:id',
        name: 'AnalisePlanilha',
        component: Planilha,
        meta: {
            title: 'Analise da planilha',
        },
    },
    {
        path: '/visualizar-planilha/:id',
        name: 'VisualizarPlanilha',
        component: VisualizarPlanilha,
        meta: {
            title: 'Visualizar Planilha',
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
        path: '/diligenciar/:id',
        name: 'Diligenciar',
        component: Diligenciar,
        meta: {
            title: 'Dilengiar o proponente',
        },
    },
    {
        path: '/emitir-laudo-final/:id',
        name: 'EmitirLaudoFinal',
        component: EmitirLaudoFinal,
        meta: {
            title: 'Emitir Laudo Final',
        },
    },
    {
        path: '/laudo',
        name: 'Laudo',
        component: Laudo,
        meta: {
            title: 'Avaliação de Resultados: Laudo Final',
        },
    },
    {
        path: '/analisar-item/*',
        name: 'AnalisarItem',
        component: AnalisarItem,
        meta: {
            title: 'Análise de itens',
        },
    },
    {
        path: '/visualizar-parecer/:id',
        name: 'VisualizarParecer',
        component: VisualizarParecer,
        meta: {
            title: 'Visualizar parecer',
        },
    },
    {
        path: '/visualizar-laudo/:id',
        name: 'VisualizarLaudo',
        component: VisualizarLaudo,
        meta: {
            title: 'Visualizar laudo',
        },
    },
    {
        path: '*',
        name: 'Painel',
        component: Painel,
        meta: {
            title: 'Avaliação de Resultados: Parecer Técnico',
        },
    },
];

export default new Router({ routes });
