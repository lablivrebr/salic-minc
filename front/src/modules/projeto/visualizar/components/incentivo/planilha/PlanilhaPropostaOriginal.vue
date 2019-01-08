<template>
    <div id="planilha-proposta-original">
        <Carregando
            v-if="loading"
            :text="'Procurando planilha'"/>

        <Planilha
            v-if="Object.keys(planilha).length > 0"
            :array-planilha="planilha"/>
        <div
            v-if="semResposta"
            class="card-panel padding 20 center-align">{{ mensagem }}</div>
    </div>
</template>

<script>
import Carregando from '@/components/Carregando';
import Planilha from '@/components/Planilha/Planilha';
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'PlanilhaPropostaOriginal',
    components: {
        Carregando,
        Planilha,
    },
    data() {
        return {
            loading: true,
            semResposta: false,
            mensagem: '',
        };
    },
    watch: {
        dadosProjeto(value) {
            this.buscaPlanilhaOriginal(value.idPronac);
        },
        planilha() {
            this.loading = false;
        },
    },
    mounted() {
        this.buscaPlanilhaOriginal(this.dadosProjeto.idPronac);
    },
    computed: {
        ...mapGetters({
            dadosProjeto: 'projeto/projeto',
            planilha: 'projeto/planilhaOriginal',
        }),
    },
    methods: {
        ...mapActions({
            buscaPlanilhaOriginal: 'projeto/buscaPlanilhaOriginal',
        }),
    },
};
</script>
