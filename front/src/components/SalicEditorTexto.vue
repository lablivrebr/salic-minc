<template>
    <div>
        <vue-editor
            :editor-toolbar="customToolbar"
            v-model="editor"
            v-bind="$attrs"
            @input="enviar($event)"
        />
    </div>
</template>

<script>
import { VueEditor } from 'vue2-editor';

export default {
    components: {
        VueEditor,
    },
    props: {
        value: {
            type: String,
            default: '',
        },
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
    watch: {
        value(val) {
            this.editor = val.trim();
        },
        editor(val) {
            this.$emit('editor-texto-counter', val.replace(/(<([^>]+)>)/ig, '').length);
        },
    },
    mounted() {
        this.editor = this.value.trim();
    },
    methods: {
        enviar(e) {
            this.$emit('input', e);
        },
    },
};
</script>
