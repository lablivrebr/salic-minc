<template>
    <v-container fluid>
        <v-layout column>
            <v-flex xs9>
                <v-subheader>
                    <h2 class="grey--text text--darken-4">Painel de Readequações</h2>
                    <v-spacer/>
                    <h3
                        class="grey--text text--darken-4"
                    >{{ dadosProjeto.Pronac }} - {{ dadosProjeto.NomeProjeto }}</h3>
                </v-subheader>
                <div v-if="loading">
                    <Carregando :text="'Carregando readequações...'"/>
                </div>
                <div v-else-if="getReadequacoesProponente">
                    <v-tabs
                        color="#0a420e"
                        centered
                        dark
                        icons-and-text
                    >
                        <v-tabs-slider color="yellow"/>
                        <v-tab href="#tab-1">Edição
                            <v-icon>edit</v-icon>
                        </v-tab>
                        <v-tab href="#tab-2">Em Análise
                            <v-icon>gavel</v-icon>
                        </v-tab>
                        <v-tab href="#tab-3">Finalizadas
                            <v-icon>check</v-icon>
                        </v-tab>
                        <v-tab-item :value="'tab-1'">
                            <v-card>
                                <TabelaReadequacoes
                                    :dados-readequacao="getReadequacoesProponente"
                                    :componentes="acoesProponente"
                                    :dados-projeto="dadosProjeto"
                                    :item-em-edicao="itemEmEdicao"
                                    :min-char="minChar"
                                    @excluir-readequacao="excluirReadequacao"
                                    @atualizar-readequacao="atualizarReadequacao"
                                />
                            </v-card>
                        </v-tab-item>
                        <v-tab-item :value="'tab-2'">
                            <v-card>
                                <TabelaReadequacoes
                                    :dados-readequacao="getReadequacoesAnalise"
                                    :componentes="acoesAnalise"
                                    :dados-projeto="dadosProjeto"
                                    :perfis-aceitos="getPerfis('analise')"
                                    :perfil="getUsuario.grupo_ativo"
                                />
                            </v-card>
                        </v-tab-item>
                        <v-tab-item :value="'tab-3'">
                            <v-card>
                                <TabelaReadequacoes
                                    :dados-readequacao="getReadequacoesFinalizadas"
                                    :componentes="acoesFinalizadas"
                                    :dados-projeto="dadosProjeto"
                                />
                            </v-card>
                        </v-tab-item>
                    </v-tabs>
                    <CriarReadequacao
                        :id-pronac="dadosProjeto.idPronac"
                        @criar-readequacao="criarReadequacao($event)"
                    />
                </div>
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
    </v-container>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import Const from '../const';
import TabelaReadequacoes from '../components/TabelaReadequacoes';
import ExcluirButton from '../components/ExcluirButton';
import FinalizarButton from '../components/FinalizarButton';
import EditarReadequacaoButton from '../components/EditarReadequacaoButton';
import VisualizarReadequacaoButton from '../components/VisualizarReadequacaoButton';
import Carregando from '@/components/CarregandoVuetify';
import CriarReadequacao from '../components/CriarReadequacao';

export default {
    name: 'PainelReadequacoesView',
    components: {
        Carregando,
        TabelaReadequacoes,
        ExcluirButton,
        EditarReadequacaoButton,
        VisualizarReadequacaoButton,
        FinalizarButton,
        CriarReadequacao,
    },
    data() {
        return {
            listaStatus: ['proponente', 'analise', 'finalizadas'],
            acoesProponente: {
                usuario: '',
                acoes: [ExcluirButton, EditarReadequacaoButton, VisualizarReadequacaoButton, FinalizarButton],
            },
            acoesAnalise: {
                acoes: [VisualizarReadequacaoButton],
            },
            acoesFinalizadas: {
                acoes: [VisualizarReadequacaoButton],
            },
            itemEmEdicao: 0,
            loading: true,
            mensagem: {
                ativa: false,
                timeout: 2300,
                conteudo: '',
                cor: '',
            },
            perfisAceitos: {
                proponente: [
                    Const.PERFIL_PROPONENTE,
                ],
                analise: [
                    Const.PERFIL_TECNICO_ACOMPANHAMENTO,
                    Const.PERFIL_COORDENADOR_ACOMPANHAMENTO,
                    Const.PERFIL_COORDENADOR_GERAL_ACOMPANHAMENTO,
                    Const.PERFIL_DIRETOR,
                    Const.PERFIL_SECRETARIO,
                ],
            },
            minChar: {
                solicitacao: 3,
                justificativa: 10,
            },
        };
    },
    computed: {
        ...mapGetters({
            getUsuario: 'autenticacao/getUsuario',
            getReadequacoesProponente: 'readequacao/getReadequacoesProponente',
            getReadequacoesAnalise: 'readequacao/getReadequacoesAnalise',
            getReadequacoesFinalizadas: 'readequacao/getReadequacoesFinalizadas',
            dadosProjeto: 'projeto/projeto',
        }),
    },
    watch: {
        getReadequacoesProponente(value) {
            if (typeof value === 'object') {
                if (Object.keys(value).length > 0) {
                    this.loading = false;
                }
            }
        },
    },
    created() {
        if (typeof this.$route.params.idPronac !== 'undefined') {
            this.idPronac = this.$route.params.idPronac;
            if (Object.keys(this.dadosProjeto).length === 0) {
                this.buscaProjeto(this.idPronac);
            }
        }
        this.listaStatus.forEach((stStatusAtual) => {
            this.obterReadequacoesPorStatus(stStatusAtual);
        });
    },
    methods: {
        ...mapActions({
            obterListaDeReadequacoes: 'readequacao/obterListaDeReadequacoes',
            buscaProjeto: 'projeto/buscaProjeto',
        }),
        obterReadequacoesPorStatus(stStatusAtual) {
            if (this.listaStatus.includes(stStatusAtual)) {
                this.obterListaDeReadequacoes({
                    idPronac: this.$route.params.idPronac,
                    stStatusAtual,
                });
            }
        },
        criarReadequacao(idReadequacao) {
            this.itemEmEdicao = idReadequacao;
        },
        excluirReadequacao() {
            this.mensagem.conteudo = 'Readequação excluída!';
            this.mensagem.ativa = true;
            this.mensagem.cor = 'green lighteen-1';
            this.timeout = 1300;
        },
        atualizarReadequacao() {
            this.itemEmEdicao = 0;
        },
        getPerfis(tipo) {
            return this.perfisAceitos[tipo];
        },
    },
};
</script>
