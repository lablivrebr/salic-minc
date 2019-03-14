<template>
    <v-dialog
        v-model="dialog"
        scrollable
        max-width="1200px"
        min-width="500px"
        @keydown.esc="dialog = false"
    >
        <v-card>
            <v-toolbar
                dark
                color="primary"
            >
                <v-btn
                    icon
                    dark
                    @click="dialog = false"
                >
                    <v-icon>close</v-icon>
                </v-btn>
                <v-toolbar-title>Diligênciar</v-toolbar-title>
                <v-spacer />
            </v-toolbar>
            <v-card-text>
                <v-container>
                    <v-card>
                        <v-card-text>
                            <v-form
                                ref="form"
                                v-model="valid"
                            >
                                <div
                                    v-show="solicitacaoRules.show"
                                    class="text-xs-left"
                                >
                                    <h4 :class="solicitacaoRules.color">
                                        {{ solicitacaoRules.msg }}*
                                    </h4>
                                </div>
                                <s-editor-texto
                                    v-model="diligenciaEmEdicao.solicitacao"
                                    placeholder="Texto da diligência:"
                                    :style="solicitacaoRules.backgroundColor"
                                    @editor-texto-counter="validarSolicitacao($event)"
                                />
                            </v-form>
                        </v-card-text>
                        <v-card-actions class="justify-center">
                            <v-btn
                                :disabled="!valid || !solicitacaoRules.enable"
                                color="primary"
                                @click.native="enviarDiligencia()"
                            >
                                Enviar
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import SEditorTexto from '@/components/SalicEditorTexto';

export default {
    name: 'DiligenciaDialogCriar',
    components: {
        SEditorTexto,
    },
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        idPronac: {
            type: Number,
            required: true,
        },
        tpDiligencia: {
            type: Number,
            required: true,
        },
        idProduto: {
            type: Number,
            default: null,
        },
        situacao: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            solicitacao: '',
            valid: false,
            dialog: false,
            solicitacaoRules: {
                show: false,
                color: '',
                backgroundColor: '',
                msg: '',
                enable: false,
            },
            diligenciaRules: [
                v => !!v || 'Tipo de diligencia é obrigatório!',
            ],
            diligenciaEmEdicao: {
                idPronac: this.idPronac,
                tpDiligencia: this.tpDiligencia,
                solicitacao: '',
                idProduto: this.idProduto,
                situacao: this.situacao,
            },
        };
    },
    computed: {
        ...mapGetters({
            projeto: 'avaliacaoResultados/projeto',
        }),
    },
    watch: {
        value(val) {
            this.dialog = val;
        },
        dialog(val) {
            this.$emit('input', val);
        },
    },
    created() {
    },
    methods: {
        ...mapActions({
            requestEmissaoParecer: 'avaliacaoResultados/getDadosEmissaoParecer',
            salvar: 'diligencia/salvarDiligencia',
        }),
        enviarDiligencia() {
            this.salvar(this.diligenciaEmEdicao).then(() => {
                this.dialog = false;
            });
        },
        validarSolicitacao(e) {
            if (e < 1) {
                this.solicitacaoRules = {
                    show: true,
                    color: 'red--text',
                    backgroundColor: { 'background-color': '#FFCDD2' },
                    msg: 'A solicitação é obrigatória!',
                    enable: false,
                };
            }
            if (e > 0) {
                this.solicitacaoRules = {
                    show: false,
                    color: '',
                    backgroundColor: '',
                    msg: '',
                    enable: true,
                };
            }
        },
    },
};
</script>
