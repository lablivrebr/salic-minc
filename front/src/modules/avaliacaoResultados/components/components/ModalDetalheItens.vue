<template>
    <div class="text-xs-center">
        <v-dialog
            v-model="dialog"
            width="90%"
        >
            <v-card>
                <v-card-title
                    class="headline grey lighten-2"
                    primary-title
                >
                    Visualizar Comprovantes
                </v-card-title>
                <v-subheader>Item de custo: {{item}}</v-subheader>

                <v-card-text>
                    <lista-de-comprovantes :comprovantes="comprovantes"></lista-de-comprovantes>
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
    import ListaDeComprovantes from './ListaDeComprovantes';

    export default {
        name: 'ModalDetalheItens',
        props: {
            item: String,
            idPronac: String,
            uf: String,
            codigoCidade: Number,
            codigoProduto: Number,
            stItemAvaliado: String,
            codigoEtapa: Number,
            idPlanilhaItens: Number,
        },
        data() {
            return {
                dialog: false,
                currentComprovantes: [],
            };
        },
        components: { ListaDeComprovantes },
        computed: {
            ...mapGetters({
                comprovantes: 'avaliacaoResultados/comprovantes',
                isModalVisible: 'modal/default',
            }),
        },
        watch: {
            comprovantes(value) {
                this.currentComprovantes = value;
            },
            isModalVisible(value) {
                if (value === 'visualizar-comprovantes') {
                    this.dialog = true;
                    this.buscar();
                }
            },
            dialog(value) {
                if (value === false) {
                    this.modalClose();
                }
            },
        },
        methods: {
            ...mapActions({
                buscarComprovantes: 'avaliacaoResultados/buscarComprovantes',
                modalClose: 'modal/modalClose',
            }),
            buscar() {
                this.currentComprovantes = [];

                const params = {
                    uf: this.uf,
                    idPronac: this.idPronac,
                    codigoCidade: this.codigoCidade,
                    codigoProduto: this.codigoProduto,
                    stItemAvaliado: this.stItemAvaliado,
                    codigoEtapa: this.codigoEtapa,
                    idPlanilhaItens: this.idPlanilhaItens,
                };

                this.buscarComprovantes(params);
            },
        },
    };
</script>
