<template>
    <v-container
        fluid>
        <s-carregando
            v-if="Object.keys(produto).length === 0"
            :text="'Carregando produto'"/>
        <template v-else>
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
            <v-stepper
                v-model="currentStep"
                non-linear
            >
                <v-stepper-header>
                    <template v-for="step in arraySteps">
                        <v-stepper-step
                            :key="`${step.path}-step`"
                            :step="step.id"
                            :editable="step.editable"
                            :complete="step.complete"
                            :rules="step.rules"
                        >
                            {{ step.name }}
                            <small v-if="step.message">
                                {{ step.message }}
                            </small>
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
                        :key="`${step.path}-content`"
                        :step="step.id"
                    >
                        <v-card
                            class="mb-5"
                            elevation="0"
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
                            Próximo
                        </v-btn>
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
        </template>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import SCarregando from '@/components/CarregandoVuetify';

export default {
    name: 'ParecerAnalisarView',
    components: { SCarregando },
    data: () => ({
        currentStep: 1,
        arraySteps: [
            {
                id: 1,
                name: 'Análise de conteúdo',
                message: '',
                path: 'analise-conteudo',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 2,
                name: 'Análise de custos',
                message: '',
                path: 'analise-de-custos',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 3,
                name: 'Outros produtos do projeto',
                message: '',
                path: 'produtos-secundarios',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 4,
                name: 'Consolidação',
                message: '',
                path: 'parecer-consolidacao',
                complete: false,
                editable: true,
                rules: [() => true],
            },
        ],
        fab: false,
        hidden: false,
        tabs: null,
    }),
    computed: {
        ...mapGetters({
            produto: 'parecer/getProduto',
            analiseConteudo: 'parecer/getAnaliseConteudo',
        }),
        activeFab() {
            switch (this.currentStep) {
            case 2: return { color: 'red', icon: 'edit' };
            case 3: return { color: 'green', icon: 'keyboard_arrow_up' };
            default: return {};
            }
        },
    },
    watch: {
        currentStep(val) {
            this.$router.push({ name: this.getStepById(val).path });
        },
        produto() {
            this.removerSteps();
        },
        analiseConteudo() {
            this.validarSteps();
        },
    },
    created() {
        this.obterProdutoParaAnalise({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
        this.currentStep = this.arraySteps.find(element => element.path === this.$route.name).id;
        this.obterAnaLiseConteudo({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
    },
    methods: {
        ...mapActions({
            obterProdutoParaAnalise: 'parecer/obterProdutoParaAnalise',
            obterAnaLiseConteudo: 'parecer/obterAnaLiseConteudo',
        }),
        nextStep(n) {
            this.currentStep = (n === Object.keys(this.arraySteps).length) ? 1 : n + 1;
        },
        back() {
            this.$router.push({ name: 'parecer-listar-view' });
        },
        getStepById(id) {
            return this.arraySteps.find(element => element.id === id);
        },
        getIndexStep(path) {
            return this.arraySteps.findIndex(element => element.path === path);
        },
        deleteStepByPath(path) {
            this.arraySteps.splice(this.getIndexStep(path), 1);
        },
        setStepCompleteStatus(id, val = true) {
            this.$set(this.getStepById(id), 'complete', val);
        },
        setStepEditableStatus(id, val = false) {
            this.$set(this.getStepById(id), 'editable', val);
        },
        setStepMessage(id, msg) {
            this.$set(this.getStepById(id), 'message', msg);
        },
        setStepRules(id, rules) {
            this.$set(this.getStepById(id), 'rules', rules);
        },
        removerSteps() {
            if (Object.keys(this.produto).length > 0) {
                if (this.produto.quantidadeProdutos === 1) {
                    this.deleteStepByPath('produtos-secundarios');
                }
                if (this.produto.stPrincipal !== 1) {
                    this.deleteStepByPath('parecer-consolidacao');
                }
            }
        },
        validarSteps() {
            if (Object.keys(this.analiseConteudo).length > 0) {
                this.setStepEditableStatus(2, true);
                this.setStepMessage(2, '');
                this.setStepRules(2, [() => true]);
                if (this.analiseConteudo.ParecerFavoravel !== true
                    && this.analiseConteudo.ParecerFavoravel !== 1) {
                    this.setStepEditableStatus(2, false);
                    this.setStepMessage(2, 'Não disponível! Parecer não favorável');
                    this.setStepRules(2, [() => false]);
                }
            }
        },
    },
};
</script>
