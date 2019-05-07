<template>
    <div>{{ teste }}</div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    name: 'Captacao',
    data() {
        return {
            teste: 'Hello Darkness my old friend...',
        };
    },
    computed: {
        ...mapGetters({}),
    },
    methods: {
        ...mapActions({}),
    },
};
</script>
