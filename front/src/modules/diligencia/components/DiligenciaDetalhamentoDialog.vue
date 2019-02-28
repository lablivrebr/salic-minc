<template>
    <v-dialog
        v-model="dialogDetalhamento"
        row
        justify-center
        @keydown.esc="dialogDetalhamento = false"
    >
        <v-card
            tile
        >
            <v-toolbar
                card
                dark
                color="primary lighten-1"
            >
                <v-btn
                    icon
                    dark
                    @click="dialogDetalhamento = false"
                >
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>
                    Visualizar diligência
                </v-toolbar-title>
            </v-toolbar>
            <v-card-text>

                adasdsadasd
                {{ diligencia }}
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import { utils } from '@/mixins/utils';

export default {
    name: 'DiligenciaDetalhamentoDialog',
    mixins: [utils],
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        item: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            dialogDetalhamento: false,
            loading: true,
        };
    },
    computed: {
        ...mapGetters({
            diligencia: 'diligencia/getDiligencia',
        }),
    },
    watch: {
        value(val) {
            this.dialogDetalhamento = val;
        },
        dialogDetalhamento(val) {
            this.$emit('input', val);
        },
        item(val) {
            if (Object.keys(val).length > 0 && this.value) {
                this.visualizarDetalhesDiligencia(val);
            }
        },
    },
    methods: {
        ...mapActions({
            obterDiligencia: 'diligencia/obterDiligencia',
        }),
        visualizarDetalhesDiligencia(diligencia) {
            this.obterDiligencia({
                idDiligencia: diligencia.idDiligencia,
                idPronac: diligencia.IdPRONAC,
            });
        },
    },
};
</script>
