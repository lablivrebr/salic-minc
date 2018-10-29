<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Dados Complementares do Projeto'"></Carregando>
        </div>
        <div v-else-if="dados.Proposta && dados.CustosVinculados">
            <IdentificacaoProjeto :pronac="dadosProjeto.Pronac"
                                  :nomeProjeto="dadosProjeto.NomeProjeto">
            </IdentificacaoProjeto>
            <TabelaDadosComplementares dadoComplementar="Objetivos"
                                       :dsDadoComplementar="dados.Proposta.Objetivos">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Justificativa"
                                       :dsDadoComplementar="dados.Proposta.Justificativa">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Custos Vinculados"
                                       :custosVinculados="dados.CustosVinculados">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Acessibilidade"
                                       :dsDadoComplementar="dados.Proposta.Acessibilidade">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Democratiza&ccedil;&atilde;o de Acesso"
                                       :dsDadoComplementar="dados.Proposta.DemocratizacaoDeAcesso">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Etapa de Trabalho"
                                       :dsDadoComplementar="dados.Proposta.EtapaDeTrabalho">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Ficha T&eacute;cnica"
                                       :dsDadoComplementar="dados.Proposta.FichaTecnica">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Sinopse da Obra"
                                       :dsDadoComplementar="dados.Proposta.Sinopse">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Impacto Ambiental"
                                       :dsDadoComplementar="dados.Proposta.ImpactoAmbiental">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Especifica&ccedil;&otilde;es t&eacute;cnicas do produto"
                                       :dsDadoComplementar="dados.Proposta.EspecificacaoTecnica">
            </TabelaDadosComplementares>
            <TabelaDadosComplementares dadoComplementar="Outras Informa&ccedil;&otilde;es"
                                       :dsDadoComplementar="dados.Proposta.OutrasInformacoes">
            </TabelaDadosComplementares>

        </div>
    </div>
</template>
<script>
    import { mapActions, mapGetters } from 'vuex';
    import Carregando from '@/components/Carregando';
    import IdentificacaoProjeto from './IdentificacaoProjeto';
    import TabelaDadosComplementares from './TabelaDadosComplementares';

    export default {
        name: 'DadosComplementares',
        data() {
            return {
                loading: true,
            };
        },
        components: {
            Carregando,
            IdentificacaoProjeto,
            TabelaDadosComplementares,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPronac !== 'undefined') {
                this.buscarDadosComplementares(this.dadosProjeto.idPronac);
            }
        },
        watch: {
            dados() {
                this.loading = false;
            }
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dados: 'projeto/dadosComplementares',
            }),
        },
        methods: {
            ...mapActions({
                buscarDadosComplementares: 'projeto/buscarDadosComplementares',
            }),
        },
    };
</script>

