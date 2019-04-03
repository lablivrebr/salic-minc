<template>
    <v-layout>
        <div v-if="telaEdicao">
            <v-btn
                :disabled="disabled"
                absolute
                dark
                color="green darken-1"
                class="mt-2 mb-5"
                @click="dialog = true"
            >
                Finalizar Readequação
                <v-icon
                    right
                    dark
                >send</v-icon>
            </v-btn>
        </div>
        <v-btn
            v-else
            dark
            icon
            flat
            small
            color="green"
            @click.stop="dialog = true"
        >
            <v-tooltip bottom>
                <v-icon slot="activator">send</v-icon>
                <span>Finalizar Readequação</span>
            </v-tooltip>
        </v-btn>

        <v-dialog
            v-model="dialog"
            max-width="350"
        >
            <v-card>
                <v-card-title class="headline">Finalizar Readequação?</v-card-title>
                <v-card-text>
                    <h4
                        class="title mb-2"
                        v-html="dadosProjeto.NomeProjeto"
                    />
                    <h4>Readequação do Tipo:</h4>
                    <span v-html="dadosReadequacao.dsTipoReadequacao"/>
                    <h4>Data de abertura: </h4>
                    <span v-html="dadosReadequacao.dtSolicitacao"/>
                </v-card-text>
                <v-card-actions>
                    <v-spacer/>
                    <v-btn
                        color="red darken-1"
                        flat="flat"
                        @click="dialog = false"
                    >
                        Cancelar
                    </v-btn>

                    <v-btn
                        color="green darken-1"
                        flat="flat"
                        @click="finalizar()"
                    >
                        OK
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    name: 'FinalizarButton',
    props: {
        disabled: { type: Boolean, default: false },
        dadosProjeto: { type: Object, default: () => {} },
        dadosReadequacao: { type: Object, default: () => {} },
        telaEdicao: { type: Boolean, default: false },
    },
    data() {
        return {
            dialog: false,
        };
    },
    computed: {
    },
    methods: {
        ...mapActions({
            finalizarReadequacao: 'readequacao/finalizarReadequacao',
        }),
        finalizar() {
            this.finalizarReadequacao({
                idReadequacao: this.dadosReadequacao.idReadequacao,
                idPronac: this.dadosReadequacao.idPronac,
            })
                .then(() => {
                    this.$emit('readequacao-finalizada');
                    this.dialog = false;
                });
        },
    },
};
</script>
