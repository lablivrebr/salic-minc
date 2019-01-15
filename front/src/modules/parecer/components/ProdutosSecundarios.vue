<template>
    <div>
        Produtos secundários
    </div>
</template>

<script>

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
};
</script>
