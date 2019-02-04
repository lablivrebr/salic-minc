<template>
    <div @keydown.esc="compararPlanilha = false">
        <PaneRs
            v-if="Object.keys(planilha).length > 0"
            :allow-resize="true"
            :size="size"
            units="percents"
            split-to="columns"
        >

            <div
                v-if="compararPlanilha === true"
                slot="firstPane">
                <s-planilha
                    :array-planilha="planilha">
                    <template slot-scope="slotProps">
                        <s-planilha-itens-comparacao :table="slotProps.itens"/>
                    </template>
                </s-planilha>
            </div>

            <div
                slot="secondPane">
                <s-planilha
                    :array-planilha="planilha">
                    <template slot-scope="slotProps">
                        <s-planilha-itens-analise-inicial :table="slotProps.itens"/>
                    </template>
                </s-planilha>
            </div>
        </PaneRs>

        <v-fab-transition>
            <v-tooltip
                v-if="active && Object.keys(planilha).length > 0"
                left>
                <v-btn
                    slot="activator"
                    v-model="compararPlanilha"
                    color="teal"
                    dark
                    fab
                    fixed
                    bottom
                    right
                    @click="compararPlanilha = !compararPlanilha"
                >
                    <v-icon>vertical_split</v-icon>
                    <v-icon>reorder</v-icon>
                </v-btn>
                <span>Comparar planilha</span>
            </v-tooltip>
        </v-fab-transition>
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import SPlanilha from '@/components/Planilha/Planilha';
import SPlanilhaItensAnaliseInicial from './PlanilhaItensAnaliseInicial';
import SPlanilhaItensComparacao from './PlanilhaItensComparacao';
import SCarregando from '@/components/CarregandoVuetify';
import PaneRs from '@/components/resize-panel/ResizeSplitPane';

export default {
    name: 'AnaliseDeCustos',
    components: {
        PaneRs,
        SPlanilhaItensComparacao,
        SPlanilha,
        SPlanilhaItensAnaliseInicial,
        SCarregando,
    },
    data() {
        return {
            compararPlanilha: false,
            size: 50,
        };
    },
    computed: {
        ...mapGetters({
            planilha: 'parecer/getPlanilhaParecer',
            produto: 'parecer/getProduto',
        }),
        active() {
            return this.$route.name === 'analise-de-custos';
        },
    },
    watch: {
        produto(value) {
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
