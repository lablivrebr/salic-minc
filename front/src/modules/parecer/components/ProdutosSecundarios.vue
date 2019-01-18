<template>
    <div>
        Produtos secundários

        {{ produtosSecundarios }}
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'ProdutosSecundarios',
    props: {
        produto: {
            type: Object,
            default: () => {},
        },
        active: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        ...mapGetters({
            produtosSecundarios: 'parecer/getProdutosSecundarios',
        }),
    },
    created() {
        this.obterProdutosSecundarios({
            idProduto: this.$route.params.id,
            idPronac: this.$route.params.idPronac,
        });
    },
    methods: {
        ...mapActions({
            obterProdutosSecundarios: 'parecer/obterProdutosSecundarios',
        }),
    },
};
</script>
