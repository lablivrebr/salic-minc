<template>
    <div class="text-xs-center">
        <v-dialog
            v-model="dialog"
            width="500"
        >
            <v-btn
                @click="buscar()"
                slot="activator"
                color="blue lighten-2"
                dark
            >
                <v-icon dark>visibility</v-icon>
            </v-btn>

            <v-card>
                <v-card-title
                    class="headline grey lighten-2"
                    primary-title
                >
                    Prestação de Contas: Analise
                </v-card-title>

                <v-card-text>
                    <v-expansion-panel>
                        <v-expansion-panel-content v-for="(comprovante, index) in comprovantes" :key="index">
                            <div slot="header">Fornecedor: {{comprovante.nmFornecedor}}</div>
                            <v-card>
                                <v-card-text>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                    <!--Fornecedor: {{comprovante.nmFornecedor}}-->
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="red lighten-2"
                        flat
                        @click="dialog = false"
                    >
                        Fechar
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        props: {
            comprovanteIndex: Number,
        },
        data() {
            return {
                dialog: false,
            };
        },
        computed: {
            ...mapGetters({
                comprovantes: 'avaliacaoResultados/comprovantes',
            }),
        },
        methods: {
            ...mapActions({
                buscarComprovantes: 'avaliacaoResultados/buscarComprovantes',
            }),
            buscar() {
                this.buscarComprovantes(this.comprovanteIndex);
            },
        },
    };
</script>
content_copy
