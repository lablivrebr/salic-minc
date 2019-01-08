<template>
    <v-btn
        v-if="linkDiretoAssinatura"
        :href="'/assinatura/index/visualizar-projeto?idDocumentoAssinatura='+documento"
        dark
        icon
        flat
        small
        color="green"
    >
        <v-tooltip bottom>
            <v-icon slot="activator">edit</v-icon>
            <span>Assinar</span>
        </v-tooltip>
    </v-btn>

    <v-btn
        v-else
        :to="{ name: 'AnalisePlanilha', params:{ id: idPronac }}"
        dark
        icon
        flat
        small
        color="green"
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
        'linkDiretoAssinatura',
        'documento',
    ],
    data() {
        return {
        };
    },
    computed: {
    },
    methods: {
    },
};
</script>
