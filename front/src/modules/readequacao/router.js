import SaldoAplicacao from './views/SaldoAplicacaoView';
import PainelReadequacoes from './views/PainelReadequacoesView';

export default [
    {
        path: '/readequacao/saldo-aplicacao/:idPronac',
        component: SaldoAplicacao,
        meta: {
            title: 'Saldo de aplicação',
        },
    },
    {
        path: '/readequacao/painel/:idPronac',
        name: 'PainelReadequacoes',
        component: PainelReadequacoes,
        meta: {
            title: 'Painel de Readequações',
        },
    },
];
