<template>
    <v-btn flat icon color="green" :to="{ name: 'AnalisePlanilha', params:{ id: idPronac }}">
        <v-tooltip bottom>
            <v-icon 
                slot="activator" 
                class="material-icons"
            >
                visibility
            </v-icon>
            <span>Visualizar Projeto</span>
        </v-tooltip>
    </v-btn>

</template>

<script>
    export default {
        name: 'VisualizarButton',
        props: [
            'idPronac',
        ],
    };
</script>