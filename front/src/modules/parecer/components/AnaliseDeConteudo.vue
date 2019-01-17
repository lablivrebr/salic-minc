<template>
    <v-card id="create">
        <v-fab-transition>
            <v-btn
                v-if="active"
                v-model="fab"
                color="blue darken-2"
                dark
                fab
                @click="dialog = !dialog"
            >
                <v-icon>edit</v-icon>
            </v-btn>
        </v-fab-transition>

        <Proposta
            v-if="Object.keys(analiseConteudo).length > 0"
            :idpreprojeto="produto.idProjeto"/>

        <v-dialog
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            scrollable
        >
            <v-card tile>
                <v-toolbar
                    card
                    dark
                    color="primary">
                    <v-btn
                        icon
                        dark
                        @click="dialog = false">
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>
                        Análise de conteúdo - Produto:
                        {{ produto.dsProduto }}
                    </v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-items>
                        <v-btn
                            dark
                            flat
                            @click="dialog = false">Save</v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-text>
                    <form
                        ref="form"
                        v-model="valid"
                        lazy-validation
                    >
                        <v-layout wrap>
                            <div
                                v-if="editorParecerRules.show"
                                class="text-xs-left">
                                <p
                                    :class="editorParecerRules.color"
                                >
                                    {{ editorParecerRules.msg }}
                                </p>
                            </div>
                            <v-flex
                                xs12
                                sm12
                                md12>
                                <p><b>Parecer de Conteúdo do Produto</b></p>
                                <salic-editor-texto
                                    v-model="analiseConteudoEmEdicao.ParecerDeConteudo"
                                    :placeholder="'Parecer técnico sobre o conteúdo do produto'"
                                />
                            </v-flex>
                            <v-flex
                                xs12
                                sm12
                                md12>
                                <v-switch
                                    :label="`Parecer Favorável?: ${labelSimOuNao(analiseConteudoEmEdicao.ParecerFavoravel)}`"
                                    v-model="analiseConteudoEmEdicao.ParecerFavoravel"/>
                            </v-flex>
                        </v-layout>
                        <v-subheader class="pa-0">
                            Todos os campos s&atilde;o obrigat&oacute;rios
                        </v-subheader>

                        <v-layout
                            row
                            justify-center>
                            <v-btn
                                :loading="loadingButton"
                                @click="submit">
                                <v-icon>save</v-icon>
                                Salvar
                            </v-btn>
                            <v-btn
                                @click="dialog = false">
                                Fechar
                                <v-icon>close</v-icon>
                            </v-btn>
                        </v-layout>
                    </form>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-card>
</template>

<script>
import Proposta from '@/modules/proposta/visualizar/Proposta';
import SalicEditorTexto from '@/components/SalicEditorTexto';

import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'AnaliseDeConteudo',
    components: { Proposta, SalicEditorTexto },
    props: {
        produto: {
            type: Object,
            default: () => {},
        },
        active: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            loadingButton: false,
            valid: false,
            dialog: false,
            overlay: true,
            direction: 'top',
            fab: false,
            fling: false,
            hover: false,
            tabs: null,
            editorParecerRules: {
                show: false,
                color: '',
                backgroundColor: '',
                msg: '',
                enable: false,
            },
            analiseConteudoEmEdicao: {
                idAnaliseDeConteudo: null,
                IdPRONAC: this.produto.IdPRONAC,
                idProduto: this.idProduto,
                ParecerFavoravel: true,
                ParecerDeConteudo: '',
            },
            rules: {
                // required: v => !!v || 'Campo obrigatório',
                // avaliacao: v => v !== '4' || 'Avaliação deve ser aprovado ou reprovado',
                parecer: v => (!!v || this.$refs.stItemAvaliado.value !== '3') || 'Parecer é obrigatório',
            },
        };
    },
    computed: {
        ...mapGetters({
            analiseConteudo: 'parecer/getAnaliseConteudo',
        }),
    },
    watch: {
        produto(val) {
            this.obterAnaLiseConteudo({
                id: val.idProduto,
                idPronac: val.IdPRONAC,
            });
        },
        analiseConteudo(val) {
            this.analiseConteudoEmEdicao = Object.assign({}, val);
        },
    },
    methods: {
        ...mapActions({
            obterAnaLiseConteudo: 'parecer/obterAnaLiseConteudo',
            salvarAnaliseConteudo: 'parecer/salvarAnaLiseConteudo',
        }),
        submit() {
            // if (!this.$refs.form.validate()) {
            //     return false;
            // }

            const analise = Object.assign(
                {},
                this.analiseConteudoEmEdicao,
                {
                    stPrincipal: this.produto.stPrincipal,
                },
            );

            this.loadingButton = true;
            this.salvarAnaliseConteudo(analise).then((response) => {
                this.loadingButton = false;
            }).catch((e) => {
                this.loadingButton = false;
            });

            return true;
        },
        validarEditor(e) {
            if (e < 10) {
                this.editorParecerRules = {
                    show: true,
                    color: 'red--text',
                    backgroundColor: { 'background-color': '#FFCDD2' },
                    msg: 'O Parecer deve conter mais que 10 caracteres',
                    enable: false,
                };
            }
            if (e < 1) {
                this.editorParecerRules = {
                    show: true,
                    color: 'red--text',
                    backgroundColor: { 'background-color': '#FFCDD2' },
                    msg: 'O Laudo é obrigatório!',
                    enable: false,
                };
            }
            if (e >= 10) {
                this.editorParecerRules = {
                    show: false,
                    color: '',
                    backgroundColor: '',
                    msg: '',
                    enable: true,
                };
                return true;
            }
            return false;
        },
        labelSimOuNao(val) {
            return val ? 'Sim' : 'Não';
        },
    },
};
</script>

<style>
    /* This is for documentation purposes and will not be needed in your application */
    #create {
        position: relative;
    }

    #create .v-btn--floating {
        position: fixed;
        top: 230px;
        right: 5%;
    }

     #parecer-conteudo {
         height: 400px;
     }
</style>
