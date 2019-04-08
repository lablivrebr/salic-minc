<template>
    <div id="planilhaOrcamentariaMontada">
        <component :idPronac="idPronac" :objPlanilha="planilhaOrcamentaria" :perfil="perfil" :link="link" :idReadequacao="idReadequacao" :disabled="disabled" :disponivelParaAdicaoItensReadequacaoPlanilha="disponivelParaAdicaoItensReadequacaoPlanilha" :disponivelParaEdicaoReadequacaoPlanilha="disponivelParaEdicaoReadequacaoPlanilha"
            v-bind:is="componentePlanilha" v-on:atualizarSaldoEntrePlanilhas="atualizarSaldoEntrePlanilhas" v-on:atualizarPlanilha="montarPlanilhaOrcamentaria">
        </component>
    </div>
</template>

<script>
    import ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria from './ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria';
    import PlanilhaOrcamentariaAlterarItem from './PlanilhaOrcamentariaAlterarItem';
    import PlanilhaOrcamentariaIncluirItem from './PlanilhaOrcamentariaIncluirItem';

    export default {
        name: 'PlanilhaOrcamentaria',
        components: {
            ReadequacaoSaldoAplicacaoPlanilhaOrcamentaria,
            PlanilhaOrcamentariaAlterarItem,
            PlanilhaOrcamentariaIncluirItem,
        },
        props: {
            idPronac: '',
            idReadequacao: '',
            tipoPlanilha: '',
            link: false,
            componentePlanilha: '',
            perfil: '',
            disabled: false,
            disponivelParaAdicaoItensReadequacaoPlanilha: '',
            disponivelParaEdicaoReadequacaoPlanilha: '',
        },
        data() {
            return {
                planilhaOrcamentaria: {},
            };
        },
        created() {
            this.montarPlanilhaOrcamentaria();
        },
        methods: {
            montarPlanilhaOrcamentaria() {
                const self = this;
		/*
                $3.ajax({
                    type: 'GET',
                    url: '/readequacao/readequacoes/obter-planilha-orcamentaria',
                    data: {
                        idPronac: self.idPronac,
                        tipoPlanilha: self.tipoPlanilha,
                        link: self.link,
                    },
                }).done(
                    (response) => {
                        self.planilhaOrcamentaria = response.planilhaOrcamentaria;
                    },
                );
*/
            },
            atualizarSaldoEntrePlanilhas() {
                this.$emit('atualizarSaldoEntrePlanilhas');
            },
        },
    };
</script>
