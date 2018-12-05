<template>
    <v-btn
        v-if="linkDiretoAssinatura"
        dark
        icon
        flat
        small
        color="green"
        :href="'/assinatura/index/visualizar-projeto?idDocumentoAssinatura='+documento"
    >
        <v-tooltip bottom>
            <v-icon slot="activator">edit</v-icon>
            <span>Assinar</span>
        </v-tooltip>
    </v-btn>

    <v-btn
        v-else
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
            'linkDiretoAssinatura',
            'documento',
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
