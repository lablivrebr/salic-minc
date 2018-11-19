<template>
    <v-btn
        flat
        icon
        color="primary"
        :to="{ name: 'AnalisePlanilha', params:{ id: idPronac }}"
    >
        <v-tooltip bottom>
            <v-icon
                slot="activator"
                class="material-icons"
            >
                gavel
            </v-icon>
            <span>Analisar Projeto</span>
        </v-tooltip>
    </v-btn>
</template>
<script>

export default {
    name: 'AnalisarButton',
    props: [
        'idPronac',
    ],
};
</script>