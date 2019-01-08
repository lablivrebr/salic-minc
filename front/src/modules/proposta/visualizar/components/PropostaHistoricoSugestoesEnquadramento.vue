<template>
    <div class="tabelas">
        <div class="row">
            <slTabelaSimples :dados="dado"/>
        </div>
    </div>
</template>
<script>
import slTabelaSimples from '@/components/slTabelaSimples';
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'PropostaHistoricoSugestoesEnquadramento',
    components: {
        slTabelaSimples,
    },
    props: ['idpreprojeto'],
    watch: {
        idpreprojeto(value) {
            this.buscarHistoricoEnquadramento(value);
        },
    },
    mounted() {
        if (typeof this.idpreprojeto !== 'undefined') {
            this.buscarHistoricoEnquadramento(this.idpreprojeto);
        }
    },
    computed: {
        ...mapGetters({
            dado: 'proposta/historicoEnquadramento',
        }),
    },
    methods: {
        ...mapActions({
            buscarHistoricoEnquadramento: 'proposta/buscarHistoricoEnquadramento',
        }),
    },
};
</script>
