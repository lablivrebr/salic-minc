<template>
    <div @keydown.esc="compararPlanilha = false">
        <v-container
            grid-list-md
            text-xs-center
        >
            <v-layout
                row
                wrap
            >
                <v-flex xs6>
                    <v-card
                        class="mx-auto mb-2"
                        max-width="600"
                    >
                        <v-toolbar
                            card
                            dense
                        >
                            <v-toolbar-title>
                                <span class="subheading">
                                    SOLICITADO
                                </span>
                            </v-toolbar-title>
                            <v-spacer />
                        </v-toolbar>

                        <v-card-text>
                            <v-layout
                                justify-space-between
                            >
                                <v-flex text-xs-left>
                                    <span class="subheading font-weight-light mr-1">
                                        R$
                                    </span>
                                    <span
                                        class="display-2 font-weight-light"
                                    >
                                        {{ calculos.totalSolicitado | filtroFormatarParaReal }}
                                    </span>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs6>
                    <v-card
                        class="mx-auto mb-2"
                        max-width="600"
                    >
                        <v-toolbar
                            card
                            dense
                        >
                            <v-toolbar-title>
                                <span class="subheading">
                                    SUGERIDO
                                </span>
                            </v-toolbar-title>
                            <v-spacer />
                        </v-toolbar>

                        <v-card-text>
                            <v-layout
                                justify-space-between
                            >
                                <v-flex text-xs-left>
                                    <span class="subheading font-weight-light mr-1">
                                        R$
                                    </span>
                                    <span
                                        class="display-2 font-weight-light"
                                    >
                                        {{ calculos.totalSugerido | filtroFormatarParaReal }}
                                    </span>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>

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
                    :agrupamentos="agrupamentos"
                    :totais="totaisPlanilha"
                >
                    <template
                        slot="badge"
                        slot-scope="slotProps"
                    >
                        <VChip
                            v-if="slotProps.planilha.VlSolicitado"
                            outline="outline"
                            label="label"
                            color="#565555"
                        >
                            R$ {{ formatarParaReal(slotProps.planilha.VlSolicitado) }}
                        </VChip>
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
                        <s-planilha-itens-analise-inicial :table="slotProps.itens" />
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
                    medium
                >
                    Esconder itens da planilha
                </span>
                <span
                    v-else
                    medium
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
                        vertical_split
                    </v-icon>
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
import MxPlanilha from '@/mixins/planilhas';

const dataDefaults = {
    calculos: {
        totalSolicitado: 0,
        totalSugerido: 0,
        fontes: {},
        produtos: {},
        etapas: {},
    },
};

export default {
    name: 'AnaliseDeCustos',
    components: {
        ResizePanel,
        SPlanilhaItensVisualizarSolicitado,
        SPlanilha,
        SPlanilhaItensAnaliseInicial,
        SCarregando,
    },
    mixins: [MxPlanilha],
    data() {
        return {
            compararPlanilha: false,
            size: 50,
            expandAll: true,
            fab: false,
            calculos: dataDefaults.calculos,
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
        planilha: {
            handler(val) {
                if (Object.keys(val).length > 0) {
                    this.calculos = Object.assign({}, this.$options.data().calculos);
                    this.calcularTotais(val);
                }
            },
            deep: true,
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
        calcularTotais(planilha) {
            if (!planilha) {
                return {};
            }

            planilha.forEach((item) => {
                this.calculos.totalSugerido += item.VlSugeridoParecerista;
                this.calculos.totalSolicitado += item.VlSolicitado;
            });
            return true;
        },
    },
};
</script>
