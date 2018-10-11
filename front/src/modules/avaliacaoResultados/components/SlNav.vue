<template>
    <div>
        <MenuSuperior :dadosMenu="dadosMenu"></MenuSuperior>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';
    import MenuSuperior from '@/components/menu-superior';

    export default {
        name: 'SlNav',
        components: {
            MenuSuperior,
        },
        computed: {
            ...mapGetters({
                dadosMenu: 'avaliacaoResultados/dadosMenu',
            }),
        },
        created() {
            this.dadosMenuAjax();
        },
        methods: {
            ...mapActions({
                dadosMenuAjax: 'avaliacaoResultados/dadosMenu',
            }),
        },
    };
</script>
