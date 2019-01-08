<template>
    <div>
        <div v-if="loading">
            <Carregando :text="'Carregando Dados Complementares do Projeto'"/>
        </div>
        <div v-else-if="dados.Proposta && dados.CustosVinculados">
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.Objetivos"
                dado-complementar="Objetivos"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.Justificativa"
                dado-complementar="Justificativa"/>
            <TabelaDadosComplementares
                :custos-vinculados="dados.CustosVinculados"
                dado-complementar="Custos Vinculados"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.Acessibilidade"
                dado-complementar="Acessibilidade"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.DemocratizacaoDeAcesso"
                dado-complementar="Democratiza&ccedil;&atilde;o de Acesso"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.EtapaDeTrabalho"
                dado-complementar="Etapa de Trabalho"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.FichaTecnica"
                dado-complementar="Ficha T&eacute;cnica"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.Sinopse"
                dado-complementar="Sinopse da Obra"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.ImpactoAmbiental"
                dado-complementar="Impacto Ambiental"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.EspecificacaoTecnica"
                dado-complementar="Especifica&ccedil;&otilde;es t&eacute;cnicas do produto"/>
            <TabelaDadosComplementares
                :ds-dado-complementar="dados.Proposta.OutrasInformacoes"
                dado-complementar="Outras Informa&ccedil;&otilde;es"/>

        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import Carregando from '@/components/CarregandoVuetify';
import TabelaDadosComplementares from './TabelaDadosComplementares';

export default {
    name: 'DadosComplementares',
    components: {
        Carregando,
        TabelaDadosComplementares,
    },
    data() {
        return {
            loading: true,
        };
    },
    watch: {
        dados() {
            this.loading = false;
        },
    },
    mounted() {
        if (typeof this.dadosProjeto.idPronac !== 'undefined') {
            this.buscarDadosComplementares(this.dadosProjeto.idPronac);
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

