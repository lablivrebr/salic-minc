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
                    <template v-for="(step, index) in arraySteps">
                        <v-stepper-step
                            :key="`${step.name}-step`"
                            :step="index + 1"
                            :editable="step.editable"
                            :complete="step.complete"
                            :rules="step.rules"
                        >
                            {{ step.label }}
                            <small v-if="step.message">
                                {{ step.message }}
                            </small>
                        </v-stepper-step>
                        <v-divider
                            v-if="index + 1 !== Object.keys(arraySteps).length"
                            :key="index + 1"
                        />
                    </template>
                </v-stepper-header>

                <v-stepper-items>
                    <v-stepper-content
                        v-for="(step, index) in arraySteps"
                        :key="`${step.name}-content`"
                        :step="index + 1"
                    >
                        <v-card
                            class="mb-5"
                            elevation="0"
                        >
                            <keep-alive>
                                <router-view
                                    v-if="(index + 1) === currentStep"
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
        currentStep: '1',
        arraySteps: [
            {
                id: 1,
                label: 'Análise de conteúdo',
                message: '',
                name: 'analise-conteudo',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 2,
                label: 'Análise de custos',
                message: '',
                name: 'analise-de-custos',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 3,
                label: 'Outros produtos do projeto',
                message: '',
                name: 'analise-outros-produtos',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 4,
                label: 'Consolidação',
                message: '',
                name: 'analise-consolidacao',
                complete: false,
                editable: true,
                rules: [() => true],
            },
            {
                id: 5,
                label: 'Finalizar análise',
                message: '',
                name: 'analise-finalizacao',
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
    },
    watch: {
        currentStep(step) {
            this.$router.push({ name: this.arraySteps[step - 1].name });
        },
        produto() {
            this.removerSteps();
            this.atualizarStepByRoute();
        },
        analiseConteudo() {
            this.validarSteps();
        },
        $route() {
            this.atualizarStepByRoute();
        },
    },
    created() {
        this.obterProdutoParaAnalise({
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
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
        getIndexStepByName(name) {
            return this.arraySteps.findIndex(element => element.name === name);
        },
        deleteStepByName(name) {
            this.arraySteps.splice(this.getIndexStepByName(name), 1);
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
                    this.deleteStepByName('analise-outros-produtos');
                }
                if (this.produto.stPrincipal !== 1) {
                    this.deleteStepByName('analise-consolidacao');
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
        atualizarStepByRoute() {
            this.currentStep = this.getIndexStepByName(this.$route.name) + 1;
        },
    },
};
</script>
