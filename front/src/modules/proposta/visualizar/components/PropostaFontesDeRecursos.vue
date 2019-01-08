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
    name: 'PropostaFontesDeRecursos',
    components: {
        slTabelaSimples,
    },
    props: ['idpreprojeto'],
    watch: {
        idpreprojeto(value) {
            this.buscaFontesDeRecursos(value);
        },
    },
    mounted() {
        if (typeof this.idpreprojeto !== 'undefined') {
            this.buscaFontesDeRecursos(this.idpreprojeto);
        }
    },
    computed: {
        ...mapGetters({
            dado: 'proposta/fontesDeRecursos',
        }),
    },
    methods: {
        ...mapActions({
            buscaFontesDeRecursos: 'proposta/buscaFontesDeRecursos',
        }),
    },
};
</script>
