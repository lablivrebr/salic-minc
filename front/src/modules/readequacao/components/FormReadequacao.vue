<template>
    <v-container fluid>
        <v-layout
            row
            wrap
        >
            <v-flex xs12>
                <v-card>
                    <EditorTexto
                        :style="''"
                        :value="dadosReadequacao.dsJustificativa"
                        required="required"
                        @editor-texto-input="atualizarForm($event)"
                        @editor-texto-counter="atualizarContador($event)"
                    />
                    <!--
                         <s-editor-texto
                         v-model="dadosReadequacao.dsJustificativa"
                         :placeholder="'Justificativa da solicitação de readequação'"
                         :min-char="minChar"
                         @text-change="atualizarForm($event)"
                         @editor-texto-counter="validateText($event)"
                         />-->
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
<script>
//import SEditorTexto from '@/components/SalicEditorTexto';
import EditorTexto from '../../avaliacaoResultados/components/components/EditorTexto';
 
export default {
    name: 'FormReadequacao',
    components: {
        //SEditorTexto,
        EditorTexto,
    },
    props: {
        dadosReadequacao: { type: Object, default: () => {} },
    },
    data() {
        return {
            dialog: false,
            minChar: 10,
        };
    },
    watch: {
        textIsValid() {
            this.$emit('texto-valido', textIsValid);
        }
    },
    computed: {
    },
    methods: {
        atualizarForm(texto) {
            this.$emit('dados-update', texto);
        },
        atualizarContador(valor) {
            this.$emit('editor-texto-counter', valor);
        },
    },
};
</script>
