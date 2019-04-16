<template>
    <v-dialog
        v-model="dialog"
        width="500"
    >
        <v-tooltip
            slot="activator"
            top
        >
            <v-btn
                slot="activator"
                flat
                icon
                color="red"
            >
                <v-icon>delete</v-icon>
            </v-btn>
            <span>Excluir</span>
        </v-tooltip>
        <v-card>
            <v-card-text>
                Você tem certeza que deseja excluir este comprovante?
            </v-card-text>
            <v-divider/>
            <v-card-actions>
                <v-spacer/>
                <v-btn
                    color="success"
                    flat
                    @click="confirmarExclusao(idComprovantePagamento)"
                >
                    SIM
                </v-btn>
                <v-btn
                    color="error"
                    flat
                    @click="dialog = false"
                >
                    NÃO
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    props: {
        idComprovantePagamento: { type: String, default: '' },
    },
    data() {
        return {
            dialog: false,
        };
    },
    methods: {
        ...mapActions({
            excluirComprovante: 'avaliacaoResultados/excluirComprovante',
        }),
        confirmarExclusao(idComprovantePagamento) {
            this.excluirComprovante({ 'comprovante[idComprovantePagamento]': idComprovantePagamento });
            this.$root.$emit('recarregar-comprovantes');
            this.dialog = false;
        },
    },
};
</script>
