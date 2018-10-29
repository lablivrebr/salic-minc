<template>
    <v-btn
        dark
        icon
        flat
        small
        color="green"
        :to="{ name: 'AnalisePlanilha', params:{ id: idPronac }}"
    >
        <v-tooltip bottom>
            <v-icon slot="activator">edit</v-icon>
            <span>Assinar</span>
        </v-tooltip>
    </v-btn>
</template>

<script>
    export default {
        name: 'AssinarButton',
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