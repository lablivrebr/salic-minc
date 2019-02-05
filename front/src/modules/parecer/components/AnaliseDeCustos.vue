<template>
    <div @keydown.esc="compararPlanilha = false">
        <resize-panel
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
                    :array-planilha="planilha"
                    :expand-all="expandAll"
                >
                    <template slot-scope="slotProps">
                        <s-planilha-itens-visualizar-solicitado :table="slotProps.itens"/>
                    </template>
                </s-planilha>
            </div>
            <div
                slot="secondPane">
                <s-planilha
                    :array-planilha="planilha"
                    :expand-all="expandAll"
                >
                    <template slot-scope="slotProps">
                        <s-planilha-itens-analise-inicial :table="slotProps.itens"/>
                    </template>
                </s-planilha>
            </div>
        </resize-panel>
        <s-carregando
            v-else
            :text="'Carregando Planilha'"/>
        <v-speed-dial
            v-if="active && Object.keys(planilha).length > 0"
            v-model="fab"
            bottom
            right
            direction="top"
            open-on-hover
            transition="slide-y-reverse-transition"
            fixed
        >
            <v-btn
                slot="activator"
                v-model="fab"
                color="red darken-2"
                dark
                fab
            >
                <v-icon>add</v-icon>
                <v-icon>close</v-icon>
            </v-btn>
            <v-tooltip
                left>
                <v-btn
                    slot="activator"
                    fab
                    dark
                    small
                    color="green"
                    @click="expandAll = !expandAll"
                >
                    <v-icon v-if="expandAll">grid_off</v-icon>
                    <v-icon v-else>grid_on</v-icon>
                </v-btn>
                <span
                    v-if="expandAll"
                    medium>Esconder itens da planilha</span>
                <span
                    v-else
                    medium>Mostrar itens da planilha</span>
            </v-tooltip>
            <v-tooltip left>
                <v-btn
                    slot="activator"
                    color="teal"
                    dark
                    small
                    fab
                    @click="compararPlanilha = !compararPlanilha"
                >
                    <v-icon medium>vertical_split</v-icon>
                </v-btn>
                <span>Comparar planilha</span>
            </v-tooltip>
        </v-speed-dial>
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import SPlanilha from '@/components/Planilha/Planilha';
import SPlanilhaItensAnaliseInicial from './PlanilhaItensAnaliseInicial';
import SPlanilhaItensVisualizarSolicitado from './PlanilhaItensVisualizarSolicitado';
import SCarregando from '@/components/CarregandoVuetify';
import ResizePanel from '@/components/resize-panel/ResizeSplitPane';

export default {
    name: 'AnaliseDeCustos',
    components: {
        ResizePanel,
        SPlanilhaItensVisualizarSolicitado,
        SPlanilha,
        SPlanilhaItensAnaliseInicial,
        SCarregando,
    },
    data() {
        return {
            compararPlanilha: false,
            size: 50,
            expandAll: true,
            fab: false,
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
