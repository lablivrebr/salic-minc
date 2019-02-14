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

                    <v-list-tile :key="`${i}-${task.text}`">
                        <v-list-tile-action>
                            <v-checkbox
                                v-model="task.done"
                                color="info darken-3"
                            >
                                <div
                                    slot="label"
                                    :class="task.done && 'grey--text' || 'text--primary'"
                                    class="ml-3"
                                    v-text="task.text"
                                />
                            </v-checkbox>
                        </v-list-tile-action>

                        <v-spacer />

                        <v-scroll-x-transition>
                            <v-icon
                                v-if="task.done"
                                color="success"
                            >
                                check
                            </v-icon>
                        </v-scroll-x-transition>
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
            },
            {
                done: false,
                text: 'Análise de custos',
            },
            {
                done: false,
                text: 'Consolidação',
            },
            {
                done: false,
                text: 'Todos os produtos estão analisados',
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
    methods: {
        ...mapActions({
            obterProdutoParaAnalise: 'parecer/obterProdutoParaAnalise',
            obterAnaLiseConteudo: 'parecer/obterAnaLiseConteudo',
            obterPlanilhaParecer: 'parecer/obterPlanilhaParaAnalise',
            obterConsolidacao: 'parecer/obterConsolidacao',
        }),
        create() {
            this.tasks.push({
                done: false,
                text: this.task,
            });

            this.task = null;
        },
    },
};
</script>
