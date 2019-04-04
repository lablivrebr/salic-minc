<template>
    <v-layout>
        <v-btn
            dark
            icon
            flat
            small
            color="green"
            @click.stop="abrirEdicao()"
        >
            <v-tooltip bottom>
                <v-icon slot="activator">edit</v-icon>
                <span>Editar Readequação</span>
            </v-tooltip>
        </v-btn>
        <template
            v-if="!getTemplateParaTipo"
        >
            <TemplateRedirect
                :dados-readequacao="dadosReadequacao"
                :campo="campoAtual"
                :redirecionar="redirecionar"
            />
        </template>
        <v-dialog
            :persistent="mensagem.ativa"
            v-model="dialog"
            fullscreen
            hide-overlay
            transition="dialog-bottom-transition"
            @keydown.esc="dialog = false"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                    fixed
                >
                    <v-btn
                        icon
                        dark
                        @click="dialog = false"
                    >
                        <v-icon>close</v-icon>
                    </v-btn>
                    <v-toolbar-title>Readequação - {{ dadosReadequacao.dsTipoReadequacao }}</v-toolbar-title>
                    <v-spacer/>
                    <v-toolbar-title>{{ dadosProjeto.Pronac }} - {{ dadosProjeto.NomeProjeto }}</v-toolbar-title>
                </v-toolbar>
                <v-layout
                    row
                    wrap
                    class="mt-5"
                >
                    <v-flex
                        v-if="loading"
                        xs10
                        offset-xs1
                    >
                        <Carregando
                            :text="'Montando edição de readequação...'"
                            class="mt-5"
                        />
                    </v-flex>
                    <v-flex
                        v-else
                        xs10
                        offset-xs1
                    >
                        <v-expansion-panel
                            v-model="panel"
                            expand
                            popout
                        >
                            <v-expansion-panel-content
                                readonly
                                hide-actions
                            >
                                <v-card
                                    v-if="getTemplateParaTipo"
                                >
                                    <component
                                        :is="getTemplateParaTipo"
                                        :dados-readequacao="dadosReadequacao"
                                        :campo="getDadosCampo"
                                        @dados-update="atualizarCampo($event, 'dsSolicitacao')"
                                        @editor-texto-counter="validarFormulario()"
                                    />
                                </v-card>
                            </v-expansion-panel-content>
                            <v-expansion-panel-content
                                readonly
                                hide-actions
                            >

                                <v-card
                                    class="mb-5"
                                >
                                    <v-card-title
                                        class="green lighten-2 title"
                                    >
                                        Justificativa da readequação
                                    </v-card-title>
                                    <FormReadequacao
                                        :dados-readequacao="dadosReadequacao"
                                        @dados-update="atualizarCampo($event, 'dsJustificativa')"
                                        @editor-texto-counter="validarFormulario()"
                                    />
                                    <v-card-text>
                                        <v-layout row>
                                            <v-flex xs3>
                                                <UploadFile
                                                :arquivo-inicial="this.getDocumentoReadequacao"
                                                :formatos-aceitos="formatosAceitos"
                                                class="mt-1"
                                                @arquivo-anexado="atualizarArquivo($event)"
                                                @arquivo-removido="removerArquivo($event)"
                                                />
                                            </v-flex>
                                            <v-flex xs1>
                                                <template v-if="possuiDocumentoAnexado">
                                                    <v-btn
                                                        flat
                                                        icon
                                                        small
                                                        class="green darken-1"
                                                        color="white"
                                                        @click="abrirArquivo()"
                                                    >
                                                        <v-icon small>attach_file</v-icon>
                                                    </v-btn>
                                                    <v-spacer/>
                                                    <v-btn
                                                        flat
                                                        icon
                                                        small
                                                        class="red"
                                                        color="white"
                                                        @click="removerArquivo()"
                                                    >
                                                        <v-icon small>
                                                            delete
                                                        </v-icon>
                                                    </v-btn>
                                                </template>
                                            </v-flex>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                        <v-footer
                            id="footer"
                            class="pb-4 pt-4 elevation-18"
                            fixed
                        >
                            <v-flex xs11>
                                <v-layout
                                    row
                                    wrap
                                    justify-end
                                >
                                    <v-btn
                                        color="green darken-1"
                                        dark
                                        @click="salvarReadequacao()"
                                    >Salvar
                                        <v-icon
                                            right
                                            dark
                                        >done</v-icon>
                                    </v-btn>
                                    <FinalizarButton
                                        :disabled="!validacao"
                                        :dados-readequacao="dadosReadequacao"
                                        :dados-projeto="dadosProjeto"
                                        :tela-edicao="true"
                                        color="green darken-1"
                                        class="mr-2"
                                        dark
                                        @readequacao-finalizada="readequacaoFinalizada()"
                                    />
                                </v-layout>
                            </v-flex>
                        </v-footer>
                    </v-flex>
                </v-layout>
                <v-snackbar
                    :color="mensagem.cor"
                    :timeout="mensagem.timeout"
                    v-model="mensagem.ativa"
                    bottom
                ><span>{{ mensagem.conteudo }}</span>
                    <v-btn
                        dark
                        flat
                        @click="mensagem.ativa = false"
                    >
                        Fechar
                    </v-btn>
                </v-snackbar>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import _ from 'lodash';
import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';
import FormReadequacao from './FormReadequacao';
import TemplateTextarea from './TemplateTextarea';
import TemplateInput from './TemplateInput';
import TemplateDate from './TemplateDate';
import TemplateRedirect from './TemplateRedirect'
import FinalizarButton from '../components/FinalizarButton';
import UploadFile from './UploadFile';

export default {
    name: 'EditarReadequacaoButton',
    components: {
        Carregando,
        FinalizarButton,
        FormReadequacao,
        TemplateTextarea,
        TemplateInput,
        TemplateDate,
        TemplateRedirect,
        UploadFile,
    },
    props: {
        dadosReadequacao: { type: Object, default: () => {} },
        dadosProjeto: { type: Object, default: () => {} },
        bindClick: { type: Number, default: 0 },
    },
    data() {
        return {
            dialog: false,
            tiposComponentesReadequacao: {
                textarea: 'TemplateTextarea',
                input: 'TemplateInput',
                date: 'TemplateDate',
            },
            templateEdicao: [],
            formatosAceitos: 'application/pdf',
            panel: [true, true],
            readequacaoEditada: {
                idReadequacao: 0,
                dsSolicitacao: '',
                dsJustificativa: '',
                dtSolicitacao: '',
                documento: {},
                idDocumento: '',
                dsAvaliacao: '',
            },
            redirecionar: false,
            mensagem: {
                ativa: false,
                timeout: 2300,
                conteudo: '',
                cor: '',
                finaliza: false,
            },
            recarregarReadequacoes: false,
            minChar: 3,
            validacao: false,
            validacaoOk: false,
            campos: ['dsSolicitacao', 'dsJustificativa'],
            loading: true,
        };
    },
    computed: {
        ...mapGetters({
            campoAtual: 'readequacao/getCampoAtual',
            getDocumentoReadequacao: 'readequacao/getDocumentoReadequacao',
        }),
        getTemplateParaTipo() {
            let templateName = false;
            const chave = `key_${this.dadosReadequacao.idTipoReadequacao}`;
            if (Object.prototype.hasOwnProperty.call(this.campoAtual, chave)) {
                templateName = this.tiposComponentesReadequacao[this.campoAtual[chave].tpCampo];
            }
            return templateName;
        },
        getDadosCampo() {
            const chave = `key_${this.dadosReadequacao.idTipoReadequacao}`;
            if (Object.prototype.hasOwnProperty.call(this.campoAtual, chave)) {
                return {
                    valor: this.campoAtual[chave].dsCampo,
                    titulo: this.campoAtual[chave].descricao,
                    tpCampo: this.campoAtual[chave].tpCampo,
                };
            }
            return {};
        },
        possuiDocumentoAnexado() {
            if (this.dadosReadequacao.idDocumento
                && this.dadosReadequacao.idDocumento !== ''
               ) {
                return true;
            }
            return false;
        },
    },
    watch: {
        getDadosCampo() {
            if (!_.isEmpty(this.getDadosCampo)) {
                this.loading = false;
            }
        },
        bindClick() {
            if (this.bindClick === this.dadosReadequacao.idReadequacao) {
                this.dialog = true;
            }
        },
        mensagem: {
            handler(mensagem) {
                if (mensagem.ativa === false
                    && mensagem.finaliza === true
                   ) {
                    this.dialog = false;
                }
            },
            deep: true,
        },
        dadosReadequacao: {
            handler(value) {
                if (value.idPronac && value.idTipoReadequacao) {
                    this.obterDadosIniciais();
                    if (this.bindClick === this.dadosReadequacao.idReadequacao) {
                        this.dialog = true;
                    }
                }
            },
            deep: true,
        },
        dialog() {
            if (this.dialog === false
                && this.recarregarReadequacoes === true
               ) {
                this.$emit('atualizar-readequacao', { idReadequacao: this.readequacaoEditada.idReadequacao });
            }
        },
    },
    created() {
        this.obterDadosIniciais();
    },
    methods: {
        ...mapActions({
            obterCampoAtual: 'readequacao/obterCampoAtual',
            updateReadequacao: 'readequacao/updateReadequacao',
            obterDocumento: 'readequacao/obterDocumento',
            finalizarReadequacao: 'readequacao/finalizarReadequacao',
        }),
        obterDadosIniciais() {
            if (
                this.dadosReadequacao.idPronac && this.dadosReadequacao.idTipoReadequacao
            ) {
                this.obterCampoAtual({
                    idPronac: this.dadosReadequacao.idPronac,
                    idTipoReadequacao: this.dadosReadequacao.idTipoReadequacao,
                }).then(() => {
                    this.inicializarReadequacaoEditada();
                });
            }
        },
        abrirEdicao() {
            if (typeof this.getTemplateParaTipo === 'undefined') {
                this.redirecionar = true;
            } else {
                this.dialog = true;
            }
            this.validarFormulario();
        },
        salvarReadequacao() {
            this.updateReadequacao(this.readequacaoEditada).then(() => {
                this.mensagem.conteudo = 'Readequação salva com sucesso!';
                this.mensagem.timeout = 2300;
                this.mensagem.ativa = true;
                this.mensagem.finaliza = true;
                this.mensagem.cor = 'green darken-1';
                this.recarregarReadequacoes = true;
            });
        },
        inicializarReadequacaoEditada() {
            this.readequacaoEditada = {
                idPronac: this.dadosReadequacao.idPronac,
                idReadequacao: this.dadosReadequacao.idReadequacao,
                idTipoReadequacao: this.dadosReadequacao.idTipoReadequacao,
                dsAvaliacao: this.dadosReadequacao.dsAvaliacao,
                idDocumento: this.dadosReadequacao.idDocumento || '',
                dsSolicitacao: this.dadosReadequacao.dsSolicitacao,
                dsJustificativa: this.dadosReadequacao.dsJustificativa,
                documento: {},
            };
            this.obterArquivoReadequacao(this.readequacaoEditada.idDocumento);
        },
        obterArquivoReadequacao(id) {
            if(id) {
                this.obterDocumento(id);
                if(typeof this.dadosReadequacao.documento !== 'undefined'){
                    return this.dadosReadequacao.documento;
                }
            }
            else {
                return {};
            }
        },
        atualizarArquivo(arquivo) {
            this.readequacaoEditada.documento = arquivo;
            this.updateReadequacao(this.readequacaoEditada).then(() => {
                this.mensagem.conteudo = 'Arquivo enviado!';
                this.mensagem.ativa = true;
                this.mensagem.finaliza = false;
                this.mensagem.cor = 'green darken-1';
                this.recarregarReadequacoes = true;
            });
        },
        removerArquivo() {
            this.readequacaoEditada.documento = '';
            this.readequacaoEditada.idDocumento = '';
            this.updateReadequacao(this.readequacaoEditada).then(() => {
                this.mensagem.conteudo = 'Arquivo removido!';
                this.mensagem.ativa = true;
                this.mensagem.finaliza = false;
                this.mensagem.cor = 'green darken-1';
                this.recarregarReadequacoes = true;
            });
        },
        abrirArquivo() {
            const urlArquivo = `/readequacao/readequacoes/abrir-documento-readequacao?id=${this.dadosReadequacao.idDocumento}`;
            window.location.href = urlArquivo;
        },
        atualizarCampo(valor, campo) {
            if (this.campos.includes(campo)) {
                this.readequacaoEditada[campo] = valor;
            }
            this.validarFormulario();
        },
        validarFormulario() {
            this.validacao = true;
            if (this.readequacaoEditada.dsJustificativa.trim().length < this.minChar
                || this.readequacaoEditada.dsSolicitacao.trim().length < this.minChar) {
                this.validacao = false;
            }
        },
        readequacaoFinalizada() {
            this.dialog = false;
        },
    },
};
</script>
<style>

#footer {
    z-index: 10;
}
</style>
