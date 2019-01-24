<template>
    <div>
        <s-planilha
            v-if="Object.keys(planilha).length > 0"
            :array-planilha="planilha">
            <template slot-scope="slotProps">
                <s-planilha-itens-analise-tecnica :table="slotProps.itens"/>
            </template>
        </s-planilha>
        <s-carregando
            v-else
            :text="'Carregando Planilha'"/>
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import SPlanilha from '@/components/Planilha/Planilha';
import SPlanilhaItensAnaliseTecnica from './PlanilhaItensAnaliseTecnica';
import SCarregando from '@/components/CarregandoVuetify';

export default {
    name: 'AnaliseDeCustos',
    components: {
        SPlanilha,
        SPlanilhaItensAnaliseTecnica,
        SCarregando,
    },
    props: {
        active: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters({
            planilha: 'parecer/getPlanilhaParecer',
            produto: 'parecer/getProduto',
        }),
    },
    watch: {
        produto(value) {
            console.log('watch produto custos');
            if (Object.keys(value).length > 0) {
                const params = {
                    id: value.idProduto,
                    idPronac: value.IdPRONAC,
                    stPrincipal: value.stPrincipal,

                };
                this.obterPlanilhaParecer(params);
                this.obterUnidades();
            }
        },
    },
    created() {
        if (Object.keys(this.produto).length > 0) {
            const params = {
                id: this.produto.idProduto,
                idPronac: this.produto.IdPRONAC,
                stPrincipal: this.produto.stPrincipal,
            };
            this.obterPlanilhaParecer(params);
            this.obterUnidades();
        }
    },
    methods: {
        ...mapActions({
            obterPlanilhaParecer: 'parecer/obterPlanilhaParaAnalise',
            obterUnidades: 'planilha/obterUnidadesPlanilha',
        }),
    },
};
</script>
