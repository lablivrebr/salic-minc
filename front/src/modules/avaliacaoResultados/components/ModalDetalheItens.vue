<template>
    <div class="text-xs-center">
        <v-dialog
            v-model="dialog"
            width="750"
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
                    Visualizar Comprovantes
                </v-card-title>
                <v-subheader>Item de custo: {{item}}</v-subheader>

                <v-card-text v-if="Object.keys(comprovantes).length > 0">
                    <v-expansion-panel>
                        <v-expansion-panel-content v-for="(comprovante, index) in comprovantes" :key="index">
                            <div slot="header">
                                <div style="display:inline-block;">
                                    Fornecedor: {{comprovante.nmFornecedor}}
                                </div>
                                <v-chip
                                    style="display: inline-block; float: right; margin-right: 20px;"
                                    :color="badgeCSS(comprovante.stItemAvaliado)"
                                    text-color="white"
                                >
                                    {{situacao(comprovante.stItemAvaliado)}}
                                </v-chip>
                            </div>
                            <v-card>
                                <v-card-text>
                                    <b>Valor: </b>R$ {{comprovante.vlComprovacao | filtroFormatarParaReal}}
                                </v-card-text>
                                <v-card-text>
                                    <b>Arquivo: </b>
                                    <a :href="'/upload/abrir/id/' + comprovante.arquivo.id">
                                        {{comprovante.arquivo.nome}}
                                    </a>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card-text>
                <v-card-text v-else>
                    <div style="align: center" class="text-xs-center">
                        <div style="padding-top: 25px">
                            <v-progress-circular
                                :size="50"
                                color="primary"
                                indeterminate
                            ></v-progress-circular>
                        </div>
                        <br>
                        <div style="padding-top: 20px">
                            Carregando...
                        </div>
                    </div>
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
    import numeral from 'numeral';

    export default {
        name: 'ModalDetalheItens',
        props: {
            comprovanteIndex: Number,
            item: String,
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
            badgeCSS(id) {
                const currentId = parseInt(id, 10);

                if (currentId === 1) {
                    return 'green';
                }

                if (currentId === 3) {
                    return 'red';
                }
                if (currentId === 4) {
                    return 'grey';
                }

                return 'white';
            },
            situacao(id) {
                let estado = '';

                switch (parseInt(id, 10)) {
                case 1:
                    estado = 'Aprovado';
                    break;
                case 3:
                    estado = 'Recusado';
                    break;
                default:
                    estado = 'N\xE3o avaliado';
                }
                return estado;
            },
        },
        filters: {
            filtroFormatarParaReal(value) {
                const parsedValue = parseFloat(value);
                return numeral(parsedValue)
                    .format('0,0.00');
            },
        },
    };
</script>
