<template>
    <v-btn flat icon color="green" :to="{ name: 'AnalisePlanilha', params:{ id: idPronac }}">
        <v-icon class="material-icons">compare_arrows</v-icon>
    </v-btn>
</template>

<script>
    export default {
        name: 'AnalisarButton',
        props: [
            'idPronac',
        ],
        data() {
            return {
            };
        },
        methods: {
        },
        computed: {
        },
    };
</script>