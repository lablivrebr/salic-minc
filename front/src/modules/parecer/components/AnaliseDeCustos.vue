<template>
    <div @keydown.esc="compararPlanilha = false">
        <s-analise-de-custos-header />
        <resize-panel
            v-if="Object.keys(planilha).length > 0"
            :allow-resize="true"
            :size="size"
            units="percents"
            split-to="columns"
        >
            <div
                v-if="compararPlanilha === true"
                slot="firstPane"
            >
                <s-planilha
                    :array-planilha="planilha"
                    :expand-all="expandAll"
                    :list-items="mostrarListagem"
                    :agrupamentos="agrupamentos"
                    :totais="totaisPlanilha"
                >
                    <template
                        slot="badge"
                        slot-scope="slotProps"
                    >
                        <v-chip
                            v-if="slotProps.planilha.VlSolicitado"
                            outline="outline"
                            label="label"
                            color="#565555"
                        >
                            R$ {{ formatarParaReal(slotProps.planilha.VlSolicitado) }}
                        </v-chip>
                    </template>
                    <template slot-scope="slotProps">
                        <s-planilha-itens-visualizar-solicitado :table="slotProps.itens" />
                    </template>
                </s-planilha>
            </div>
            <div
                slot="secondPane"
            >
                <s-planilha
                    :array-planilha="planilha"
                    :expand-all="expandAll"
                    :list-items="mostrarListagem"
                    :agrupamentos="agrupamentos"
                    :totais="totaisPlanilha"
                >
                    <template
                        slot="badge"
                        slot-scope="slotProps"
                    >
                        <v-chip
                            outline="outline"
                            label="label"
                            color="#565555"
                        >
                            R$ {{ formatarParaReal(slotProps.planilha.VlSugeridoParecerista) }}
                        </v-chip>
                    </template>
                    <template slot-scope="slotProps">
                        <s-analise-de-custos-planilha-itens :table="slotProps.itens" />
                    </template>
                </s-planilha>
            </div>
        </resize-panel>
        <s-carregando
            v-else
            :text="'Carregando Planilha'"
        />
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
                left
            >
                <v-btn
                    slot="activator"
                    fab
                    dark
                    small
                    color="green"
                    @click="expandAll = !expandAll"
                >
                    <v-icon v-if="expandAll">
                        grid_off
                    </v-icon>
                    <v-icon v-else>
                        grid_on
                    </v-icon>
                </v-btn>
                <span
                    v-if="expandAll"
                >
                    Esconder itens da planilha
                </span>
                <span
                    v-else
                >
                    Mostrar itens da planilha
                </span>
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
                    <v-icon medium>
                        compare
                    </v-icon>
                </v-btn>
                <span>Comparar planilha</span>
            </v-tooltip>
            <v-tooltip left>
                <v-btn
                    slot="activator"
                    color="teal"
                    dark
                    small
                    fab
                    @click="mostrarListagem = !mostrarListagem"
                >
                    <v-icon
                        v-if="!mostrarListagem"
                        medium>
                        list
                    </v-icon >
                    <v-icon
                        v-else
                        medium>
                        calendar_view_day
                    </v-icon>
                </v-btn>
                <span
                    v-if="!mostrarListagem"
                >
                    Mostrar apenas lista de itens
                </span>
                <span
                    v-else
                >
                    Mostrar planilha completa
                </span>
            </v-tooltip>
        </v-speed-dial>
    </div>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';
import SPlanilha from '@/components/Planilha/Planilha';
import SAnaliseDeCustosPlanilhaItens from './AnaliseDeCustosPlanilhaItens';
import SPlanilhaItensVisualizarSolicitado from './PlanilhaItensVisualizarSolicitado';
import SCarregando from '@/components/CarregandoVuetify';
import ResizePanel from '@/components/resize-panel/ResizeSplitPane';
import MxPlanilha from '@/mixins/planilhas';
import SAnaliseDeCustosHeader from '@/modules/parecer/components/AnaliseDeCustosHeader';

export default {
    name: 'AnaliseDeCustos',
    components: {
        SAnaliseDeCustosHeader,
        ResizePanel,
        SPlanilhaItensVisualizarSolicitado,
        SPlanilha,
        SAnaliseDeCustosPlanilhaItens,
        SCarregando,
    },
    mixins: [MxPlanilha],
    data() {
        return {
            compararPlanilha: false,
            mostrarListagem: false,
            size: 49.8,
            expandAll: true,
            fab: false,
            show: false,
            totaisPlanilha: [
                {
                    label: 'Valor Sugerido',
                    column: 'VlSugeridoParecerista',
                },
                {
                    label: 'Valor Solicitado',
                    column: 'VlSolicitado',
                },
            ],
            agrupamentos: ['FonteRecurso', 'Produto', 'Etapa', 'UF', 'Cidade'],
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
