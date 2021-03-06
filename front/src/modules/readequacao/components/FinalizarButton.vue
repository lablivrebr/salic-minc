<template>
    <v-layout
        v-if="perfilAceito"
    >
        <v-btn
            v-if="telaEdicao"
            :disabled="disabled"
            dark
            color="blue darken-1"
            class="m-2"
            @click="dialog = true"
        >
            Finalizar Readequação
            <v-icon
                right
                dark
            >send</v-icon>
        </v-btn>
        <div v-else>
            <v-btn
                :disabled="!validacao"
                dark
                icon
                flat
                small
                color="green darken-3"
                @click.stop="dialog = true"
            >
                <v-tooltip bottom>
                    <v-icon slot="activator">send</v-icon>
                    <span>Finalizar Readequação</span>
                </v-tooltip>
            </v-btn>
        </div>
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
                    <span>{{ dadosReadequacao.dtSolicitacao | formatarData }}</span>
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
import _ from 'lodash';
import { mapActions } from 'vuex';
import { utils } from '@/mixins/utils';
import validarFormulario from '../mixins/validarFormulario';
import verificarPerfil from '../mixins/verificarPerfil';

export default {
    name: 'FinalizarButton',
    mixins: [
        utils,
        validarFormulario,
        verificarPerfil,
    ],
    props: {
        disabled: {
            type: Boolean,
            default: false,
        },
        dadosProjeto: {
            type: Object,
            default: () => {},
        },
        dadosReadequacao: {
            type: Object,
            default: () => {},
        },
        telaEdicao: {
            type: Boolean,
            default: false,
        },
        minChar: {
            type: Object,
            default: () => {},
        },
        perfisAceitos: {
            type: Array,
            default: () => [],
        },
        perfil: {
            type: [Number, String],
            default: 0,
        },
    },
    data() {
        return {
            dialog: false,
            validacao: false,
        };
    },
    computed: {
        perfilAceito() {
            return this.verificarPerfil(this.perfil, this.perfisAceitos);
        },
    },
    watch: {
        dadosReadequacao: {
            handler() {
                this.validar();
            },
            deep: true,
        },
        minChar() {
            if (!_.isEmpty(this.minChar)) {
                this.validar();
            }
        },
    },
    created() {
        this.validar();
    },
    methods: {
        ...mapActions({
            finalizarReadequacao: 'readequacao/finalizarReadequacao',
        }),
        validar() {
            if (typeof this.minChar === 'object') {
                if (typeof this.minChar.solicitacao === 'number') {
                    if (typeof this.dadosReadequacao.dsSolicitacao !== 'undefined'
                        && typeof this.dadosReadequacao.dsJustificativa !== 'undefined') {
                        const contador = {
                            solicitacao: this.dadosReadequacao.dsSolicitacao.length,
                            justificativa: this.dadosReadequacao.dsJustificativa.length,
                        };
                        this.validacao = this.validarFormulario(
                            this.dadosReadequacao,
                            contador,
                            this.minChar,
                        );
                    }
                }
            }
        },
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
