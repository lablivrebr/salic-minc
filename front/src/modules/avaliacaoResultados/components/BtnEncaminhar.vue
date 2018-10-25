<template>
    <v-container fluid>
        <v-btn> 
            teste {{idPronac}}
        </v-btn> 
    </v-container>
</template>

<script>
export default {
    name: 'BtnEncaminhar',
    props: ['idPronac'],
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
