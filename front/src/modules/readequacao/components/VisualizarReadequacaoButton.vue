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
                        xs5
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
                                    <v-list-tile-sub-title>Beltrano Ciclano - {{ dadosReadequacao.idSolicitante }} </v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile
                                avatar
                                @click="visualizarSolicitacao = !visualizarSolicitacao"
                            >
                                <VisualizarCampoDetalhado
                                    v-if="visualizarSolicitacao"
                                    :dialog="true"
                                    :dados="{ titulo: 'Solicitação', descricao: dadosReadequacao.dsSolicitacao }"
                                    @fechar-visualizacao="visualizarSolicitacao = false"
                                />
                                <v-list-tile-avatar>
                                    <v-icon class="green lighten-1 white--text">mode_comment</v-icon>
                                </v-list-tile-avatar>
                                <v-list-tile-content>
                                    <v-list-tile-title>Solicitação</v-list-tile-title>
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
                    </v-flex>
                    <v-flex xs5>
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
                                    <v-list-tile-title>Data da Solicitação</v-list-tile-title>
                                    <v-list-tile-sub-title>{{ dadosReadequacao.dtSolicitacao | formatarData }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                            </v-list-tile>
                            <v-list-tile
                                avatar
                                @click="visualizarJustificativa = !visualizarJustificativa"
                            >
                                <VisualizarCampoDetalhado
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
                                </v-list-tile-avatar>:
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
import { utils } from '@/mixins/utils';
import VisualizarCampoDetalhado from './VisualizarCampoDetalhado';

export default {
    name: 'VisualizarReadequacaoButton',
    components: {
        VisualizarCampoDetalhado,
    },
    mixins: [utils],
    props: {
        obj: { type: Object, default: () => {} },
        dadosProjeto: { type: Object, default: () => {} },
        dadosReadequacao: { type: Object, default: () => {} },
        perfisAceitos: { type: Array, default: () => [] },
        perfil: { type: Number, default: 0 },
    },
    data() {
        return {
            dialog: false,
            panel: [true, true],
            visualizarSolicitacao: false,
            visualizarJustificativa: false,
        };
    },
    computed: {
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
    },
    methods: {
        perfilAceito() {
            if (!_.isEmpty(this.perfisAceitos)) {
                if (this.perfisAceitos.includes(this.perfil)) {
                    return true;
                }
                return false;
            }
            return true;
        },
    },
};
</script>
