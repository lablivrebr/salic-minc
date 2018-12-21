<template>
    <div>
        <vue-editor
            :editorToolbar="customToolbar"
            v-model="editor"
            :placeholder="'Texto do Documento *'"
            @input="enviar($event)"
            @text-change="counter($event)"
        >
        </vue-editor>
    </div>
</template>

<script>
import { VueEditor } from 'vue2-editor';

export default {
    props: { value: String },
    components: {
        VueEditor,
    },
    data() {
        return {
            editor: '',
            customToolbar: [
                [{ font: [] }],
                [{ header: [false, 1, 2, 3, 4, 5, 6] }],
                [{ size: ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ align: '' }, { align: 'center' }, { align: 'right' }, { align: 'justify' }],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                [{ color: [] }],
            ],
        };
    },
    methods: {
        enviar(e) {
            this.$emit('editor-texto-input', e);
        },

        counter(e) {
            if (typeof e !== 'undefined' && e.ops.length > 0 && e.ops[0].retain !== undefined) {
                this.$emit('editor-texto-counter', e.ops[0].retain);
            }
        },
        setInfo() {
            this.editor = this.value;
        },
    },
    mounted() {
        this.setInfo();
        this.counter();
    },

};

</script>
