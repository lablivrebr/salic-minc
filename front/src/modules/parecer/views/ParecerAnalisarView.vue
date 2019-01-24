<template>
    <v-container
        fluid>
        <v-toolbar>
            <v-btn
                icon
                class="hidden-xs-only"
                @click="back()"
            >
                <v-icon>arrow_back</v-icon>
            </v-btn>
            <v-toolbar-title>
                Análise inicial - Produto:
                {{ produto.dsProduto }}
            </v-toolbar-title>
            <v-spacer/>
            <v-chip
                v-if="produto.stPrincipal === 1"
                color="teal lighten-5">
                Produto Principal
            </v-chip>
            <v-chip
                v-else
                color="blue-grey lighten-5">
                Produto Secundário
            </v-chip>
            <v-btn icon>
                <v-icon>more_vert</v-icon>
            </v-btn>
        </v-toolbar>
        <v-stepper v-model="currentStep">
            <v-stepper-header>
                <template v-for="step in arraySteps">
                    <v-stepper-step
                        :complete="currentStep > step.id"
                        :key="`${step.path}-step`"
                        :step="step.id"
                        editable
                    >{{ step.name }}</v-stepper-step>
                    <v-divider
                        v-if="step.id !== Object.keys(arraySteps).length"
                        :key="step.id"
                    />
                </template>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content
                    v-for="step in arraySteps"
                    :key="`${step.path}-content`"
                    :step="step.id"
                >
                    <v-card
                        class="mb-5"
                    >
                        <keep-alive>
                            <router-view
                                v-if="step.id === currentStep"
                                :is-active="true"
                                class="view"
                            />
                        </keep-alive>
                    </v-card>
                    <v-btn
                        color="primary"
                        @click="nextStep(step.id)"
                    >
                        Continue
                    </v-btn>

                    <v-btn flat>Cancel</v-btn>
                </v-stepper-content>

                <v-fab-transition>
                    <v-btn
                        v-if="Object.keys(activeFab).length > 0"
                        :color="activeFab.color"
                        :key="activeFab.icon"
                        v-model="fab"
                        dark
                        fab
                        fixed
                        bottom
                        right
                    >
                        <v-icon>{{ activeFab.icon }}</v-icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-fab-transition>
            </v-stepper-items>
        </v-stepper>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'ParecerAnalisarView',
    data: () => ({
        currentStep: 1,
        arraySteps: [
            {
                id: 1,
                name: 'Análise de conteúdo',
                hidden: false,
                component: 'AnaliseDeConteudo',
                path: 'analise-conteudo',
            },
            {
                id: 2,
                name: 'Análise de custos',
                hidden: false,
                component: 'AnaliseDeCustos',
                path: 'analise-de-custos',
            },
            {
                id: 3,
                name: 'Dados dos Produtos Secundários',
                hidden: false,
                component: 'ProdutosSecundarios',
                path: 'produtos-secundarios',
            },
            {
                id: 4,
                name: 'Finalizar análise',
                hidden: false,
                component: 'FinalizarAnalise',
                path: 'parecer-consolidacao',
            },
        ],
        fab: false,
        hidden: false,
        tabs: null,
    }),
    computed: {
        ...mapGetters({
            produto: 'parecer/getProduto',
        }),
        activeFab() {
            switch (this.currentStep) {
            // case 1: return { color: 'indigo', icon: 'share' };
            case 2: return { color: 'red', icon: 'edit' };
            case 3: return { color: 'green', icon: 'keyboard_arrow_up' };
            default: return {};
            }
        },
    },
    watch: {
        currentStep(val, old) {
            console.log('watch', val, old);
            this.$router.push({ name: this.getStepById(val).path });
        },
    },
    created() {
        this.obterProdutoParaAnalise({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
        this.currentStep = this.arraySteps.find(element => element.path === this.$route.name).id;
    },
    methods: {
        ...mapActions({
            obterProdutoParaAnalise: 'parecer/obterProdutoParaAnalise',
        }),
        onInput(val) {
            this.steps = parseInt(val, 10);
        },
        nextStep(n) {
            this.currentStep = (n === this.steps) ? 1 : n + 1;
        },
        back() {
            this.$router.push({ name: 'parecer-listar-view' });
        },
        getStepById(id) {
            return this.arraySteps.find(element => element.id === id);
        },
    },
};
</script>
