<template>
    <v-container fluid>
        <v-layout
            row
            wrap
        >
            <v-flex
                xs10
                md5>
                <v-card min-height="336px">
                    <v-card-title class="grey lighten-2 title">Versão original</v-card-title>
                    <v-divider/>
                    <v-card-text>
                        <span v-html="tratarCampoVazio(campo.valor)"/>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex
                xs10
                md6
                offset-md1
            >
                <v-card min-height="336px">
                    <v-card-title class="green lighten-2 title">Versão readequada</v-card-title>
                    <EditorTexto
                        :style="''"
                        :value="campoTexto"
                        @editor-texto-input="salvarInput($event)"
                        @editor-texto-counter="atualizarContador($event)"
                    />
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
import EditorTexto from '../../avaliacaoResultados/components/components/EditorTexto';

export default {
    name: 'TemplateTextarea',
    components: {
        EditorTexto,
    },
    props: {
        campo: { type: Object, default: () => {} },
        dadosReadequacao: { type: Object, default: () => {} },
    },
    data() {
        return {};
    },
    computed: {
        campoTexto() {
            return this.dadosReadequacao.dsSolicitacao;
        },
    },
    methods: {
        salvarInput(e) {
            this.$emit('dados-update', e);
        },
        atualizarContador(valor) {
            this.$emit('editor-texto-counter', valor);
        },
        tratarCampoVazio(value) {
            if (value.trim() === '') {
                const msgVazio = '<em>Campo vazio</em>';
                return msgVazio;
            }
            return value;
        },
    },
};
</script>
