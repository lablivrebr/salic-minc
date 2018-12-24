<template>
    <v-dialog
        v-model="dialog"
        fullscreen
        hide-overlay
        scrollable
    >
        <v-btn slot="activator" color="success" dark>CONSOLIDAÇÃO</v-btn>
        <v-card>
            <v-card-text>
                <h2>{{ nomeProjeto }}</h2>
                <div class="mt-3" v-if="Object.keys(getConsolidacaoAnalise).length > 0">
                    <v-expansion-panel>
                        <v-expansion-panel-content
                            v-for="(consolidacao, i) in getConsolidacaoAnalise"
                            :key="i"
                        >
                            <v-layout slot="header" class="blue--text">
                                <v-icon class="mr-3 blue--text" >{{ consolidacao.icon }}</v-icon>
                                <span v-text="consolidacao.title"></span>
                                <v-spacer></v-spacer>
                            </v-layout>

                            <v-card>
                                <v-card-text>
                                    <v-data-table
                                        :headers="consolidacaoHeaders(consolidacao.cols)"
                                        :items="consolidacao.lines"
                                        hide-actions
                                        no-data-text="Não há dados disponíveis."
                                    >
                                        <template slot="items" slot-scope="props">
                                            <td
                                                v-for="(celula, i) in props.item"
                                                :key="i"
                                                v-html="celula"
                                            ></td>
                                        </template>
                                    </v-data-table>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </div>
                <Carregando v-else :text="'Carregando dados da consolidação ...'" />
            </v-card-text>
            <v-btn color="red" @click="dialog = false" flat>FECHAR</v-btn>
        </v-card>
    </v-dialog>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/CarregandoVuetify';

    export default {
        name: 'ConsolidacaoAnalise',
        data() {
            return {
                dialog: false,
            };
        },
        components: { Carregando },
        props: {
            idPronac: String,
            nomeProjeto: String,
        },
        computed: {
            ...mapGetters({
                getConsolidacaoAnalise: 'avaliacaoResultados/consolidacaoAnalise',
            }),
        },
        mounted() {
        },
        watch: {
            dialog(value) {
                if (value) {
                    this.setConsolidacaoAnalise(this.idPronac);
                }
            },
        },
        methods: {
            ...mapActions({
                setConsolidacaoAnalise: 'avaliacaoResultados/consolidacaoAnalise',
            }),
            consolidacaoHeaders: (cols) => {
                const headerKeys = Object.keys(cols);
                const headers = headerKeys.map(item => (
                    {
                        text: cols[item].name,
                        value: item,
                        sortable: false,
                        class: cols[item].class,
                    }
                ));
                return headers;
            },
        },
    };
</script>
