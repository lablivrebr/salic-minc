<template>
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
                        <v-tooltip
                            bottom
                        >
                            <v-btn
                                slot="activator"
                                icon
                                @click="dialog = !dialog"
                            >
                                <v-icon>restore</v-icon>
                            </v-btn>
                            <span>Restaurar planilha original</span>
                        </v-tooltip>
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

        <v-dialog
            v-model="dialog"
            persistent
            max-width="300"
        >
            <v-card>
                <v-card-title class="headline">
                    Tem certeza?
                </v-card-title>
                <v-card-text>Ao restaurar a planilha as alterações realizadas serão descartadas!</v-card-text>
                <v-card-actions>
                    <v-spacer />
                    <v-btn
                        color="green darken-1"
                        flat
                        @click="dialog = false"
                    >
                        Cancelar
                    </v-btn>
                    <v-btn
                        color="green darken-1"
                        flat
                        @click="restaurarPlanilha()"
                    >
                        Tenho certeza
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <progresso-dialog
            v-model="loadingRestore"
            label="Aguarde, restaurando planilha"
        />
    </v-container>
</template>
<script>

import { mapActions, mapGetters } from 'vuex';
import MxPlanilha from '@/mixins/planilhas';
import ProgressoDialog from '@/components/ProgressoDialog';

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
    name: 'AnaliseDeCustosHeader',
    components: { ProgressoDialog },
    mixins: [MxPlanilha],
    data() {
        return {
            dialog: false,
            loadingRestore: false,
            calculos: dataDefaults.calculos,
        };
    },
    computed: {
        ...mapGetters({
            planilha: 'parecer/getPlanilhaParecer',
            produto: 'parecer/getProduto',
        }),
    },
    watch: {
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
    methods: {
        ...mapActions({
            restaurarPlanilhaProduto: 'parecer/restaurarPlanilhaProduto',
            obterPlanilhaParecer: 'parecer/obterPlanilhaParaAnalise',
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
        restaurarPlanilha() {
            if (Object.keys(this.produto).length === 0) {
                return false;
            }
            this.dialog = false;
            this.loadingRestore = true;
            const params = {
                id: this.produto.idProduto,
                idPronac: this.produto.IdPRONAC,
                stPrincipal: this.produto.stPrincipal,
            };
            this.restaurarPlanilhaProduto(params).then(() => {
                this.loadingRestore = false;
                this.obterPlanilhaParecer(params);
            }).catch(() => {
                this.loadingRestore = false;
            });

            return true;
        },
    },
};
</script>
