import Vue from 'vue';
import Vuex from 'vuex';

import modal from '@/components/modal/store';
import layout from '@/components/layout/store';
import projeto from './modules/projeto/store';
import foo from './modules/foo/store';
import avaliacaoResultados from './modules/avaliacaoResultados/store';
import proposta from './modules/proposta/store';
import autenticacao from './modules/autenticacao/store';
import noticias from './modules/noticias/store';
import dateFilter from './filters/date';

Vue.use(Vuex);
Vue.filter('date', dateFilter);

const debug = process.env.NODE_ENV !== 'production' || process.env.NODE_ENV !== 'staging';

export default new Vuex.Store({
    modules: {
        projeto,
        foo,
        modal,
        layout,
        avaliacaoResultados,
        proposta,
        autenticacao,
        noticias,
    },
    getters: {
        route: state => state.route,
    },
    strict: debug,
});
