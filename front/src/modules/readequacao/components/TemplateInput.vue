<template>
    <v-container fluid>
        <v-layout
            row
            wrap
        >
            <v-flex
                xs10
                md5
            >
                <v-card height="130px">
                    <v-card-title class="grey lighten-2 title">Versão original</v-card-title>
                    <v-divider/>
                    <v-card-text>
                        {{ campo.valor }}
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex
                xs10
                md5
                offset-md2
            >
                <v-card height="130px">
                    <v-card-title class="green lighten-2 title">Versão readequada</v-card-title>
                    <v-card-actions>
                        <v-text-field
                            :label="campo.titulo"
                            :value="campoTexto"
                            @input="updateCampo"
                        />
                    </v-card-actions>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
export default {
    name: 'TemplateInput',
    props: {
        campo: { type: Object, default: () => {} },
        dadosReadequacao: { type: Object, default: () => {} },
        nomeAtributo: { type: String, default: () => '' },
    },
    computed: {
        campoTexto() {
            return this.dadosReadequacao.dsSolicitacao;
        },
    },
    methods: {
        updateCampo(e) {
            this.$emit('dados-update', e);
        },
    },
};
</script>
