<template>
    <div>
        Consolidacao
    </div>
</template>

<script>

export default {
    name: 'Consolidacao',
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
