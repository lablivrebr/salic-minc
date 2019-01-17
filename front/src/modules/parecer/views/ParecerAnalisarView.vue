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
                {{ $route.meta.title }} - Produto:
                {{ produto.dsProduto }}
            </v-toolbar-title>
            <v-spacer/>
            <v-chip
                v-if="produto.stPrincipal === 1"
                color="teal"
                text-color="white">
                Produto Principal
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
                        :key="`${step.id}-step`"
                        :step="step.id"
                        editable
                    >
                        {{ step.name }}
                    </v-stepper-step>

                    <v-divider
                        v-if="step.id !== Object.keys(arraySteps).length"
                        :key="step.id"
                    />
                </template>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content
                    v-for="step in arraySteps"
                    :key="`${step.id}-content`"
                    :step="step.id"
                >
                    <v-card
                        class="mb-5"
                    >
                        <component
                            :is="step.component"
                            :produto="produto"
                            :active="step.id === currentStep"
                        />
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

import AnaliseDeConteudo from '../components/AnaliseDeConteudo';
import AnaliseDeCustos from '../components/AnaliseDeCustos';
import ProdutosSecundarios from '../components/ProdutosSecundarios';
import FinalizarAnalise from '../components/FinalizarAnalise';


export default {
    name: 'ParecerAnalisarView',
    components: {
        AnaliseDeConteudo,
        AnaliseDeCustos,
        ProdutosSecundarios,
        FinalizarAnalise,
    },
    data: () => ({
        currentStep: 1,
        steps: 4,
        arraySteps: [
            {
                id: 1,
                name: 'Análise de conteúdo',
                hidden: false,
                component: 'AnaliseDeConteudo',
            },
            {
                id: 2,
                name: 'Análise de custos',
                hidden: false,
                component: 'AnaliseDeCustos',
            },
            {
                id: 3,
                name: 'Dados dos Produtos Secundários',
                hidden: false,
                component: 'ProdutosSecundarios',
            },
            {
                id: 4,
                name: 'Finalizar análise',
                hidden: false,
                component: 'FinalizarAnalise',
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
        arraySteps(val) {
            const index = Object.keys(val).length;
            if (this.currentStep > index) {
                this.currentStep = index;
            }
        },
    },
    mounted() {
        this.obterProdutoParaAnalise({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
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
            /* eslint-disable-next-line */
            window.history.length > 1 ? this.$router.go(-1) : this.$router.push('/');
        },
    },
};
</script>
