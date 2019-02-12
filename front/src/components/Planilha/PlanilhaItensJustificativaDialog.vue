<template>
    <v-dialog
        v-model="dialog"
        max-width="500px"
    >
        <v-card>
            <v-card-title>
                <span class="headline">
                    {{ label }}
                </span>
            </v-card-title>

            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex
                            xs12
                            v-html="text"
                        />
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer />
                <v-btn
                    color="blue darken-1"
                    flat
                    @click="close"
                >
                    Fechar
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import planilhas from '@/mixins/planilhas';

export default {
    name: 'SPlanilhaJustificativaDialog',
    mixins: [planilhas],
    props: {
        label: {
            type: String,
            default: 'Justificativa',
        },
        value: {
            type: Boolean,
            default: false,
        },
        text: {
            type: String,
            default: '',
        },
    },
    data() {
        return {
            dialog: false,
        };
    },
    watch: {
        value(val) {
            this.dialog = val;
        },
        dialog(val) {
            this.$emit('input', val);
        },
    },
    methods: {
        close() {
            this.dialog = false;
        },
    },
};
</script>
