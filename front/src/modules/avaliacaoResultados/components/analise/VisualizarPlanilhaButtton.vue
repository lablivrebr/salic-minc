<template>
    <v-btn flat icon :to="{ name: 'VisualizarPlanilha', params:{ id: idPronac }}">
        <v-tooltip bottom>
            <v-icon 
                slot="activator" 
                class="material-icons">visibility
            </v-icon>
            <span>Visualizar Projeto</span>
        </v-tooltip>
    </v-btn>
</template>

<script>
    export default {
        name: 'VisualizarPlanilhaButtton',
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