<template>
    <div id="conteudo">
        <div v-if="loading">
            <Carregando :text="'Carregando Plano de Distribuicao'"></Carregando>
        </div>
        <div v-else>
            <IdentificacaoProjeto
                :pronac="dadosProjeto.Pronac"
                :nomeProjeto="dadosProjeto.NomeProjeto">
            </IdentificacaoProjeto>
            <PropostaPlanoDistribuicao
                    :arrayProdutos="dadosIn2017.planodistribuicaoproduto"
                    :arrayDetalhamentos="dadosIn2017.tbdetalhaplanodistribuicao">
            </PropostaPlanoDistribuicao>
        </div>
    </div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
    import Carregando from '@/components/Carregando';
    import PropostaPlanoDistribuicao from '@/modules/proposta/visualizar/components/PropostaPlanoDistribuicao';
    import IdentificacaoProjeto from './IdentificacaoProjeto';

    export default {
        name: 'PlanoDistribuicaoIn2017',
        props: ['idPronac'],
        data() {
            return {
                loading: true,
            };
        },
        components: {
            Carregando,
            IdentificacaoProjeto,
            PropostaPlanoDistribuicao,
        },
        mounted() {
            if (typeof this.dadosProjeto.idPreProjeto !== 'undefined') {
                this.buscarPlanoDistribuicaoIn2017(this.dadosProjeto.idPreProjeto);
            }
        },
        watch: {
            dadosIn2017() {
                this.loading = false;
            },
        },
        computed: {
            ...mapGetters({
                dadosProjeto: 'projeto/projeto',
                dadosIn2017: 'projeto/planoDistribuicaoIn2017',
            }),
        },
        methods: {
            ...mapActions({
                buscarPlanoDistribuicaoIn2017: 'projeto/buscarPlanoDistribuicaoIn2017',
            }),
        },
    };
</script>
