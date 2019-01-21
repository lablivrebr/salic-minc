<template>
    <div>
        <div id="planilha-homologada">
            <Planilha
                v-if="Object.keys(planilha).length > 0"
                :array-planilha="planilha">
                <template slot-scope="slotProps">
                    <PlanilhaItensAnaliseTecnica :table="slotProps.itens"/>
                </template>
            </Planilha>
        </div>
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import Planilha from '@/components/Planilha/Planilha';
import PlanilhaItensAnaliseTecnica from './PlanilhaItensAnaliseTecnica';

export default {
    name: 'AnaliseDeCustos',
    components: {
        Planilha,
        PlanilhaItensAnaliseTecnica,
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
            }
        },
    },
    created() {
        if (Object.keys(this.produto).length > 0) {
            console.log('created custos');
            const params = {
                id: this.produto.idProduto,
                idPronac: this.produto.IdPRONAC,
                stPrincipal: this.produto.stPrincipal,
            };
            this.obterPlanilhaParecer(params);
        }
    },
    methods: {
        ...mapActions({
            obterPlanilhaParecer: 'parecer/obterPlanilhaParaAnalise',
        }),
    },
};
</script>
