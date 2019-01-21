<template>
    <div
        v-if="dados"
        class="proposta">
        <Carregando
            v-if="loading"
            :text="'Carregando proposta'"/>
        <v-expansion-panel
            v-show="!loading"
            focusable>
            <v-expansion-panel-content>
                <div slot="header">
                    <i class="material-icons">assignment</i>
                    <span v-if="dados.PRONAC">
                        Projeto - {{ dados.PRONAC }} - {{ dados.NomeProjeto }}
                    </span>
                    <span v-else>
                        Proposta - {{ idpreprojeto }} - {{ dados.NomeProjeto }}
                    </span>
                </div>
                <PropostaIdentificacao
                    :idpreprojeto="idpreprojeto"
                    :proposta="dados"/>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">history</i>
                    Hist&oacute;rico de avalia&ccedil;&otilde;es
                </div>
                <div class="pa-3">
                    <PropostaHistoricoAvaliacoes
                        :idpreprojeto="dados.idPreProjeto"
                        :proposta="dados"
                    />
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">history</i>
                    Hist&oacute;rico de sugest&otilde;es de enquadramento
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <PropostaHistoricoSugestoesEnquadramento
                        :idpreprojeto="dados.idPreProjeto"
                    />
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">history</i>
                    Hist&oacute;rico de solicita&ccedil;&otilde;es
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <PropostaHistoricoSolicitacoes
                        :idpreprojeto="dados.idPreProjeto"
                    />
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header">
                    <i class="material-icons">person</i>
                    Proponente
                </div>
                <div class="pa-3">
                    <AgenteProponente :idagente="dados.idAgente"/>
                    <AgenteUsuario :idusuario="dados.idUsuario"/>
                </div>
            </v-expansion-panel-content>

            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Ficha t&eacute;cnica
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.FichaTecnica"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Resumo
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.ResumoDoProjeto"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Objetivos
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.Objetivos"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Etapa de Trabalho
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.EtapaDeTrabalho"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Acessibilidade
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.Acessibilidade"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Especifica&ccedil;&otilde;es t&eacute;cnicas do produto
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.EspecificacaoTecnica"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Sinopse de Obra
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.Sinopse"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Democratiza&ccedil;&atilde;o de Acesso
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.DemocratizacaoDeAcesso"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Justificativa
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.Justificativa"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">subject</i>
                    Descri&ccedil;&atilde;o de Atividades
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <div class="card padding20">
                        <SalicTextoSimples :texto="dados.DescricaoAtividade"/>
                    </div>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">attachment</i>
                    Documentos anexados
                </div>
                <div class="pa-3">
                    <PropostaDocumentos :proposta="dados"/>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div
                    id="plano-distribuicao"
                    slot="header"><i class="material-icons">equalizer</i>Plano
                    Distribui&ccedil;&atilde;o
                </div>
                <div class="pa-3">
                    <PropostaPlanoDistribuicao
                        :array-produtos="dados.planodistribuicaoproduto"
                        :array-detalhamentos="dados.tbdetalhaplanodistribuicao"
                    />
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">history</i>
                    Fonte de Recurso
                </div>
                <div class="pa-3">
                    <PropostaFontesDeRecursos
                        :idpreprojeto="idpreprojeto"/>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">place</i>
                    Local de realiza&ccedil;&atilde;o/Deslocamento
                </div>
                <div class="pa-3">
                    <PropostaLocalRealizacaoDeslocamento :proposta="dados"/>
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div slot="header"><i class="material-icons">attach_money</i>
                    Custos Vinculados
                </div>
                <div
                    v-if="dados"
                    class="pa-3">
                    <PropostaCustosVinculados
                        :array-custos="dados.tbcustosvinculados"
                    />
                </div>
            </v-expansion-panel-content>
            <v-expansion-panel-content>
                <div
                    id="planilha-orcamentaria"
                    slot="header"><i class="material-icons">attach_money</i>
                    Planilha or&ccedil;ament&aacute;ria
                </div>
                <div class="pa-3">
                    <Planilha
                        :array-planilha="dados.tbplanilhaproposta"
                    />
                </div>
            </v-expansion-panel-content>
        </v-expansion-panel>
    </div>
    <div
        v-else
        class="center-align">
        <div class="padding20 card-panel">Opa! Proposta n&atilde;o encontrada...</div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Planilha from '@/components/Planilha/Planilha';
import Carregando from '@/components/CarregandoVuetify';
import SalicTextoSimples from '@/components/SalicTextoSimples';
import PropostaIdentificacao from './components/PropostaIdentificacao';
import PropostaHistoricoAvaliacoes from './components/PropostaHistoricoAvaliacoes';
import PropostaHistoricoSugestoesEnquadramento from './components/PropostaHistoricoSugestoesEnquadramento';
import PropostaHistoricoSolicitacoes from './components/PropostaHistoricoSolicitacoes';
import AgenteProponente from '../components/AgenteProponente';
import AgenteUsuario from '../components/AgenteUsuario';
import PropostaDocumentos from './components/PropostaDocumentos';
import PropostaPlanoDistribuicao from './components/PropostaPlanoDistribuicao';
import PropostaFontesDeRecursos from './components/PropostaFontesDeRecursos';
import PropostaLocalRealizacaoDeslocamento from './components/PropostaLocalRealizacaoDeslocamento';
import PropostaCustosVinculados from './components/PropostaCustosVinculados';

export default {
    name: 'Proposta',
    components: {
        PropostaIdentificacao,
        PropostaHistoricoAvaliacoes,
        PropostaHistoricoSugestoesEnquadramento,
        PropostaHistoricoSolicitacoes,
        AgenteProponente,
        AgenteUsuario,
        SalicTextoSimples,
        Carregando,
        PropostaDocumentos,
        PropostaPlanoDistribuicao,
        PropostaFontesDeRecursos,
        PropostaLocalRealizacaoDeslocamento,
        PropostaCustosVinculados,
        Planilha,
    },
    props: {
        idpreprojeto: {
            type: [String, Number],
            default: '',
        },
        proposta: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            dados: {
                type: Object,
                default() {
                    return {};
                },
            },
            loading: true,
        };
    },
    computed: {
        ...mapGetters({
            dadosProposta: 'proposta/proposta',
        }),
    },
    watch: {
        dadosProposta(value) {
            this.dados = value;
            this.loading = false;
        },
        idpreprojeto(value) {
            if (value !== '') {
                this.buscarDadosProposta(value);
            }
        },
    },
    mounted() {
        if (this.idpreprojeto !== ''
            && typeof this.proposta === 'undefined') {
            this.buscarDadosProposta(this.idpreprojeto);
            this.dados = this.dadosProposta;
        }

        if (typeof this.proposta !== 'undefined') {
            this.dados = this.proposta;
            this.loading = false;
        }

        this.iniciarCollapsible();
    },
    methods: {
        ...mapActions({
            buscarDadosProposta: 'proposta/buscarDadosProposta',
        }),
        iniciarCollapsible() {
            // eslint-disable-next-line
            // $3('.collapsible').each(function () {
            //     // eslint-disable-next-line
            //     $3(this).collapsible();
            // });
        },
    },
};
</script>
