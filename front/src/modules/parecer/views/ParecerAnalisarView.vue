<template>
    <div v-if="true">
        <v-stepper v-model="e1">
            <v-stepper-header>
                <template v-for="n in arraySteps">
                    <v-stepper-step
                        :complete="e1 > n.id"
                        :key="`${n.id}-step`"
                        :step="n.id"
                        editable
                    >
                        <component :is="n.componente"/>
                    </v-stepper-step>

                    <v-divider
                        v-if="n.id !== Object.keys(arraySteps).length"
                        :key="n.id"
                    />
                </template>
            </v-stepper-header>

            <v-stepper-items>
                <v-stepper-content
                    v-for="n in arraySteps"
                    :key="`${n.id}-content`"
                    :step="n.id"
                >
                    <v-card
                        class="mb-5"
                        color="grey lighten-1"
                        height="200px"
                    >{{ n.componente }}</v-card>
                    <v-btn
                        color="primary"
                        @click="nextStep(n.id)"
                    >
                        Continue
                    </v-btn>

                    <v-btn flat>Cancel</v-btn>
                </v-stepper-content>

                <v-fab-transition>
                    <v-btn
                        :color="activeFab.color"
                        :key="activeFab.icon"
                        v-model="fab"
                        dark
                        fab
                        fixed
                        bottom
                        left
                    >
                        <v-icon>{{ activeFab.icon }}</v-icon>
                        <v-icon>close</v-icon>
                    </v-btn>
                </v-fab-transition>
            </v-stepper-items>
        </v-stepper>
    </div>
    <div
        v-else
        id="lateral">
        <v-tabs
            slot="extension"
            v-model="tabs"
            fixed-tabs
            color="transparent"
        >
            <v-tab href="#one">Análise de conteúdo</v-tab>
            <v-tab href="#two">Análise de custos</v-tab>
            <v-tab href="#three">Dados dos Produtos Secundários</v-tab>
            <v-tabs-slider color="pink"/>
        </v-tabs>
        <v-tabs-items v-model="tabs">
            <v-tab-item
                v-for="content in ['one', 'two', 'three']"
                :key="content"
                :value="content"
            >
                <v-card
                    height="300px"
                    flat/>
            </v-tab-item>
        </v-tabs-items>
        <v-fab-transition>
            <v-btn
                :color="activeFab.color"
                :key="activeFab.icon"
                v-model="fab"
                dark
                fab
                fixed
                bottom
                left
            >
                <v-icon>{{ activeFab.icon }}</v-icon>
                <v-icon>close</v-icon>
            </v-btn>
        </v-fab-transition>
    </div>
</template>

<script>
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
        e1: 1,
        steps: 4,
        arraySteps: [
            {
                id: 1,
                name: 'Análise de conteúdo',
                componente: 'AnaliseDeConteudo',
            },
            {
                id: 2,
                name: 'Análise de custos',
                componente: 'AnaliseDeCustos',
            },
            {
                id: 3,
                name: 'Dados dos Produtos Secundários',
                componente: 'ProdutosSecundarios',
            },
            {
                id: 4,
                name: 'Finalizar análise',
                componente: 'FinalizarAnalise',
            },
        ],
        fab: false,
        hidden: false,
        tabs: null,
    }),
    computed: {
        activeFab() {
            switch (this.el) {
            case 1: return { color: 'indigo', icon: 'share' };
            case 2: return { color: 'red', icon: 'edit' };
            case 3: return { color: 'green', icon: 'keyboard_arrow_up' };
            default: return {};
            }
        },
    },
    watch: {
        steps(val) {
            if (this.e1 > val) {
                this.e1 = val;
            }
        },
    },
    methods: {
        onInput(val) {
            this.steps = parseInt(val, 10);
        },
        nextStep(n) {
            if (n === this.steps) {
                this.e1 = 1;
            } else {
                this.e1 = n + 1;
            }
        },
    },
};
</script>

<style>
    /* This is for documentation purposes and will not be needed in your application */
    #lateral .v-speed-dial,
    #lateral .v-btn--floating {
        position: absolute;
    }
    #lateral .v-btn--floating {
        margin: 0 0 16px 16px;
    }
</style>
