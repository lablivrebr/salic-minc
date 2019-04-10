<template>
    <div>
        <s-analise-de-custos-header :planilha="planilhaParecer" />
        <v-snackbar
            :value="totalItensSelecionados > 0"
            :timeout="0"
            color="cyan darken-2"
        >
            <v-btn
                dark
                flat
                class="ml-0"
                @click="filtrarItensSelecionados = !filtrarItensSelecionados"
            >
                <span v-if="!filtrarItensSelecionados">
                    <v-icon
                        class="mr-2 left"
                    >
                        visibility
                    </v-icon>
                    Visualizar itens selecionados ({{ totalItensSelecionados }})
                </span>
                <span v-else>
                    <v-icon
                        class="mr-2 left"
                    >
                        visibility_off
                    </v-icon>
                    Voltar planilha completa ({{ totalItensSelecionados }})
                </span>
            </v-btn>
        </v-snackbar>
        <v-container
            fluid
            class="pa-0"
        >
            <v-flex
                xs12
                sm6
                class="py-2"
            >
                <v-btn-toggle
                    v-model="toggle_multiple"
                    multiple
                >
                    <v-tooltip bottom>
                        <v-btn
                            slot="activator"
                            flat
                        >
                            <v-icon>calendar_view_day</v-icon>
                        </v-btn>
                        <span>
                            Mostrar planilha completa
                        </span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <v-btn
                            slot="activator"
                            flat
                        >
                            <v-icon>compare</v-icon>
                        </v-btn>
                        <span>
                            Comparar planilhas
                        </span>
                    </v-tooltip>
                    <v-tooltip bottom>
                        <v-btn
                            slot="activator"
                            flat
                        >
                            <v-icon>list</v-icon>
                        </v-btn>
                        <span>
                            Apenas itens
                        </span>
                    </v-tooltip>
                </v-btn-toggle>
            </v-flex>
        </v-container>
        <resize-panel
            v-if="Object.keys(planilha).length > 0"
            :allow-resize="true"
            :size="sizePanel"
            units="percents"
            split-to="columns"
        >
            <div
                v-if="compararPlanilha === true"
                slot="firstPane"
            >
                <s-planilha
                    :array-planilha="planilhaParecer"
                    :expand-all="expandirTudo"
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
                    :array-planilha="planilhaParecer"
                    :expand-all="expandirTudo"
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
            text="Carregando Planilha"
        />
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
            // compararPlanilha: false,
            // mostrarListagem: false,
            toggle_multiple: [0],
            sizePanel: 49.8,
            totalItensSelecionados: 0,
            filtrarItensSelecionados: false,
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
            planilhaParecer: [],
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
        expandirTudo() {
            return this.isOptionActive(0);
        },
        compararPlanilha() {
            return this.isOptionActive(1);
        },
        mostrarListagem() {
            return this.isOptionActive(2);
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
                this.totalItensSelecionados = this.contarItensSelecionados(val);
                this.planilhaParecer = this.filtrarItensSelecionados ? this.planilha.filter(item => item.selecionado) : this.planilha;
            },
            deep: true,
        },
        filtrarItensSelecionados(val) {
            this.planilhaParecer = val ? this.planilha.filter(item => item.selecionado) : this.planilha;
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
        isOptionActive(index) {
            return this.toggle_multiple.includes(index);
        },
        contarItensSelecionados(planilha) {
            if (planilha.length === 0) {
                return 0;
            }

            return planilha.reduce((soma, item) => (item.selecionado ? 1 : 0) + soma, 0);
        },
    },
};
</script>
