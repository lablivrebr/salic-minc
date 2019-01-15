<template>
    <div>
        FinalizarAnalise
    </div>
</template>

<script>

export default {
    name: 'FinalizarAnalise',
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
