<template>
    <v-btn flat icon color="green" :to="{ name: 'Revisao', params:{ id: idPronac }}">
        <v-tooltip bottom>
            <v-icon slot="activator" class="material-icons">rate_review</v-icon>
            <span>Revisão</span>
        </v-tooltip>
    </v-btn>

</template>

<script>
    export default {
        name: 'revisaoButton',
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