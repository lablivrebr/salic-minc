<template>
    <v-container style="max-width: 800px">
        <h2 class="display-1 success--text pl-3">
            Validações:&nbsp;
            <v-fade-transition leave-absolute>
                <span :key="`tasks-${tasks.length}`">
                    {{ tasks.length }}
                </span>
            </v-fade-transition>
        </h2>

        <v-divider class="mt-3" />

        <v-layout
            my-1
            align-center
        >
            <strong class="mx-3 info--text text--darken-3">
                Pendentes: {{ remainingTasks }}
            </strong>

            <v-divider vertical />

            <strong class="mx-3 black--text">
                Validado: {{ completedTasks }}
            </strong>

            <v-spacer />

            <v-progress-circular
                :value="progress"
                class="mr-2"
            />
        </v-layout>

        <v-divider class="mb-3" />

        <v-card v-if="tasks.length > 0">
            <v-slide-y-transition
                class="py-0"
                group
                tag="v-list"
            >
                <template v-for="(task, i) in tasks">
                    <v-divider
                        v-if="i !== 0"
                        :key="`${i}-divider`"
                    />

                    <v-list-tile
                        :key="`${i}-${task.text}`"
                        two-line
                    >
                        <v-list-tile-content>
                            <v-list-tile-title
                                :class="task.done && 'text--primary' || 'grey--text'"
                                v-text="task.text"
                            />
                            <v-list-tile-sub-title
                                v-if="!task.done && !task.loading"
                                class="red--text"
                                v-text="task.error"
                            />
                        </v-list-tile-content>
                        <v-spacer />
                        <v-progress-circular
                            v-if="!task.done && task.loading"
                            :width="3"
                            color="primary"
                            small
                            indeterminate
                        />
                        <v-list-tile-action>
                            <v-scroll-x-transition>
                                <v-icon
                                    v-if="task.done && !task.loading"
                                    color="success"
                                >
                                    check
                                </v-icon>
                                <v-icon
                                    v-if="!task.done && !task.loading"
                                    color="red"
                                >
                                    clear
                                </v-icon>
                            </v-scroll-x-transition>
                        </v-list-tile-action>
                    </v-list-tile>
                </template>
            </v-slide-y-transition>
        </v-card>
        <div class="text-xs-center mt-3">
            <v-btn
                color="primary"
                :disabled="remainingTasks > 0"
            >
                <v-icon left>
                    send
                </v-icon>
                Finalizar parecer
            </v-btn>
        </div>
    </v-container>
</template>

<script>

import { mapActions, mapGetters } from 'vuex';

export default {
    data: () => ({
        tasks: [
            {
                done: false,
                text: 'Análise de conteúdo',
                value: 'analiseConteudo',
                loading: true,
                rules: [(v, self) => (Object.keys(v).length > 0
                    && self.stripTags(v.ParecerDeConteudo).length > 10) || 'Falta parecer da análise de conteúdo'],
                error: '',
            },
            {
                done: false,
                text: 'Análise de custos',
                value: 'planilha',
                loading: true,
                rules: [
                    (v, self) => (v.length > 0 && v.filter(i => i.stCustoPraticadoParc === 1
                    && self.stripTags(i.dsJustificativaParecerista).length < 10).length === 0)
                    || 'Existem itens com o valor praticado e não justificado',
                ],
                error: '',
            },
            {
                done: false,
                text: 'Outros produtos',
                value: 'produtosSecundarios',
                loading: true,
                rules: [
                    v => (v.length > 0 && v.filter(i => i.DtDevolucao === null
                        && i.FecharAnalise === '0'
                        && i.stPrincipal === 0).length === 0)
                        || 'Existem produtos pendentes de análise',
                ],
                error: '',
            },
            {
                done: false,
                text: 'Consolidação do projeto',
                value: 'consolidacao',
                loading: true,
                rules: [(v, self) => (Object.keys(v).length > 0
                    && self.stripTags(v.ResumoParecer).length > 10) || 'Falta parecer da consolidação'],
                error: '',
            },
        ],
        task: null,
    }),
    computed: {
        ...mapGetters({
            analiseConteudo: 'parecer/getAnaliseConteudo',
            produto: 'parecer/getProduto',
            planilha: 'parecer/getPlanilhaParecer',
            consolidacao: 'parecer/getConsolidacao',
            produtosSecundarios: 'parecer/getProdutosSecundarios',
        }),
        completedTasks() {
            return this.tasks.filter(task => task.done).length;
        },
        progress() {
            return this.completedTasks / this.tasks.length * 100;
        },
        remainingTasks() {
            return this.tasks.length - this.completedTasks;
        },
    },
    watch: {
        analiseConteudo() {
            this.checkAll();
        },
        planilha: {
            handler() {
                this.checkAll();
            },
            deep: true,
        },
        consolidacao() {
            this.checkAll();
        },
        produtosSecundarios() {
            this.checkAll();
        },
    },
    mounted() {
        const params = {
            id: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
            stPrincipal: this.$route.params.produtoPrincipal,
        };
        this.obterAnaLiseConteudo(params);
        this.obterPlanilhaParecer(params);
        this.obterConsolidacao(params);
        this.obterProdutosSecundarios(params);
    },
    methods: {
        ...mapActions({
            obterAnaLiseConteudo: 'parecer/obterAnaLiseConteudo',
            obterPlanilhaParecer: 'parecer/obterPlanilhaParaAnalise',
            obterConsolidacao: 'parecer/obterConsolidacao',
            obterProdutosSecundarios: 'parecer/obterProdutosSecundarios',
        }),
        checkAll() {
            this.tasks.forEach((item, i) => {
                const task = this.tasks[i];
                this.tasks[i].done = false;
                if (Array.isArray(item.rules)) {
                    item.rules.forEach((f) => {
                        const valid = typeof f === 'function' ? f(this[task.value], this) : false;

                        if (typeof valid === 'string') {
                            this.tasks[i].error = valid;
                        }

                        if (typeof this[task.value] !== 'undefined'
                            && ((Array.isArray(this[task.value]) && this[task.value].length > 0)
                            || Object.keys(this[task.value]).length > 0)) {
                            this.tasks[i].loading = false;
                        }

                        this.tasks[i].done = typeof valid === 'boolean' ? valid : false;
                    });
                }
            });
        },
        stripTags(string) {
            if (typeof string !== 'string') {
                return string;
            }
            return string.replace(/(<([^>]+)>)/ig, '');
        },
    },
};
</script>
