<template>
    <v-layout>
        <v-btn
            dark
            icon
            flat
            small
            color="#212121"
            @click.stop="dialog = true"
        >
            <v-tooltip bottom>
                <v-icon slot="activator">visibility</v-icon>
                <span>Visualizar Readequação</span>
            </v-tooltip>
        </v-btn>
        <v-dialog
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
                <v-divider/>
                <v-layout
                    row
                    wrap
                    class="mt-5 pt-3"
                >
                    <v-flex
                        xs10
                        offset-xs1
                    >
                        <v-list
                            two-line
                            subheader
                        >
                            <v-subheader inset>Dados da Solicitação</v-subheader>
                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">person</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Nome do Solicitante</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ dadosReadequacao.dsNomeSolicitante }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">date_range</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content class="ml-3">
                                    <v-list-tile-title>Data da Solicitação</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ dadosReadequacao.dtSolicitacao | formatarData }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                        <v-list
                            two-line
                        >
                            <v-list-tile
                                avatar
                                @click="visualizarJustificativa = !visualizarJustificativa"
                            >
                                <visualizar-campo-detalhado
                                    v-if="visualizarJustificativa"
                                    :dialog="true"
                                    :dados="{ titulo: 'Justificativa', descricao: dadosReadequacao.dsJustificativa }"
                                    @fechar-visualizacao="visualizarJustificativa = false"
                                />
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">assignment</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Justificativa</v-list-tile-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-btn
                                        icon
                                        ripple
                                    >
                                        <v-icon color="grey lighten-1">info</v-icon>
                                    </v-btn>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                        <v-list
                            v-if="dadosReadequacao.idDocumento"
                            two-line
                        >
                            <v-list-tile
                                avatar
                                @click="abrirArquivo(dadosReadequacao.idDocumento)"
                            >
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">insert_drive_file</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Documento anexo</v-list-tile-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                    <v-flex
                        xs10
                        offset-xs1
                    >
                        <v-card>
                            <v-card-title
                                class="subheading"
                            >
                                <v-btn
                                    fab
                                    depressed
                                    small
                                    class="green lighten-1"
                                >
                                    <v-icon color="white">mode_comment</v-icon>
                                </v-btn>
                                Solicitação
                            </v-card-title>
                            <v-card-text>
                                <campo-diff
                                    v-if="readequacaoTipoSimples() && dadosReadequacao.dsSolicitacao"
                                    :original-text="tratarCampoVazio(getDadosCampo.valor)"
                                    :changed-text="textoSolicitacao"
                                    :method="'diffSentences'"
                                />
                                <div
                                    v-else
                                >
                                    <h4 v-html="dadosReadequacao.dsTipoReadequacao"/>
                                    <span v-html="mensagemPadraoOutrasSolicitacoes"/>
                                </div>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex
                        v-if="existeAvaliacao"
                        xs5
                        offset-xs1
                    >
                        <v-list
                            two-line
                            subheader
                        >
                            <v-subheader inset>Dados da Avaliação</v-subheader>
                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">person</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Nome do Avaliador</v-list-tile-title>
                                    <v-list-tile-sub-title>Avaliador - {{ dadosReadequacao.idAvaliador }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">rate_review</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Avaliação</v-list-tile-title>
                                    <v-list-tile-sub-title v-html="dadosReadequacao.dsAvaliacao"/>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                    <v-flex
                        v-if="existeAvaliacao"
                        xs5
                    >
                        <v-list
                            two-line
                            subheader
                        >
                            <v-subheader inset/>
                            <v-list-tile avatar>
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">date_range</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Data da Avaliação</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ dadosReadequacao.dtAvaliador | formatarData }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                        </v-list>
                    </v-flex>
                </v-layout>
            </v-card>
        </v-dialog>
    </v-layout>
</template>
<script>
import _ from 'lodash';
import { mapGetters } from 'vuex';
import { utils } from '@/mixins/utils';
import Const from '../const';
import VisualizarCampoDetalhado from './VisualizarCampoDetalhado';
import CampoDiff from '@/components/CampoDiff';
import verificarPerfil from '../mixins/verificarPerfil';
import abrirArquivo from '../mixins/abrirArquivo';

export default {
    name: 'VisualizarReadequacaoButton',
    components: {
        VisualizarCampoDetalhado,
        CampoDiff,
    },
    mixins: [
        utils,
        verificarPerfil,
        abrirArquivo,
    ],
    props: {
        obj: {
            type: Object,
            default: () => {},
        },
        dadosProjeto: {
            type: Object,
            default: () => {},
        },
        dadosReadequacao: {
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
            panel: [true, true],
            visualizarSolicitacao: false,
            visualizarJustificativa: false,
            outrosTiposSolicitacoes: [
                Const.TIPO_READEQUACAO_REMANEJAMENTO_PARCIAL,
                Const.TIPO_READEQUACAO_PLANILHA_ORCAMENTARIA,
                Const.TIPO_READEQUACAO_LOCAL_REALIZACAO,
                Const.TIPO_READEQUACAO_PLANO_DISTRIBUICAO,
                Const.TIPO_READEQUACAO_SALDO_APLICACAO,
                Const.TIPO_READEQUACAO_TRANSFERENCIA_RECURSOS,
            ],
            mensagemPadraoOutrasSolicitacoes: 'Visualização indisponível para esse tipo de readequação.',
        };
    },
    computed: {
        ...mapGetters({
            campoAtual: 'readequacao/getCampoAtual',
        }),
        existeAvaliacao() {
            if (this.dadosReadequacao
                && this.perfilAceito()) {
                if (!_.isNull(this.dadosReadequacao.dsAvaliacao)
                    && !_.isNull(this.dadosReadequacao.dtAvaliador)) {
                    return true;
                }
            }
            return false;
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
        textoSolicitacao() {
            let result = '';
            if (this.dadosReadequacao.idTipoReadequacao === Const.TIPO_READEQUACAO_PERIODO_EXECUCAO
                && this.dadosReadequacao.dsSolicitacao.trim() !== '') {
                const [year, month, day] = this.dadosReadequacao.dsSolicitacao.substr(0, 10).split('-');
                result = `${day}/${month}/${year}`;
            } else {
                result = this.dadosReadequacao.dsSolicitacao;
            }
            return result;
        },
    },
    methods: {
        perfilAceito() {
            return this.verificarPerfil(this.perfil, this.perfisAceitos);
        },
        tratarCampoVazio(value) {
            if (typeof value !== 'undefined') {
                if (value.trim() === '') {
                    const msgVazio = '<em>Campo vazio</em>';
                    return msgVazio;
                }
            }
            return value;
        },
        readequacaoTipoSimples() {
            if (this.outrosTiposSolicitacoes.indexOf(this.dadosReadequacao.idTipoReadequacao) > -1) {
                return false;
            }
            return true;
        },
    },
};
</script>
